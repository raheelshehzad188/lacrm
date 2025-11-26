<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - CRM</title>

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
                <img class="navbar-brand-icon" src="<?php echo base_url('design/assets/images/stack-logo-blue.svg'); ?>" width="25" alt="CRM">
                <span>CRM</span>
            </a>
        </div>

        <h4 class="m-0">Welcome back!</h4>
        <p class="mb-5">Login to access your CRM Account</p>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo base_url('auth/login'); ?>" method="post" novalidate>
            <div class="form-group">
                <label class="text-label" for="email">Email Address:</label>
                <div class="input-group input-group-merge">
                    <input id="email" name="email" type="email" required="" class="form-control form-control-prepended" placeholder="john@doe.com" value="<?php echo isset($email) ? $email : ''; ?>">
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

</body>
</html>

