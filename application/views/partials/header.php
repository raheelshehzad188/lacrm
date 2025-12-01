<!-- Header -->
<div id="header" class="mdk-header js-mdk-header m-0" data-fixed>
    <div class="mdk-header__content">

        <div class="navbar navbar-expand-sm navbar-main navbar-dark bg-dark pr-0" id="navbar" data-primary>
            <div class="container-fluid p-0">

                <!-- Navbar toggler -->
                <button class="navbar-toggler navbar-toggler-custom navbar-toggler-right d-block" type="button" data-toggle="sidebar">
                    <span class="material-icons">apps</span>
                </button>

                <!-- Navbar Brand -->
                <a href="<?php echo base_url(); ?>" class="navbar-brand">
                    <img class="navbar-brand-icon" src="<?php echo base_url('design/assets/images/stack-logo-white.svg'); ?>" width="22" alt="CRM">
                    <span>CRM</span>
                </a>

                <div class="navbar-collapse collapse flex">
                    <ul class="nav navbar-nav">
                        <li class="nav-item">
                            <a href="<?php echo base_url('dashboard'); ?>" class="nav-link">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('companies'); ?>" class="nav-link">Companies</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('contacts'); ?>" class="nav-link">Contacts</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('leads'); ?>" class="nav-link">Leads</a>
                        </li>
                    </ul>
                </div>

                <ul class="nav navbar-nav ml-auto d-none d-md-flex">
                    <li class="nav-item dropdown">
                        <a href="#notifications_menu" class="nav-link dropdown-toggle" data-toggle="dropdown" data-caret="false">
                            <i class="material-icons nav-icon navbar-notifications-indicator">notifications</i>
                        </a>
                        <div id="notifications_menu" class="dropdown-menu dropdown-menu-right navbar-notifications-menu">
                            <div class="dropdown-item d-flex align-items-center py-2">
                                <span class="flex navbar-notifications-menu__title m-0">Notifications</span>
                                <a href="javascript:void(0)" class="text-muted"><small>Clear all</small></a>
                            </div>
                            <div class="navbar-notifications-menu__content" data-simplebar>
                                <div class="py-2">
                                    <div class="dropdown-item d-flex">
                                        <div class="flex">
                                            <small class="text-muted">No new notifications</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#account_menu" class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown" data-caret="false">
                            <?php if (isset($profile_photo) && !empty($profile_photo)): ?>
                                <span class="avatar avatar-sm mr-2">
                                    <img src="<?php echo base_url('uploads/profiles/' . $profile_photo); ?>" alt="<?php echo isset($user_name) ? html_escape($user_name) : 'User'; ?>" class="avatar-img rounded-circle">
                                </span>
                            <?php else: ?>
                                <span class="avatar avatar-sm mr-2">
                                    <span class="avatar-title rounded-circle bg-primary"><?php echo isset($user_name) ? strtoupper(substr($user_name, 0, 1)) : 'U'; ?></span>
                                </span>
                            <?php endif; ?>
                            <span class="d-none d-md-inline">
                                <span class="text-light"><?php echo isset($user_name) ? html_escape($user_name) : 'User'; ?></span>
                                <?php if (isset($role_id)): ?>
                                    <span class="badge badge-light ml-2"><?php 
                                        $role_badges = array(1 => 'Admin', 2 => 'Sales Manager', 3 => 'Sales Person', 4 => 'Doctor');
                                        echo isset($role_badges[$role_id]) ? $role_badges[$role_id] : 'User';
                                    ?></span>
                                <?php endif; ?>
                            </span>
                        </a>
                        <div id="account_menu" class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-item-text dropdown-item-text--lh">
                                <div><strong><?php echo isset($user_name) ? html_escape($user_name) : 'User'; ?></strong></div>
                                <div class="text-muted"><?php echo isset($user_email) ? html_escape($user_email) : 'user@example.com'; ?></div>
                                <?php if (isset($role_id)): ?>
                                    <div class="mt-1">
                                        <span class="badge badge-primary"><?php 
                                            $role_badges = array(1 => 'Admin', 2 => 'Sales Manager', 3 => 'Sales Person', 4 => 'Doctor');
                                            echo isset($role_badges[$role_id]) ? $role_badges[$role_id] : 'User';
                                        ?></span>
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
<!-- // END Header -->

