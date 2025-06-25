<?php
class User_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function check_login($username, $password) 
    {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        $user = $query->row();
        if ($user) 
        {
            if ($password === $user->password) 
            {
                return $user;
            } 
            else 
            {
                log_message('error', 'Password mismatch for user: ' . $username);
            }
        } 
        else 
        {
            log_message('error', 'User not found: ' . $username);
        }
        return false;
    }
} 