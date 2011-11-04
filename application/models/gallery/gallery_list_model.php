<?php

  class Gallery_list_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        //gandrīz visam vajadzēs datubāzi.
        $this->load->database();		
    }    

    
    /**
     * Uzmeklē tās galerijas, kuru autors ir norādītais lietotājs
     * @param int $user_id - lietotāja ID no "user" tabulas
     * @return array id=>title masīvs
     */
    function get_user_galleries($user_id){
	$this->db->from('gallery')->where(array("author_id"=>$user_id))->select("id, title");
	
	$results = $this->db->get()->result();
	
	$items = array();
	foreach ($results as $value) {
	    $items[$value->id] = $value->title;
	}
	return $items;
	
    }
  }