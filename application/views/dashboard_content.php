<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$role_id = isset($role_id) ? $role_id : 1;
$role_name = isset($role_name) ? $role_name : 'Admin';

// Ensure all variables are set with defaults
$total_leads = isset($total_leads) ? $total_leads : 0;
$today_leads = isset($today_leads) ? $today_leads : 0;
$converted_leads = isset($converted_leads) ? $converted_leads : 0;
$lost_leads = isset($lost_leads) ? $lost_leads : 0;
$leads_by_source = isset($leads_by_source) ? $leads_by_source : array();
$pipeline_summary = isset($pipeline_summary) ? $pipeline_summary : array();
$upcoming_followups = isset($upcoming_followups) ? $upcoming_followups : array();
$course_revenue = isset($course_revenue) ? $course_revenue : 0;
$recent_activities = isset($recent_activities) ? $recent_activities : array();
$patient_count = isset($patient_count) ? $patient_count : 0;
$enrollment_count = isset($enrollment_count) ? $enrollment_count : 0;
?>

<!-- Lead Overview Cards (Hidden for Doctor) -->
<?php if ($role_id != 4): ?>
<div class="row mb-4">
    <!-- Total Leads -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex">
                        <p class="text-uppercase text-muted mb-0 small font-weight-bold">Total Leads</p>
                        <h3 class="mb-0 font-weight-bold"><?php echo isset($total_leads) ? number_format($total_leads) : '0'; ?></h3>
                    </div>
                    <div class="avatar avatar-lg">
                        <span class="avatar-title bg-primary rounded-circle">
                            <i class="material-icons">people</i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Leads -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex">
                        <p class="text-uppercase text-muted mb-0 small font-weight-bold">Today's Leads</p>
                        <h3 class="mb-0 font-weight-bold"><?php echo isset($today_leads) ? number_format($today_leads) : '0'; ?></h3>
                    </div>
                    <div class="avatar avatar-lg">
                        <span class="avatar-title bg-success rounded-circle">
                            <i class="material-icons">today</i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Converted Leads -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex">
                        <p class="text-uppercase text-muted mb-0 small font-weight-bold">Converted</p>
                        <h3 class="mb-0 font-weight-bold text-success"><?php echo isset($converted_leads) ? number_format($converted_leads) : '0'; ?></h3>
                    </div>
                    <div class="avatar avatar-lg">
                        <span class="avatar-title bg-success rounded-circle">
                            <i class="material-icons">check_circle</i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lost Leads -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex">
                        <p class="text-uppercase text-muted mb-0 small font-weight-bold">Lost</p>
                        <h3 class="mb-0 font-weight-bold text-danger"><?php echo isset($lost_leads) ? number_format($lost_leads) : '0'; ?></h3>
                    </div>
                    <div class="avatar avatar-lg">
                        <span class="avatar-title bg-danger rounded-circle">
                            <i class="material-icons">cancel</i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leads by Source -->
<?php if (isset($leads_by_source) && !empty($leads_by_source)): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Leads by Source</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php 
                    $sources = array('Facebook Ads', 'Instagram Ads', 'Meta Ads', 'WhatsApp Bot', 'Manual Entry');
                    $source_data = array();
                    if (!empty($leads_by_source)) {
                        foreach ($leads_by_source as $item) {
                            $source_data[$item->source] = $item->count;
                        }
                    }
                    foreach ($sources as $source): 
                        $count = isset($source_data[$source]) ? $source_data[$source] : 0;
                    ?>
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="d-flex align-items-center p-3 border rounded">
                            <div class="flex">
                                <p class="mb-0 small text-muted"><?php echo html_escape($source); ?></p>
                                <h5 class="mb-0"><?php echo number_format($count); ?></h5>
                            </div>
                            <div class="ml-3">
                                <i class="material-icons text-primary">source</i>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Pipeline Stage Summary -->
