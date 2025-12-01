<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Flash Helper
 * Helper functions for displaying flash messages (success, error, info, warning)
 */

if (!function_exists('show_flash_alert')) {
    /**
     * Show flash alert message
     * 
     * @param string $type Alert type: success, error, warning, info
     * @param string $message Alert message
     * @param bool $dismissible Whether alert can be dismissed
     * @return string HTML alert
     */
    function show_flash_alert($type = 'info', $message = '', $dismissible = true)
    {
        if (empty($message)) {
            return '';
        }
        
        $alert_classes = array(
            'success' => 'alert-success',
            'error' => 'alert-danger',
            'danger' => 'alert-danger',
            'warning' => 'alert-warning',
            'info' => 'alert-info'
        );
        
        $alert_colors = array(
            'success' => array('bg' => '#d4edda', 'text' => '#155724', 'border' => '#28a745'),
            'error' => array('bg' => '#f8d7da', 'text' => '#721c24', 'border' => '#dc3545'),
            'danger' => array('bg' => '#f8d7da', 'text' => '#721c24', 'border' => '#dc3545'),
            'warning' => array('bg' => '#fff3cd', 'text' => '#856404', 'border' => '#ffc107'),
            'info' => array('bg' => '#d1ecf1', 'text' => '#0c5460', 'border' => '#17a2b8')
        );
        
        $icons = array(
            'success' => 'check_circle',
            'error' => 'error_outline',
            'danger' => 'error_outline',
            'warning' => 'warning',
            'info' => 'info'
        );
        
        $class = isset($alert_classes[$type]) ? $alert_classes[$type] : 'alert-info';
        $color = isset($alert_colors[$type]) ? $alert_colors[$type] : $alert_colors['info'];
        $icon = isset($icons[$type]) ? $icons[$type] : 'info';
        
        $dismissible_class = $dismissible ? 'alert-dismissible fade show' : '';
        $close_button = $dismissible ? '<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); font-size: 20px; font-weight: bold; opacity: 0.5; background: none; border: none; cursor: pointer;"><span aria-hidden="true">&times;</span></button>' : '';
        
        $html = '<div class="alert ' . $class . ' ' . $dismissible_class . '" role="alert" id="flashAlert" style="display: block !important; margin-bottom: 20px; padding: 12px 20px; border-left: 4px solid ' . $color['border'] . '; background-color: ' . $color['bg'] . '; color: ' . $color['text'] . '; border-radius: 4px; position: relative; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">';
        $html .= '<i class="material-icons mr-2" style="vertical-align: middle; font-size: 20px;">' . $icon . '</i>';
        $html .= '<strong style="font-weight: 600;">' . ucfirst($type) . ':</strong> ';
        $html .= '<span style="margin-left: 5px;">' . html_escape($message) . '</span>';
        $html .= $close_button;
        $html .= '</div>';
        
        return $html;
    }
}

if (!function_exists('get_flash_message')) {
    /**
     * Get flash message from session
     * 
     * @param string $type Message type: success, error, warning, info
     * @return string|false Flash message or false if not set
     */
    function get_flash_message($type = 'error')
    {
        $CI =& get_instance();
        $CI->load->library('session');
        
        return $CI->session->flashdata($type);
    }
}

if (!function_exists('display_flash_alerts')) {
    /**
     * Display all flash alerts from session
     * Checks for: error, success, warning, info
     * 
     * @return string HTML for all flash alerts
     */
    function display_flash_alerts()
    {
        $CI =& get_instance();
        $CI->load->library('session');
        
        $html = '';
        $types = array('error', 'success', 'warning', 'info');
        
        foreach ($types as $type) {
            $message = $CI->session->flashdata($type);
            if (!empty($message) && is_string($message)) {
                $html .= show_flash_alert($type, $message, true);
            }
        }
        
        return $html;
    }
}

