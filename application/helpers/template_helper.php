<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Template Helper
 * 
 * Helper functions for loading views with template
 */

if (!function_exists('load_template')) {
    /**
     * Load a view with base template
     * 
     * @param string $view View name
     * @param array $data Data to pass to view
     * @param array $template_data Template configuration
     */
    function load_template($view, $data = array(), $template_data = array())
    {
        $CI =& get_instance();
        
        // Default template data
        $default_template = array(
            'title' => 'CRM',
            'body_class' => 'layout-mini',
            'show_preloader' => true,
            'header' => array(
                'user_name' => 'User',
                'user_email' => 'user@example.com'
            ),
            'sidebar' => array(
                'user_name' => 'User',
                'user_role' => 'User'
            ),
            'page_header' => array(
                'page_title' => 'Page',
                'page_description' => ''
            ),
            'extra_css' => array(),
            'extra_js' => array()
        );
        
        // Merge with provided template data
        $template_data = array_merge($default_template, $template_data);
        
        // Load view content
        $content = $CI->load->view($view, $data, true);
        $template_data['content'] = $content;
        
        // Load base template
        $CI->load->view('layouts/base', $template_data);
    }
}

