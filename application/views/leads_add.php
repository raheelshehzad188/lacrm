<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
.lead-form-card {
    max-width: 800px;
    margin: 0 auto;
}

.form-section {
    margin-bottom: 2rem;
}

.form-section-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #212529;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

.form-group label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-group label .required {
    color: #dc3545;
}

.form-actions {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 0 0 0.375rem 0.375rem;
    margin: 2rem -1.5rem -1.5rem -1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
</style>

<?php
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

<div class="card lead-form-card">
    <div class="card-header">
        <h4 class="card-header__title">Create New Lead</h4>
    </div>
    <div class="card-body">
        <?php echo form_open('leads/process_add', array('id' => 'lead-form', 'class' => 'form')); ?>
        
            <!-- Basic Information -->
            <div class="form-section">
                <h5 class="form-section-title">Basic Information</h5>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Lead Name <span class="required">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo set_value('name'); ?>" required autofocus>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo set_value('email'); ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" 
                                   value="<?php echo set_value('phone'); ?>" placeholder="e.g., +92 300 1234567">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lead Details -->
            <div class="form-section">
                <h5 class="form-section-title">Lead Details</h5>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="source">Source</label>
                            <select class="form-control" id="source" name="source">
                                <option value="">-- Select Source --</option>
                                <option value="Facebook Ads" <?php echo set_select('source', 'Facebook Ads'); ?>>Facebook Ads</option>
                                <option value="Instagram Ads" <?php echo set_select('source', 'Instagram Ads'); ?>>Instagram Ads</option>
                                <option value="Meta Ads" <?php echo set_select('source', 'Meta Ads'); ?>>Meta Ads</option>
                                <option value="WhatsApp Bot" <?php echo set_select('source', 'WhatsApp Bot'); ?>>WhatsApp Bot</option>
                                <option value="Manual Entry" <?php echo set_select('source', 'Manual Entry', true); ?>>Manual Entry</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stage">Stage</label>
                            <select class="form-control" id="stage" name="stage">
                                <option value="New" <?php echo set_select('stage', 'New', true); ?>>New</option>
                                <option value="Contacted" <?php echo set_select('stage', 'Contacted'); ?>>Contacted</option>
                                <option value="Interested" <?php echo set_select('stage', 'Interested'); ?>>Interested</option>
                                <option value="Follow-Up" <?php echo set_select('stage', 'Follow-Up'); ?>>Follow-Up</option>
                                <option value="Converted" <?php echo set_select('stage', 'Converted'); ?>>Converted</option>
                                <option value="Lost" <?php echo set_select('stage', 'Lost'); ?>>Lost</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="assigned_to">Assign To</label>
                            <select class="form-control" id="assigned_to" name="assigned_to">
                                <option value="">-- Unassigned --</option>
                                <?php if (!empty($sales_users) && is_array($sales_users)): ?>
                                    <?php foreach ($sales_users as $user): ?>
                                        <option value="<?php echo $user->id; ?>" <?php echo set_select('assigned_to', $user->id); ?>>
                                            <?php echo html_escape($user->name); ?> (<?php echo html_escape($user->role_name); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small class="form-text text-muted">Leave unassigned to assign later</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="follow_up_date">Follow Up Date & Time</label>
                            <input type="datetime-local" class="form-control" id="follow_up_date" name="follow_up_date" 
                                   value="<?php echo set_value('follow_up_date'); ?>">
                            <small class="form-text text-muted">Schedule a follow-up reminder</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="form-section">
                <h5 class="form-section-title">Additional Information</h5>
                
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="4" 
                              placeholder="Add any additional notes about this lead..."><?php echo set_value('notes'); ?></textarea>
                    <small class="form-text text-muted">Maximum 1000 characters</small>
                </div>
            </div>

            <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>

            <!-- Form Actions -->
            <div class="form-actions">
                <div>
                    <a href="<?php echo base_url('leads'); ?>" class="btn btn-secondary">
                        <i class="material-icons" style="font-size: 18px; vertical-align: middle;">close</i>
                        Cancel
                    </a>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="material-icons" style="font-size: 18px; vertical-align: middle;">save</i>
                        Create Lead
                    </button>
                </div>
            </div>

        <?php echo form_close(); ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default follow-up date to tomorrow
    var tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    tomorrow.setHours(10, 0, 0, 0);
    
    var followUpInput = document.getElementById('follow_up_date');
    if (followUpInput && !followUpInput.value) {
        var year = tomorrow.getFullYear();
        var month = String(tomorrow.getMonth() + 1).padStart(2, '0');
        var day = String(tomorrow.getDate()).padStart(2, '0');
        var hours = String(tomorrow.getHours()).padStart(2, '0');
        var minutes = String(tomorrow.getMinutes()).padStart(2, '0');
        followUpInput.value = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
    }

    // Form validation
    var form = document.getElementById('lead-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            var nameInput = document.getElementById('name');
            if (!nameInput.value.trim()) {
                e.preventDefault();
                alert('Please enter lead name.');
                nameInput.focus();
                return false;
            }
        });
    }
});
</script>

