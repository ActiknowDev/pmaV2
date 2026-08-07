<?php 
// echo "<pre>";print_r($emp_details);die('dd');
$session = new \Cake\Http\Session();
$userSession = $session->read('data');

// Calculate Average Time for Insights if not already set
$total_seconds = 0;
$total_records = count($emp_attendence_list);
$present_count = 0;
$chart_data = [];
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, (int)$month, (int)$year);

// Prepare Chart Data & Present Count
for ($d = 1; $d <= $daysInMonth; $d++) {
    $currentDate = sprintf('%04d-%02d-%02d', $year, $month, $d);
    $found_hours = 0;
    foreach ($emp_attendence_list as $att) {
        if ($att['date'] == $currentDate) {
            if(!empty($att['total_time'])) {
                $found_hours = (float)date('H', strtotime($att['total_time'])) + (date('i', strtotime($att['total_time'])) / 60);
                $time_seconds = strtotime($att['total_time']) - strtotime('TODAY');
                $total_seconds += $time_seconds;
            }
            if ($att['status'] == 'Present') $present_count++;
            break;
        }
    }
    $chart_data[] = round($found_hours, 2);
}

$average_time_display = ($total_records > 0) ? gmdate('H:i', $total_seconds / $total_records) : "0:00";
// ____________________________________________________________________________________________________
// Calculate On-Time Arrival Score
$on_time_count = $present_count - $late_entries;
$on_time_score = ($present_count > 0) ? round(($on_time_count / $present_count) * 100) : 0;

// Determine status label based on new thresholds
if ($on_time_score >= 95) {
    $status_label = "Excellent";
    $status_class = "text-success";
} elseif ($on_time_score >= 90) {
    $status_label = "Average";
    $status_class = "text-warning";
} else {
    $status_label = "Poor";
    $status_class = "text-danger";
}
// ____________________________________________________________________________________________________

?>

