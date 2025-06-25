<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
        $this->load->helper(array('url', 'form'));
    }

    public function login() {
        if ($this->session->userdata('logged_in')) {
            redirect($this->_redirect_by_role($this->session->userdata('role')));
        }
        $this->load->view('auth/login');
    }

    public function admin_login() {
        $this->load->view('auth/admin_login', ['role' => 'admin']);
    }

    public function teacher_login() {
        $this->load->view('auth/teacher_login', ['role' => 'teacher']);
    }

    public function student_login() {
        $this->load->model('Class_model');
        $data['classes'] = $this->Class_model->get_all_classes();
        $this->load->view('auth/student_login', $data);
    }

    public function get_sections_json($class_id) {
        $this->load->model('Section_model');
        $sections = $this->Section_model->get_sections_by_class($class_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($sections));
    }

    private function _role_login($role) {
        // This function is no longer needed and can be removed, 
        // but for safety, we'll leave it empty.
    }

    public function do_login($role = null) {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        
        $user = null;

        if ($role === 'teacher') {
            $this->load->model('Teacher_model');
            $user_data = $this->Teacher_model->check_login($username, $password); // Using 'username' field for email
            if ($user_data) {
                $user = (object) [
                    'id' => $user_data->id,
                    'username' => $user_data->name,
                    'role' => 'teacher'
                ];
            }
        } else if ($role === 'student') {
            $this->load->model('Student_model');
            $student_data = $this->Student_model->check_login(
                $this->input->post('class_id'),
                $this->input->post('section_id'),
                $this->input->post('roll_no'),
                $this->input->post('password')
            );
            if ($student_data) {
                $user = (object) [
                    'id' => $student_data->id,
                    'username' => $student_data->name,
                    'role' => 'student'
                ];
            }
        } else {
            $user = $this->User_model->check_login($username, $password);
        }

        if ($user) {
            $session_data = [
                'username'  => $user->username,
                'role'      => $user->role,
                'logged_in' => TRUE
            ];

            // Always set both for safety, but teacher pages will use teacher_id
            if (strtolower($user->role) === 'teacher') {
                $session_data['teacher_id'] = $user->id;
            } else if (strtolower($user->role) === 'student') {
                $session_data['student_id'] = $user->id;
            }
            $session_data['user_id'] = $user->id;

            $this->session->set_userdata($session_data);
            redirect($this->_redirect_by_role($user->role));
        } 
        else 
        {
            $data['error'] = 'Invalid username, password, or role';
            $data['role'] = $role;
            if ($role === 'student') {
                $this->load->model('Class_model');
                $data['classes'] = $this->Class_model->get_all_classes();
            }
            $this->load->view('auth/' . $role . '_login', $data);
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }

    private function _redirect_by_role($role) {
        switch ($role) {
            case 'admin': return 'admin/dashboard';
            case 'teacher': return 'teacher/dashboard';
            case 'student': return 'student/dashboard';
            case 'parent': return 'student/dashboard';
            default: return 'auth/login';
        }
    }
} 