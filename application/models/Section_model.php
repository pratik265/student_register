<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Section_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_sections_by_class($class_id) {
        $this->db->where('class_id', $class_id);
        $this->db->order_by('section_name', 'ASC');
        return $this->db->get('sections')->result();
    }
    
    public function get_section_by_id($id) {
        return $this->db->get_where('sections', ['id' => $id])->row();
    }
} 