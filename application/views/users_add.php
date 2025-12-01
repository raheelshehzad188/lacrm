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
?>

<div class="card">
    <div class="card-body">
        <form method="post" action="<?php echo base_url('users/process_add'); ?>">
            <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?php echo set_value('name'); ?>" required>
                        <?php echo form_error('name', '<div class="text-danger small">', '</div>'); ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo set_value('email'); ?>" required>
                        <?php echo form_error('email', '<div class="text-danger small">', '</div>'); ?>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" 
                               minlength="8" required>
                        <small class="form-text text-muted">Minimum 8 characters</small>
                        <?php echo form_error('password', '<div class="text-danger small">', '</div>'); ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" 
                               value="<?php echo set_value('phone'); ?>">
                        <?php echo form_error('phone', '<div class="text-danger small">', '</div>'); ?>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="role_id">Role <span class="text-danger">*</span></label>
                        <select class="form-control" id="role_id" name="role_id" required>
                            <option value="">Select Role</option>
                            <?php if (!empty($roles) && is_array($roles)): ?>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?php echo $role->id; ?>" <?php echo set_select('role_id', $role->id); ?>>
                                        <?php echo html_escape($role->role_name); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php echo form_error('role_id', '<div class="text-danger small">', '</div>'); ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="1" <?php echo set_select('status', '1', true); ?>>Active</option>
                            <option value="0" <?php echo set_select('status', '0'); ?>>Inactive</option>
                        </select>
                        <?php echo form_error('status', '<div class="text-danger small">', '</div>'); ?>
                    </div>
                </div>
            </div>
            
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="material-icons" style="font-size: 18px; vertical-align: middle;">save</i>
                    Create User
                </button>
                <a href="<?php echo base_url('users'); ?>" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