<?php if (isset($pipeline_summary) && !empty($pipeline_summary)): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Pipeline Stage Summary</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php 
                    $stages = array('New', 'Contacted', 'Interested', 'Follow-Up', 'Converted', 'Lost');
                    $stage_colors = array(
                        'New' => 'primary',
                        'Contacted' => 'info',
                        'Interested' => 'warning',
                        'Follow-Up' => 'secondary',
                        'Converted' => 'success',
                        'Lost' => 'danger'
                    );
                    foreach ($stages as $stage): 
                        $count = isset($pipeline_summary[$stage]) ? $pipeline_summary[$stage] : 0;
                        $color = isset($stage_colors[$stage]) ? $stage_colors[$stage] : 'secondary';
                    ?>
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                        <div class="text-center p-3 border rounded">
                            <p class="mb-1 small text-muted font-weight-bold"><?php echo html_escape($stage); ?></p>
                            <h3 class="mb-0 text-<?php echo $color; ?>"><?php echo number_format($count); ?></h3>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php endif; ?>

<!-- Doctor Role - Patient/Course Stats -->
<?php if ($role_id == 4): ?>
<div class="row mb-4">
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex">
                        <p class="text-uppercase text-muted mb-0 small font-weight-bold">Total Patients</p>
                        <h3 class="mb-0 font-weight-bold"><?php echo isset($patient_count) ? number_format($patient_count) : '0'; ?></h3>
                    </div>
                    <div class="avatar avatar-lg">
                        <span class="avatar-title bg-primary rounded-circle">
                            <i class="material-icons">local_hospital</i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex">
                        <p class="text-uppercase text-muted mb-0 small font-weight-bold">Course Enrollments</p>
                        <h3 class="mb-0 font-weight-bold"><?php echo isset($enrollment_count) ? number_format($enrollment_count) : '0'; ?></h3>
                    </div>
                    <div class="avatar avatar-lg">
                        <span class="avatar-title bg-success rounded-circle">
                            <i class="material-icons">school</i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex">
                        <p class="text-uppercase text-muted mb-0 small font-weight-bold">Course Revenue</p>
                        <h3 class="mb-0 font-weight-bold text-success"><?php echo isset($course_revenue) ? '₹' . number_format($course_revenue, 2) : '₹0.00'; ?></h3>
                        <small class="text-muted">Courses Revenue Only</small>
                    </div>
                    <div class="avatar avatar-lg">
                        <span class="avatar-title bg-warning rounded-circle">
                            <i class="material-icons">attach_money</i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Main Content Row -->
