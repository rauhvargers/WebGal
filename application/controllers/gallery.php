<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Gallery extends MY_Controller {

    //konstruējot, uzreiz ielādē galeriju modeli
    public function __construct() {
	parent::__construct();
	$this->load->model("gallery/gallery_model");
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
