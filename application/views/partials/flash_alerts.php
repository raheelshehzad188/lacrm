<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load flash helper
$this->load->helper('flash');

// Get flash alerts from session first
$flash_output = display_flash_alerts();

// Only show additional messages if no flashdata exists (to avoid duplicates)
if (empty($flash_output)) {
    // Check for error_message variable (for backward compatibility)
    if (isset($error_message) && !empty($error_message)) {
        $flash_output = show_flash_alert('error', $error_message, true);
    }
    
    // Check for success_message variable
    if (isset($success_message) && !empty($success_message)) {
        $flash_output = show_flash_alert('success', $success_message, true);
    }
    
    // Display validation errors (only if no flashdata)
    if (validation_errors()) {
        $flash_output = show_flash_alert('error', validation_errors(), true);
    }
}

// Display once
echo $flash_output;
?>

