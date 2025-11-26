<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'core/MX_Controller.php';

class Base_Module_Controller extends MX_Controller {

    protected $user_id;
    protected $role_id;
    protected $user_data;
    protected $role_name;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('User_model');
        $this->load->model('Role_model');
        $this->load->helper('url');
        
        $this->check_login();
        $this->load_user_data();
    }

    /**
     * Check if user is logged in
     */
    protected function check_login()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    /**
     * Load user data from session
     */
    protected function load_user_data()
    {
        $this->user_id = $this->session->userdata('user_id');
        $this->role_id = $this->session->userdata('role_id');
        
        if ($this->user_id) {
            $this->user_data = $this->User_model->get_by_id($this->user_id);
            if ($this->user_data) {
                $this->role_name = $this->user_data->role_name;
            }
        }
    }

    /**
     * Check if user has permission for module
     */
    protected function check_permission($module_name, $permission_type = 'can_view')
    {
        if ($this->role_id == 1) { // Admin has full access
            return true;
        }
        
        return $this->Role_model->has_permission($this->role_id, $module_name, $permission_type);
    }

    /**
     * Require permission or show error
     */
    protected function require_permission($module_name, $permission_type = 'can_view')
    {
        if (!$this->check_permission($module_name, $permission_type)) {
            show_error('You do not have permission to access this resource.', 403);
        }
    }

    /**
     * Check if user can access module
     */
    protected function can_access_module($module_name)
    {
        return $this->check_permission($module_name, 'can_view');
    }

    /**
     * Check if user is admin
     */
    protected function is_admin()
    {
        return $this->role_id == 1;
    }

    /**
     * Check if user is Sales Manager
     */
    protected function is_sales_manager()
    {
        return $this->role_id == 2;
    }

    /**
     * Check if user is Sales Person
     */
    protected function is_sales_person()
    {
        return $this->role_id == 3;
    }

    /**
     * Check if user is Doctor
     */
    protected function is_doctor()
    {
        return $this->role_id == 4;
    }

    /**
     * Load view with master layout
     * This method loads a module view and wraps it in the master layout
     * 
     * @param string $module_view Path to the module view file (e.g., 'dashboard/content' or 'modules/dashboard/views/content')
     * @param array $view_data Data to pass to the module view
     * @param array $layout_data Additional data for the master layout (title, extra_css, extra_js, etc.)
     */
    protected function load_master_view($module_view, $view_data = array(), $layout_data = array())
    {
        // Load the module view content
        $module_content = $this->load->view($module_view, $view_data, true);
        
        // Prepare master layout data
        $master_data = array(
            'module_view' => $module_content,
            'user_data' => $this->user_data,
            'user_name' => isset($this->user_data) ? $this->user_data->name : 'User',
            'user_email' => isset($this->user_data) ? $this->user_data->email : 'user@example.com',
            'role_id' => $this->role_id,
            'profile_photo' => isset($this->user_data) ? $this->user_data->profile_photo : '',
            'title' => isset($layout_data['title']) ? $layout_data['title'] : 'LÀ CRM',
            'body_class' => isset($layout_data['body_class']) ? $layout_data['body_class'] : 'layout-mini',
            'show_preloader' => isset($layout_data['show_preloader']) ? $layout_data['show_preloader'] : false,
            'extra_css' => isset($layout_data['extra_css']) ? $layout_data['extra_css'] : array(),
            'extra_js' => isset($layout_data['extra_js']) ? $layout_data['extra_js'] : array()
        );
        
        // Merge any additional layout data
        $master_data = array_merge($master_data, $layout_data);
        
        // Load master layout
        $this->load->view('layouts/master', $master_data);
    }
}

