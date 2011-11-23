<?php

  class Photo_list_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
		//visām saraksta metodēm vajadzēs datubāzi
		$this->load->database();		
    }
	
    /**
     * Ielasa visas kādā galerijā esošas bildes
     * @param type $gallery 
     */
    function from_gallery($gallery){
	$query = $this->db->from("picture")->where("gallery_id", $gallery->id); 	
	return $this->db->get()->result(); 	
    }
    
    /**
     * Ielasa piecus jaunākos attēlus
     * @return type 
     */
    function recent_five(){
	$query = $this->db->from("picture")->order_by("created", "desc")->limit(5); 
	return $this->db->get()->result(); 	
    }
	
}
?>