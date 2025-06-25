<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');

        // Redirect if not logged in or not a teacher
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'teacher') {
            redirect('auth/teacher_login');
        }
        $this->load->model('Teacher_model');
        $this->load->model('Student_model');
    }

    public function dashboard() {
        $data['username'] = $this->session->userdata('username');
        $this->load->view('teacher/dashboard', $data);
    }

    public function my_schedule() {
        $teacher_id = $this->session->userdata('user_id');
        $data['schedule'] = $this->Teacher_model->get_my_schedule($teacher_id);
        $this->load->view('teacher/my_schedule', $data);
    }

    public function my_attendance_report() {
        $this->load->model('Attendance_model');
        $teacher_id = $this->session->userdata('user_id');
        $data['teacher_summary'] = $this->Attendance_model->get_teacher_monthly_summary($teacher_id);
        $this->load->view('teacher/my_attendance_report', $data);
    }

    public function take_attendance() {
        if (!$this->session->userdata('teacher_id')) {
            return redirect('auth/teacher_login');
        }
        $this->load->model('Teacher_model');
        $teacher_id = $this->session->userdata('teacher_id');
        $data['schedule'] = $this->Teacher_model->get_my_schedule($teacher_id);
        $this->load->view('teacher/take_attendance', $data);
    }

    public function get_students_for_attendance() {
        if (!$this->session->userdata('teacher_id')) {
            return redirect('auth/teacher_login');
        }
        $class_id = $this->input->get('class_id');
        $section_id = $this->input->get('section_id');
        $subject_id = $this->input->get('subject_id');
        $date = $this->input->get('date');
        $students = $this->Student_model->get_students_by_class_and_section($class_id, $section_id, $subject_id, $date);
        $this->output->set_content_type('application/json')->set_output(json_encode($students));
    }

    public function save_attendance() {
        if (!$this->session->userdata('teacher_id')) {
            return redirect('auth/teacher_login');
        }
        
        $data = $this->input->post();
        $teacher_id = $this->session->userdata('teacher_id');

        $this->load->model('Attendance_model');
        
        foreach ($data['attendance'] as $record) {
            $attendance_payload = [
                'teacher_id' => $teacher_id,
                'student_id' => $record['student_id'],
                'class_id'   => $data['class_id'],
                'section_id' => $data['section_id'],
                'subject_id' => $data['subject_id'],
                'date'       => $data['date'],
                'status'     => $record['status']
            ];
            
            // This will either insert a new record or update the existing one for that day
            $this->Attendance_model->add_or_update_attendance($attendance_payload);
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => 'success']));
    }

    public function change_password() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('new_password', 'New Password', 'required');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('teacher/change_password');
        } else {
            $new_password = $this->input->post('new_password');
            $teacher_id = $this->session->userdata('user_id');
            
            $this->Teacher_model->update_password($teacher_id, $new_password);
            
            $data['success_message'] = "Password changed successfully!";
            $this->load->view('teacher/change_password', $data);
        }
    }
} 