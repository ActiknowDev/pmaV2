<?php
$unavailableEmployees = array_merge($leaveEmployees, $wfhEmployees);
$unavailableCount = count($unavailableEmployees);
?>
<div class="page-content">
    <div class="project-dashboard container-fluid pma_body">
        <div class="pd-page-heading">
            <h1>Projects Overview Dashboard</h1>
            <p> Get real-time insights on projects, milestones and team capacity</p>
        </div>
        <!-- =====================================================
            KPI CARDS
            ===================================================== -->
        <div class="pd-kpi-grid">

            <!-- ACTIVE PROJECTS -->
            <div class="pd-kpi-card">
                <div class="pd-kpi-top">
                    <div class="pd-kpi-icon pd-blue">
                        <i class="fa fa-folder"></i>
                    </div>
                    <div>
                        <div class="pd-kpi-title">
                            Active Projects
                        </div>
                        <div class="pd-kpi-value">
                            <?= number_format($total > 0 ? $total : count(
                                array_filter($projects, fn($p) => $p['status'] === 'Pending')
                            )) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PENDING MILESTONES -->

            <div class="pd-kpi-card">
                <div class="pd-kpi-top">
                    <div class="pd-kpi-icon pd-red">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <div>
                        <div class="pd-kpi-title">
                            Pending Milestones
                        </div>
                        <div class="pd-kpi-value">
                            <?= number_format($pendingMilestoneCount) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- EMPLOYEES -->
            <div class="pd-kpi-card">
                <div class="pd-kpi-top">
                    <div class="pd-kpi-icon pd-purple">
                        <i class="fa fa-users"></i>
                    </div>
                    <div>
                        <div class="pd-kpi-title">
                            Total Employees
                        </div>
                        <div class="pd-kpi-value">
                            <?= number_format(count($employees)) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employees Present -->
            <div class="pd-kpi-card">
                <div class="pd-kpi-top">
                    <div class="pd-kpi-icon pd-green">
                        <i class="fa fa-user-check"></i>
                    </div>
                    <div>
                        <div class="pd-kpi-title">
                            Employees Present
                        </div>
                        <div class="pd-kpi-value">
                            <?= number_format($presentCount) ?>
                        </div>
                    </div>
                </div>
                <div class="pdi-bottom"> Today </div>
            </div>

            <!-- On Leave / WFH -->
            <div class="pd-kpi-card">
                <div class="pd-kpi-top">
                    <div class="pd-kpi-icon pd-red">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <div>
                        <div class="pd-kpi-title">
                            On Leave / WFH
                        </div>
                        <div class="pd-kpi-value">
                            <a href="javascript:void(0)" class="pd-attendance-link" data-type="On Leave / WFH"
                                data-employees='<?= h(json_encode($unavailableEmployees)) ?>'>
                                <?= number_format($unavailableCount) ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="pdi-bottom"> Today </div>
            </div>

            <!-- Average Availability -->
            <div class="pd-kpi-card">
                <div class="pd-kpi-top">
                    <div class="pd-kpi-icon pd-purple">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div>
                        <div class="pd-kpi-title">
                            Average Availability
                        </div>
                        <div class="pd-kpi-value">
                            <?= number_format($averageAvailability, 1) ?>%
                        </div>
                    </div>
                </div>
                <div class="pdi-bottom"> This Month </div>
            </div>
        </div>

        <!-- =====================================================
            PROJECTS / MILESTONES
            ===================================================== -->
        <div class="pd-two-column">
            <div class="pd-card">
                <div class="pd-card-header">
                    <strong> Active Projects</strong>
                </div>
                <div class="pd-table-wrap">
                    <table class="pd-table" id="projectsTable">
                        <thead>
                            <tr>
                                <th>PROJECT NAME</th>
                                <th>CLIENT</th>
                                <th>MANAGER</th>
                                <th>BILLABLE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $activeProjectsFound = false;
                            foreach ($projects as $project):
                                if ($project['status'] !== 'Pending') {
                                    continue;
                                }
                                $activeProjectsFound = true;
                                $isBillable = $project['bill'] == 'Billable' ? 1 : 0;
                            ?>
                                <tr>
                                    <td> <?php
                                            $project_name = $project['project_name'] ?? '';
                                            $project_name_words = preg_split('/\s+/', trim($project_name));
                                            $short_project_name = implode(' ', array_slice($project_name_words, 0, 4));
                                            ?>
                                        <a href="<?= $this->Url->build('/edit-project/' . $project['id']) ?>" title="<?= h($project_name) ?>" target="_Blank">
                                            <span> <?= h($short_project_name) ?><?= count($project_name_words) > 4 ? '...' : '' ?> </span>
                                        </a>
                                    </td>
                                    <td> <?= h($project['client']) ?> </td>
                                    <td> <?= h($project['project_manager']) ?></td>
                                    <td><?php if ($isBillable): ?>
                                            <span class="pd-pill pd-success"> Yes </span>
                                        <?php else: ?>
                                            <span class="pd-pill pd-danger">No</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (!$activeProjectsFound): ?>
                                <tr>
                                    <td colspan="4" class="text-center">No active projects found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="pd-card">
                <div class="pd-card-header">
                    <strong> Pending Milestones (Overdue) </strong>
                </div>
                <div class="pd-table-wrap">
                    <table class="pd-table" id="milestonesTable">
                        <thead>
                            <tr>
                                <th>MILESTONE</th>
                                <th>PROJECT</th>
                                <th>MANAGER</th>
                                <th>DUE DATE</th>
                                <!-- <th>DAYS OVERDUE</th> -->
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pendingMilestones)): ?>
                                <?php foreach ($pendingMilestones as $milestone): ?>
                                    <?php
                                    $dueDate = '';
                                    $daysOverdue = 0;
                                    if (!empty($milestone['due_date'])) {
                                        $dueDate = date('d M Y', strtotime($milestone['due_date']));
                                        $dueTimestamp = strtotime(date('Y-m-d', strtotime($milestone['due_date'])));
                                        $todayTimestamp = strtotime(date('Y-m-d'));

                                        if ($dueTimestamp < $todayTimestamp) {
                                            $daysOverdue = floor(($todayTimestamp - $dueTimestamp) / 86400);
                                        }
                                    } ?>
                                    <tr>
                                        <td>
                                            <span class="pd-milestone-dot"></span>
                                            <?php
                                            $title = $milestone['title'] ?? '';
                                            $words = preg_split('/\s+/', trim($title));
                                            $shortTitle = implode(' ', array_slice($words, 0, 4));
                                            ?>
                                            <span title="<?= h($title) ?>"> <?= h($shortTitle) ?><?= count($words) > 4 ? '...' : '' ?> </span>
                                        </td>
                                        <td> <?php
                                                $projectName = $milestone['project_name'] ?? '';
                                                $projectNameWords = preg_split('/\s+/', trim($projectName));
                                                $shortProjectName = implode(' ', array_slice($projectNameWords, 0, 4));
                                                ?>
                                            <a href="<?= $this->Url->build('/edit-project/' . $milestone['project_id']) ?>" title="<?= h($projectName) ?>" target="_Blank">
                                                <span> <?= h($shortProjectName) ?><?= count($projectNameWords) > 4 ? '...' : '' ?> </span>
                                            </a>
                                        </td>
                                        <td> <?= h($milestone['project_manager'] ?? '') ?> </td>
                                        <td><?= h($dueDate) ?></td>
                                        <!-- <td class="pd-overdue">
                                        <?php if ($daysOverdue > 0): ?>
                                            -<?= $daysOverdue ?> day<?= $daysOverdue > 1 ? 's' : '' ?>
                                        <?php else: ?>
                                            Due today
                                        <?php endif; ?>
                                    </td> -->
                                        <td>
                                            <span class="pd-pill pd-not-completed">
                                                <?= h($milestone['status'] ?? 'Pending') ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center"> No pending milestones found. </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- =====================================================
            EMPLOYEE / GIT
            ===================================================== -->
        <div class="pd-bottom-grid">
            <div class="pd-card pd-employee-card">
                <div class="pd-card-header">
                    <strong> Employee Occupancy &amp; Availability (Month to Date) </strong>
                </div>
                <div class="pd-table-wrap">
                    <table class="pd-table pd-employee-table" id="employeeTable">
                        <thead>
                            <tr>
                                <th>EMPLOYEE</th>
                                <th>Office Hours</th>
                                <th class="hours-tooltip">
                                    TOTAL HOURS <span>TOTAL HOURS (TILL DATE)</span>
                                </th>
                                <th class="hours-tooltip">
                                    Billable HOURS <span>OCCUPIED HOURS (BILLABLE PROJECTS)</span>
                                </th>
                                <th>OCCUPANCY %</th>
                                <th>AVAILABILITY %</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // echo "<pre>";print_r($employees);die('djhs');
                            if (!empty($employees)): ?>
                                <?php foreach ($employees as $employee): ?>
                                    <?php $name = $employee['name'] ?? '';
                                    $nameParts = preg_split('/\s+/', trim($name));

                                    $initials = strtoupper(substr($nameParts[0] ?? '', 0, 1) . substr(0, 1));
                                    $occupancy = (float)$employee['occupancy'];
                                    $availability = (float)$employee['availability'];

                                    if ($occupancy >= 75) {
                                        $meterClass = 'pd-meter-red';
                                    } elseif ($occupancy >= 40) {
                                        $meterClass = 'pd-meter-orange';
                                    } else {
                                        $meterClass = 'pd-meter-green';
                                    }

                                    $statusClass = strtolower(str_replace(' ', '-', $employee['status']));
                                    $roleNames = ['4' => 'Manager', '5' => 'BD', '6' => 'Tech Lead', '7' =>  'Developer', '8' => 'Designer']
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="pd-employee-name">
                                                <span class="pd-avatar"> <?= h($initials) ?></span>
                                                <?= h($name) ?>
                                            </div>
                                        </td>
                                        <?php
                                        $roleIds = explode(',', $employee['role_name']);
                                        $employeeRoles = [];
                                        foreach ($roleIds as $roleId) {
                                            $roleId = trim($roleId);
                                            if (isset($roleNames[$roleId])) {
                                                $employeeRoles[] = $roleNames[$roleId];
                                            }
                                        }
                                        ?>
                                        <!-- <td><?= h(implode(', ', $employeeRoles)) ?></td> -->
                                        <td> <?= $employee['office_hours'] ?> hrs </td>
                                        <td> <?= number_format($employee['total_hours'], 2) ?> hrs </td>
                                        <td> <?= number_format($employee['occupied_hours'], 2) ?> hrs </td>
                                        <td>
                                            <div class="pd-meter-cell">
                                                <div class="pd-meter">
                                                    <span class="<?= h($meterClass) ?>" style="width:<?= min(100, $occupancy) ?>%"></span>
                                                </div>
                                                <?= number_format($occupancy,  1) ?>%
                                            </div>
                                        </td>
                                        <td>
                                            <div class="pd-meter-cell">
                                                <div class="pd-meter">
                                                    <span class="pd-meter-green" style="width:<?= min(100, $availability) ?>%"></span>
                                                </div>
                                                <?= number_format($availability, 1) ?>%
                                            </div>
                                        </td>
                                        <td>
                                            <span class="pd-status pd-status-<?= h($statusClass) ?>"> <?= h($employee['status']) ?> </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center"> No employee data found. </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="pd-employee-footer">
                    <div>
                        <span class="pd-legend">
                            <i class="pd-legend-red"></i>
                            High Load (≥ 80%)
                        </span>
                        <span class="pd-legend">
                            <i class="pd-legend-orange"></i>
                            Normal (60% - 80%)
                        </span>
                        <span class="pd-legend">
                            <i class="pd-legend-green"></i>
                            Available (&lt; 60%)
                        </span>
                    </div>
                    <div> Note: Total Hours is based on working days till date in the current month. </div>
                </div>
            </div>
            <div class="pd-chart-column">
                <div class="pd-card pd-chart-card">
                    <div class="pd-card-header">
                        <strong> Latest Git Commits </strong>

                        <button
                            type="button"
                            id="refreshGitData"
                            class="btn btn-sm btn-secondary">
                            <i class="fa fa-refresh"></i> Refresh
                        </button>

                    </div>
                    <div class="pd-table-wrap">
                        <table class="pd-table pd-employee-table" id="gitTable">
                            <thead>
                                <tr>
                                    <th>EMPLOYEE</th>
                                    <th>Project Name</th>
                                    <th>COMMITS</th>
                                    <th>Commited On</th>
                                </tr>
                            </thead>
                            <tbody id="gitTableBody">
                                <?php if (!empty($githubData)): ?>
                                    <?php foreach ($githubData as $git): ?>
                                        <tr>
                                            <td><?= h($git['github_user'] ?? '') ?></td>
                                            <td><?= h($git['repository'] ?? '') ?></td>
                                            <td><?= h($git['commits'] ?? '') ?></td>
                                            <td>
                                                <?php
                                                echo h(
                                                    (new \DateTime($git['last_commit_date'], new \DateTimeZone('UTC')))
                                                        ->setTimezone(new \DateTimeZone('Asia/Kolkata'))
                                                        ->format('Y-m-d H:i:s')
                                                ); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" style="text-align:center;">
                                            No GitHub data found.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- On Leave/WFH Modal -->
