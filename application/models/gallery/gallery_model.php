<?php

  class Gallery_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        //gandrīz visam vajadzēs datubāzi.
        $this->load->database();		
    }    
  
    
    
  }
