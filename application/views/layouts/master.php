<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo isset($title) ? $title : 'LÀ CRM'; ?></title>

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

    <?php if (isset($extra_css)): ?>
        <?php foreach ($extra_css as $css): ?>
            <link type="text/css" href="<?php echo base_url($css); ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body class="<?php echo isset($body_class) ? $body_class : 'layout-mini'; ?>">

    <?php if (isset($show_preloader) && $show_preloader): ?>
        <div class="preloader"></div>
    <?php endif; ?>

    <!-- A. TOPBAR -->
    <div class="mdk-header-layout js-mdk-header-layout">
        <div id="header" class="mdk-header js-mdk-header m-0" data-fixed>
            <div class="mdk-header__content">
                <div class="navbar navbar-expand-sm navbar-main navbar-dark bg-dark pr-0" id="navbar" data-primary>
                    <div class="container-fluid p-0">
                        <!-- Navbar toggler -->
                        <button class="navbar-toggler navbar-toggler-custom navbar-toggler-right d-block" type="button" data-toggle="sidebar">
                            <span class="material-icons">apps</span>
                        </button>

                        <!-- Navbar Brand -->
                        <a href="<?php echo base_url('dashboard'); ?>" class="navbar-brand">
                            <img class="navbar-brand-icon" src="<?php echo base_url('design/assets/images/stack-logo-white.svg'); ?>" width="22" alt="LÀ CRM">
                            <span>LÀ CRM</span>
                        </a>

                        <div class="navbar-collapse collapse flex">
                            <!-- Empty space for future nav items -->
                        </div>

                        <!-- User Menu -->
                        <ul class="nav navbar-nav ml-auto d-none d-md-flex">
                            <li class="nav-item dropdown">
                                <a href="#account_menu" class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown" data-caret="false">
                                    <?php 
                                    $user_name = isset($user_data) && isset($user_data->name) ? $user_data->name : (isset($user_name) ? $user_name : 'User');
                                    $profile_photo = isset($user_data) && isset($user_data->profile_photo) ? $user_data->profile_photo : (isset($profile_photo) ? $profile_photo : '');
                                    $role_id = isset($user_data) && isset($user_data->role_id) ? $user_data->role_id : (isset($role_id) ? $role_id : 0);
                                    $role_name = isset($user_data) && isset($user_data->role_name) ? $user_data->role_name : 'User';
                                    $user_email = isset($user_data) && isset($user_data->email) ? $user_data->email : (isset($user_email) ? $user_email : 'user@example.com');
                                    ?>
                                    <?php if (!empty($profile_photo)): ?>
                                        <span class="avatar avatar-sm mr-2">
                                            <img src="<?php echo base_url('uploads/profiles/' . html_escape($profile_photo)); ?>" alt="<?php echo html_escape($user_name); ?>" class="avatar-img rounded-circle">
                                        </span>
                                    <?php else: ?>
                                        <span class="avatar avatar-sm mr-2">
                                            <span class="avatar-title rounded-circle bg-primary"><?php echo strtoupper(substr($user_name, 0, 1)); ?></span>
                                        </span>
                                    <?php endif; ?>
                                    <span class="d-none d-md-inline">
                                        <span class="text-light"><?php echo html_escape($user_name); ?></span>
                                        <?php 
                                        $role_badges = array(
                                            1 => 'Admin',
                                            2 => 'Sales Manager',
                                            3 => 'Sales Person',
                                            4 => 'Doctor'
                                        );
                                        if ($role_id && isset($role_badges[$role_id])): 
                                        ?>
                                            <span class="badge badge-light ml-2"><?php echo $role_badges[$role_id]; ?></span>
                                        <?php endif; ?>
                                    </span>
                                </a>
                                <div id="account_menu" class="dropdown-menu dropdown-menu-right">
                                    <div class="dropdown-item-text dropdown-item-text--lh">
                                        <div><strong><?php echo html_escape($user_name); ?></strong></div>
                                        <div class="text-muted"><?php echo html_escape($user_email); ?></div>
                                        <?php if ($role_id && isset($role_badges[$role_id])): ?>
                                            <div class="mt-1">
                                                <span class="badge badge-primary"><?php echo $role_badges[$role_id]; ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="<?php echo base_url('users/profile'); ?>">
                                        <i class="material-icons">account_circle</i> My Profile
                                    </a>
                                    <a class="dropdown-item" href="<?php echo base_url('users/change_password'); ?>">
                                        <i class="material-icons">lock</i> Change Password
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="<?php echo base_url('auth/logout'); ?>">
                                        <i class="material-icons">exit_to_app</i> Logout
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Horizontal divider below topbar -->
    <hr class="m-0">

    <div class="mdk-drawer-layout js-mdk-drawer-layout" data-push data-responsive-width="992px">
        <div class="mdk-drawer-layout__content page">
            
            <!-- C. MAIN CONTENT AREA -->
            <div class="container-fluid page__container">
                <div class="page-section">
                    <?php 
                    // Render module output using $module_view variable
                    if (isset($module_view) && !empty($module_view)) {
                        echo $module_view;
                    } elseif (isset($content)) {
                        // Fallback to content variable for backward compatibility
                        echo $content;
                    } else {
                        echo '<div class="alert alert-warning">No content to display.</div>';
                    }
                    ?>
                </div>
            </div>

        </div>

        <!-- B. SIDEBAR MENU -->
        <div class="mdk-drawer js-mdk-drawer" id="default-drawer" data-align="start">
            <div class="mdk-drawer__content js-sidebar-mini" data-responsive-width="992px">
                <div class="sidebar sidebar-light sidebar-left simplebar" data-simplebar>
                    <div class="d-flex align-items-center sidebar-p-a border-bottom sidebar-account">
                        <a href="<?php echo base_url('users/profile'); ?>" class="flex d-flex align-items-center text-underline-0 text-body">
                            <?php if (!empty($profile_photo)): ?>
                                <span class="avatar mr-3">
                                    <img src="<?php echo base_url('uploads/profiles/' . html_escape($profile_photo)); ?>" alt="<?php echo html_escape($user_name); ?>" class="avatar-img rounded-circle">
                                </span>
                            <?php else: ?>
                                <span class="avatar mr-3">
                                    <span class="avatar-title rounded-circle"><?php echo strtoupper(substr($user_name, 0, 1)); ?></span>
                                </span>
                            <?php endif; ?>
                            <span class="flex d-flex flex-column">
                                <strong><?php echo html_escape($user_name); ?></strong>
                                <small class="text-muted text-uppercase"><?php echo html_escape($role_name); ?></small>
                            </span>
                        </a>
                    </div>
                    <div class="sidebar-heading sidebar-m-t">Menu</div>
                    <ul class="sidebar-menu">
                        <?php
                        // Role-based menu items
                        // role_id = 1 (Admin)
                        if ($role_id == 1): ?>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="<?php echo base_url('dashboard'); ?>">
                                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">dashboard</span>
                                    <span class="sidebar-menu-text">Dashboard</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="<?php echo base_url('leads'); ?>">
                                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">person_add</span>
                                    <span class="sidebar-menu-text">Leads</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="<?php echo base_url('users'); ?>">
                                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">people</span>
                                    <span class="sidebar-menu-text">Users</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="<?php echo base_url('roles'); ?>">
                                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">admin_panel_settings</span>
                                    <span class="sidebar-menu-text">Roles</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="<?php echo base_url('reports'); ?>">
                                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">assessment</span>
                                    <span class="sidebar-menu-text">Reports</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="<?php echo base_url('settings'); ?>">
                                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">settings</span>
                                    <span class="sidebar-menu-text">Settings</span>
                                </a>
                            </li>
                        <?php
                        // role_id = 2 (Sales Manager)
                        elseif ($role_id == 2): ?>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="<?php echo base_url('dashboard'); ?>">
                                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">dashboard</span>
                                    <span class="sidebar-menu-text">Dashboard</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="<?php echo base_url('leads'); ?>">
                                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">person_add</span>
                                    <span class="sidebar-menu-text">Leads</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="<?php echo base_url('reports'); ?>">
                                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">assessment</span>
                                    <span class="sidebar-menu-text">Reports</span>
                                </a>
                            </li>
                        <?php
                        // role_id = 3 (Sales Person)
                        elseif ($role_id == 3): ?>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="<?php echo base_url('dashboard'); ?>">
                                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">dashboard</span>
                                    <span class="sidebar-menu-text">Dashboard</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="<?php echo base_url('leads/my_leads'); ?>">
                                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">person_add</span>
                                    <span class="sidebar-menu-text">My Leads</span>
                                </a>
                            </li>
                        <?php
                        // role_id = 4 (Doctor)
                        elseif ($role_id == 4): ?>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="<?php echo base_url('dashboard'); ?>">
                                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">dashboard</span>
                                    <span class="sidebar-menu-text">Dashboard</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="<?php echo base_url('patients'); ?>">
                                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">local_hospital</span>
                                    <span class="sidebar-menu-text">My Patients</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="<?php echo base_url('courses'); ?>">
                                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">school</span>
                                    <span class="sidebar-menu-text">My Courses</span>
                                </a>
                            </li>
                        <?php
                        // Default/Unknown role - show only Dashboard
                        else: ?>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="<?php echo base_url('dashboard'); ?>">
                                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">dashboard</span>
                                    <span class="sidebar-menu-text">Dashboard</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Vertical divider between sidebar and main content -->
        <div class="mdk-drawer__border"></div>
    </div>

    <!-- Horizontal divider above footer -->
    <hr class="m-0">

    <!-- D. FOOTER -->
    <footer class="footer bg-white border-top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center py-3">
                    <small class="text-muted">
                        Developed for clinic + courses lead system &copy; <?php echo date('Y'); ?>
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="<?php echo base_url('design/assets/vendor/jquery.min.js'); ?>"></script>

    <!-- Bootstrap -->
    <script src="<?php echo base_url('design/assets/vendor/popper.min.js'); ?>"></script>
    <script src="<?php echo base_url('design/assets/vendor/bootstrap.min.js'); ?>"></script>

    <!-- Simplebar -->
    <script src="<?php echo base_url('design/assets/vendor/simplebar.min.js'); ?>"></script>

    <!-- App JS -->
    <script src="<?php echo base_url('design/assets/js/app.js'); ?>"></script>

    <?php if (isset($extra_js)): ?>
        <?php foreach ($extra_js as $js): ?>
            <script src="<?php echo base_url($js); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

</body>
</html>

