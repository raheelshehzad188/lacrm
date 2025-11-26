<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$data = array(
    'title' => 'Dashboard - CRM',
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
        'page_title' => 'Dashboard',
        'page_description' => 'Welcome to your CRM dashboard'
    ),
    'content' => $this->load->view('dashboard_content', null, true)
);
$this->load->view('layouts/base', $data);
?>

