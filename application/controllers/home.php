<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#neviens kontrolleris nedrīkstētu strādāt, ja tas nav ticis ielādēts 
/**
 * Autors: 		Krišs Rauhvargers
 * Izveidots: 	05.08.2011 
 * Fails:  		Sistēmas sākums - portāla centrālā lapa
 */
class Home extends MY_Controller {
		
	public function index()
	{		
                $this->load->model("photo/photo_list_model");
                
		
		$viewdata = $this->DefaultViewData();
                $viewdata["rowset"] = $this->photo_list_model->recent_five(); 
                $viewdata["pagetitle"] = "Fotoportāls";
                
                $this->load->view('home/index', $viewdata);
                
	}
}

