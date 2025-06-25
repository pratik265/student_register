<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Student_model');
        $this->load->model('Attendance_model');
        $this->load->model('Class_model');
        $this->load->model('Section_model');
        $this->load->model('Holiday_model');

        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'student') {
            redirect('auth/student_login');
        }
    }

    public function dashboard() {
        $student_id = $this->session->userdata('user_id');
        $username = $this->session->userdata('username');
        
        // Get student details
        $student = $this->Student_model->get_student_by_id($student_id);
        
        // Get today's date
        $today = date('Y-m-d');
        $current_month = date('Y-m');
        $current_year = date('Y');
        
        // Get today's lectures count
        $today_lectures = $this->get_today_lectures_count($student_id, $today);
        
        // Get attendance statistics
        $attendance_stats = $this->get_attendance_statistics($student_id, $today, $current_month, $current_year);
        
        // Get subject-wise attendance for today
        $subject_attendance = $this->get_subject_attendance_today($student_id, $today);
        
        // Get class and section names
        $class_name = '';
        $section_name = '';
        if ($student) {
            $class = $this->Class_model->get_class_by_id($student->class_id);
            $section = $this->Section_model->get_section_by_id($student->section_id);
            $class_name = $class ? $class->class_name : '';
            $section_name = $section ? $section->section_name : '';
        }
        
        // Get holidays for current year
        $holidays = $this->Holiday_model->get_holidays($current_year);
        
        // Get upcoming holidays
        $upcoming_holidays = $this->Holiday_model->get_upcoming_holidays(8);
        
        $data = array(
            'username' => $username,
            'student' => $student,
            'class_name' => $class_name,
            'section_name' => $section_name,
            'today_lectures' => $today_lectures,
            'attendance_stats' => $attendance_stats,
            'subject_attendance' => $subject_attendance,
            'holidays' => $holidays,
            'upcoming_holidays' => $upcoming_holidays
        );
        
        $this->load->view('student/dashboard', $data);
    }
    
    private function get_today_lectures_count($student_id, $date) {
        $this->db->select('COUNT(DISTINCT subject_id) as lecture_count');
        $this->db->from('attendance');
        $this->db->where('student_id', $student_id);
        $this->db->where('date', $date);
        $result = $this->db->get()->row();
        return $result ? $result->lecture_count : 0;
    }
    
    private function get_attendance_statistics($student_id, $today, $current_month, $current_year) {
        $stats = array();
        
        // Today's attendance
        $this->db->select('COUNT(*) as total, SUM(CASE WHEN status = "Present" THEN 1 ELSE 0 END) as present');
        $this->db->from('attendance');
        $this->db->where('student_id', $student_id);
        $this->db->where('date', $today);
        $today_result = $this->db->get()->row();
        
        $stats['today_total'] = $today_result ? $today_result->total : 0;
        $stats['today_present'] = $today_result ? $today_result->present : 0;
        $stats['today_percentage'] = $stats['today_total'] > 0 ? round(($stats['today_present'] / $stats['today_total']) * 100) : 0;
        
        // This month's attendance
        $this->db->select('COUNT(*) as total, SUM(CASE WHEN status = "Present" THEN 1 ELSE 0 END) as present');
        $this->db->from('attendance');
        $this->db->where('student_id', $student_id);
        $this->db->where('DATE_FORMAT(date, "%Y-%m") =', $current_month);
        $month_result = $this->db->get()->row();
        
        $stats['month_total'] = $month_result ? $month_result->total : 0;
        $stats['month_present'] = $month_result ? $month_result->present : 0;
        $stats['month_percentage'] = $stats['month_total'] > 0 ? round(($stats['month_present'] / $stats['month_total']) * 100) : 0;
        
        // This year's attendance
        $this->db->select('COUNT(*) as total, SUM(CASE WHEN status = "Present" THEN 1 ELSE 0 END) as present');
        $this->db->from('attendance');
        $this->db->where('student_id', $student_id);
        $this->db->where('YEAR(date)', $current_year);
        $year_result = $this->db->get()->row();
        
        $stats['year_total'] = $year_result ? $year_result->total : 0;
        $stats['year_present'] = $year_result ? $year_result->present : 0;
        $stats['year_percentage'] = $stats['year_total'] > 0 ? round(($stats['year_present'] / $stats['year_total']) * 100) : 0;
        
        return $stats;
    }
    
    private function get_subject_attendance_today($student_id, $date) {
        $this->db->select('sub.subject_name, COUNT(*) as total_lectures, SUM(CASE WHEN att.status = "Present" THEN 1 ELSE 0 END) as attended');
        $this->db->from('attendance att');
        $this->db->join('subjects sub', 'sub.id = att.subject_id', 'left');
        $this->db->where('att.student_id', $student_id);
        $this->db->where('att.date', $date);
        $this->db->group_by('att.subject_id, sub.subject_name');
        $this->db->order_by('sub.subject_name');
        
        return $this->db->get()->result();
    }
} 