<?php

  class Photo_list_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
		//visām saraksta metodēm vajadzēs datubāzi
		$this->load->database();		
    }
	
	function recent_five(){
		$query = $this->db->from("picture")->order_by("created", "desc")->limit(5); 
		return $this->db->get()->result(); 	
	}
	
}
?>