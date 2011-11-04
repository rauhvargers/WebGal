<?php

  class Photo_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        //gandrīz visam vajadzēs datubāzi.
        $this->load->database();		
    }    
    
    var $id = 0;
    var $title = '';
    var $filename ='';      //attēla nosaukums bez pilnas takas
    var $fullpath = '';     //pilna taka, iekļaujot attēla nosaukumu
    var $description = '';
    var $gallery_id = '';
    var $created = '';
    
    /**
     *
     * @param int $id meklētā attēla identifikators
     * @return Photo_model  atgriež pats sevi, lai būtu iespējama metožu ķēdēšana
     */
    public function read_by_id($id){
         $query = $this->db->from("picture")->where(array("id" => $id))->limit(1,0); 
         $results = $query->get()->result();
         if (count($results) > 0){
             //ja ir ielasījies kaut viens rezultāts
             $this->id = $results[0]->id;
             $this->title = $results[0]->title;
             $this->filename = $results[0]->filename;
             $this->description = $results[0]->description;
             $this->gallery_id = $results[0]->gallery_id;
             $this->created = $results[0]->created;
         } else {
             $this->id = 0; //pazīme, ka nav atrasts
         }
         return $this;
    }
    
    
       /**
     * Atrod attēla failu nosūta to klientam
     * @param string $size Kādu izmēru atgriezt. 
        * Noklusējums - oriģinālo izmēru, bet var norādīt arī "thumb" (noklusētais priekšskatījuma izmērs)
        * vai pēc formāta (w|h)(ierobežojums), piemēram w200 - platums, 200px 
     */
    public function send_sized($size='full') {

       if ($this->id == 0) {
           //attēls vai nu vēl nav nolasīts vai nav atradies
           $this->fullpath = $this->config->item('webgal_notfound_image');
           $this->filename = 'not_found.gif';
       } else {
           $this->fullpath = $this->config->item('webgal_file_base')."/".$this->filename;
           
       }
       $this->prepare_or_cache($size);
    }
    
    /**
     * Nosūta klientam pašreizējo bildi. Ja iespējams, izmanto kešā saglabātos failus.
     * @param string $size Tādi paši ierobežojumi kā send_sized
     */
    private function prepare_or_cache($size){
        $sizeinfo = $this->parse_size($size);
        
        
        $this->output->set_header('Content-Disposition: inline; filename="'.$sizeinfo["version_name"].'";');
        
        //gadījums, kad nepieciešams pats oriģinālais fails, ir speciāls: to nemeklē kešā
        if ($sizeinfo["version_name"] == $this->filename) {
             $this->output->set_header("Content-Type: " . $this->get_mimetype($this->fullpath) );             
             $this->output->set_output(file_get_contents($this->fullpath));
             return;
        }
        
        //ir nepieciešama cita izmēra versija
        $thumbdir = $this->config->item("webgal_thumb_path");
        
        if ($thumbdir == "") {
            //konfigurācijā nav norādīta direktorija samazinātajiem failiem
            //tas nozīmē, ka kešu izmantot nedrīkst
            $this->output->set_header("Content-Type: image/jpeg");  //visi mazie attēli ir JPEG
            $this->output->set_output( $this->resize_image($sizeinfo["width_limit"], $sizeinfo["height_limit"]) );
            return;
        }
        
        //acīmredzot thumbdir ir uzstādīts.
        //1)pārbaudīsim, vai attēls ir jau kešā. Ja ir tāda versija- nolasa un atgriež
        //2)ja ir tādas versijas nav, izveido, saglabā un atgriež
        $thumb_containingdir = str_replace('//','/', $thumbdir.'/'.$this->id); 
        $thumb_fullpath = $thumb_containingdir . '/' . $sizeinfo["version_name"];
        
        if ( ! file_exists($thumb_fullpath)) {
            //tieši šo versiju kešā neatrodam. ģenerēsim
            $resized = $this->resize_image($sizeinfo["width_limit"], $sizeinfo["height_limit"]);
            if (!file_exists($thumb_containingdir)) {
                 //var gadīties, ka nav arī direktorijas, kurā failam būt - uztaisam
                 mkdir($thumb_containingdir);
             }
             
            //mēģina ieglabāt pašu failu un uzlikt tam tiesības
            @file_put_contents($thumb_fullpath, $resized);            
            @chmod($thumb_fullpath, 0777);
        } else {
            //otrs gadījums - mazā versija ir atradusies
            //vienkārši ielasa no faila
            $resized = file_get_contents($thumb_fullpath);
        }
        
        $this->output->set_header("Content-Type: image/jpeg");  //visi mazie attēli ir JPEG
        $this->output->set_output( $resized );

    }
    
    
    /**
     * No tekstuālā pieprasījuma atrod, kāds izmērs patiesībā būs vajadzīgs.
     * @param string $size
     * @return array
     */
    private function parse_size($size){
        $nameparts = explode(".", $this->filename);
        $version_name = "";
        $width_limit = -1; //-1 būs maģiskā konstante, kas nozīmē "nav noteikts" 
        $height_limit = -1;
        if ($size == 'full') {
            $version_name = $this->filename;
        } elseif ($size == 'thumb') {
            $version_name = $nameparts[0] . '_w' . $this->config->item("webgal_thumb_width") . "." . $nameparts[1];
            $width_limit = $this->config->item("webgal_thumb_width");
        } else {
            //ja nav kāds no vienkāršajiem variantiem, tad sagaidām 
            //formātā <dimensija><izmērs>, piemēram w700 vai h50
            $sizeparts = array();
            if (preg_match('/(w|h)(\d+)/',$size, $sizeparts)) {

                if ($sizeparts[2] > $this->config->item("webgal_thumb_max_dimension") ) {
                    //gadījumā, ja lietotājs pieprasījis pārāk lielu versiju, piemēram, 3000px platu
                    //atgriezīs vienkārši pilno izmēru.
                    //tas nepieciešams, lai PHP nepārtērētu pieejamo atmiņu
                    $version_name = $this->filename;
                } else {
                    //atkarībā no izmērā norādītā burta, vai nu ierobežo augstumu vai platumu
                    $width_limit = ($sizeparts[1] == "w") ? $sizeparts[2] : -1;
                    $height_limit = ($sizeparts[1] == "h") ? $sizeparts[2] : -1;
                    $version_name = $nameparts[0] . '_' . $size . "." . $nameparts[1];
                }
            } else {
                //ja ir kaut kas cits nekā "full" vai "thumb", bet neizdodas atpazīt formātu,
                //atgriež tādu pašu kā "thumb"  gadījumā
                $version_name = $nameparts[0] . '_w' . $this->config->item("webgal_thumb_width") . "." . $nameparts[1];
            }
        }
        //prasītājs atpakaļ dabū gan faila vārdu, gan nepieciešamos ierobežojumus
        return array(
                "version_name"=>$version_name, 
                "height_limit"=>$height_limit, 
                "width_limit"=>$width_limit);
    }
    
    /**
     * Nolasa oriģinālo failu un pārveido pēc prasītā izmēra
     * @param int $width_limit izmēra maiņa pēc platuma
     * @param int $height_limit izmēra maiņa pēc noteikta augstuma
     */
    private function resize_image($width_limit, $height_limit = -1){
        $newX = 0;
        $newY = 0;
        

        //ielādei jāizmanto dažādas funkcijas
        switch($this->get_mimetype($this->fullpath)) {
            case "image/jpeg" :
                 $src_img = imagecreatefromjpeg($this->fullpath);
                break;
            case "image/gif"  : 
                $src_img  = imagecreatefromgif($this->fullpath);
                break;
            case "image/png"  :
                $src_img = imagecreatefrompng($this->fullpath);
                break;
        }
        
        //$src_img=imagecreatefromjpeg("images/".$row->blogpic_filename);
	$oldX=imageSX($src_img);
	$oldY=imageSY($src_img);
        
	
        //ja ir norādīts platuma ierobežojums, tad primāri pēc tā
        if ($width_limit > -1) {
              $newX = $width_limit;
              $newY  = $oldY * ($newX / $oldX);
        } else {
              $newY = $height_limit;
              $newX = $oldX * ($newY / $oldY);
        }
        
        $resized_img=ImageCreateTrueColor($newX,$newY);
        imagecopyresampled($resized_img,$src_img,0,0,0,0,$newX,$newY,$oldX,$oldY); 
        
        //imagejpeg cenšas uzreiz nosūtīt rezultātus klientam.
        //mēs gribam pārķert šos datus un atgriezt pieprasītājam.
        
        ob_start();
        imagejpeg($resized_img);
	return ob_get_clean(); 
    }
    
 
    
    
    /**
     * Izmantojot libmagic, noskaidro faila MIME tipu.
     * @return string attēla MIME tips
     */
    public function get_mimetype($fullpath){
       $finfo = finfo_open(FILEINFO_MIME_TYPE);
       $fmime = finfo_file($finfo, $fullpath);
       finfo_close($finfo); 
       return $fmime;
    }
}