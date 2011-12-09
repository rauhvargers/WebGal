<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends CI_Controller {

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
	    #jau autentificēts - lai dodas uz pirmo lapu
	    redirect("/");
	}
    }

    /**
     * Autentifikācija ar Facebook, precīza kopija no
     * http://developers.facebook.com/docs/authentication/
     * servera puses piemēra, tikai pielāgota CodeIgniter 
     * sesijām un servera mainīgo lasīšanas pieejai
     */
    function login_fb() {
	$app_id = FACEBOOK_APP_ID;
	$app_secret = FACEBOOK_APP_SECRET;

	$my_url = site_url("users/login_fb");


	$code = $this->input->get("code");
	//vai GET parametros saņemts mainīgais "code" (pirmajā reizē nav)
	if (empty($code)) {
	    $my_state = md5(uniqid(rand(), TRUE));

	    //nākamajā iterācijā šī vērtība noderēs
	    $this->session->set_userdata('state', $my_state); //CSRF protection
	    $dialog_url = "http://www.facebook.com/dialog/oauth?client_id="
		    . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
		    . $my_state;
	    header("location:" . $dialog_url);
	    exit();
	}

	//ja "GET" parametros saņemts "state" un tas sakrīt ar sesijas 
	//mainīgajos esošajiem
	if ($this->input->get('state') == $this->session->userdata('state')) {
	    $token_url = "https://graph.facebook.com/oauth/access_token?"
		    . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
		    . "&client_secret=" . $app_secret . "&code=" . $code;

	    //solis, kur facebook-am pasaka, ka tagad mums interesētu izmantot
	    //saņemto autentifikācijas kodu
	    $response = @file_get_contents($token_url);
	    $params = null;
	    parse_str($response, $params);


	    $graph_url = "https://graph.facebook.com/me?access_token="
		    . $params['access_token'];

	    //un te jau nolasa reālos lietotāja datus
	    $user_json = file_get_contents($graph_url);
	    //print_r(json_decode($user_json));
	    //exit();
	    $this->session->set_userdata("is_authenticated", "true");
	    $this->session->set_userdata("fb_data", $user_json);
	    $this->session->set_userdata("auth_method", "facebook");
	    redirect("/");
	} else {
	    die("The state does not match. You may be a victim of CSRF.");
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
	    $this->session->set_userdata("auth_method", "local");
	    redirect('users/index', 'location');
	}
    }

    //jauna lietotāja reģistrācijas forma
    function registrationform() {
	$this->load->view('register/index');
    }

    //lietotājs aizpildījis reģistrācijas formu
    //mēģinās reģistrēt jaunu
    function register() {
	$user = $this->input->post("username");
	$pass = $this->input->post("password");
	$mail = $this->input->post("mail");
	$website = $this->input->post("website");

	if ((trim($user) == "") || (trim($pass) == "") || (trim($mail) == "")) {
	    $this->load->view("register/index", array("errormsg" => "Nav aizpildīts kāds no lauciņiem"));
	    return;
	}

	//visi lauciņi bija aizpildīti - mēģina reģistrēt
	$this->load->model("users_model");
	$results = $this->users_model->register_user($user, $pass, $mail, $website);

	//arī reģistrācijas brīdī varētu būt bijusi kāda problēma
	if ($results === true) {
	    $this->load->view('register/registered.php');
	} else {
	    $this->load->view("register/index", array("errormsg" => $results));
	}
    }

    function confirm($user, $salthash) {
	$this->load->model("users_model");
	$data["success"] = $this->users_model->validate($user, $salthash);

	$this->load->view("register/validated", $data);
    }

}
