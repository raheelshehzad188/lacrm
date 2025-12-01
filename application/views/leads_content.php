<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
// Display flash messages - Check both flashdata and flash helper
$has_flash = false;

// Check direct flashdata first
if ($this->session->flashdata('error')) {
    $error_msg = $this->session->flashdata('error');
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 1.5rem; display: block !important;">';
    echo '<i class="material-icons mr-2" style="vertical-align: middle;">error_outline</i>';
    echo '<strong>Error:</strong> ' . html_escape($error_msg);
    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%);">';
    echo '<span aria-hidden="true">&times;</span>';
    echo '</button>';
    echo '</div>';
    $has_flash = true;
}

if ($this->session->flashdata('success')) {
    $success_msg = $this->session->flashdata('success');
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 1.5rem; display: block !important;">';
    echo '<i class="material-icons mr-2" style="vertical-align: middle;">check_circle</i>';
    echo '<strong>Success:</strong> ' . html_escape($success_msg);
    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%);">';
    echo '<span aria-hidden="true">&times;</span>';
    echo '</button>';
    echo '</div>';
    $has_flash = true;
}

// Also try flash helper if no direct flashdata
if (!$has_flash) {
    $this->load->helper('flash');
    $flash_output = display_flash_alerts();
    if (!empty($flash_output)) {
        echo '<div style="margin-bottom: 1.5rem;">' . $flash_output . '</div>';
    }
}
?>

<style>
.leads-header {
    background: #fff;
    border-bottom: 1px solid #e0e0e0;
    padding: 1rem 1.5rem;
    margin: -1.5rem -1.5rem 1.5rem -1.5rem;
}

.leads-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.leads-search {
    flex: 1;
    min-width: 250px;
    max-width: 400px;
}

.leads-filters {
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
}

.filter-badge:hover {
    background: #f5f5f5;
    border-color: #d0d0d0;
}

.filter-badge.active {
    background: #007bff;
    color: #fff;
    border-color: #007bff;
}

.leads-table {
    background: #fff;
}

.lead-row {
    transition: background 0.2s;
    cursor: pointer;
}

.lead-row:hover {
    background: #f8f9fa;
}

.stage-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
}

