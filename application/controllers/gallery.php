<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Gallery extends MY_Controller {

    //konstruējot, uzreiz ielādē galeriju modeli
    public function __construct() {
	parent::__construct();
	$this->load->model("gallery/gallery_model");
	$this->load->helper("url");
	$this->load->helper("form");
    }

    /**
     *
     * @param type $id parādāmās galerijas ID no datubāzes
     */
    public function view($id = 0) {
	$id = intval($id);
	if ($id == 0) {
	    redirect("/gallery/notfound");
	    exit(); //pēc pāradresācijas beidz darbu.
	}
	
	//id vērtība ir jēdzīga, meklējam objektu
	$gal = $this->gallery_model->read_by_id($id);
	//kaut arī ID bija labs, datubāzē tāds neatradās
	if ($gal->id==0) {
	    redirect("/gallery/notfound");
	    exit();
	}
	$viewdata= $this->DefaultViewData();
	$viewdata["gallery"] = $gal;
	$viewdata["pagetitle"] = $gal->title;
	//ir atradies.
	$this->load->view("gallery/view.php", $viewdata);
    }
    
    public function addnew(){
	
	$data=array();
	$data["defaulttitle"]="Kriša galerija";
	$this->load->view("gallery/addnew.php", $data);
    }
    
    public function savenew(){
	$title = $this->input->post("title");
	$description = $this->input->post("description");
	$public = $this->input->post("public");
	
	$this->load->database();
	$data = array(
	   'title' => $title ,
	   'description' => $description ,
	   'author_id' => 1
	);
	$this->db->insert('gallery', $data); 
	redirect('gallery/view/'. $this->db->insert_id());
	
	/*$connection = mysql_connect("localhost", "root", "");
	mysql_select_db("webgal");
	mysql_set_charset("utf8");
	$query = "insert into gallery (title, description, author_id)
			values ('". mysql_real_escape_string($title)."',
				'". mysql_real_escape_string($description)."',
				1)";
	$this->db->query($query);
	//mysql_query($query, $connection);
	
	//echo mysql_error();
	 * 
	 */
	
    }

    /**
     * Šo skatu rādām gadījumos, kad meklētais objekts nav atrasts.
     *  Klikšķinot caur saitēm lietotājam te it kā nevajadzētu nonākt.
     */
    public function notfound() {
	$viewdata = $this->DefaultViewData();
	$viewdata["pagetitle"] = "Galerija nav atrasta!";
	$this->load->view('gallery/notfound', $viewdata);
    }

}
