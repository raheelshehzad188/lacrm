<!-- drawer -->
<div class="mdk-drawer js-mdk-drawer" id="default-drawer" data-align="start">
    <div class="mdk-drawer__content js-sidebar-mini" data-responsive-width="992px">

        <!-- Mini Sidebar (Left) - Icons Only -->
        <div class="sidebar sidebar-mini sidebar-primary sidebar-left simplebar" data-simplebar>
            <ul class="nav flex-column sidebar-menu mt-3" id="sidebar-mini-tabs" role="tablist">
                <li class="sidebar-menu-item active" data-toggle="tooltip" data-title="Dashboard" data-placement="right" data-boundary="window">
                    <a class="sidebar-menu-button" href="#sm_dashboard" data-toggle="tab" role="tab" aria-controls="sm_dashboard" aria-selected="true">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dvr</i>
                        <span class="sidebar-menu-text">Dashboard</span>
                    </a>
                </li>
                <?php if ($role_id == 1 || $role_id == 2 || $role_id == 3): // Admin, Sales Manager, Sales Person ?>
                <li class="sidebar-menu-item" data-toggle="tooltip" data-title="Sales" data-placement="right" data-container="body" data-boundary="window">
                    <a class="sidebar-menu-button" href="#sm_sales" data-toggle="tab" role="tab" aria-controls="sm_sales" aria-selected="false">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">trending_up</i>
                        <span class="sidebar-menu-text">Sales</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($role_id == 1 || $role_id == 4): // Admin, Doctor ?>
                <li class="sidebar-menu-item" data-toggle="tooltip" data-title="Clinic" data-placement="right" data-container="body" data-boundary="window">
                    <a class="sidebar-menu-button" href="#sm_clinic" data-toggle="tab" role="tab" aria-controls="sm_clinic">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">local_hospital</i>
                        <span class="sidebar-menu-text">Clinic</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($role_id == 1): // Admin Only ?>
                <li class="sidebar-menu-item" data-toggle="tooltip" data-title="Management" data-placement="right" data-container="body" data-boundary="window">
                    <a class="sidebar-menu-button" href="#sm_management" data-toggle="tab" role="tab" aria-controls="sm_management">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">settings</i>
                        <span class="sidebar-menu-text">Management</span>
                    </a>
                </li>
                <?php endif; ?>
                <li class="sidebar-menu-item" data-toggle="tooltip" data-title="Reports" data-placement="right" data-boundary="window">
                    <a class="sidebar-menu-button" href="#sm_reports" data-toggle="tab" role="tab" aria-controls="sm_reports" aria-selected="false">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">assessment</i>
                        <span class="sidebar-menu-text">Reports</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Sidebar (Right) - Full Menu Items -->
        <div class="sidebar sidebar-light sidebar-left simplebar flex sidebar-secondary" data-simplebar>
            <div class="tab-content" id="sidebar-tab-content">
                
                <!-- Dashboard Tab -->
                <div class="tab-pane fade active show" id="sm_dashboard" role="tabpanel">
                    <div class="sidebar-heading">Dashboard</div>
                    <div class="sidebar-block p-0">
                        <ul class="sidebar-menu">
                            <li class="sidebar-menu-item <?php echo (uri_string() == 'dashboard' || uri_string() == '') ? 'active' : ''; ?>">
                                <a class="sidebar-menu-button" href="<?php echo base_url('dashboard'); ?>">
                                    <span class="sidebar-menu-text">Main Dashboard</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Sales Tab -->
                <?php if ($role_id == 1 || $role_id == 2 || $role_id == 3): // Admin, Sales Manager, Sales Person ?>
                <div class="tab-pane fade" id="sm_sales" role="tabpanel">
                    <div class="sidebar-heading">Sales</div>
                    <div class="sidebar-block p-0">
                        <ul class="sidebar-menu">
                            <li class="sidebar-menu-item <?php echo (uri_string() == 'leads') ? 'active' : ''; ?>">
                                <a class="sidebar-menu-button" href="<?php echo base_url('leads'); ?>">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">person_add</i>
                                    <span class="sidebar-menu-text">Leads</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item <?php echo (uri_string() == 'companies') ? 'active' : ''; ?>">
                                <a class="sidebar-menu-button" href="<?php echo base_url('companies'); ?>">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">business</i>
                                    <span class="sidebar-menu-text">Companies</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item <?php echo (uri_string() == 'contacts') ? 'active' : ''; ?>">
                                <a class="sidebar-menu-button" href="<?php echo base_url('contacts'); ?>">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">contacts</i>
                                    <span class="sidebar-menu-text">Contacts</span>
                                </a>
                            </li>
                            <?php if ($role_id == 1 || $role_id == 2): // Admin, Sales Manager ?>
                            <li class="sidebar-menu-item <?php echo (uri_string() == 'deals') ? 'active' : ''; ?>">
                                <a class="sidebar-menu-button" href="<?php echo base_url('deals'); ?>">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">attach_money</i>
                                    <span class="sidebar-menu-text">Deals</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <li class="sidebar-menu-item <?php echo (uri_string() == 'tasks') ? 'active' : ''; ?>">
                                <a class="sidebar-menu-button" href="<?php echo base_url('tasks'); ?>">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">check_circle</i>
                                    <span class="sidebar-menu-text">Tasks</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Clinic Tab -->
                <?php if ($role_id == 1 || $role_id == 4): // Admin, Doctor ?>
                <div class="tab-pane fade" id="sm_clinic" role="tabpanel">
                    <div class="sidebar-heading">Clinic</div>
                    <div class="sidebar-block p-0">
                        <ul class="sidebar-menu">
                            <li class="sidebar-menu-item <?php echo (uri_string() == 'patients') ? 'active' : ''; ?>">
                                <a class="sidebar-menu-button" href="<?php echo base_url('patients'); ?>">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">people</i>
                                    <span class="sidebar-menu-text">Patients</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item <?php echo (uri_string() == 'courses') ? 'active' : ''; ?>">
                                <a class="sidebar-menu-button" href="<?php echo base_url('courses'); ?>">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">school</i>
                                    <span class="sidebar-menu-text">Courses</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item <?php echo (uri_string() == 'appointments') ? 'active' : ''; ?>">
                                <a class="sidebar-menu-button" href="<?php echo base_url('appointments'); ?>">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">event</i>
                                    <span class="sidebar-menu-text">Appointments</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Management Tab (Admin Only) -->
                <?php if ($role_id == 1): // Admin Only ?>
                <div class="tab-pane fade" id="sm_management" role="tabpanel">
                    <div class="sidebar-heading">Management</div>
                    <div class="sidebar-block p-0">
                        <ul class="sidebar-menu">
                            <li class="sidebar-menu-item <?php echo (strpos(uri_string(), 'users') === 0 && uri_string() != 'users/profile' && uri_string() != 'users/change_password' && uri_string() != 'users/update_profile' && uri_string() != 'users/process_change_password') ? 'active' : ''; ?>">
                                <a class="sidebar-menu-button" href="<?php echo base_url('users'); ?>">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">people</i>
                                    <span class="sidebar-menu-text">Users</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item <?php echo (uri_string() == 'roles') ? 'active' : ''; ?>">
                                <a class="sidebar-menu-button" href="<?php echo base_url('roles'); ?>">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">admin_panel_settings</i>
                                    <span class="sidebar-menu-text">Roles & Permissions</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item <?php echo (uri_string() == 'settings') ? 'active' : ''; ?>">
                                <a class="sidebar-menu-button" href="<?php echo base_url('settings'); ?>">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">settings</i>
                                    <span class="sidebar-menu-text">Settings</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Reports Tab -->
                <div class="tab-pane fade" id="sm_reports" role="tabpanel">
                    <div class="sidebar-heading">Reports</div>
                    <div class="sidebar-block p-0">
                        <ul class="sidebar-menu">
                            <li class="sidebar-menu-item <?php echo (uri_string() == 'reports') ? 'active' : ''; ?>">
                                <a class="sidebar-menu-button" href="<?php echo base_url('reports'); ?>">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">assessment</i>
                                    <span class="sidebar-menu-text">All Reports</span>
                                </a>
                            </li>
                            <?php if ($role_id == 1 || $role_id == 2 || $role_id == 3): // Sales roles ?>
                            <li class="sidebar-menu-item <?php echo (uri_string() == 'reports/leads') ? 'active' : ''; ?>">
                                <a class="sidebar-menu-button" href="<?php echo base_url('reports/leads'); ?>">
                                    <span class="sidebar-menu-text">Lead Reports</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item <?php echo (uri_string() == 'reports/sales') ? 'active' : ''; ?>">
                                <a class="sidebar-menu-button" href="<?php echo base_url('reports/sales'); ?>">
                                    <span class="sidebar-menu-text">Sales Reports</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if ($role_id == 1 || $role_id == 4): // Admin, Doctor ?>
                            <li class="sidebar-menu-item <?php echo (uri_string() == 'reports/courses') ? 'active' : ''; ?>">
                                <a class="sidebar-menu-button" href="<?php echo base_url('reports/courses'); ?>">
                                    <span class="sidebar-menu-text">Course Reports</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
