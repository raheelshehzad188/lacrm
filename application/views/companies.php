<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$data = array(
    'title' => 'Companies - CRM',
    'body_class' => 'layout-mini',
    'show_preloader' => true,
    'header' => array(
        'user_name' => 'Admin User',
        'user_email' => 'admin@crm.com'
    ),
    'sidebar' => array(
        'user_name' => 'Admin User',
        'user_role' => 'Administrator'
    ),
    'page_header' => array(
        'page_title' => 'Companies',
        'page_description' => 'Manage your companies',
        'page_actions' => '<a href="' . base_url('companies/add') . '" class="btn btn-primary"><i class="material-icons">add</i> Add Company</a>'
    ),
    'content' => $this->load->view('companies_content', null, true)
);
$this->load->view('layouts/base', $data);
?>

