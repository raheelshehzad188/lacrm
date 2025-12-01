<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'core/Base_Module_Controller.php';

class Users extends Base_Module_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Activity_log_model');
        $this->load->library('form_validation');
        $this->load->helper('form');
    }

    /**
     * User Profile Page
     */
    public function profile()
    {
        $user = $this->User_model->get_by_id($this->user_id);
        
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('dashboard');
            return;
        }

        // Prepare data for view
        $data = array(
            'title' => 'My Profile - LÀ CRM',
            'body_class' => 'layout-mini',
            'show_preloader' => false,
            'user' => $user,
            'header' => array(
                'user_name' => $this->user_data->name,
                'user_email' => $this->user_data->email,
                'user_id' => $this->user_id,
                'role_id' => $this->role_id,
                'profile_photo' => $this->user_data->profile_photo
            ),
            'sidebar' => array(
                'user_name' => $this->user_data->name,
                'user_role' => $this->user_data->role_name,
                'role_id' => $this->role_id
            ),
            'page_header' => array(
                'page_title' => 'My Profile',
                'page_description' => 'View and update your profile information'
            )
        );

        // Load profile view content (try module view first, then fallback)
        $view_file = APPPATH . 'modules/users/views/profile_content.php';
        if (file_exists($view_file)) {
            ob_start();
            extract($data);
            include($view_file);
            $data['content'] = ob_get_clean();
        } else {
            $data['content'] = $this->load->view('users/profile_content', $data, true);
        }
        
        // Load base layout
        $this->load->view('layouts/base', $data);
    }

    /**
     * Update Profile
     */
    public function update_profile()
    {
        if ($this->input->method() !== 'post') {
            redirect('users/profile');
            return;
        }

        $this->form_validation->set_rules('name', 'Name', 'required|trim|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[20]');
        $this->form_validation->set_rules('bio', 'Bio', 'trim|max_length[500]');

        // Email can only be changed by admin
        if ($this->role_id == 1) {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        }

        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('users/profile');
            return;
        }

        $update_data = array(
            'name' => $this->input->post('name'),
            'phone' => $this->input->post('phone'),
            'bio' => $this->input->post('bio')
        );

        // Only admin can change email
        if ($this->role_id == 1) {
            $email = $this->input->post('email');
            if ($email && $email != $this->user_data->email) {
                // Check if email already exists
                if ($this->User_model->email_exists($email, $this->user_id)) {
                    $this->session->set_flashdata('error', 'Email already exists.');
                    redirect('users/profile');
                    return;
                }
                $update_data['email'] = $email;
            }
        }

        // Handle profile photo upload
        if (!empty($_FILES['profile_photo']['name'])) {
            $upload_result = $this->upload_profile_photo();
            if ($upload_result['success']) {
                // Delete old photo if exists
                if ($this->user_data->profile_photo) {
                    $old_photo_path = FCPATH . 'uploads/profiles/' . $this->user_data->profile_photo;
                    if (file_exists($old_photo_path)) {
                        @unlink($old_photo_path);
                    }
                }
                $update_data['profile_photo'] = $upload_result['file_name'];
            } else {
                $this->session->set_flashdata('error', $upload_result['error']);
                redirect('users/profile');
                return;
            }
        }

        // Update user
        if ($this->User_model->update($this->user_id, $update_data)) {
            // Log activity
            $this->Activity_log_model->log($this->user_id, 'profile_update', 'Profile updated successfully');
            
            // Update session data
            $this->session->set_userdata('user_name', $update_data['name']);
            if (isset($update_data['email'])) {
                $this->session->set_userdata('user_email', $update_data['email']);
            }
            
            $this->session->set_flashdata('success', 'Profile updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update profile. Please try again.');
        }

        redirect('users/profile');
    }

    /**
     * Change Password Page
     */
    public function change_password()
    {
        $data = array(
            'title' => 'Change Password - LÀ CRM',
            'body_class' => 'layout-mini',
            'show_preloader' => false,
            'header' => array(
                'user_name' => $this->user_data->name,
                'user_email' => $this->user_data->email,
                'user_id' => $this->user_id,
                'role_id' => $this->role_id,
                'profile_photo' => $this->user_data->profile_photo
            ),
            'sidebar' => array(
                'user_name' => $this->user_data->name,
                'user_role' => $this->user_data->role_name,
                'role_id' => $this->role_id
            ),
            'page_header' => array(
                'page_title' => 'Change Password',
                'page_description' => 'Update your account password'
            )
        );

        // Load change password view content (try module view first, then fallback)
        $view_file = APPPATH . 'modules/users/views/change_password_content.php';
        if (file_exists($view_file)) {
            ob_start();
            extract($data);
            include($view_file);
            $data['content'] = ob_get_clean();
        } else {
            $data['content'] = $this->load->view('users/change_password_content', $data, true);
        }
        
        // Load base layout
        $this->load->view('layouts/base', $data);
    }

    /**
     * Process Password Change
     */
    public function process_change_password()
    {
        if ($this->input->method() !== 'post') {
            redirect('users/change_password');
            return;
        }

        $this->form_validation->set_rules('current_password', 'Current Password', 'required');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');

        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('users/change_password');
            return;
        }

        $current_password = $this->input->post('current_password');
        $new_password = $this->input->post('new_password');

        // Get current user data
        $user = $this->User_model->get_by_id($this->user_id);
        
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('users/change_password');
            return;
        }

        // Verify current password
        if (!$this->User_model->verify_password($current_password, $user->password)) {
            $this->session->set_flashdata('error', 'Current password is incorrect.');
            redirect('users/change_password');
            return;
        }

        // Update password
        if ($this->User_model->update_password($this->user_id, $new_password)) {
            // Log activity
            $this->Activity_log_model->log($this->user_id, 'password_change', 'Password changed successfully');
            
            $this->session->set_flashdata('success', 'Password changed successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to change password. Please try again.');
        }

        redirect('users/change_password');
    }

    /**
     * Upload Profile Photo
     */
    private function upload_profile_photo()
    {
        $config['upload_path'] = FCPATH . 'uploads/profiles/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = 2048; // 2MB
        $config['encrypt_name'] = true;

        // Create upload directory if it doesn't exist
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, true);
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('profile_photo')) {
            return array(
                'success' => false,
                'error' => $this->upload->display_errors('', '')
            );
        }

        $upload_data = $this->upload->data();
        return array(
            'success' => true,
            'file_name' => $upload_data['file_name']
        );
    }
}