<div class="row">
    <!-- Follow-Up Reminder Widget -->
    <?php if ($role_id != 4): ?>
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="material-icons mr-2">schedule</i> Follow-Up Reminders
                </h5>
            </div>
            <div class="card-body">
                <?php if (isset($upcoming_followups) && !empty($upcoming_followups)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($upcoming_followups as $followup): ?>
                        <div class="list-group-item px-0">
                            <div class="d-flex align-items-center">
                                <div class="flex">
                                    <h6 class="mb-1"><?php echo html_escape($followup->name ? $followup->name : 'Lead #' . $followup->id); ?></h6>
                                    <p class="mb-0 small text-muted">
                                        <i class="material-icons" style="font-size: 14px;">access_time</i>
                                        <?php echo date('M d, Y h:i A', strtotime($followup->follow_up_date)); ?>
                                    </p>
                                    <?php if (($role_id == 1 || $role_id == 2) && isset($followup->assigned_name) && !empty($followup->assigned_name)): ?>
                                        <small class="text-muted">
                                            <i class="material-icons" style="font-size: 14px;">person</i>
                                            Assigned to: <?php echo html_escape($followup->assigned_name); ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="material-icons text-muted" style="font-size: 48px;">event_busy</i>
                        <p class="text-muted mb-0 mt-2">No upcoming follow-ups.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Revenue Summary (Admin & Sales Manager only) -->
    <?php if (($role_id == 1 || $role_id == 2) && isset($course_revenue)): ?>
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="material-icons mr-2">attach_money</i> Revenue Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center py-4">
                    <h2 class="mb-2 text-success"><?php echo '₹' . number_format($course_revenue, 2); ?></h2>
                    <p class="text-muted mb-0">Total Course Revenue</p>
                    <small class="text-muted">Courses Revenue Only</small>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Doctor Revenue -->
    <?php if ($role_id == 4 && isset($course_revenue)): ?>
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="material-icons mr-2">attach_money</i> Course Revenue
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center py-4">
                    <h2 class="mb-2 text-success"><?php echo '₹' . number_format($course_revenue, 2); ?></h2>
                    <p class="text-muted mb-0">My Course Revenue</p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Charts Row -->
<?php if ($role_id != 4 && (isset($leads_trend) || isset($conversion_by_source))): ?>
<div class="row mb-4">
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Leads Trend (Last 30 Days)</h5>
            </div>
            <div class="card-body">
                <?php if (isset($leads_trend) && !empty($leads_trend)): ?>
                    <canvas id="leadsTrendChart" height="100"></canvas>
                <?php else: ?>
                    <p class="text-muted text-center py-4">No data available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Conversion Rate by Source</h5>
            </div>
            <div class="card-body">
                <?php if (isset($conversion_by_source) && !empty($conversion_by_source)): ?>
                    <canvas id="conversionChart" height="100"></canvas>
                <?php else: ?>
                    <p class="text-muted text-center py-4">No data available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Recent Activity Log -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="material-icons mr-2">history</i> Recent Activity
                </h5>
            </div>
            <div class="card-body">
                <?php if (isset($recent_activities) && !empty($recent_activities)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recent_activities as $activity): 
                            $activity_icons = array(
                                'login' => 'login',
                                'logout' => 'logout',
                                'profile_update' => 'edit',
                                'password_change' => 'lock',
                                'lead_created' => 'add_circle',
                                'lead_assigned' => 'assignment',
                                'lead_stage_updated' => 'update'
                            );
                            $icon = isset($activity_icons[$activity->activity]) ? $activity_icons[$activity->activity] : 'info';
                        ?>
                        <div class="list-group-item px-0">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm mr-3">
                                    <span class="avatar-title bg-primary rounded-circle">
                                        <i class="material-icons" style="font-size: 16px;"><?php echo $icon; ?></i>
                                    </span>
                                </div>
                                <div class="flex">
                                    <h6 class="mb-1"><?php echo ucwords(str_replace('_', ' ', html_escape($activity->activity))); ?></h6>
                                    <p class="mb-0 small text-muted">
                                        <i class="material-icons" style="font-size: 14px;">access_time</i>
                                        <?php echo date('M d, Y h:i A', strtotime($activity->timestamp)); ?>
                                        <?php if (!empty($activity->ip_address)): ?>
                                            <span class="ml-2">
                                                <i class="material-icons" style="font-size: 14px;">computer</i>
                                                <?php echo html_escape($activity->ip_address); ?>
                                            </span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="material-icons text-muted" style="font-size: 48px;">history</i>
                        <p class="text-muted mb-0 mt-2">No recent activity</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Chart Scripts (Placeholder - Add Chart.js library) -->
<?php if ($role_id != 4): ?>
<script>
// Leads Trend Chart (Placeholder - requires Chart.js)
document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($leads_trend) && !empty($leads_trend)): ?>
    // Chart.js implementation would go here
    console.log('Leads trend data:', <?php echo json_encode($leads_trend); ?>);
    <?php endif; ?>
    
    <?php if (isset($conversion_by_source) && !empty($conversion_by_source)): ?>
    // Conversion chart implementation would go here
    console.log('Conversion data:', <?php echo json_encode($conversion_by_source); ?>);
    <?php endif; ?>
});
</script>
<?php endif; ?>
