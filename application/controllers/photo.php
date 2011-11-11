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
	$this->load->view('photo/index', $viewdata);
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

}

