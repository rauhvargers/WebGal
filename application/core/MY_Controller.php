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
				
	}

	protected function DefaultViewData(){
		$username = "";
		if(!$this->session->userdata("is_authenticated")){
		    $is_auth=false;
		} else {
		    $is_auth =true;
		    $username =  $this->session->userdata("username");
		}
		return array("show_loginform"=> !$is_auth,
			     "show_logoutform" => $is_auth,
			     "username"	=> $username,
			     "pagetitle" => "Nav aizpildīts mainīgais pagetitle");
	}
}
  
?>