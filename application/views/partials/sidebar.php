<!-- Sidebar -->
<div class="mdk-drawer js-mdk-drawer" id="default-drawer" data-align="start">
    <div class="mdk-drawer__content js-sidebar-mini" data-responsive-width="992px">
        <div class="sidebar sidebar-light sidebar-left simplebar" data-simplebar>
            <div class="d-flex align-items-center sidebar-p-a border-bottom sidebar-account">
                <a href="<?php echo base_url('profile'); ?>" class="flex d-flex align-items-center text-underline-0 text-body">
                    <span class="avatar mr-3">
                        <span class="avatar-title rounded-circle"><?php echo isset($user_name) ? substr($user_name, 0, 1) : 'U'; ?></span>
                    </span>
                    <span class="flex d-flex flex-column">
                        <strong><?php echo isset($user_name) ? $user_name : 'User'; ?></strong>
                        <small class="text-muted text-uppercase"><?php echo isset($user_role) ? $user_role : 'User'; ?></small>
                    </span>
                </a>
            </div>
            <div class="sidebar-heading sidebar-m-t">Menu</div>
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="<?php echo base_url('dashboard'); ?>">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">dashboard</span>
                        <span class="sidebar-menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="<?php echo base_url('companies'); ?>">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">business</span>
                        <span class="sidebar-menu-text">Companies</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="<?php echo base_url('contacts'); ?>">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">contacts</span>
                        <span class="sidebar-menu-text">Contacts</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="<?php echo base_url('leads'); ?>">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">person_add</span>
                        <span class="sidebar-menu-text">Leads</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="<?php echo base_url('deals'); ?>">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">attach_money</span>
                        <span class="sidebar-menu-text">Deals</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="<?php echo base_url('tasks'); ?>">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">check_circle</span>
                        <span class="sidebar-menu-text">Tasks</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="<?php echo base_url('reports'); ?>">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">assessment</span>
                        <span class="sidebar-menu-text">Reports</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