<!-- Dependencies -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<style>
    body { background-color: #f8fafc; font-family: 'Poppins', sans-serif; color: #334155; }
    .text-teal { color: #49d1d1 !important; }
    .bg-teal { background-color: #49d1d1 !important; }
    .card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); margin-bottom: 20px; transition: 0.3s; }
    .profile-card { background: linear-gradient(135deg, #49d1d1 0%, #3bbdbd 100%); color: white; padding: 25px; position: relative; overflow: hidden; }
    .att-count-label { font-size: 9px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 2px; }
    .att-count-val { font-size: 15px; font-weight: 700; }
    .dot { height: 7px; width: 7px; border-radius: 50%; display: inline-block; margin-right: 4px; }
    .table-clean thead th { border: none; background: #f8fafc; font-size: 10px; color: #64748b; text-transform: uppercase; padding: 10px; }
    .table-clean td { padding: 10px; font-size: 12px; vertical-align: middle; border-top: 1px solid #f1f5f9; font-weight: 500; }
    .badge-status { padding: 3px 8px; border-radius: 50px; font-size: 9px; font-weight: 600; }
    .status-present { background: #ecfdf5; color: #10b981; }
    .status-absent { background: #fef2f2; color: #ef4444; }
    .status-holiday { background: #f1f5f9; color: #64748b; }
    .status-leave { background: #fffbeb; color: #d97706; }
    .achievement-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 12px; }
    .custom-select-sm { height: 32px; border-radius: 6px; font-size: 12px; border: 1px solid #e2e8f0; }
    .stat-link:hover .card { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); }
    .stat-link { text-decoration: none !important; }
    .dropdown a {
        color: #3fd5db;
    }
</style>

<div class="container-fluid py-4" id="resdata">
    <div class="row">
        <!-- LEFT COLUMN -->
        <div class="col-lg-7">
            <!-- Profile Card -->
            <div class="card profile-card border-0 shadow-lg">
                <div class="row align-items-center">
                    <div class="col-md-7 d-flex align-items-center">
                        <div class="position-relative">
                            <?php if (!empty($emp_details->user_image)): ?>
                                <img src="<?= $this->Url->webroot('/img/user_images/' . $emp_details->user_image) ?>" class="rounded-circle border border-white" style="width: 100px; height: 100px; border-width: 3px !important; object-fit: cover;">
                            <?php else: ?>
                                <img src="<?= $this->Url->webroot('/img/default-user.png') ?>" class="rounded-circle border border-white" style="width: 100px; height: 100px; border-width: 3px !important;">
                            <?php endif; ?>
                            <!-- <span class="badge badge-warning position-absolute" style="bottom: -5px; left: 50%; transform: translateX(-50%); font-size: 9px; padding: 4px 8px;">TOP PERFORMER</span> -->
                        </div>
                        <div class="ml-4">
                            <h3 class="font-weight-bold mb-1"><?= h($emp_details->name); ?></h3>
                            <p class="mb-2 opacity-75 small font-weight-bold"><?= h($emp_details->designation); ?></p>
                            <div class="small">
                                <div><i class="fas fa-phone mr-2"></i> <?= h($emp_details_table->mobile_no); ?></div>
                                <div><i class="fas fa-envelope mr-2"></i> <?= h($emp_details->email); ?></div>
                                <div class="mt-1 opacity-75">Employer ID: <b><?= h($emp_details->id); ?></b></div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-5 text-center mt-3 mt-md-0 border-left border-white-50">
                        <p class="small font-weight-bold text-uppercase mb-1 tracking-wider">Performance Score</p>
                        <div id="perfChart" style="width: 130px; margin: 0 auto;"></div>
                        <p class="small font-weight-bold text-success mb-0">● Excellent</p>
                    </div> -->
                    <div class="col-md-5 text-center mt-3 mt-md-0 border-left border-white-50">
                        <!-- Changed mb-1 to mb-0 -->
                        <p class="small font-weight-bold text-uppercase mb-0 tracking-wider">On-Time Arrival</p>
                        
                        <!-- Added negative top and bottom margins to squeeze the container -->
                        <div id="perfChart" style="width: 130px; margin: -5px auto -20px auto;"></div>
                        
                        <!-- Kept mt-n3 logic but refined it -->
                        <p class="small font-weight-bold <?= $status_class ?> mb-0 mt-n1" style="position: relative; z-index: 10;">
                            ● <?= $status_label ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="row mb-1">
                <div class="col-md-4 mb-3">
                    <a href="#" class="stat-link" data-toggle="modal" data-target="#projects_assigned_modal">
                        <div class="card p-3 border-left h-100" style="border-left: 4px solid #49d1d1 !important;">
                            <div class="d-flex justify-content-between"><i class="fas fa-folder text-teal"></i> 
                            <!-- <span class="text-success small font-weight-bold">+12%</span> -->
                        </div>
                            <p class="small text-muted font-weight-bold mt-2 mb-1 uppercase">Projects Assigned</p>
                            <h4 class="font-weight-bold mb-0"><?= $total_billable_project_assign ?></h4>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="#" class="stat-link" data-toggle="modal" data-target="#milestones_modal">
                        <div class="card p-3 border-left h-100" style="border-left: 4px solid #f59e0b !important;">
                            <div class="d-flex justify-content-between"><i class="fas fa-flag text-warning"></i> 
                            <!-- <span class="text-success small font-weight-bold">+8%</span> -->
                        </div>
                            <p class="small text-muted font-weight-bold mt-2 mb-1 uppercase">Milestones Assigned</p>
                            <h4 class="font-weight-bold mb-0"><?= $total_miles; ?></h4>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="#" class="stat-link" data-toggle="modal" data-target="#internal_project_modal">
                        <div class="card p-3 border-left h-100" style="border-left: 4px solid #6366f1 !important;">
                            <div class="d-flex justify-content-between"><i class="fas fa-project-diagram text-primary"></i> 
                            <!-- <span class="text-success small font-weight-bold">+5%</span> -->
                        </div>
                            <p class="small text-muted font-weight-bold mt-2 mb-1 uppercase">Internal Projects</p>
                            <h4 class="font-weight-bold mb-0"><?= sprintf('%02d', $total_non_billable_project_assign) ?></h4>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Stats2 -->
            <div class="row mb-1">
                <!-- Hours Allocated (Allocated Time) -->
                <div class="col-md-4 mb-3">
                    <a href="#" class="stat-link" data-toggle="modal" data-target="#allocated_time_modal">
                        <div class="card p-3 border-left h-100" style="border-left: 4px solid #49d1d1 !important;">
                            <div class="d-flex justify-content-between">
                                <i class="far fa-clock text-teal"></i> 
                                <!-- <span class="text-muted small font-weight-bold">Target</span> -->
                            </div>
                            <p class="small text-muted font-weight-bold mt-2 mb-1 uppercase">Hours Allocated</p>
                            <h4 class="font-weight-bold mb-0"><?= number_format($total_time_slot, 1); ?></h4>
                        </div>
                    </a>
                </div>

                <!-- Hours Filled (Total Time Used) -->
                <div class="col-md-4 mb-3">
                    <a href="#" class="stat-link" data-toggle="modal" data-target="#hours_filled_modal">
                        <div class="card p-3 border-left h-100" style="border-left: 4px solid #f59e0b !important;">
                            <div class="d-flex justify-content-between">
                                <i class="fas fa-history text-warning"></i>
                                <!-- <span class="text-success small font-weight-bold">+15% vs last month</span> -->
                            </div>
                            <p class="small text-muted font-weight-bold mt-2 mb-1 uppercase">Hours Filled</p>
                            <h4 class="font-weight-bold mb-0"><?= number_format($user_time_use, 1); ?></h4>
                        </div>
                    </a>
                </div>

                <!-- Hours Filled on Billable Projects -->
                <div class="col-md-4 mb-3">
                    <a href="#" class="stat-link" data-toggle="modal" data-target="#billable_hours_modal">
                        <div class="card p-3 border-left h-100" style="border-left: 4px solid #6366f1 !important;">
                            <div class="d-flex justify-content-between">
                                <i class="fas fa-file-invoice-dollar text-primary"></i>
                                <!-- <span class="text-success small font-weight-bold">+10% vs last month</span> -->
                            </div>
                            <p class="small text-muted font-weight-bold mt-2 mb-1 uppercase">Hours Filled on Billable</p>
                            <h4 class="font-weight-bold mb-0"><?= number_format($billable_user_time_use, 1); ?></h4>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Work Overview Monthly Chart -->
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="font-weight-bold mb-0">Work Overview</h6>
                    <!-- <select class="custom-select custom-select-sm w-auto"><option>Monthly Trend</option></select> -->
                </div>
                <div id="monthlyChart" style="height: 250px;"></div>
            </div>

            <!-- Recent Achievements -->
            <!-- <h6 class="font-weight-bold mb-3 text-muted small tracking-widest">RECENT ACHIEVEMENTS</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card p-3 d-flex flex-row align-items-center border-0" style="background: #f0fdf4;">
                        <div class="achievement-icon bg-white text-success shadow-sm"><i class="fas fa-calendar-check"></i></div>
                        <div><p class="small font-weight-bold mb-0">Consistent</p><p style="font-size: 10px;" class="text-muted mb-0">95% attendance</p></div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card p-3 d-flex flex-row align-items-center border-0" style="background: #fdfcf0;">
                        <div class="achievement-icon bg-white text-warning shadow-sm"><i class="fas fa-check-double"></i></div>
                        <div><p class="small font-weight-bold mb-0">High Contributor</p><p style="font-size: 10px;" class="text-muted mb-0"><?= $total_miles ?> Milestones</p></div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card p-3 d-flex flex-row align-items-center border-0" style="background: #f0f4fd;">
                        <div class="achievement-icon bg-white text-primary shadow-sm"><i class="fas fa-star"></i></div>
                        <div><p class="small font-weight-bold mb-0">Efficiency Star</p><p style="font-size: 10px;" class="text-muted mb-0"><?= $user_time_use ?> Hours</p></div>
                    </div>
                </div>
            </div> -->
        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-lg-5">
            <!-- Global Filters Bar -->
            <div class="card p-3 mb-3">
                <div class="row no-gutters">
                    <div class="col-6 pr-1">
                        <select id="emp_filter" onchange="FilterData()" class="form-control custom-select-sm">
                            <?php foreach ($emp_list as $row): ?>
                                <option value="<?= $row['id']; ?>" <?= ($emp_details->id == $row['id']) ? 'selected' : '' ?>><?= h($row['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-6 pl-1">
                        <select id="month" onchange="FilterData()" class="form-control custom-select-sm">
                            <?php for($m=1; $m<=12; $m++): $mVal = sprintf('%02d', $m); ?>
                                <option value="<?= $mVal ?>" <?= ($month == $mVal) ? 'selected' : '' ?>><?= date('F', mktime(0,0,0,$m,1)) ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Attendance Log Section -->
            <div class="card overflow-hidden mb-4">
                <div class="p-3 border-bottom">
                    <h6 class="font-weight-bold mb-3">Attendance Log (<?= date('F', mktime(0,0,0,$month,1)) ?>)</h6>
                    <div class="row text-center no-gutters">
                        <div class="col"><p class="att-count-label"><span class="dot bg-success"></span>Present</p><p class="att-count-val text-success"><?= $present_count ?></p></div>
                        <div class="col"><p class="att-count-label"><span class="dot bg-warning"></span>Leave</p><p class="att-count-val text-warning"><?= $total_lv ?></p></div>
                        <div class="col"><p class="att-count-label"><span class="dot bg-primary"></span>WFH</p><p class="att-count-val text-primary"><?= $total_wfh ?></p></div>
                        <div class="col"><p class="att-count-label"><span class="dot bg-purple" style="background:#a855f7"></span>Late</p><p class="att-count-val" style="color:#a855f7"><?= $late_entries ?></p></div>
                        <div class="col"><p class="att-count-label"><span class="dot bg-danger"></span>Exit</p><p class="att-count-val text-danger"><?= $early_exits ?></p></div>
                    </div>
                </div>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-clean mb-0">
                        <thead>
                            <tr><th>Date</th><th>In/Out</th><th class="text-center">Hrs</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            <?php 
                            for ($d = 1; $d <= $daysInMonth; $d++): 
                                $currentDate = sprintf('%04d-%02d-%02d', $year, $month, $d);
                                $dayOfWeek = date('N', strtotime($currentDate));
                                $record = null;
                                foreach($emp_attendence_list as $emp) { if($emp['date'] == $currentDate) { $record = $emp; break; } }
                            ?>
                                <tr>
                                    <td><?= date('M d', strtotime($currentDate)) ?></td>
                                    <?php if ($record): ?>
                                        <td class="text-muted small"><?= date('g:ia', strtotime($record['intime'])) ?> - <?= date('g:ia', strtotime($record['outtime'])) ?></td>
                                        <td class="text-center font-weight-bold"><?= !empty($record['total_time']) ? date('H:i', strtotime($record['total_time'])) : '-' ?></td>
                                        <td><span class="badge-status status-present">Present</span></td>
                                    <?php else: 
                                        $leaveData = null;
                                        foreach($leaves as $lv) { if($currentDate >= $lv['from_date'] && $currentDate <= $lv['to_date']) { $leaveData = $lv; break; } }
                                        $holidayData = null;
                                        foreach($holidays as $h) { if($currentDate == $h['start']) { $holidayData = $h; break; } }
                                    ?>
                                        <?php if ($leaveData): ?>
                                            <td colspan="2" class="text-center opacity-50 small italic">Leave Record</td>
                                            <td><span class="badge-status status-leave"><?= h($leaveData['leave_type']) ?></span></td>
                                        <?php elseif ($holidayData): ?>
                                            <td colspan="2" class="text-center small font-weight-bold text-info"><?= h($holidayData['title']) ?></td>
                                            <td><span class="badge-status status-holiday">Holiday</span></td>
                                        <?php elseif ($dayOfWeek >= 6): ?>
                                            <td colspan="2" class="text-center opacity-50 small"><?= ($dayOfWeek == 6) ? 'Saturday' : 'Sunday' ?></td>
                                            <td><span class="badge-status status-holiday">Weekend</span></td>
                                        <?php else: ?>
                                            <td colspan="2" class="text-center">-</td>
                                            <td><span class="badge-status status-absent">Absent</span></td>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
                <?= $this->Html->link(
                    'View Full Attendance Report <i class="fas fa-arrow-right ml-1"></i>',
                    ['controller' => 'Users', 'action' => 'attendancePunchTimeReport'],
                    ['class' => 'btn btn-link btn-block text-teal small font-weight-bold py-3 border-top', 'escape' => false,'target' => '_blank' ]
                ) ?>
            </div>

            <!-- Quick Insights List -->
            <div class="card p-3 mb-4">
                <h6 class="font-weight-bold mb-4 small text-uppercase tracking-widest text-muted"><i class="fas fa-bolt text-warning mr-2"></i> Quick Insights</h6>
                <div class="small">
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span><i class="far fa-clock mr-2 text-primary"></i> Avg Office Hours</span>
                        <a href="#" data-toggle="modal" data-target="#average_hours_modal">
                            <span class="font-weight-bold"><?= $average_time_display ?> Hr</span>
                        </a>
                    </div>
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span><i class="far fa-calendar-times mr-2 text-warning"></i> Leaves Taken</span>
                        <a href="#" data-toggle="modal" data-target="#leave_modal">
                            <span class="font-weight-bold"><?= $total_lv ?></span>
                        </a>
                    </div>
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span><i class="fas fa-home mr-2 text-info"></i> WFH Days</span>
                        <a href="#" data-toggle="modal" data-target="#wfh_modal">
                            <span class="font-weight-bold"><?= $total_wfh ?></span>
                        </a>
                    </div>
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span><i class="fas fa-home mr-2 text-info"></i> Average Leave Plan time</span>
                        <a href="#" data-toggle="modal" data-target="#avg_leave_plan_modal">
                            <span class="font-weight-bold"><?= number_format($total_average_leave_plan, 2) ?></span>
                        </a>
                    </div>
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span><i class="fas fa-user-clock mr-2 text-purple"></i> Late Entries</span>
                        <a href="#" data-toggle="modal" data-target="#late_entry">
                            <span class="font-weight-bold text-danger"><?= $late_entries ?></span>
                        </a>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><i class="fas fa-walking mr-2 text-danger"></i> Early Exits</span>
                        <a href="#" data-toggle="modal" data-target="#early_exits">
                            <span class="font-weight-bold"><?= $early_exits ?></span>
                                        </a>
                    </div>
                </div>
            </div>

            <!-- Success Banner -->
            <!-- <div class="card bg-teal p-3 text-white shadow-lg border-0">
                <div class="d-flex align-items-center">
                    <i class="fas fa-medal fa-2x mr-3 text-white-50"></i>
                    <div>
                        <p class="font-weight-bold mb-0">Great going, <?= explode(' ', $emp_details->name)[0]; ?>!</p>
                        <p class="small mb-0 opacity-75">You are currently leading in productivity.</p>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>

<!-- Working Hours modal start -->
<div class="modal fade" id="average_hours_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Average Working Hours</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="Working_hours_table">
                        <thead class="table-dark">
                            <tr>
                                <th class="fw_500">#</th>
                                <th class="fw_500">Date</th>
                                <th class="fw_500">Working Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($emp_attendence_list as $att) {
                                if (!empty($att['total_time'])) {
                                    echo '<tr>';
                                    echo '<td>' . $i++ . '</td>';
                                    echo '<td>' . date('M d, Y', strtotime($att['date'])) . '</td>';
                                    echo '<td>' . date('H:i', strtotime($att['total_time'])) . '</td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Working Hours modal end -->

<!-- Leaves modal start -->
<div class="modal fade" id="leave_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Leaves</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="leave_table">
                        <thead class="table-dark">
                            <tr>
                                <th class="fw_500">#</th>
                                <th class="fw_500">Date</th>
                                <th class="fw_500">Leave Type</th>
                            </tr>
                        </thead>
                        <!-- <tbody>
                            <?php if (!empty($leave_details_list)): ?>
                                <?php foreach ($leave_details_list as $lv): ?>
                                    <tr>
                                        <td><?= $lv['count'] ?></td>
                                        <td><?= date('M d, Y', strtotime($lv['date'])) ?></td>
                                        <td><?= $lv['leave_type'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">No Leaves Found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody> -->

                          <tbody>
                            <?php if (!empty($total_leave)): ?>
                                <?php
                                $count = 1;
                                foreach ($total_leave as $lv):
                                    $from = new DateTime($lv['from_date']->format('Y-m-d'));
                                    $to = new DateTime($lv['to_date']->format('Y-m-d'));

                                    // loop through each day
                                    for ($date = $from; $date <= $to; $date->modify('+1 day')):
                                ?>
                                        <tr>
                                            <td><?= $count++ ?></td>
                                            <td><?= $date->format('M d, Y') ?></td>
                                            <td><?= h($lv['leave_type']) ?></td>
                                        </tr>
                                    <?php endfor; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">No Leaves Found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Leaves modal end -->

<!-- Average leave plan modal start -->
<div class="modal fade" id="avg_leave_plan_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Average Leave Plan</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="avg_leave_plan_table">
                        <thead class="table-dark">
                            <tr>
                                <th class="fw_500">#</th>
                                <th class="fw_500">Applied On</th>
                                <th class="fw_500">Leave From</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($average_leave_plan)): ?>
                                 <?php $count = 1; ?>
                                <?php 
                                    foreach ($average_leave_plan as $leave): ?>
                                    <tr>
                                         <td><?= $count++ ?></td>
                                        <td><?= date('M d, Y', strtotime($leave['applied_on'])) ?></td>
                                        <td><?= date('M d, Y', strtotime($leave['from_date'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">No leave plan data found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Average leave plan modal end -->

<!-- WFH modal start -->
<div class="modal fade" id="wfh_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">WFH Status</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="wfh_table">
                        <thead class="table-dark">
                            <tr>
                                <th class="fw_500">#</th>
                                <th class="fw_500">Date</th>
                                <th class="fw_500">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($wfh_dates)) : ?>
                                <?php foreach ($wfh_dates as $index => $date) : ?>
                                    <tr>
                                        <td><?= $index + 1; ?></td>
                                        <td><?= date('l, M d, Y', strtotime($date)); ?></td>
                                        <td>
                                            <span class="ms-1 ml-1 text-warning">WFH</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="3" class="text-center">No WFH records found</td>
                                </tr>
                            <?php endif; ?>

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- WFH modal end -->

<!-- early exists modal start -->
<div class="modal fade" id="early_exits" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-14 fw-semibold">Early Exits</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="early_exits_table">
                        <thead class="table-dark">
                            <tr>
                                <th class="fw_500">#</th>
                                <th class="fw_500">Date</th>
                                <th class="fw_500">In Time</th>
                                <th class="fw_500">Out Time</th>
                                <th class="fw_500">Early Exit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($early_exits_list)): ?>
                                <?php foreach ($early_exits_list as $exit): ?>
                                    <tr>
                                        <td><?= $exit['count'] ?></td>
                                        <td><?= date('M d, Y', strtotime($exit['date'])) ?></td>
                                        <td><?= date('g:i A', strtotime($exit['intime'])) ?></td>
                                        <td><?= date('g:i A', strtotime($exit['outtime'])) ?></td>
                                        <td><span class="badge bg-danger text-dark"><?= $exit['difference'] ?> early</span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No Early Exits</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- early exists modal end -->

<!-- late entry modal start -->
<div class="modal fade" id="late_entry" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Late Entry</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="late_entry_table">
                        <thead class="table-dark">
                            <tr>
                                <th class="fw_500">#</th>
                                <th class="fw_500">Date</th>
                                <th class="fw_500">In Time</th>
                                <th class="fw_500">Out Time</th>
                                <th class="fw_500">Late Entry</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($late_entries_list)): ?>
                                <?php foreach ($late_entries_list as $entry): ?>
                                    <tr>
                                        <td><?= $entry['count'] ?></td>
                                        <td><?= $entry['date'] ?></td>
                                        <td><?= date('g:i A', strtotime($entry['intime'])) ?></td>
                                        <td><?= date('g:i A', strtotime($entry['outtime'])) ?></td>
                                        <td><span class="badge bg-danger text-light"><?= $entry['difference'] ?> late</span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No Late Entries</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- late entry modal end -->

<!-- Projects assigned modal start -->
<div class="modal fade" id="projects_assigned_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Assigned Projects</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="projects_assigned_table">
                        <thead class="table-dark">
                            <tr>
                                <th class="fw_500">#</th>
                                <th class="fw_500">Project Name</th>
                                <th class="fw_500">Client</th>
                                <th class="fw_500">Project Manager</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($total_project_assigned)): ?>
                                <?php $index = 1; ?>
                                <?php foreach ($total_project_assigned as $project): ?>
                                    <?php if ($project['bill'] == 'Billable'): ?>
                                        <tr>
                                            <td><?= $index++ ?></td>
                                            <td><?= h($project['project_name']) ?></td>
                                            <td><?= h($project['client_name']) ?></td>
                                            <td><?= h($project['project_manager']) ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                                <?php if ($index == 1): ?>
                                    <tr>
                                        <td colspan="6">No billable projects found.</td>
                                    </tr>
                                <?php endif; ?>

                            <?php else: ?>
                                <tr>
                                    <td colspan="6">No projects assigned.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Projects assigned modal end -->

<!-- Internal Projects not billable assigned modal start -->
<div class="modal fade" id="internal_project_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Internal Assigned Projects</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="internal_projects_table">
                        <thead class="table-dark">
                            <tr>
                                <th class="fw_500">#</th>
                                <th class="fw_500">Project Name</th>
                                <th class="fw_500">Client</th>
                                <th class="fw_500">Project Manager</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($total_project_assigned)): ?>
                                <?php $index = 1; ?>
                                <?php foreach ($total_project_assigned as $project): ?>
                                    <?php if ($project['bill'] == 'Non Billable'): ?>
                                        <tr>
                                            <td><?= $index++ ?></td>
                                            <td><?= h($project['project_name']) ?></td>
                                            <td><?= h($project['client_name']) ?></td>
                                            <td><?= h($project['project_manager']) ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                                <?php if ($index == 1): ?>
                                    <tr>
                                        <td colspan="6">No billable projects found.</td>
                                    </tr>
                                <?php endif; ?>

                            <?php else: ?>
                                <tr>
                                    <td colspan="6">No projects assigned.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Projects assigned modal end -->

<!-- Timesheet Modal start -->
<div class="modal fade" id="timesheetModal" tabindex="-1" aria-labelledby="timesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Timesheet Records</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="time_sheet_table">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Project</th>
                                <th>Work Date</th>
                                <th>Time Used (hrs)</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($time_used)): ?>
                                <?php $i = 1;
                                foreach ($time_used as $t): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= h($t['project_name']) ?></td>
                                        <td><?= date('M d, Y', strtotime($t['work_date'])) ?></td>
                                        <td><?= h($t['time_used']) ?></td>
                                        <td><?= h($t['bill']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">No timesheet records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Timesheet Modal end -->

<!-- Allocated Hours Modal start -->
<div class="modal fade" id="allocated_time_modal" tabindex="-1" aria-labelledby="timesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Allocated Hours</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="allocated_hours_table">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Project Name</th>
                                <th>Allocated Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($projects as $project): ?>
                                <?php if ($project['bill'] === 'Billable' && $project['time_slot'] > 0): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= h($project['project_name']) ?></td>
                                        <td><?= h($project['time_slot']) ?> hrs</td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Allocated Hours Modal end -->

<!-- Hours filled Modal start -->
<div class="modal fade" id="hours_filled_modal" tabindex="-1" aria-labelledby="timesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Hours Filled</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="hours_filled_table">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Project Name</th>
                                <th>Hours Filled</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($projects as $project): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= h($project['project_name']) ?></td>
                                    <td><?= h($project['time_used']) ?> hrs</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Hours filled Modal end -->

<!--Billable Hours Modal start -->
<div class="modal fade" id="billable_hours_modal" tabindex="-1" aria-labelledby="timesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Billable Hours</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="billable_hours_table">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Project Name</th>
                                <th>Hours Filled on Billable Project</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($projects as $project): ?>
                                <?php if ($project['bill'] === 'Billable' && $project['time_used'] > 0): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= h($project['project_name']) ?></td>
                                        <td><?= h($project['time_used']) ?> hrs</td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Billable Hours Modal end -->

<!--Milestones Modal start -->
<div class="modal fade" id="milestones_modal" tabindex="-1" aria-labelledby="timesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Milestones Assigned</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="milestones_assigned_table">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Project Name</th>
                                <th>Milestone</th>
                                <th>Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php if (!empty($melist1)): ?>
                            <?php foreach ($melist1 as $mlist): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= h($mlist['project_name']) ?></td>
                                    <td><?= h($mlist['title']) ?></td>
                                    <td><?= h($mlist['due_date']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No Milestones Exits</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Milestones Modal end -->

<!-- Scripts -->
<!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script> -->

<script>
function FilterData() {
    var emp = $("#emp_filter").val();
    var month = $("#month").val();
    window.location.href = "<?= $this->Url->build(['controller' => 'ScoreCard', 'action' => 'index']) ?>/" + emp + "/" + month;
}

document.addEventListener("DOMContentLoaded", function() {
    // 1. Performance Gauge
    new ApexCharts(document.querySelector("#perfChart"), {
        series: [<?= $on_time_score ?>],
        chart: { height: 140, type: 'radialBar', sparkline: { enabled: true } },
        plotOptions: {
            radialBar: {
                offsetY: 0, 
                hollow: { size: '60%' },
                track: { background: "rgba(255,255,255,0.2)" },
                dataLabels: { name: { show: false }, value: { offsetY: 5, fontSize: '18px', fontWeight: 'bold', color: '#fff', formatter: (v) => v + '%' } }
            }
        },
        fill: { colors: ['#ffffff'] }
    }).render();

    // 2. Work Overview (Monthly Trend)
    new ApexCharts(document.querySelector("#monthlyChart"), {
        series: [{
            name: 'Working Hours',
            data: <?= json_encode($chart_data) ?>
        }],
        chart: { height: 250, type: 'area', toolbar: { show: false }, zoom: { enabled: false } },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3, colors: ['#49d1d1'] },
        fill: { type: 'gradient', gradient: { opacityFrom: 0.4, opacityTo: 0.05 } },
        markers: { size: 4, colors: ["#49d1d1"], strokeColors: "#fff", strokeWidth: 2 },
        xaxis: {
            categories: <?= json_encode(range(1, $daysInMonth)) ?>,
            axisBorder: { show: false },
            labels: { style: { colors: '#94a3b8', fontSize: '10px' } },
            title: { text: 'Day of Month', style: { fontSize: '10px', color: '#cbd5e1' } }
        },
        yaxis: { max: 12, labels: { style: { colors: '#94a3b8' } } },
        annotations: {
            yaxis: [{ y: 8, borderColor: '#cbd5e1', strokeDashArray: 4, label: { text: 'Target (8h)', style: { color: '#64748b', background: '#f8fafc' } } }]
        },
        grid: { borderColor: '#f1f5f9' }
    }).render();
});
</script>