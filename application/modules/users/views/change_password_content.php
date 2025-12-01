<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Display flash messages
if ($this->session->flashdata('error')) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
    echo $this->session->flashdata('error');
    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
    echo '<span aria-hidden="true">&times;</span>';
    echo '</button>';
    echo '</div>';
}

if ($this->session->flashdata('success')) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
    echo $this->session->flashdata('success');
    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
    echo '<span aria-hidden="true">&times;</span>';
    echo '</button>';
    echo '</div>';
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-header__title">Change Password</h4>
            </div>
            <div class="card-body">
                <?php echo form_open('users/process_change_password', array('class' => 'form')); ?>
                
                    <div class="form-group">
                        <label for="current_password">Current Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required autocomplete="current-password">
                    </div>

                    <div class="form-group">
                        <label for="new_password">New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8" autocomplete="new-password">
                        <small class="form-text text-muted">Minimum 8 characters required.</small>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="8" autocomplete="new-password">
                    </div>

                    <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                    
                    <button type="submit" class="btn btn-primary">Change Password</button>
                    <a href="<?php echo base_url('users/profile'); ?>" class="btn btn-secondary">Back to Profile</a>
                
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
// Client-side password confirmation validation
document.addEventListener('DOMContentLoaded', function() {
    var form = document.querySelector('form');
    var newPassword = document.getElementById('new_password');
    var confirmPassword = document.getElementById('confirm_password');
    
    form.addEventListener('submit', function(e) {
        if (newPassword.value !== confirmPassword.value) {
            e.preventDefault();
            alert('New password and confirm password do not match.');
            confirmPassword.focus();
            return false;
        }
        
        if (newPassword.value.length < 8) {
            e.preventDefault();
            alert('New password must be at least 8 characters long.');
            newPassword.focus();
            return false;
        }
    });
});
</script>