<div class="modal fade" id="attendanceEmployeesModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-users"></i>
                    <span id="attendanceModalTitle">On Leave / WFH</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="attendance-table-wrapper">
                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th>EMP NAME</th>
                                <th>PARTICULAR</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceEmployeeList">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- On Leave/WFH Modal End -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $.fn.dataTable.ext.pager.numbers_length = 5;
        $('#projectsTable').DataTable({
            ordering: true,
            order: [
                [3, 'asc']
            ],
            scrollX: true,
            scrollCollapse: false,
            scrollY: '315px',
            autoWidth: true
        });

        $('#milestonesTable').DataTable({
            ordering: true,
            order: [
                [3, 'desc']
            ],
            scrollX: true,
            scrollCollapse: false,
            scrollY: '315px',
            autoWidth: true
        });

        $('#employeeTable').DataTable({
            scrollX: true,
            scrollCollapse: false,
            scrollY: '315px',
            autoWidth: true
        });

        $('#gitTable').DataTable({
            ordering: true,
            order: [
                [3, 'desc']
            ],
            scrollX: true,
            scrollCollapse: false,
            autoWidth: true,
            scrollY: '315px',
        });

        let resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                projectsTable.columns.adjust().draw(false);
                milestonesTable.columns.adjust().draw(false);
                employeeTable.columns.adjust().draw(false);
                gitTable.columns.adjust().draw(false);
            }, 250);
        });
    });


    $(document).on('click', '#refreshGitData', function() {
        const button = $(this);
        const originalHtml = button.html();
        button.prop('disabled', true);
        button.html('<i class="fa fa-spinner fa-spin"></i> Refreshing...');

        $.ajax({
            url: '<?= $this->Url->build(["controller" => "Dashboard", "action" => "refreshGithubData"]) ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                button.prop('disabled', false);
                button.html(originalHtml);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                button.prop('disabled', false);
                button.html(originalHtml);
                let message = 'Unable to refresh GitHub data.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message
                });
            }
        });
    });

    $(document).on('click', '.pd-attendance-link', function() {
        const type = $(this).data('type');
        const employees = $(this).data('employees');
        $('#attendanceModalTitle').text(type);
        let html = '';

        if (employees && employees.length > 0) {
            employees.forEach(function(employee) {
                const name = employee.name || '-';
                // WFH when wfh_flag = 1, otherwise show leave_type
                const particular = employee.wfh_flag == 1 ?
                    'WFH' :
                    (employee.leave_type || 'Leave');

                html += `
                    <tr>
                        <td>${name}</td>
                        <td>${particular}</td>
                    </tr>
                `;
            });

        } else {
            html = `
                <tr>
                    <td colspan="2" class="text-center">
                        No employees found
                    </td>
                </tr>
            `;
        }

        $('#attendanceEmployeeList').html(html);
        $('#attendanceEmployeesModal').modal('show');
    });
</script>