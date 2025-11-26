<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_Controller extends CI_Controller {

    protected $user_id;
    protected $role_id;
    protected $user_data;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('User_model');
        $this->load->model('Role_model');
        
        // Check if user is logged in
        $this->check_login();
        
        // Load user data if logged in
        if ($this->is_logged_in()) {
            $this->user_id = $this->session->userdata('user_id');
            $this->role_id = $this->session->userdata('role_id');
            $this->user_data = $this->User_model->get_by_id($this->user_id);
        }
    }

    /**
     * Check if user is logged in
     */
    protected function check_login()
    {
        if (!$this->is_logged_in()) {
            // Store current URL to redirect after login
            $this->session->set_userdata('redirect_after_login', current_url());
            redirect('auth/login');
        }
    }

    /**
     * Check if user is logged in
     */
    protected function is_logged_in()
    {
        return $this->session->userdata('logged_in') === true && 
               $this->session->userdata('user_id') !== null;
    }

    /**
     * Check if user has permission to access module
     */
    protected function check_module_access($module_name, $permission_type = 'can_view')
    {
        if (!$this->is_logged_in()) {
            redirect('auth/login');
        }

        // Admin (role_id = 1) has full access to everything
        if ($this->role_id == 1) {
            return true;
        }

        // Check role permission
        $has_permission = $this->Role_model->has_permission($this->role_id, $module_name, $permission_type);
        
        if (!$has_permission) {
            show_error('Access forbidden. You do not have permission to access this module.', 403);
        }

        return true;
    }

    /**
     * Check if user can perform action
     */
    protected function can($module_name, $action = 'view')
    {
        // Admin (role_id = 1) has full access to everything
        if ($this->role_id == 1) {
            return true;
        }
        
        $permission_type = 'can_' . $action;
        return $this->Role_model->has_permission($this->role_id, $module_name, $permission_type);
    }

    /**
     * Restrict access to admin only
     */
    protected function admin_only()
    {
        if (!$this->is_logged_in()) {
            redirect('auth/login');
        }
        
        if ($this->role_id != 1) {
            show_error('Access forbidden. Admin access required.', 403);
        }
    }

    /**
     * Check if current user is admin
     */
    protected function is_admin()
    {
        return $this->is_logged_in() && $this->role_id == 1;
    }
}
