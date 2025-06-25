<?php
class Teacher_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function insert_teacher($data) {
        return $this->db->insert('teachers', $data);
    }
    public function get_all_teachers() {
        return $this->db->get('teachers')->result();
    }
    public function get_teacher_by_id($id) {
        return $this->db->get_where('teachers', ['id' => $id])->row();
    }
    public function update_teacher($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('teachers', $data);
    }
    public function check_login($email, $password) {
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        return $this->db->get('teachers')->row();
    }
    public function update_password($teacher_id, $new_password) {
        $this->db->where('id', $teacher_id);
        return $this->db->update('teachers', ['password' => $new_password]);
    }
    public function get_my_schedule($teacher_id) {
        $this->db->select('
            sub.id as subject_id, 
            sub.subject_name, 
            c.id as class_id, 
            c.class_name,
            sec.id as section_id,
            sec.section_name
        ');
        $this->db->from('subjects as sub');
        $this->db->join('sections as sec', 'sec.id = sub.class_id', 'left');
        $this->db->join('classes as c', 'c.id = sec.class_id', 'left');
        $this->db->where('sub.teacher_id', $teacher_id);
        $this->db->order_by('c.class_name, sec.section_name, sub.subject_name', 'asc');
        return $this->db->get()->result();
    }
    public function delete_teacher($id) {
        $this->db->where('id', $id);
        return $this->db->delete('teachers');
    }
    public function get_teacher_attendance_summary($teacher_id) {
        $this->db->select('status, COUNT(id) as count');
        // ... existing code ...
    }
} 