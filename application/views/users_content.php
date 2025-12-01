<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
// Display flash messages
if ($this->session->flashdata('error')) {
    $error_msg = $this->session->flashdata('error');
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 1.5rem;">';
    echo '<i class="material-icons mr-2" style="vertical-align: middle;">error_outline</i>';
    echo '<strong>Error:</strong> ' . html_escape($error_msg);
    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
    echo '<span aria-hidden="true">&times;</span>';
    echo '</button>';
    echo '</div>';
}

if ($this->session->flashdata('success')) {
    $success_msg = $this->session->flashdata('success');
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 1.5rem;">';
    echo '<i class="material-icons mr-2" style="vertical-align: middle;">check_circle</i>';
    echo '<strong>Success:</strong> ' . html_escape($success_msg);
    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
    echo '<span aria-hidden="true">&times;</span>';
    echo '</button>';
    echo '</div>';
}
?>

<style>
.users-header {
    background: #fff;
    border-bottom: 1px solid #e0e0e0;
    padding: 1rem 1.5rem;
    margin: -1.5rem -1.5rem 1.5rem -1.5rem;
}

.users-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.users-search {
    flex: 1;
    min-width: 250px;
    max-width: 400px;
}

.users-filters {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.filter-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s;
    border: 1px solid #e0e0e0;
    background: #fff;
    color: #666;
    text-decoration: none;
}

.filter-badge:hover {
    background: #f5f5f5;
    border-color: #d0d0d0;
    text-decoration: none;
    color: #666;
}

.filter-badge.active {
    background: #007bff;
    color: #fff;
    border-color: #007bff;
}

.users-table {
    background: #fff;
}

.user-row {
    transition: background 0.2s;
}

.user-row:hover {
    background: #f8f9fa;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #007bff;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1rem;
    margin-right: 0.75rem;
}

.role-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
}

