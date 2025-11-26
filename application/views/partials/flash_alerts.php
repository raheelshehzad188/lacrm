<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load flash helper
$this->load->helper('flash');

// Display flash alerts from session (error, success, warning, info)
echo display_flash_alerts();

// Check for error_message variable (for backward compatibility)
if (isset($error_message) && !empty($error_message)) {
    echo show_flash_alert('error', $error_message, true);
}

// Check for success_message variable
if (isset($success_message) && !empty($success_message)) {
    echo show_flash_alert('success', $success_message, true);
}

// Display validation errors
if (validation_errors()) {
    echo show_flash_alert('error', validation_errors(), true);
}
?>

