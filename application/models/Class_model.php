<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Class_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_classes() {
        $this->db->order_by('class_name', 'ASC');
        return $this->db->get('classes')->result();
    }
    
    public function get_class_by_id($id) {
        return $this->db->get_where('classes', ['id' => $id])->row();
    }
} 