.stage-new { background: #e3f2fd; color: #1976d2; }
.stage-contacted { background: #fff3e0; color: #f57c00; }
.stage-interested { background: #f3e5f5; color: #7b1fa2; }
.stage-follow-up { background: #e8f5e9; color: #388e3c; }
.stage-converted { background: #e8f5e9; color: #2e7d32; }
.stage-lost { background: #ffebee; color: #c62828; }

.source-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    background: #f5f5f5;
    color: #666;
}

.lead-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #007bff;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    margin-right: 0.75rem;
}

.lead-name-email {
    display: flex;
    flex-direction: column;
}

.lead-name {
    font-weight: 500;
    color: #212529;
    margin-bottom: 0.125rem;
}

.lead-email {
    font-size: 0.875rem;
    color: #6c757d;
}

.lead-actions {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    padding: 0.25rem 0.5rem;
    border: none;
    background: transparent;
    color: #6c757d;
    cursor: pointer;
    border-radius: 4px;
    transition: all 0.2s;
}

.action-btn:hover {
    background: #f0f0f0;
    color: #007bff;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}
</style>

<div class="card leads-table">
    <!-- Header Toolbar -->
    <div class="leads-header">
        <div class="leads-toolbar">
            <div class="d-flex align-items-center flex-wrap" style="gap: 1rem; flex: 1;">
                <!-- Search -->
                <form method="get" action="<?php echo base_url('leads'); ?>" class="leads-search">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0">
                                <i class="material-icons" style="font-size: 20px; color: #6c757d;">search</i>
                            </span>
                        </div>
                        <input type="text" name="search" class="form-control border-left-0" 
                               placeholder="Search leads..." 
                               value="<?php echo html_escape($current_search); ?>">
                        <?php if (!empty($current_stage)): ?>
                            <input type="hidden" name="stage" value="<?php echo html_escape($current_stage); ?>">
                        <?php endif; ?>
                        <?php if (!empty($current_source)): ?>
                            <input type="hidden" name="source" value="<?php echo html_escape($current_source); ?>">
                        <?php endif; ?>
                    </div>
                </form>

                <!-- Stage Filters -->
                <div class="leads-filters">
                    <a href="<?php echo base_url('leads'); ?>" 
                       class="filter-badge <?php echo empty($current_stage) ? 'active' : ''; ?>">
                        All (<?php echo isset($total_count) ? $total_count : 0; ?>)
                    </a>
                    <?php if (!empty($stages) && is_array($stages)): ?>
                        <?php foreach ($stages as $stage_item): ?>
                            <a href="<?php echo base_url('leads?stage=' . urlencode($stage_item->stage)); ?>" 
                               class="filter-badge <?php echo (isset($current_stage) && $current_stage == $stage_item->stage) ? 'active' : ''; ?>">
                                <?php echo html_escape($stage_item->stage); ?> (<?php echo isset($stage_item->count) ? $stage_item->count : 0; ?>)
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Actions -->
            <div class="d-flex align-items-center" style="gap: 0.5rem;">
                <?php if (isset($role_id) && ($role_id == 1 || $role_id == 2)): // Admin, Sales Manager ?>
                <a href="<?php echo base_url('leads/add'); ?>" class="btn btn-primary">
                    <i class="material-icons" style="font-size: 18px; vertical-align: middle;">add</i>
                    Create Lead
                </a>
                <?php endif; ?>
                <button class="btn btn-outline-secondary" title="Export">
                    <i class="material-icons" style="font-size: 18px;">file_download</i>
                </button>
            </div>
        </div>

        <!-- Source Filters -->
        <?php if (!empty($sources) && is_array($sources)): ?>
        <div class="mt-3 pt-3 border-top">
            <div class="d-flex align-items-center flex-wrap" style="gap: 0.5rem;">
                <span class="text-muted small mr-2">Source:</span>
                <a href="<?php echo base_url('leads'); ?>" 
                   class="filter-badge <?php echo empty($current_source) ? 'active' : ''; ?>" style="font-size: 0.75rem;">
                    All
                </a>
                <?php foreach ($sources as $source_item): ?>
                    <a href="<?php echo base_url('leads?source=' . urlencode($source_item->source)); ?>" 
                       class="filter-badge <?php echo (isset($current_source) && $current_source == $source_item->source) ? 'active' : ''; ?>" 
                       style="font-size: 0.75rem;">
                        <?php echo html_escape($source_item->source); ?> (<?php echo isset($source_item->count) ? $source_item->count : 0; ?>)
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Table -->
    <div class="card-body p-0">
        <?php 
        // Debug info (remove in production)
        if (ENVIRONMENT === 'development' && isset($total_count)) {
            echo '<!-- Debug: Total leads = ' . $total_count . ', Array count = ' . (is_array($leads) ? count($leads) : 0) . ' -->';
        }
        ?>
        
        <?php if (!empty($leads) && is_array($leads) && count($leads) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="width: 40px; padding-left: 1.5rem;">
                                <input type="checkbox" class="form-check-input">
                            </th>
                            <th style="min-width: 250px;">Contact</th>
                            <th style="min-width: 120px;">Source</th>
                            <th style="min-width: 120px;">Stage</th>
                            <th style="min-width: 150px;">Assigned To</th>
                            <th style="min-width: 150px;">Follow Up</th>
                            <th style="min-width: 100px;">Created</th>
                            <th style="width: 100px; text-align: right; padding-right: 1.5rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leads as $lead): ?>
                        <tr class="lead-row" onclick="window.location='<?php echo base_url('leads/view/' . $lead->id); ?>'">
                            <td style="padding-left: 1.5rem;" onclick="event.stopPropagation();">
                                <input type="checkbox" class="form-check-input" value="<?php echo $lead->id; ?>">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="lead-avatar">
                                        <?php echo strtoupper(substr($lead->name, 0, 1)); ?>
                                    </div>
                                    <div class="lead-name-email">
                                        <div class="lead-name"><?php echo html_escape($lead->name); ?></div>
                                        <div class="lead-email"><?php echo html_escape($lead->email ?: $lead->phone); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="source-badge"><?php echo html_escape($lead->source ?: 'N/A'); ?></span>
                            </td>
                            <td>
                                <span class="stage-badge stage-<?php echo strtolower(str_replace(' ', '-', $lead->stage)); ?>">
                                    <?php echo html_escape($lead->stage); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($lead->assigned_name): ?>
                                    <div class="d-flex align-items-center">
                                        <span class="lead-avatar" style="width: 24px; height: 24px; font-size: 0.75rem; margin-right: 0.5rem;">
                                            <?php echo strtoupper(substr($lead->assigned_name, 0, 1)); ?>
                                        </span>
                                        <span><?php echo html_escape($lead->assigned_name); ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">Unassigned</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($lead->follow_up_date): ?>
                                    <div class="small">
                                        <div><?php echo date('M d, Y', strtotime($lead->follow_up_date)); ?></div>
                                        <div class="text-muted"><?php echo date('h:i A', strtotime($lead->follow_up_date)); ?></div>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="small text-muted">
                                    <?php echo date('M d, Y', strtotime($lead->created_at)); ?>
                                </div>
                            </td>
                            <td style="text-align: right; padding-right: 1.5rem;" onclick="event.stopPropagation();">
                                <div class="lead-actions">
                                    <button class="action-btn" title="Edit" onclick="window.location='<?php echo base_url('leads/edit/' . $lead->id); ?>'">
                                        <i class="material-icons" style="font-size: 18px;">edit</i>
                                    </button>
                                    <button class="action-btn" title="More" data-toggle="dropdown">
                                        <i class="material-icons" style="font-size: 18px;">more_vert</i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="material-icons">inbox</i>
                </div>
                <h5 class="mb-2">No leads found</h5>
                <p class="text-muted mb-4">
                    <?php if (!empty($current_search) || !empty($current_stage) || !empty($current_source)): ?>
                        Try adjusting your filters to see more results.
                    <?php else: ?>
                        Get started by creating your first lead.
                    <?php endif; ?>
                </p>
                <?php if (isset($role_id) && ($role_id == 1 || $role_id == 2) && empty($current_search) && empty($current_stage) && empty($current_source)): ?>
                <a href="<?php echo base_url('leads/add'); ?>" class="btn btn-primary">
                    <i class="material-icons" style="font-size: 18px; vertical-align: middle;">add</i>
                    Create Lead
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
