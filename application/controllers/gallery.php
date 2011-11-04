<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Gallery extends CI_Controller {

    /**
     *
     * @param type $id parādāmās galerijas ID no datubāzes
     */
    public function view($id = 0)
	{	 
	    $data = array("id"=>intval($id));
	    $this->load->view("gallery/view.php", $data);
	}
}
