<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/Base_Controller.php';

class Users extends Base_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Activity_log_model');
        $this->load->model('Role_model');
        $this->load->library('form_validation');
        $this->load->helper('form');
        
        // Check admin access for user management
        if ($this->uri->segment(2) != 'profile' && $this->uri->segment(2) != 'change_password' && $this->uri->segment(2) != 'update_profile' && $this->uri->segment(2) != 'process_change_password') {
            if ($this->role_id != 1) {
                $this->session->set_flashdata('error', 'Access denied. Admin only.');
                redirect('dashboard');
            }
        }
    }
    
    /**
     * List all users (Admin only)
     */
    public function index()
    {
        $search = $this->input->get('search');
        $role_filter = $this->input->get('role');
        $status_filter = $this->input->get('status');
        
        $filters = array();
        if ($role_filter) {
            $filters['role_id'] = $role_filter;
        }
        if ($status_filter !== null && $status_filter !== '') {
            $filters['status'] = $status_filter;
        }
        
        $users = $this->User_model->get_all($filters);
        
        // Apply search filter
        if ($search) {
            $filtered_users = array();
            foreach ($users as $user) {
                if (stripos($user->name, $search) !== false || 
                    stripos($user->email, $search) !== false ||
                    stripos($user->phone, $search) !== false) {
                    $filtered_users[] = $user;
                }
            }
            $users = $filtered_users;
        }
        
        $roles = $this->Role_model->get_all();
        
        $view_data = array(
            'users' => $users,
            'roles' => $roles,
            'current_search' => $search,
            'current_role' => $role_filter,
            'current_status' => $status_filter,
            'role_id' => $this->role_id,
            'current_user_id' => $this->user_id
        );
        
        $data = array(
            'title' => 'User Management - LÀ CRM',
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
                'page_title' => 'User Management',
                'page_description' => 'Manage system users and permissions',
                'breadcrumbs' => array(
                    array('label' => 'Home', 'url' => 'dashboard'),
                    array('label' => 'Users')
                )
            ),
            'content' => $this->load->view('users_content', $view_data, true)
        );
        
        $this->load->view('layouts/base', $data);
    }
    
    /**
     * Add new user
     */
    public function add()
    {
        $roles = $this->Role_model->get_all();
        
        $view_data = array(
            'roles' => $roles,
            'user' => null
        );
        
        $data = array(
            'title' => 'Add User - LÀ CRM',
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
                'page_title' => 'Add User',
                'page_description' => 'Create a new user account',
                'breadcrumbs' => array(
                    array('label' => 'Home', 'url' => 'dashboard'),
                    array('label' => 'Users', 'url' => 'users'),
                    array('label' => 'Add User')
                )
            ),
            'content' => $this->load->view('users_add', $view_data, true)
        );
        
        $this->load->view('layouts/base', $data);
    }
    
    /**
     * Process add user
     */
    public function process_add()
    {
        if ($this->input->method() !== 'post') {
            redirect('users/add');
            return;
        }
        
        $this->form_validation->set_rules('name', 'Name', 'required|trim|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('role_id', 'Role', 'required|integer');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[20]');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');
        
        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('users/add');
            return;
        }
        
        $user_data = array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'password' => $this->input->post('password'),
            'role_id' => $this->input->post('role_id'),
            'phone' => $this->input->post('phone') ?: null,
            'status' => $this->input->post('status')
        );
        
        $user_id = $this->User_model->create($user_data);
        
        if ($user_id) {
            $this->Activity_log_model->log($this->user_id, 'user_create', 'User created: ' . $user_data['name']);
            $this->session->set_flashdata('success', 'User created successfully.');
            redirect('users');
        } else {
            $this->session->set_flashdata('error', 'Failed to create user. Please try again.');
            redirect('users/add');
        }
    }
    
    /**
     * Edit user
     */
    public function edit($id = null)
    {
        if (!$id) {
            redirect('users');
            return;
        }
        
        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('users');
            return;
        }
        
        $roles = $this->Role_model->get_all();
        
        $view_data = array(
            'user' => $user,
            'roles' => $roles
        );
        
        $data = array(
            'title' => 'Edit User - LÀ CRM',
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
                'page_title' => 'Edit User',
                'page_description' => 'Update user information',
                'breadcrumbs' => array(
                    array('label' => 'Home', 'url' => 'dashboard'),
                    array('label' => 'Users', 'url' => 'users'),
                    array('label' => 'Edit User')
                )
            ),
            'content' => $this->load->view('users_edit', $view_data, true)
        );
        
        $this->load->view('layouts/base', $data);
    }
    
    /**
     * Process edit user
     */
    public function process_edit()
    {
        if ($this->input->method() !== 'post') {
            redirect('users');
            return;
        }
        
        $id = $this->input->post('id');
        if (!$id) {
            redirect('users');
            return;
        }
        
        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('users');
            return;
        }
        
        $this->form_validation->set_rules('name', 'Name', 'required|trim|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('role_id', 'Role', 'required|integer');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[20]');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');
        
        // Check email uniqueness if changed
        $email = $this->input->post('email');
        if ($email != $user->email && $this->User_model->email_exists($email, $id)) {
            $this->session->set_flashdata('error', 'Email already exists.');
            redirect('users/edit/' . $id);
            return;
        }
        
        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('users/edit/' . $id);
            return;
        }
        
        $update_data = array(
            'name' => $this->input->post('name'),
            'email' => $email,
            'role_id' => $this->input->post('role_id'),
            'phone' => $this->input->post('phone') ?: null,
            'status' => $this->input->post('status')
        );
        
        // Update password if provided
        $password = $this->input->post('password');
        if ($password && strlen($password) >= 8) {
            $update_data['password'] = $password;
        }
        
        if ($this->User_model->update($id, $update_data)) {
            $this->Activity_log_model->log($this->user_id, 'user_update', 'User updated: ' . $update_data['name']);
            $this->session->set_flashdata('success', 'User updated successfully.');
            redirect('users');
        } else {
            $this->session->set_flashdata('error', 'Failed to update user. Please try again.');
            redirect('users/edit/' . $id);
        }
    }
    
    /**
     * Delete user
     */
    public function delete($id = null)
    {
        if (!$id) {
            redirect('users');
            return;
        }
        
        // Prevent deleting own account
        if ($id == $this->user_id) {
            $this->session->set_flashdata('error', 'You cannot delete your own account.');
            redirect('users');
            return;
        }
        
        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('users');
            return;
        }
        
        // Soft delete - set status to 0
        if ($this->User_model->update($id, array('status' => 0))) {
            $this->Activity_log_model->log($this->user_id, 'user_delete', 'User deleted: ' . $user->name);
            $this->session->set_flashdata('success', 'User deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user. Please try again.');
        }
        
        redirect('users');
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

        // Load profile view content
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

        if ($this->role_id == 1) {
            $email = $this->input->post('email');
            if ($email && $email != $this->user_data->email) {
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

        if ($this->User_model->update($this->user_id, $update_data)) {
            $this->Activity_log_model->log($this->user_id, 'profile_update', 'Profile updated successfully');
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

        // Load change password view content
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

        $user = $this->User_model->get_by_id($this->user_id);
        
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('users/change_password');
            return;
        }

        if (!$this->User_model->verify_password($current_password, $user->password)) {
            $this->session->set_flashdata('error', 'Current password is incorrect.');
            redirect('users/change_password');
            return;
        }

        if ($this->User_model->update_password($this->user_id, $new_password)) {
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
        $config['max_size'] = 2048;
        $config['encrypt_name'] = true;

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

