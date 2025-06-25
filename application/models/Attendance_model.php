<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_attendance($filters = array()) {
        $this->db->select('a.*, COALESCE(s.name, t.name) as name, IF(s.id IS NOT NULL, "Student", "Teacher") as role');
        $this->db->from('attendance a');
        $this->db->join('students s', 's.id = a.student_id', 'left');
        $this->db->join('teachers t', 't.id = a.teacher_id', 'left');
        
        if (!empty($filters['date'])) {
            $this->db->where('a.date', $filters['date']);
        }
        if (!empty($filters['class_id'])) {
            $this->db->where('a.class_id', $filters['class_id']);
        }
        if (!empty($filters['section_id'])) {
            $this->db->where('a.section_id', $filters['section_id']);
        }
        if (!empty($filters['role'])) {
            if ($filters['role'] == 'Student') {
                $this->db->where('a.student_id IS NOT NULL', null, false);
            } else {
                $this->db->where('a.teacher_id IS NOT NULL', null, false);
            }
        }
        
        $this->db->order_by('a.date', 'DESC');
        $this->db->order_by('name', 'ASC');
        
        return $this->db->get()->result();
    }

    public function add_attendance($data) {
        if (isset($data['student_ids'])) {
            $batch_data = array();
            foreach ($data['student_ids'] as $student_id) {
                $batch_data[] = array(
                    'student_id' => $student_id,
                    'date' => $data['date'],
                    'status' => $data['status'][$student_id],
                    'lecture' => $data['lecture'][$student_id],
                    'class_id' => $data['class_id'],
                    'section_id' => $data['section_id']
                );
            }
            return $this->db->insert_batch('attendance', $batch_data);
        }
        
        if (isset($data['teacher_ids'])) {
            $batch_data = array();
            foreach ($data['teacher_ids'] as $teacher_id) {
                $batch_data[] = array(
                    'teacher_id' => $teacher_id,
                    'date' => $data['date'],
                    'status' => $data['status'][$teacher_id],
                    'lecture' => $data['lecture'][$teacher_id]
                );
            }
            return $this->db->insert_batch('attendance', $batch_data);
        }
        
        return false;
    }

    public function update_attendance($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('attendance', $data);
    }

    public function delete_attendance($id) {
        $this->db->where('id', $id);
        return $this->db->delete('attendance');
    }

    public function get_attendance_by_id($id) {
        return $this->db->get_where('attendance', array('id' => $id))->row();
    }

    public function get_teacher_attendance_summary($teacher_id) {
        $this->db->select('status, COUNT(id) as count');
        $this->db->from('attendance');
        $this->db->where('teacher_id', $teacher_id);
        $this->db->group_by('status');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_teacher_monthly_summary($teacher_id) {
        $this->db->select('status, COUNT(id) as count');
        $this->db->from('attendance');
        $this->db->where('teacher_id', $teacher_id);
        $this->db->where('MONTH(date)', date('m'));
        $this->db->where('YEAR(date)', date('Y'));
        $this->db->group_by('status');
        return $this->db->get()->result();
    }

    public function get_student_attendance_summary_by_teacher($teacher_id) {
        $this->db->select('
            c.class_name,
            sec.section_name,
            sub.subject_name,
            COUNT(att.id) as lecture_count,
            SUM(CASE WHEN att.status = "Present" THEN 1 ELSE 0 END) as present_count
        ');
        $this->db->from('attendance as att');
        $this->db->join('classes as c', 'c.id = att.class_id', 'left');
        $this->db->join('sections as sec', 'sec.id = att.section_id', 'left');
        $this->db->join('subjects as sub', 'sub.id = att.subject_id', 'left');
        $this->db->where('att.teacher_id', $teacher_id);
        $this->db->where('att.student_id IS NOT NULL');
        $this->db->group_by('c.class_name, sec.section_name, sub.subject_name');
        $this->db->order_by('c.class_name, sec.section_name, sub.subject_name');

        return $this->db->get()->result();
    }

    public function add_or_update_attendance($data) {
        $this->db->where('date', $data['date']);
        $this->db->where('student_id', $data['student_id']);
        $this->db->where('subject_id', $data['subject_id']);
        $q = $this->db->get('attendance');

        if ($q->num_rows() > 0) {
            $this->db->where('id', $q->row()->id);
            return $this->db->update('attendance', $data);
        } else {
            return $this->db->insert('attendance', $data);
        }
    }
} 