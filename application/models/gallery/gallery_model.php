<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

  class Gallery_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        //gandrīz visam vajadzēs datubāzi.
        $this->load->database();		
    }    
  
    var $id = 0;
    var $title = "";
    var $description = "";
    var $author_id = 0;
    var $created = 0;
    
     /**
     * Zinot ieraksta ID, nolasa galerijas datus
      * 
     * @param int $id meklētās galerijas identifikators
     * @return Gallery_model atgriež pats sevi, lai būtu iespējama metožu ķēdēšana
     */
    public function read_by_id($id){
         $query = $this->db->from("gallery")->where(array("id" => $id))->limit(1,0); 
         $results = $query->get()->result();
         if (count($results) > 0){
             //ja ir ielasījies kaut viens rezultāts
             $this->id = $results[0]->id;
             $this->title = $results[0]->title;             
             $this->description = $results[0]->description;
             $this->author_id = $results[0]->author_id;
             $this->created = $results[0]->created;
         } else {
             $this->id = 0; //pazīme, ka nav atrasts
         }
         return $this;
    }
  }
