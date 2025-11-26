<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get user by email
     */
    public function get_by_email($email)
    {
        return $this->db->get_where('users', array('email' => $email))->row();
    }

    /**
     * Get user by ID
     */
    public function get_by_id($id)
    {
        $this->db->select('users.*, roles.role_name');
        $this->db->from('users');
        $this->db->join('roles', 'roles.id = users.role_id', 'left');
        $this->db->where('users.id', $id);
        return $this->db->get()->row();
    }

    /**
     * Verify password
     */
    public function verify_password($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Hash password
     */
    public function hash_password($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Update last login
     */
    public function update_last_login($user_id)
    {
        $this->db->where('id', $user_id);
        $this->db->update('users', array('last_login' => date('Y-m-d H:i:s')));
    }

    /**
     * Update user
     */
    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    /**
     * Update password
     */
    public function update_password($id, $password)
    {
        $data = array(
            'password' => $this->hash_password($password),
            'password_updated_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    /**
     * Check if email exists
     */
    public function email_exists($email, $exclude_id = null)
    {
        $this->db->where('email', $email);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->get('users')->num_rows() > 0;
    }

    /**
     * Create user
     */
    public function create($data)
    {
        if (isset($data['password'])) {
            $data['password'] = $this->hash_password($data['password']);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    /**
     * Get all users (with role info)
     */
    public function get_all($filters = array())
    {
        $this->db->select('users.*, roles.role_name');
        $this->db->from('users');
        $this->db->join('roles', 'roles.id = users.role_id', 'left');
        
        if (isset($filters['status'])) {
            $this->db->where('users.status', $filters['status']);
        }
        
        if (isset($filters['role_id'])) {
            $this->db->where('users.role_id', $filters['role_id']);
        }
        
        $this->db->order_by('users.created_at', 'DESC');
        return $this->db->get()->result();
    }
}


