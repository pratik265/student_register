<?php
class Student_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function insert_student($data) {
        return $this->db->insert('students', $data);
    }
    public function get_all_students($class_id = null, $section_id = null) {
        if ($class_id) {
            $this->db->where('class_id', $class_id);
        }
        if ($section_id) {
            $this->db->where('section_id', $section_id);
        }
        return $this->db->get('students')->result();
    }
    public function get_student_by_id($id) {
        return $this->db->get_where('students', ['id' => $id])->row();
    }
    public function update_student($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('students', $data);
    }
    public function delete_student($id) {
        $this->db->where('id', $id);
        return $this->db->delete('students');
    }
    public function get_students_by_class($class_id) {
        $this->db->select('s.id, s.name, s.profile_image');
        $this->db->from('students as s');
        $this->db->join('sections sec', 'sec.id = s.section_id');
        $this->db->where('sec.class_id', $class_id);
        $this->db->order_by('s.name', 'asc');
        return $this->db->get()->result();
    }
    public function add_student($data) {
        // Unset ID to prevent errors if it's accidentally passed
    }
    public function get_students_by_section($section_id) {
        $this->db->select('id, name, profile_image');
        $this->db->from('students');
        $this->db->where('section_id', $section_id);
        $this->db->order_by('name', 'asc');
        return $this->db->get()->result();
    }
    public function get_students_by_class_and_section($class_id, $section_id, $subject_id, $date) {
        if (!$class_id || !$section_id || !$subject_id || !$date) {
            return [];
        }

        $this->db->select('students.id, students.name, students.profile_image, attendance.status');
        $this->db->from('students');
        
        $join_condition = 'attendance.student_id = students.id AND attendance.date = ' . $this->db->escape($date) . ' AND attendance.subject_id = ' . $this->db->escape($subject_id);
        $this->db->join('attendance', $join_condition, 'left');

        $this->db->where('students.class_id', $class_id);
        $this->db->where('students.section_id', $section_id);
        $this->db->order_by('students.name', 'ASC');

        return $this->db->get()->result();
    }
    public function check_login($class_id, $section_id, $roll_no, $password) {
        $this->db->where('class_id', $class_id);
        $this->db->where('section_id', $section_id);
        $this->db->where('roll_no', $roll_no);
        $this->db->where('password', $password);
        return $this->db->get('students')->row();
    }
} 