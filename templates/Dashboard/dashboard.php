<div class="project-dashboard">
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
                        <?= number_format($total > 0 ? $total : count( array_filter($projects, fn($p) => $p['status'] === 'Pending')
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

        <!-- TOTAL HOURS -->

        <!-- <div class="pd-kpi-card">
            <div class="pd-kpi-top">
                <div class="pd-kpi-icon pd-green">
                    <i class="fa fa-clock-o"></i>
                </div>
                <div>
                    <div class="pd-kpi-title">
                        Total Hours Till Date
                    </div>
                    <div class="pd-kpi-value">
                        <?= number_format($totalHours, 2) ?>hrs
                    </div>
                </div>
            </div>
        </div> -->


        <!-- BILLABLE HOURS -->

        <!-- <div class="pd-kpi-card">
            <div class="pd-kpi-top">
                <div class="pd-kpi-icon pd-cyan">
                    <i class="fa fa-file-text-o"></i>
                </div>
                <div>
                    <div class="pd-kpi-title">
                        Billable Hours (Till Date)
                    </div>
                    <div class="pd-kpi-value">
                        <?= number_format($billableHours, 2) ?>hrs
                    </div>
                </div>
            </div>
        </div> -->

        <!-- AVAILABILITY -->

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
        </div>
    </div>

    <!-- =====================================================
         PROJECTS / MILESTONES
         ===================================================== -->

    <div class="pd-two-column">
        <!-- =================================================
             ACTIVE PROJECTS
             ================================================= -->
        <div class="pd-card">
            <div class="pd-card-header">
                <strong> Active Projects</strong>
                <!-- <a href="<?= $this->Url->build([ 'controller' => 'Projects', 'action' => 'myProject']) ?>"> View All </a> -->
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
                        $isBillable = ((float)$project['hourly_rate'] > 0);
                    ?>
                    <tr>
                        <td> <?php
                                $project_name = $project['project_name'] ?? '';
                                $project_name_words = preg_split('/\s+/', trim($project_name));
                                $short_project_name = implode(' ', array_slice($project_name_words, 0, 4));
                            ?>
                            <span title="<?= h($project_name) ?>"> <?= h($short_project_name) ?><?= count($project_name_words) > 4 ? '...' : '' ?> </span>
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
                        <tr><td colspan="4" class="text-center">No active projects found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- =================================================
             PENDING MILESTONES
             ================================================= -->

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
                        <?php foreach ( $pendingMilestones as $milestone ): ?>
                            <?php
                            $dueDate = '';
                            $daysOverdue = 0;
                            if (!empty($milestone['due_date'])) {
                                $dueDate = date( 'd M Y', strtotime( $milestone['due_date'] ) );
                                $dueTimestamp = strtotime( date( 'Y-m-d', strtotime( $milestone['due_date'] ) ) );
                                $todayTimestamp = strtotime( date('Y-m-d') );

                                if ( $dueTimestamp < $todayTimestamp ) {
                                    $daysOverdue = floor( ( $todayTimestamp - $dueTimestamp ) / 86400 );
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
                                    <span title="<?= h($projectName) ?>"> <?= h($shortProjectName) ?><?= count($projectNameWords) > 4 ? '...' : '' ?> </span>
                                </td>
                                <td> <?= h( $milestone['project_manager'] ?? '' ) ?> </td>
                                <td><?= h($dueDate) ?></td>
                                <!-- <td class="pd-overdue">
                                    <?php if ($daysOverdue > 0): ?>
                                        -<?= $daysOverdue ?> day<?= $daysOverdue > 1 ? 's' : '' ?>
                                    <?php else: ?>
                                        Due today
                                    <?php endif; ?>
                                </td> -->
                                <td> 
                                    <span class="pd-pill pd-not-completed" >
                                        <?= h( $milestone['status'] ?? 'Pending' ) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr> <td colspan="6" class="text-center" > No pending milestones found. </td> </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- =====================================================
         EMPLOYEE + CHARTS
         ===================================================== -->

    <div class="pd-bottom-grid">
        <!-- =================================================
             EMPLOYEE OCCUPANCY
             ================================================= -->

        <div class="pd-card pd-employee-card">
            <div class="pd-card-header">
                <strong> Employee Occupancy &amp; Availability (Month to Date) </strong>
            </div>
            <div class="pd-table-wrap">
                <table class="pd-table pd-employee-table" id="employeeTable">
                    <thead>
                    <tr>
                        <th>EMPLOYEE</th>
                        <!-- <th>ROLE</th> -->
                        <th>TOTAL HOURS<br>(TILL DATE)</th>
                        <th> OCCUPIED HOURS<br>(BILLABLE PROJECTS)</th>
                        <th>OCCUPANCY %</th>
                        <th>AVAILABILITY %</th>
                        <th>STATUS</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                    // echo "<pre>";print_r($employees);die('djhs');
                    if (!empty($employees)): ?>
                        <?php foreach ( $employees as $employee ): ?>
                            <?php $name = $employee['name'] ?? '';
                            $nameParts = preg_split( '/\s+/', trim($name) );

                            $initials = strtoupper( substr( $nameParts[0] ?? '', 0, 1 ) . substr( 0, 1  ) );
                            $occupancy = (float)$employee['occupancy'];
                            $availability = (float)$employee['availability'];

                            if ($occupancy >= 75) {
                                $meterClass = 'pd-meter-red';

                            } elseif ($occupancy >= 40) {
                                $meterClass = 'pd-meter-orange';

                            } else {
                                $meterClass = 'pd-meter-green';
                            }

                            $statusClass = strtolower( str_replace( ' ', '-', $employee['status'] ) );
                            $roleNames = ['5' => 'BD', '6' => 'Tech Lead', '7' =>  'Developer', '8' => 'Designer']
                            ?>
                            <tr>
                                <td>
                                    <div class="pd-employee-name" >
                                        <span class="pd-avatar" > <?= h( $initials ) ?></span> 
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
                                <td> <?= number_format( $employee['total_hours'], 2 ) ?> hrs </td>
                                <td> <?= number_format($employee['occupied_hours'], 2) ?> hrs </td>
                                <td>
                                    <div class="pd-meter-cell" >
                                        <div class="pd-meter">
                                            <span class="<?= h( $meterClass ) ?>" style="width:<?= min( 100, $occupancy ) ?>%" ></span>
                                        </div>
                                        <?= number_format(  $occupancy,  1 ) ?>%
                                    </div>
                                </td>
                                <td>
                                    <div class="pd-meter-cell" >
                                        <div class="pd-meter">
                                            <span class="pd-meter-green" style="width:<?= min( 100, $availability ) ?>%" ></span>
                                        </div>
                                        <?= number_format( $availability, 1 ) ?>%
                                    </div>
                                </td>
                                <td>
                                    <span class="pd-status pd-status-<?= h( $statusClass ) ?>" > <?= h( $employee['status'] ) ?> </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr> <td colspan="7" class="text-center" > No employee data found. </td> </tr>
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

        <!-- =================================================
             GIT COLUMN
             ================================================= -->
        <div class="pd-chart-column">
            <div class="pd-card pd-chart-card">
                <div class="pd-card-header">
                    <strong> Latest Git Commits </strong>
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
                                        <td><?= h($git['user'] ?? '') ?></td>
                                        <td><?= h($git['repository'] ?? '') ?></td>
                                        <td><?= h($git['commits'] ?? '') ?></td>
                                        <td><?= !empty($git['lastCommitDate'])
                                                ? h(date('Y-m-d', strtotime($git['lastCommitDate'])))
                                                : '' ?>
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
                            <!-- <tr>
                                <td colspan="4" style="text-align:center;">
                                    Loading GitHub data...
                                </td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>            
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#projectsTable').DataTable();
        $('#milestonesTable').DataTable();
        $('#employeeTable').DataTable();
        $('#gitTable').DataTable(
            // {
            //     pageLength: 10,
            //     searching: false,
            //     lengthChange: false,
            //     ordering: true
            // }
        );

    //     fetch('http://44.230.62.131:5016/github-report?days=7')
    //     .then(function (response) {

    //         if (!response.ok) {
    //             throw new Error('GitHub API request failed');
    //         }

    //         return response.json();
    //     })
    //     .then(function (response) {

    //         console.log('GitHub API response:', response);

    //         const tbody = $('#gitTableBody');

    //         // Clear loading row
    //         tbody.empty();

    //         if (
    //             !response.success ||
    //             !response.data ||
    //             response.data.length === 0
    //         ) {

    //             tbody.html(`
    //                 <tr>
    //                     <td colspan="4" style="text-align:center;">
    //                         No GitHub data found.
    //                     </td>
    //                 </tr>
    //             `);

    //         } else {

    //             response.data.forEach(function (git) {

    //                 let commitDate = '';

    //                 if (git.lastCommitDate) {
    //                     const date = new Date(git.lastCommitDate);

    //                     commitDate =
    //                         date.getFullYear() + '-' +
    //                         String(date.getMonth() + 1).padStart(2, '0') + '-' +
    //                         String(date.getDate()).padStart(2, '0');
    //                 }

    //                 tbody.append(`
    //                     <tr>
    //                         <td>${escapeHtml(git.user || '')}</td>
    //                         <td>${escapeHtml(git.repository || '')}</td>
    //                         <td>${escapeHtml(git.commits || 0)}</td>
    //                         <td>${escapeHtml(commitDate)}</td>
    //                     </tr>
    //                 `);
    //             });
    //         }

    //         // NOW initialize DataTable
    //         $('#gitTable').DataTable({
    //             pageLength: 10,
    //             searching: false,
    //             lengthChange: false,
    //             ordering: true
    //         });

    //     })
    //     .catch(function (error) {

    //         console.error('GitHub API Error:', error);

    //         $('#gitTableBody').html(`
    //             <tr>
    //                 <td colspan="4" style="text-align:center;">
    //                     Unable to load GitHub data.
    //                 </td>
    //             </tr>
    //         `);

    //         // Initialize DataTable even if API fails
    //         $('#gitTable').DataTable({
    //             pageLength: 10,
    //             searching: false,
    //             lengthChange: false,
    //             ordering: true
    //         });

    //     });


    // function escapeHtml(value) {
    //     const div = document.createElement('div');
    //     div.textContent = value;
    //     return div.innerHTML;
    // }

});
</script>