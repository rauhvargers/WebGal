<?php

class MY_Controller extends CI_Controller {

    //pagaidām, kamēr nav lietotāju autentifikācijas
    var $user_id = 1;

    public function __construct() {
	parent::__construct();
	$this->load->helper('html');
	$this->load->helper('url');
	$this->load->helper('webgal'); //pašu helper funkcijas
	$this->load->library("session");
	
	$this->load->helper("language");
	$this->lang->load("main", "latvian");
	$this->lang->load("errors", "latvian");
	
	if ( ! $this->session->userdata("is_authenticated")) {
	    redirect("users");
	}
    }

    protected function DefaultViewData() {
	$username = "";
	if (!$this->session->userdata("is_authenticated")) {
	    $is_auth = false;
	} else {
	    $is_auth = true;

	    $auth_method = $this->session->userdata("auth_method");

	    switch ($auth_method) {
		case "facebook":
		    $fb_json = $this->session->userdata("fb_data");
		    $fb_data = json_decode($fb_json);
		    $id = $fb_data->id;
		    $username = $fb_data->name;
		    break;
		case "local" :
		    $userdata = $this->session->userdata("userdata");
		    $username = $userdata["username"];
		    $id = $userdata["id"];
		    break;
	    }
	}
	return array("show_loginform" => !$is_auth,
	    "show_logoutform" => $is_auth,
	    "username" => $username,
	    "userid" => $id,
	    "pagetitle" => "Nav aizpildīts mainīgais pagetitle");
    }

}

?>