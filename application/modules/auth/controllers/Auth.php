<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MX_Controller.php';

class Auth extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('url', 'form');
        $this->load->model('User_model');
        $this->load->model('Role_model');
        $this->load->model('Activity_log_model');
        
        // If already logged in, redirect to dashboard (but allow logout)
        $current_method = $this->router->method;
        if ($this->session->userdata('logged_in') === true && $current_method !== 'logout') {
            redirect('dashboard');
        }
    }

    /**
     * Login page
     */
    public function login()
    {
        if ($this->input->method() === 'post') {
            $this->process_login();
        } else {
            $this->show_login();
        }
    }

    /**
     * Show login form
     */
    private function show_login()
    {
        $data = array();
        
        // Get error message from flashdata
        $error = $this->session->flashdata('error');
        if ($error) {
            $data['error_message'] = $error;
        }
        
        // Get email from flashdata to pre-fill
        if ($this->session->flashdata('email')) {
            $data['email'] = $this->session->flashdata('email');
        }
        
        // Also check for validation errors
        if (validation_errors()) {
            $data['error_message'] = validation_errors();
        }
        
        // Load module view directly using file path
        // HMVC module views need to be loaded with full path
        $view_file = APPPATH . 'modules/auth/views/login.php';
        if (file_exists($view_file)) {
            // Extract variables for view
            extract($data);
            // Load the view file directly
            include($view_file);
        } else {
            // Fallback to regular view
            $this->load->view('auth/login', $data);
        }
    }

    /**
     * Process login
     */
    private function process_login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        // Validate input
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');
        
        // Set custom error messages
        $this->form_validation->set_message('required', 'The {field} field is required.');
        $this->form_validation->set_message('valid_email', 'Please enter a valid email address.');

        if ($this->form_validation->run() === false) {
            // Keep form data and show validation errors
            $this->session->set_flashdata('email', $email);
            $this->show_login();
            return;
        }

        // Get user by email
        $user = $this->User_model->get_by_email($email);

        if (!$user) {
            $this->session->set_flashdata('error', 'Invalid email or password.');
            $this->session->set_flashdata('email', $email);
            redirect('auth/login');
            return;
        }

        // Check if account is active
        if ($user->status != 1) {
            $this->session->set_flashdata('error', 'Account inactive. Please contact administrator.');
            $this->session->set_flashdata('email', $email);
            redirect('auth/login');
            return;
        }

        // Verify password
        if (!$this->User_model->verify_password($password, $user->password)) {
            $this->session->set_flashdata('error', 'Invalid email or password.');
            $this->session->set_flashdata('email', $email);
            redirect('auth/login');
            return;
        }

        // Check role access (if needed)
        // For now, all roles can login

        // Set session data
        $session_data = array(
            'user_id' => $user->id,
            'role_id' => $user->role_id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'logged_in' => true
        );
        $this->session->set_userdata($session_data);

        // Update last login
        $this->User_model->update_last_login($user->id);

        // Log activity
        $this->Activity_log_model->log($user->id, 'login', 'User logged in successfully');

        // Redirect based on role or stored redirect URL
        $redirect_url = $this->session->userdata('redirect_after_login');
        if ($redirect_url) {
            $this->session->unset_userdata('redirect_after_login');
            redirect($redirect_url);
        } else {
            redirect('dashboard');
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        // Get user_id before destroying session
        $user_id = $this->session->userdata('user_id');
        
        // Log activity before destroying session
        if ($user_id) {
            try {
                $this->load->model('Activity_log_model');
                $this->Activity_log_model->log($user_id, 'logout', 'User logged out');
            } catch (Exception $e) {
                // Log error but continue with logout
                log_message('error', 'Logout activity log failed: ' . $e->getMessage());
            }
        }

        // Clear all session data
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('role_id');
        $this->session->unset_userdata('user_name');
        $this->session->unset_userdata('user_email');
        $this->session->unset_userdata('logged_in');
        
        // Destroy session completely
        $this->session->sess_destroy();

        // Clear session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Redirect to login with success message
        $this->session->set_flashdata('success', 'You have been logged out successfully.');
        redirect('auth/login');
    }

    /**
     * Forgot password page
     */
    public function forgot_password()
    {
        if ($this->input->method() === 'post') {
            $this->process_forgot_password();
        } else {
            $this->show_forgot_password();
        }
    }

    /**
     * Show forgot password form
     */
    private function show_forgot_password()
    {
        $data = array();
        if ($this->session->flashdata('error')) {
            $data['error_message'] = $this->session->flashdata('error');
        }
        if ($this->session->flashdata('success')) {
            $data['success_message'] = $this->session->flashdata('success');
        }
        // Load module view directly using file path
        $view_file = APPPATH . 'modules/auth/views/forgot_password.php';
        if (file_exists($view_file)) {
            // Extract variables for view
            extract($data);
            // Load the view file directly
            include($view_file);
        } else {
            // Fallback to regular view
            $this->load->view('auth/forgot_password', $data);
        }
    }

    /**
     * Process forgot password
     */
    private function process_forgot_password()
    {
        $email = $this->input->post('email');

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('error', 'Please provide a valid email address.');
            redirect('auth/forgot_password');
            return;
        }

        $user = $this->User_model->get_by_email($email);

        if (!$user) {
            // Don't reveal if email exists for security
            $this->session->set_flashdata('success', 'If the email exists, a password reset link has been sent.');
            redirect('auth/forgot_password');
            return;
        }

        // TODO: Implement password reset token generation and email sending
        // For now, just show success message
        $this->session->set_flashdata('success', 'Password reset functionality will be implemented soon. Please contact administrator.');
        redirect('auth/forgot_password');
    }
}

