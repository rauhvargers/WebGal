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
     * Parāda visu pieejamo galeriju sarakstu
     */
    public function index() {
	$this->load->model("gallery/gallery_list_model");
	$galleries = $this->gallery_list_model->get_all_galleries();

	$viewdata = $this->DefaultViewData();
	$viewdata["galleries"] = $galleries;
	$viewdata["pagetitle"] = "Galeriju saraksts";
	$this->load->view("gallery/index.php", $viewdata);
    }

    public function usergalleries($format="html") {
	$id = $this->session->userdata("user_id");
	if (empty($id)) {
	    show_error("Jāautorizējas!", 403);
	}
	$this->load->model("gallery/gallery_list_model");
	$galleries = $this->gallery_list_model->get_user_galleries($id);

	if ($format == "html") {
	    $viewdata = $this->DefaultViewData();
	    $viewdata["galleries"] = $galleries;
	    $viewdata["pagetitle"] = "Galeriju saraksts";
	    $this->load->view("gallery/index.php", $viewdata);
	} elseif ($format=="json") {
	    $viewdata["galleries"] = $galleries;
	    $this->load->view("gallery/index_json", $viewdata);
	}
	
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
	if ($gal->id == 0) {
	    redirect("/gallery/notfound");
	    exit();
	}
	$viewdata = $this->DefaultViewData();
	$viewdata["gallery"] = $gal;
	$viewdata["pagetitle"] = $gal->title;
	//ir atradies.

	$gal->find_photos();
	$this->load->view("gallery/view.php", $viewdata);
    }

    public function addnew() {

	$data = array();
	$data["gallery"] = $this->gallery_model;
	$this->load->view("gallery/addnew.php", $data);
    }

    public function savenew() {

	$this->gallery_model->load_from_post();

	if ($this->gallery_model->is_valid()) {
	    $this->gallery_model->save();
	    redirect('gallery/view/' . $this->gallery_model->id);
	} else {

	    $data = array();
	    $data["gallery"] = $this->gallery_model;
	    $this->load->view("gallery/addnew.php", $data);
	}
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
