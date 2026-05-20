<?php
$session = new \Cake\Http\Session();
$userSession = $session->read('data');

$monthNames = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$m = 0;
$presentYear = date("Y");
$perviousYear = date("Y", strtotime("-1 year"));
$yearForEmpAtten = date("Y");
if (isset($_GET['year']))
    $yearForEmpAtten = $_GET['year'];

?>
<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="heading ft-secondary">
                        <?php
                        if (isset($_GET['employee-attendance'])) :
                        ?>
                        <?= $_GET['employee-attendance'] ?>
                        <?php
                        else :
                        ?>
                        Employee List
                        <?php
                        endif;
                        ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">
                            <select name="" id="" class="form-control" onchange="location = this.value">
                                <option
                                    value="<?= isset($_GET['employee-attendance']) ? $this->Url->build(['controller' => 'Salaries', 'action' => 'employeeAttendance', '?' => ['employee-attendance' => $_GET['employee-attendance']]]) : "" ?>">
                                    Select Month</option>
                                <?php
                                foreach ($monthNames as $month) :
                                    $m = $m >= 9 ? $m += 1 : '0' . $m += 1
                                ?>
                                <option
                                    value="<?= isset($_GET['employee-attendance']) ? $this->Url->build(['controller' => 'Salaries', 'action' => 'employeeAttendance', '?' => ['employee-attendance' => $_GET['employee-attendance'], 'month' => $m]]) : $this->Url->build(['controller' => 'Salaries', 'action' => 'employeeAttendance', '?' => ['month' => $m]]) ?>"
                                    <?= $lastMonth == $m ? 'selected' : null ?>>
                                    <?= $month ?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="" id="" class="form-control" onchange="location = this.value">
                                <option
                                    value="<?= isset($_GET['employee-attendance']) ? $this->Url->build(['controller' => 'Salaries', 'action' => 'employeeAttendance', '?' => ['employee-attendance' => $_GET['employee-attendance']]]) : "" ?>">
                                    Select Year</option>

                                <option
                                    value="
                                    <?= isset($_GET['employee-attendance']) ? $this->Url->build(['controller' => 'Salaries', 'action' => 'employeeAttendance', '?' => ['employee-attendance' => $_GET['employee-attendance'], 'month' => $_GET['month'], 'year' => $perviousYear]]) : $this->Url->build(['controller' => 'Salaries', 'action' => 'employeeAttendance', '?' => ['month' => $_GET['month'], 'year' => $perviousYear]]) ?>"
                                    <?= $year == $perviousYear ? 'selected' : null ?>>
                                    <?= $perviousYear ?>
                                </option>
                                <option
                                    value="<?= isset($_GET['employee-attendance']) ? $this->Url->build(['controller' => 'Salaries', 'action' => 'employeeAttendance', '?' => ['employee-attendance' => $_GET['employee-attendance'], 'month' => $_GET['month'], 'year' => $presentYear]]) : $this->Url->build(['controller' => 'Salaries', 'action' => 'employeeAttendance', '?' => ['month' => $_GET['month'], 'year' => $presentYear]]) ?>"
                                    <?= $year == $presentYear ? 'selected' : null ?>>
                                    <?= $presentYear ?>
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <a href="<?= isset($_GET['employee-attendance']) ? $this->Url->build(['controller' => 'Salaries', 'action' => 'employeeAttendance', '?' => ['employee-attendance' => $_GET['employee-attendance']]]) : $this->Url->build(['controller' => 'Salaries', 'action' => 'employeeAttendance']) ?>"
                                class="btn btn-sm text-white" style="background-color: #3fd5db;">Clear</a>
                        </div>
                        <?php if (isset($_GET['employee-attendance'])) : ?>
                        <div class="col-md-2">
                            <a href="<?= $this->Url->build(['controller' => 'Salaries', 'action' => 'employeeAttendance']) ?>"
                                class="btn btn-sm text-white" style="background-color: #3fd5db;">
                                Back
                            </a>
                        </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
                <div class="col-md-3">
                            <p>Today's Attendance : <b style="font-weight: 500;"><?= $totalemp; ?></b></p>
                        </div>
            </div>
        </div>
    </div>
    <!-- PAGE TAB -->
    <div class="page-tab">
        <div class="container">
            <div class="row">
            </div>
        </div>
    </div>
    <!-- PAGE-CONTENT -->
    <div class="page-content">
        <div class="container">
            <div class="row align-center">
            </div>
            <!-- TABLE -->
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-light nowrap table-sm  block" id="example1" style="width:100%">
                        <thead>
                            <tr>
                                <?php
                                if (isset($_GET['employee-attendance'])) :
                                ?>
                                <th>#</th>
                                <th>Working Days</th>
                                <th>Employee Attendance</th>
                                <th>Punch in Time</th>
                                <th>Punch out Time</th>
                                <th>Leave Days</th>
                                <th>Settled</th>
                                <?php
                                else :
                                ?>
                                <th>#</th>
                                <th>Employee Name</th>
                                <th>Issue</th>
                                <th>Short Leave</th>
                                <th>Total Leave</th>
                                <th>Latest Updated</th>
                                <?php
                                endif;
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $x = 0;
                            if (isset($_GET['employee-attendance'])) :
                                foreach ($dayList as $dayVal) :
                                    $holiday = $this->ReportingMName->holiday($dayVal);
                                    $leaveDate = $this->ReportingMName->leaveDate($empId, $dayVal);
                                    $wfh = $this->ReportingMName->employeeAttendance($empName, $dayVal, "date");
                                    $issueType = $this->ReportingMName->issueType($empName, $dayVal);
                                    $dateAttendance = $this->ReportingMName->employeeAttendance($empName, $dayVal, "date");
                                    $punchInAttendance = $this->ReportingMName->employeeAttendance($empName, $dayVal, "punchInTime");
                                    $punchOutAttendance = $this->ReportingMName->employeeAttendance($empName, $dayVal, "outTime");
                                    $timeDiff = - (strtotime($punchInAttendance) - strtotime($punchOutAttendance)) / 3600;
                                    $colorDanger = false;
                                    if ($timeDiff < 8.75) {
                                        if ($issueType == 1) {
                                            $colorDanger = false;
                                        } else {
                                            $colorDanger = true;
                                        }
                                    }

                            ?>
                            <tr <?php if ($issueType == 0 && $dayVal == date("Y-m-d", strtotime($this->ReportingMName->leaveDate($empId, date("Y-m-d", strtotime($dayVal))))) || $dateAttendance != $dayVal || $colorDanger) {
                                            if ($holiday == "Holiday" || $wfh == "WFH") {
                                                echo 'style="background-color: #6eb2ae;"';
                                            } else { ?> style="background-color: #e4abab;" <?php }
                                                                                    } ?>>
                                <td><?= $x += 1 ?></td>
                                <td>
                                    <?= date('d-M-Y', strtotime($dayVal)) ?>
                                </td>
                                <td>
                                    <?= $holiday == "Holiday" ? $holiday : ($dateAttendance == "WFH" ? "Present" : ($dateAttendance == $dayVal ? "Present" :  "Absent")) ?>
                                </td>
                                <td>
                                    <?= $punchInAttendance ? $punchInAttendance : '--' ?>
                                </td>
                                <td>
                                    <?= $punchOutAttendance ? $punchOutAttendance : '--' ?>
                                </td>
                                <td>
                                    <?= $leaveDate == "Forgot Card" ? $leaveDate : ($wfh == "WFH" ? "WFH" : ($leaveDate ? date("d-M-Y", strtotime($leaveDate)) : '--')) ?>
                                </td>
                                <td>
                                    <?php if (($userSession['role'] != 3) || ($userSession['role'] == 3 && array_intersect($userSession['role_name'], array(12)))) : ?>
                                    <select class="form-control input-sm" name=""
                                        onchange="changeIssueType(this.value, '<?= $dayVal ?>')">
                                        <option value="issue"
                                            <?= ($issueType == 0 || $dayVal == date("Y-m-d", strtotime($leaveDate)) && $dateAttendance != $dayVal) ? 'selected' : ($colorDanger ? 'selected' : null) ?>>
                                            Issue
                                        </option>
                                        <option value="settled"
                                            <?= ($holiday == "Holiday" || $issueType == 1 || $dayVal != date("Y-m-d", strtotime($leaveDate)) && $dateAttendance == $dayVal && $colorDanger == false) ? 'selected' : null ?>>
                                            Settled </option>
                                    </select>
                                    <?php else : ?>

                                    <?php

                                                if ($holiday == "Holiday" || $issueType == 1 || $dayVal != date("Y-m-d", strtotime($leaveDate)) && $dateAttendance == $dayVal && $colorDanger == false)
                                                    echo "Settled";
                                                else {
                                                    echo "Issue";
                                                }

                                                ?>

                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                                endforeach;
                            else :
                                foreach ($employee as $empVal) :
                                ?>
                            <tr>
                                <td><?= $x += 1 ?></td>
                                <td>
                                    <a
                                        href="<?= $this->Url->build(['controller' => 'Salaries', 'action' => 'employeeAttendance', '?' => ['employee-attendance' => $empVal->name]]) ?>">
                                        <?= $empVal->name ?>
                                    </a>
                                </td>
                                <td>
                                    <?= $this->ReportingMName->issueCount($empVal->id, $lastMonth, $yearForEmpAtten, $empVal->name) ?>
                                </td>
                                <td>
                                    <?= $this->ReportingMName->shortLeave($empVal->name, $lastMonth, $yearForEmpAtten) ?>
                                </td>
                                <td>
                                    <?= $this->ReportingMName->totalLeave($empVal->id, $lastMonth, $yearForEmpAtten) ?>
                                </td>
                                <td>
                                    <?= $this->ReportingMName->latestUpdate($empVal->name) ? date("Y-M-d", strtotime($this->ReportingMName->latestUpdate($empVal->name))) : '--' ?>
                                </td>
                            </tr>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('#example1').DataTable({
        responsive: true,
        scrollX: true,
        "pageLength": 25
    });
});

function changeIssueType(val, dayVal) {
    let issueDate = dayVal;
    let empName = "<?= $empName ?>";
    let issueVal = 0;
    if (val === "settled")
        issueVal = 1;
    // console.log(val);
    // console.log(issueDate, empName);
    $.ajax({
        url: "<?= $this->Url->build(['controller' => 'Salaries', 'action' => 'changeIssueType']) ?>",
        method: "GET",
        data: {
            empName,
            issueVal,
            issueDate
        },
        success: function(res) {
            if (res == 1)
                location.reload();
        }
    })
}
</script>