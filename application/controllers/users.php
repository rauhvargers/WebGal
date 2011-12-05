<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends MY_Controller {

    function __construct() {
	parent::__construct();
	$this->load->library('session');
	$this->load->helper('form');
	$this->load->helper('url');
    }

    function index() {
	if (!$this->session->userdata("is_authenticated")) {
	    #vēl nav autentificēts - dosim login lapu
	    return $this->login();
	} else {
	    #jau autentificēts - pasakām, ka viss OK
	    $data["username"] = $this->session->userdata("username");
	    //$this->load->view("authenticate/authenticated", $data);
	    redirect("/");
	}
    }

    function login() {
	return $this->load->view("authenticate/login_hash");
    }

    function logout() {
	$this->session->unset_userdata("is_authenticated");
	//redirect("users/index");
	redirect("/");
    }

    function authenticate() {
	$u = $this->input->post("username");
	$p = $this->input->post("password");

	$this->load->model("Users_model");
	$is_auth = $this->Users_model->clienthash_password($u, $p);

	if ($is_auth == false) {
	    $data["error"] = "Autentifikācija nav veiksmīga";
	    $this->load->view("authenticate/login_hash", $data);
	} else {
	    $this->session->set_userdata("is_authenticated", "true");
	    $this->session->set_userdata("username", $u);
	    redirect('users/index', 'location');
	}
    }
    
    
    //jauna lietotāja reģistrācijas forma
    function registrationform(){
	$this->load->view('register/index');
    }
    
    //lietotājs aizpildījis reģistrācijas formu
    //mēģinās reģistrēt jaunu
    function register(){
	    $user = $this->input->post("username");
	    $pass = $this->input->post("password");
	    $mail = $this->input->post("mail");

	    if ( (trim($user)=="") || (trim($pass)=="") || (trim($mail)=="")){
		    $this->load->view("register/index", array("errormsg"=>"Nav aizpildīts kāds no lauciņiem"));
		    return;
	    }

	    //visi lauciņi bija aizpildīti - mēģina reģistrēt
	    $this->load->model("users_model");
	    $results = $this->users_model->register_user($user, $pass, $mail);

	    //arī reģistrācijas brīdī varētu būt bijusi kāda problēma
	    if ($results === true) {
		    $this->load->view('register/registered.php');
	    } else {
		    $this->load->view("register/index", array("errormsg"=>$results));
	    }		
    }
	
	
    function confirm($user, $salthash){
	    $this->load->model("users_model");
	    $data["success"] =  $this->users_model->validate($user, $salthash);

	    $this->load->view("register/validated", $data);
    }

}
