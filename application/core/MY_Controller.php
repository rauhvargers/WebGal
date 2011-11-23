<?php
class MY_Controller extends CI_Controller {
	
	//pagaidām, kamēr nav lietotāju autentifikācijas
	var $user_id = 1;
	
  	public function __construct() {
		parent::__construct();
		$this->load->helper('html');
		$this->load->helper('url');
		$this->load->helper('webgal'); //pašu helper funkcijas
				
	}

	protected function DefaultViewData(){
		return array("show_loginform"=> FALSE,
			     "show_logoutform" => TRUE,
			     "pagetitle" => "Nav aizpildīts mainīgais pagetitle");
	}
}
  
?>