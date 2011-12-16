<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Autors: 	Krišs Rauhvargers
 * Izveidots: 	05.08.2011 
 * Fails:  	Darbības ar atsevišķām fotogrāfijām (ne failiem!)
 */
class Photo extends MY_Controller {

    public function __construct() {
	parent::__construct();
	$this->load->model("photo/photo_model"); //visi mums te strādās ar foto modeli.
    }

    public function index($id=0){
	return $this->view($id);
    }
    
    /*
     * Jauna attēla pievienošana - sākums
     */
    public function addnew($gallery_id = 0){
	$this->load->model("gallery/gallery_model");
	$this->load->model("gallery/gallery_list_model");

	$viewdata = $this->DefaultViewData();
	$viewdata["pagetitle"] = "Jauna fotogrāfija";
	
	$gal = $this->gallery_model->read_by_id($gallery_id);
	if ($gal->id == 0) {
	    //ja galerija nav atrasta, nevar pievienot bildi
	    redirect("/");
	    exit();
	} else {
	    $viewdata["gal"]  = $gal;
	}
	$viewdata["user_galleries"] = $this->gallery_list_model->get_user_galleries($this->user_id);
	
	$viewdata["pic"] = $this->photo_model;
	$this->load->view("photo/addnew",$viewdata);
    }
    
    /*
     * Lietotājs ir ievadījis datus par bildi, mēģināsim saglabāt
     */
    public function savenew(){
	//vajadzēs darboties ar galerijām
	$this->load->model("gallery/gallery_model");
	$this->load->model("gallery/gallery_list_model");
	
	$this->load->library('form_validation');
	$this->form_validation->set_rules('gallery_id', 'Galerijas identifikators', 'required|numeric');
	$this->form_validation->set_rules('title', 'Virsraksts', 'required|max_length[255]');
	$this->form_validation->set_rules('description', 'Apraksts', 'required');
	
	$this->photo_model->title = $this->input->post("title");
	$this->photo_model->description = $this->input->post("description");
	$this->photo_model->gallery_id = $this->input->post("gallery_id");
	
	if ($this->form_validation->run()==FALSE){
	    //sliktais gadījums - kaut kas no datiem nav bijis
	    //aizpildīts pareizi
	    $viewdata = $this->DefaultViewData();
	    $viewdata["pagetitle"] = "Jauna fotogrāfija";
	    
	    $gallery_id = $this->input->post("gallery_id");
	    $gal = $this->gallery_model->read_by_id($gallery_id);
	    if ($gal->id == 0) {
		//ja galerija nav atrasta, nevar pievienot bildi
		redirect("/");
		exit();
	    } else {
		$viewdata["gal"]  = $gal;
	    }
	    $viewdata["user_galleries"] = $this->gallery_list_model->get_user_galleries($this->user_id);
	    $viewdata["pic"] = $this->photo_model;
	    $this->load->view("photo/addnew",$viewdata);
	} else {
	    //labais gadījums, kad bildi drīkstētu saglabāt
	    $this->photo_model->save(); //izsauc saglabāšanu
	    redirect('photo/view/'.$this->photo_model->id);	    
	}

    }
    
    /**
     * Fotogrāfijas apskatīšana.
     * @param $id - attēla ID no datubāzes
     */
    public function view($id = 0) {
	//vispirms mēģināsim atrast, vai attēls vispār ir atrodams
	$currentPic = $this->photo_model->read_by_id($id);

	//vai nu padotais ID ir bijis 0, vai nav atrasts ieraksts DB
	if ($currentPic->id == 0) {
	    redirect("/photo/notfound");
	    exit(); //pēc pāradresācijas tālāko nedrīkstētu turpināt!
	}

	$viewdata = $this->DefaultViewData();
	$viewdata["pic"] = $currentPic;
	$viewdata["pagetitle"] = "Fotogrāfijas apskate";
	$this->load->view('photo/view', $viewdata);
    }

    /**
     * Fotogrāfijas atvēršana rediģēšanas režīmā. 
     * Lietotājs vēl nesūta izmaiņas objektā, tikai nolasa datus un gribēs tos labot
     * @param type $id 
     */
    public function edit($id = 0) {
	$id = intval($id);
	//vispirms mēģināsim atrast, vai attēls vispār ir atrodams
	$currentPic = $this->photo_model->read_by_id($id);

	//vai nu padotais ID ir bijis 0, vai nav atrasts ieraksts DB
	if ($currentPic->id == 0) {
	    redirect("/photo/notfound");
	    exit(); //pēc pāradresācijas tālāko nedrīkstētu turpināt!
	}

	$this->load->model("gallery/gallery_list_model");
	$viewdata = $this->DefaultViewData();
	$viewdata["pic"] = $currentPic;
	$viewdata["pagetitle"] = "Fotogrāfijas rediģēšana";
	$viewdata["user_galleries"] = $this->gallery_list_model->get_user_galleries(1);
	$this->load->view('photo/edit', $viewdata);
    }
    
    /**
     * Fotogrāfijā veikto izmaiņu saglabāšana
     * @param type $id 
     */
    public function update($id = 0) {
	$this->load->library('form_validation');

	//vispirms mēģināsim atrast, vai attēls vispār ir atrodams
	$currentPic = $this->photo_model->read_by_id($id);
	
	$this->form_validation->set_rules('gallery_id', 'Galerijas identifikators', 'required|numeric');
	$this->form_validation->set_rules('title', 'Virsraksts', 'required|max_length[255]');

	
	if ($this->form_validation->run() == FALSE) {
	    //lietotāja ievadītajos datos ir kļūda
	    //nebūtu korekti mēģināt turpināt darbu
	    $currentPic = $this->photo_model->read_by_id($id);
	    $this->load->model("gallery/gallery_list_model");
	    $viewdata = $this->DefaultViewData();
	    $viewdata["pic"] = $currentPic;
	    $viewdata["pagetitle"] = "Fotogrāfijas rediģēšana";
	    $viewdata["user_galleries"] = $this->gallery_list_model->get_user_galleries(1);
	    $this->load->view('photo/edit', $viewdata);
	} else {
	    //photo_model klasē ir metode, kas prot vajadzīgos
	    //laukus paņemt no POST datiem un saglabāt izmaņas
	    $currentPic->updateFromPost();
	    
	    redirect("/photo/view/".$id);
	    
	}
	
	

    }

    /**
     * Šo skatu rādām gadījumos, kad meklētais objekts nav atrasts.
     *  Klikšķinot caur saitēm lietotājam te it kā nevajadzētu nonākt.
     */
    public function notfound() {
	$viewdata = $this->DefaultViewData();
	$this->load->view('photo/notfound', $viewdata);
    }
    
    /**
     * Paredzēts izsaukšanai no AJAX
     * @param type $id 
     */
    public function updatetitle($id){
	$newtitle = $this->input->post("title");
	
	if ( ! empty($newtitle)) {
	    
	    $currentPic = $this->photo_model->read_by_id($id);
	    $currentPic->title = $newtitle;
	    $currentPic->update();
	}
    }

}

