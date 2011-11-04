<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Gallery extends CI_Controller {

    public function view($id = 0)
	{	 
	    $data = array("id"=>intval($id));
	    $this->load->view("gallery/view.php", $data);
	}
}
