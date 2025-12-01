<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/Base_Controller.php';

class Reports extends Base_Controller {

    public function index()
    {
        $this->check_module_access('reports');
        
        $data = array(
            'title' => 'Reports - LÀ CRM',
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
                'page_title' => 'Reports',
                'page_description' => 'View system reports'
            ),
            'content' => '<div class="card"><div class="card-body"><p class="text-muted">Reports module coming soon.</p></div></div>'
        );
        
        $this->load->view('layouts/base', $data);
    }
}