.role-admin { background: #e3f2fd; color: #1976d2; }
.role-sales-manager { background: #fff3e0; color: #f57c00; }
.role-sales-person { background: #e8f5e9; color: #388e3c; }
.role-doctor { background: #f3e5f5; color: #7b1fa2; }

.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
}

.status-active { background: #e8f5e9; color: #2e7d32; }
.status-inactive { background: #ffebee; color: #c62828; }
</style>

<div class="card users-table">
    <!-- Header Toolbar -->
    <div class="users-header">
        <div class="users-toolbar">
            <div class="d-flex align-items-center flex-wrap" style="gap: 1rem; flex: 1;">
                <!-- Search -->
                <form method="get" action="<?php echo base_url('users'); ?>" class="users-search">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0">
                                <i class="material-icons" style="font-size: 20px; color: #6c757d;">search</i>
                            </span>
                        </div>
                        <input type="text" name="search" class="form-control border-left-0" 
                               placeholder="Search users..." 
                               value="<?php echo html_escape($current_search); ?>">
                        <?php if (!empty($current_role)): ?>
                            <input type="hidden" name="role" value="<?php echo html_escape($current_role); ?>">
                        <?php endif; ?>
                        <?php if ($current_status !== null && $current_status !== ''): ?>
                            <input type="hidden" name="status" value="<?php echo html_escape($current_status); ?>">
                        <?php endif; ?>
                    </div>
                </form>

                <!-- Role Filters -->
                <div class="users-filters">
                    <a href="<?php echo base_url('users'); ?>" 
                       class="filter-badge <?php echo empty($current_role) ? 'active' : ''; ?>">
                        All Roles
                    </a>
                    <?php if (!empty($roles) && is_array($roles)): ?>
                        <?php foreach ($roles as $role): ?>
                            <a href="<?php echo base_url('users?role=' . $role->id); ?>" 
                               class="filter-badge <?php echo (isset($current_role) && $current_role == $role->id) ? 'active' : ''; ?>">
                                <?php echo html_escape($role->role_name); ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Status Filters -->
                <div class="users-filters">
                    <a href="<?php echo base_url('users' . (!empty($current_role) ? '?role=' . $current_role : '')); ?>" 
                       class="filter-badge <?php echo ($current_status === null || $current_status === '') ? 'active' : ''; ?>">
                        All Status
                    </a>
                    <a href="<?php echo base_url('users?status=1' . (!empty($current_role) ? '&role=' . $current_role : '')); ?>" 
                       class="filter-badge <?php echo ($current_status == '1') ? 'active' : ''; ?>">
                        Active
                    </a>
                    <a href="<?php echo base_url('users?status=0' . (!empty($current_role) ? '&role=' . $current_role : '')); ?>" 
                       class="filter-badge <?php echo ($current_status == '0') ? 'active' : ''; ?>">
                        Inactive
                    </a>
                </div>
            </div>

            <!-- Actions -->
            <div class="d-flex align-items-center" style="gap: 0.5rem;">
                <a href="<?php echo base_url('users/add'); ?>" class="btn btn-primary">
                    <i class="material-icons" style="font-size: 18px; vertical-align: middle;">add</i>
                    Add User
                </a>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card-body p-0">
        <?php if (!empty($users) && is_array($users) && count($users) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="min-width: 250px;">User</th>
                            <th style="min-width: 150px;">Role</th>
                            <th style="min-width: 150px;">Email</th>
                            <th style="min-width: 120px;">Phone</th>
                            <th style="min-width: 100px;">Status</th>
                            <th style="min-width: 150px;">Created</th>
                            <th style="width: 150px; text-align: right; padding-right: 1.5rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr class="user-row">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar">
                                        <?php echo strtoupper(substr($user->name, 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div style="font-weight: 500; color: #212529;">
                                            <?php echo html_escape($user->name); ?>
                                        </div>
                                        <?php if ($user->last_login): ?>
                                            <div class="small text-muted">
                                                Last login: <?php echo date('M d, Y', strtotime($user->last_login)); ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="small text-muted">Never logged in</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="role-badge role-<?php echo strtolower(str_replace(' ', '-', $user->role_name)); ?>">
                                    <?php echo html_escape($user->role_name); ?>
                                </span>
                            </td>
                            <td>
                                <div><?php echo html_escape($user->email); ?></div>
                            </td>
                            <td>
                                <?php echo html_escape($user->phone ?: '-'); ?>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo $user->status == 1 ? 'active' : 'inactive'; ?>">
                                    <?php echo $user->status == 1 ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td>
                                <div class="small text-muted">
                                    <?php echo date('M d, Y', strtotime($user->created_at)); ?>
                                </div>
                            </td>
                            <td style="text-align: right; padding-right: 1.5rem;">
                                <div class="d-flex align-items-center justify-content-end" style="gap: 0.5rem;">
                                    <a href="<?php echo base_url('users/edit/' . $user->id); ?>" 
                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="material-icons" style="font-size: 18px;">edit</i>
                                    </a>
                                    <?php 
                                    // Don't allow deleting own account or admin (user ID 1)
                                    $current_user_id = isset($current_user_id) ? $current_user_id : null;
                                    if ($user->id != $current_user_id && $user->id != 1): ?>
                                    <a href="<?php echo base_url('users/delete/' . $user->id); ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       title="Delete"
                                       onclick="return confirm('Are you sure you want to delete this user?');">
                                        <i class="material-icons" style="font-size: 18px;">delete</i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center p-5">
                <i class="material-icons" style="font-size: 4rem; color: #dee2e6; margin-bottom: 1rem;">people</i>
                <h5 class="mb-2">No users found</h5>
                <p class="text-muted mb-4">
                    <?php if (!empty($current_search) || !empty($current_role) || ($current_status !== null && $current_status !== '')): ?>
                        Try adjusting your filters to see more results.
                    <?php else: ?>
                        Get started by adding your first user.
                    <?php endif; ?>
                </p>
                <?php if (empty($current_search) && empty($current_role) && ($current_status === null || $current_status === '')): ?>
                <a href="<?php echo base_url('users/add'); ?>" class="btn btn-primary">
                    <i class="material-icons" style="font-size: 18px; vertical-align: middle;">add</i>
                    Add User
                </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Auto-submit search on enter
document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                if (this.form) {
                    this.form.submit();
                }
            }
        });
    }
});
</script>

