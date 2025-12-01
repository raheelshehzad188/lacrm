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

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-header__title">Profile Information</h4>
            </div>
            <div class="card-body">
                <?php echo form_open_multipart('users/update_profile', array('class' => 'form')); ?>
                
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo html_escape($user->name); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo html_escape($user->email); ?>" <?php echo ($role_id != 1) ? 'readonly' : ''; ?>>
                        <?php if ($role_id != 1): ?>
                            <small class="form-text text-muted">Email cannot be changed. Contact administrator.</small>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo html_escape($user->phone); ?>">
                    </div>

                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea class="form-control" id="bio" name="bio" rows="4"><?php echo html_escape($user->bio); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="profile_photo">Profile Photo</label>
                        <input type="file" class="form-control-file" id="profile_photo" name="profile_photo" accept="image/*">
                        <small class="form-text text-muted">Allowed types: JPG, PNG, GIF. Max size: 2MB</small>
                        <?php if ($user->profile_photo): ?>
                            <div class="mt-2">
                                <img src="<?php echo base_url('uploads/profiles/' . $user->profile_photo); ?>" alt="Profile Photo" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <input type="text" class="form-control" value="<?php echo html_escape($user->role_name); ?>" readonly>
                        <small class="form-text text-muted">Role cannot be changed from profile page.</small>
                    </div>

                    <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                    
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                    <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-secondary">Cancel</a>
                
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-header__title">Account Information</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Last Login:</strong><br>
                    <span class="text-muted">
                        <?php echo $user->last_login ? date('d M Y, h:i A', strtotime($user->last_login)) : 'Never'; ?>
                    </span>
                </div>
                
                <div class="mb-3">
                    <strong>Password Updated:</strong><br>
                    <span class="text-muted">
                        <?php echo $user->password_updated_at ? date('d M Y, h:i A', strtotime($user->password_updated_at)) : 'Never'; ?>
                    </span>
                </div>
                
                <div class="mb-3">
                    <strong>Account Status:</strong><br>
                    <span class="badge badge-<?php echo $user->status == 1 ? 'success' : 'danger'; ?>">
                        <?php echo $user->status == 1 ? 'Active' : 'Inactive'; ?>
                    </span>
                </div>
                
                <div class="mt-4">
                    <a href="<?php echo base_url('users/change_password'); ?>" class="btn btn-outline-primary btn-block">
                        <i class="material-icons">lock</i> Change Password
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

