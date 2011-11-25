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
    var $public = false;
    var $photos = array();
    
     /**
     * Zinot ieraksta ID, nolasa galerijas datus
     * @param int $id meklētās galerijas identifikators
     * @return Gallery_model atgriež pats sevi, lai būtu iespējama metožu ķēdēšana
     */
    public function read_by_id($id){
	 $id = intval($id);
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
    
    
    public function load_from_post(){
	$this->title = $this->input->post("title");
	$this->description = $this->input->post("description");
	$this->public = $this->input->post("public");
    }
    
    
    
    public function is_valid(){
	$this->load->library('form_validation');
	
	$this->form_validation->set_rules('title', 
					  'Virsraksts', 
					  'required|max_length[255]');
	
	$this->form_validation->set_rules('description', 
					   'Apraksts', 
					   'required');
	
	return ($this->form_validation->run());
    }
    
    /**
     * Saglabā jaunu ierakstu, pieņem, ka dati jau ir derīgi
     */
    public function save(){
	$data = array(
	   'title' => $this->title ,
	   'description' => $this->description,
	   'author_id' => 1,
	   'public' => $this->public
	);
	$this->db->insert('gallery', $data); 
	$this->id = $this->db->insert_id();
    }
    
    /**
     * Aizpilda klases atribūtu "photos", ielasa tajā
     * fotogrāfijas, kas atbilst šai galerijai.
     */
    public function find_photos(){
	$this->load->model("photo/photo_list_model");
	$this->photos = $this->photo_list_model->from_gallery($this);
    }
  }
