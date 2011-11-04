<?php
class MY_Controller extends CI_Controller {
  	public function __construct() {
		parent::__construct();
		$this->load->helper('html');
		$this->load->helper('url');
		$this->load->helper('webgal'); //pašu helper funkcijas
				
	}

	protected function DefaultViewData(){
		return array("show_loginform"=> FALSE,
			     "show_logoutform" => TRUE);
	}
}
  
?>