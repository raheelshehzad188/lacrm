<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - LÀ CRM</title>

    <!-- Prevent the demo from appearing in search engines -->
    <meta name="robots" content="noindex">

    <!-- Simplebar -->
    <link type="text/css" href="<?php echo base_url('design/assets/vendor/simplebar.min.css'); ?>" rel="stylesheet">

    <!-- App CSS -->
    <link type="text/css" href="<?php echo base_url('design/assets/css/app.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url('design/assets/css/app.rtl.css'); ?>" rel="stylesheet">

    <!-- Material Design Icons -->
    <link type="text/css" href="<?php echo base_url('design/assets/css/vendor-material-icons.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url('design/assets/css/vendor-material-icons.rtl.css'); ?>" rel="stylesheet">

    <!-- Font Awesome FREE Icons -->
    <link type="text/css" href="<?php echo base_url('design/assets/css/vendor-fontawesome-free.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url('design/assets/css/vendor-fontawesome-free.rtl.css'); ?>" rel="stylesheet">
</head>

<body class="layout-login">

    <div class="layout-login__overlay"></div>
    <div class="layout-login__form bg-white" data-simplebar>
        <div class="d-flex justify-content-center mt-2 mb-5 navbar-light">
            <a href="<?php echo base_url(); ?>" class="navbar-brand" style="min-width: 0">
                <img class="navbar-brand-icon" src="<?php echo base_url('design/assets/images/stack-logo-blue.svg'); ?>" width="25" alt="LÀ CRM">
                <span>LÀ CRM</span>
            </a>
        </div>

        <h4 class="m-0">Welcome back!</h4>
        <p class="mb-5">Login to access your CRM Account</p>

        <?php 
        // Include flash alerts partial (handles error, success, warning, info from flashdata)
        $this->load->view('partials/flash_alerts');
        ?>

        <form action="<?php echo base_url('auth/login'); ?>" method="post" novalidate>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            
            <div class="form-group">
                <label class="text-label" for="email">Email Address:</label>
                <div class="input-group input-group-merge">
                    <input id="email" name="email" type="email" required="" class="form-control form-control-prepended" placeholder="john@doe.com" value="<?php echo isset($email) ? html_escape($email) : ''; ?>">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="far fa-envelope"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="text-label" for="password">Password:</label>
                <div class="input-group input-group-merge">
                    <input id="password" name="password" type="password" required="" class="form-control form-control-prepended" placeholder="Enter your password">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-key"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group text-center">
                <button class="btn btn-primary mb-2" type="submit">Login</button><br>
                <a class="text-body text-underline" href="<?php echo base_url('auth/forgot_password'); ?>">Forgot password?</a>
            </div>
        </form>

    </div>

    <!-- jQuery -->
    <script src="<?php echo base_url('design/assets/vendor/jquery.min.js'); ?>"></script>

    <!-- Bootstrap -->
    <script src="<?php echo base_url('design/assets/vendor/popper.min.js'); ?>"></script>
    <script src="<?php echo base_url('design/assets/vendor/bootstrap.min.js'); ?>"></script>

    <!-- Simplebar -->
    <script src="<?php echo base_url('design/assets/vendor/simplebar.min.js'); ?>"></script>

    <!-- App JS -->
    <script src="<?php echo base_url('design/assets/js/app.js'); ?>"></script>

    <style>
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .alert-danger {
            animation: slideDown 0.3s ease-out;
            box-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);
        }
    </style>
    
    <script>
    // Auto-scroll to error alert and highlight it
    document.addEventListener('DOMContentLoaded', function() {
        var errorAlert = document.getElementById('errorAlert');
        if (errorAlert) {
            // Scroll to error
            errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
            // Add pulse effect
            errorAlert.style.animation = 'slideDown 0.3s ease-out';
        }
    });
    
    // Form validation on submit - show Bootstrap alert instead of browser alert
    document.querySelector('form').addEventListener('submit', function(e) {
        var email = document.getElementById('email').value.trim();
        var password = document.getElementById('password').value.trim();
        
        // Remove existing alerts
        var existingAlert = document.getElementById('flashAlert');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        if (!email) {
            e.preventDefault();
            showBootstrapAlert('Please enter your email address.');
            document.getElementById('email').focus();
            return false;
        }
        
        if (!password) {
            e.preventDefault();
            showBootstrapAlert('Please enter your password.');
            document.getElementById('password').focus();
            return false;
        }
        
        // Basic email validation
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            showBootstrapAlert('Please enter a valid email address.');
            document.getElementById('email').focus();
            return false;
        }
    });
    
    // Function to show Bootstrap alert
    function showBootstrapAlert(message, type = 'error') {
        var alertDiv = document.createElement('div');
        alertDiv.id = 'flashAlert';
        alertDiv.className = 'alert alert-' + (type === 'error' ? 'danger' : type) + ' alert-dismissible fade show';
        alertDiv.setAttribute('role', 'alert');
        
        var colors = {
            'error': { bg: '#f8d7da', text: '#721c24', border: '#dc3545' },
            'success': { bg: '#d4edda', text: '#155724', border: '#28a745' },
            'warning': { bg: '#fff3cd', text: '#856404', border: '#ffc107' },
            'info': { bg: '#d1ecf1', text: '#0c5460', border: '#17a2b8' }
        };
        
        var icons = {
            'error': 'error_outline',
            'success': 'check_circle',
            'warning': 'warning',
            'info': 'info'
        };
        
        var color = colors[type] || colors['error'];
        var icon = icons[type] || icons['error'];
        
        alertDiv.style.cssText = 'display: block !important; margin-bottom: 20px; padding: 12px 20px; border-left: 4px solid ' + color.border + '; background-color: ' + color.bg + '; color: ' + color.text + '; border-radius: 4px; position: relative; box-shadow: 0 2px 4px rgba(0,0,0,0.1); animation: slideDown 0.3s ease-out;';
        alertDiv.innerHTML = '<i class="material-icons mr-2" style="vertical-align: middle; font-size: 20px;">' + icon + '</i><strong style="font-weight: 600;">' + type.charAt(0).toUpperCase() + type.slice(1) + ':</strong> <span style="margin-left: 5px;">' + message + '</span>' +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); font-size: 20px; font-weight: bold; opacity: 0.5; background: none; border: none; cursor: pointer;"><span aria-hidden="true">&times;</span></button>';
        
        // Insert before form
        var form = document.querySelector('form');
        form.parentNode.insertBefore(alertDiv, form);
        
        // Scroll to alert
        alertDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    </script>

</body>
</html>

