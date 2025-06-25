<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->database();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'admin') {
            redirect('auth/admin_login');
        }
    }

    public function dashboard() {
        // Total teachers
        $total_teachers = $this->db->count_all('teachers');
        // Total students
        $total_students = $this->db->count_all('students');
        // Total student attendance
        $this->db->where('student_id IS NOT NULL', null, false);
        $total_student_attendance = $this->db->count_all_results('attendance');
        // Total teacher attendance (if you want to track teacher attendance, otherwise set to 0)
        $this->db->where('student_id IS NULL', null, false);
        $total_teacher_attendance = $this->db->count_all_results('attendance');
        // Total leaves (students + teachers)
        $total_leaves = $this->db->count_all('leaves');
        $data = [
            'total_teachers' => $total_teachers,
            'total_students' => $total_students,
            'total_student_attendance' => $total_student_attendance,
            'total_teacher_attendance' => $total_teacher_attendance,
            'total_leaves' => $total_leaves
        ];
        $this->load->view('admin/dashboard', $data);
    }

    public function students() {
        $this->load->view('admin/students');
    }

    public function teachers() {
        $this->load->view('admin/teachers');
    }

    public function attendance() {
        $this->load->model('Attendance_model');

        // Set default date to today if not provided
        $date = $this->input->get('date') ? $this->input->get('date') : date('Y-m-d');

        $filters = array(
            'date' => $date,
            'class_id' => $this->input->get('class_id'),
            'section_id' => $this->input->get('section_id'),
            'role' => $this->input->get('role')
        );
        $data['attendance_records'] = $this->Attendance_model->get_attendance($filters);
        $data['filters'] = $filters; // Pass filters to the view

        $this->load->view('admin/attendance', $data);
    }

    public function get_attendance_json() {
        $this->load->model('Attendance_model');
        $filters = array(
            'date' => $this->input->get('date'),
            'class_id' => $this->input->get('class_id'),
            'section_id' => $this->input->get('section_id'),
            'role' => $this->input->get('role')
        );
        $attendance = $this->Attendance_model->get_attendance($filters);
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($attendance));
    }

    public function add_attendance_json() {
        $this->load->model('Attendance_model');
        $data = $this->input->post();
        $result = $this->Attendance_model->add_attendance($data);
        return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => $result ? 'success' : 'error']));
    }

    public function update_attendance_json() {
        $this->load->model('Attendance_model');
        $id = $this->input->post('id');
        $data = $this->input->post();
        unset($data['id']);
        $result = $this->Attendance_model->update_attendance($id, $data);
        return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => $result ? 'success' : 'error']));
    }

    public function delete_attendance_json() {
        $this->load->model('Attendance_model');
        $id = $this->input->post('id');
        $result = $this->Attendance_model->delete_attendance($id);
        return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => $result ? 'success' : 'error']));
    }

    public function get_attendance_by_id_json($id) {
        $this->load->model('Attendance_model');
        $attendance = $this->Attendance_model->get_attendance_by_id($id);
        return $this->output->set_content_type('application/json')->set_output(json_encode($attendance));
    }

    public function students_json() {
        $this->load->model('Student_model');
        $students = $this->Student_model->get_all_students();
        return $this->output->set_content_type('application/json')->set_output(json_encode($students));
    }

    public function add_student() {
        $this->load->model('Student_model');
        $data = $this->input->post();
        unset($data['id']);

        // Simple image upload: allow all types, no size limit
        if (!empty($_FILES['profile_image']['name'])) {
            $config['upload_path'] = './uploads/students/';
            $config['allowed_types'] = '*';
            $config['max_size'] = 0;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('profile_image')) {
                $upload_data = $this->upload->data();
                $data['profile_image'] = 'uploads/students/' . $upload_data['file_name'];
            } else {
                return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'msg' => $this->upload->display_errors()]));
            }
        }
        $this->Student_model->insert_student($data);
        return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'success']));
    }

    public function edit_student($id) {
        $this->load->model('Student_model');
        $student = $this->Student_model->get_student_by_id($id);
        return $this->output->set_content_type('application/json')->set_output(json_encode($student));
    }

    public function update_student($id) {
        $this->load->model('Student_model');
        $data = $this->input->post();
        // Simple image upload: allow all types, no size limit
        if (!empty($_FILES['profile_image']['name'])) {
            $config['upload_path'] = './uploads/students/';
            $config['allowed_types'] = '*';
            $config['max_size'] = 0;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('profile_image')) {
                $upload_data = $this->upload->data();
                $data['profile_image'] = 'uploads/students/' . $upload_data['file_name'];
            } else {
                return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'msg' => $this->upload->display_errors()]));
            }
        }
        $this->Student_model->update_student($id, $data);
        return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'success']));
    }

    public function delete_student($id) {
        $this->load->model('Student_model');
        $this->Student_model->delete_student($id);
        return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'success']));
    }

    public function classes_json() {
        $classes = $this->db->get('classes')->result();
        return $this->output->set_content_type('application/json')->set_output(json_encode($classes));
    }

    public function sections_json() {
        $sections = $this->db->get('sections')->result();
        return $this->output->set_content_type('application/json')->set_output(json_encode($sections));
    }

    public function sections_by_class_json($class_id) {
        $sections = $this->db->get_where('sections', ['class_id' => $class_id])->result();
        return $this->output->set_content_type('application/json')->set_output(json_encode($sections));
    }

    public function teachers_json() {
        $this->load->model('Teacher_model');
        $teachers = $this->Teacher_model->get_all_teachers();
        return $this->output->set_content_type('application/json')->set_output(json_encode($teachers));
    }

    public function add_teacher() {
        $this->load->model('Teacher_model');
        $data = $this->input->post();
        // Simple image upload: allow all types, no size limit
        if (!empty($_FILES['profile_image']['name'])) {
            $config['upload_path'] = './uploads/teachers/';
            $config['allowed_types'] = '*';
            $config['max_size'] = 0;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('profile_image')) {
                $upload_data = $this->upload->data();
                $data['profile_image'] = 'uploads/teachers/' . $upload_data['file_name'];
            } else {
                return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'msg' => $this->upload->display_errors()]));
            }
        }
        $this->Teacher_model->insert_teacher($data);
        return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'success']));
    }

    public function edit_teacher($id) {
        $this->load->model('Teacher_model');
        $teacher = $this->Teacher_model->get_teacher_by_id($id);
        return $this->output->set_content_type('application/json')->set_output(json_encode($teacher));
    }

    public function update_teacher($id) {
        $this->load->model('Teacher_model');
        $data = $this->input->post();
        // Simple image upload: allow all types, no size limit
        if (!empty($_FILES['profile_image']['name'])) {
            $config['upload_path'] = './uploads/teachers/';
            $config['allowed_types'] = '*';
            $config['max_size'] = 0;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('profile_image')) {
                $upload_data = $this->upload->data();
                $data['profile_image'] = 'uploads/teachers/' . $upload_data['file_name'];
            } else {
                return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'msg' => $this->upload->display_errors()]));
            }
        }
        $this->Teacher_model->update_teacher($id, $data);
        return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'success']));
    }

    public function delete_teacher($id) {
        $this->load->model('Teacher_model');
        $this->Teacher_model->delete_teacher($id);
        return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'success']));
    }

    public function all_students_json() {
        $this->load->model('Student_model');
        $class_id = $this->input->get('class_id');
        $section_id = $this->input->get('section_id');
        $students = $this->Student_model->get_all_students($class_id, $section_id);
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($students));
    }

    public function all_teachers_json() {
        $this->load->model('Teacher_model');
        $teachers = $this->Teacher_model->get_all_teachers();
        return $this->output->set_content_type('application/json')->set_output(json_encode($teachers));
    }
} 