<?php $session = new \Cake\Http\Session();
$userSession = $session->read('data');

?>

<style>
  .loader-container {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.7);
    z-index: 9999;
    text-align: center;
  }

  .loader {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 2s linear infinite;
    position: absolute;
    top: 50%;
    left: 50%;
    margin-top: -25px;
    margin-left: -25px;
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  tbody tr.odd:hover{
    background-color :#eae7f7;
  }
  tbody tr.even:hover{
    background-color :#eae7f7;
  }
  .emp-name {
    z-index: 0;
    width: 150px;
    min-width: fit-content;
    left: 0px;
    position: -webkit-sticky;
    position: sticky;
    background-color: white;
  }

</style>

<section class="page page-dashboard">
    <div id="loader" class="loader-container">
        <div class="loader"></div>
        Loading...
    </div>
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-building"></i></span>Total Employee Leaves
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-tab">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="v-tab">

                        <li>
                            <!--  <a href="#">My Leaves</a>
                            -->
                            <?= $this->Html->link('Available Leaves', [
                                'controller' => 'Users',
                                'action' => 'userTotalLeaveReport'
                            ]); ?>
                        </li>

                        <li>
                            <?= $this->Html->link('Applied Leaves', [
                                'controller' => 'Users',
                                'action' => 'userAppliedLeaveReport'
                            ]); ?>
                        </li>
                        <li>
                            <?= $this->Html->link('Comp-Off Leave', [
                                'controller' => 'Users',
                                'action' => 'userCompLeave'
                            ]); ?>
                        </li>

                        <?php if (in_array(12, $userSession['role_name'])) { ?>
                            <li class="active">
                                <?= $this->Html->link('Apply Leave', [
                                    'controller' => 'Leaves',
                                    'action' => 'hrLeaves'
                                ]); ?>
                            </li>
                        <?php } ?>

                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="container">
            <?= $this->Flash->render() ?>
            <div class = "row">
                <div class= "col-log-4 col-md-4 col-sm-12">
                    <label> <b class="bold-data" style="font-weight:600; font-size: 15px;">Employee</b></label>
                    <select name="employee" class="form-control" id="employee" onchange="FilterData()">
                        <option value="">Select Employee</option>

                        <?php foreach ($users_data as $user) : ?>
                            <option value="<?= $user->id ?>"
                                <?= ($selectedEmployeeId == $user->id) ? 'selected' : '' ?>>
                                <?= $user->name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row" style="align-items: center; justify-content: center;">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <!-- <h1 class="heading" style="font-weight:800">
                        <?= @$user_data->name ?>
                    </h1> -->
                    <h1 class="heading" style="font-weight:800">
                        <?= !empty($user_data) ? $user_data->name : '' ?>
                    </h1>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 text-right">
                    <ul class="align-center mb-0">
                        <li>Total Leave:
                            <span class="fw-600">
                                <?php
                                $total_leave= (($myLeave['cl'] - $myLeave['sumCL']) + ($myLeave['sl'] - $myLeave['sumSL']) + (($myLeave['el'] - $myLeave['sumEL'])))
                                + ($myLeave['comp_off'] - $myLeave['sumComp']);
                                echo number_format(floor($total_leave * 100) / 100, 2);
                                ?>
                            </span>
                        </li>
                        <li>Casual Leave:
                            <span class="fw-600">
                                <?= number_format(floor(($myLeave['cl'] - $myLeave['sumCL']) * 100) / 100, 2) ?>
                            </span>
                        </li>
                        <li>Sick Leave:
                            <span class="fw-600">
                                <?= number_format(floor(($myLeave['sl'] - $myLeave['sumSL']) * 100) / 100, 2) ?>
                            </span>
                        </li>
                        <li>Paid Leave:
                            <span class="fw-600">
                                <?php $total_EL = $myLeave['el'] - $myLeave['sumEL']; ?>
                                <?= number_format(min(floor($total_EL * 100) / 100, 30), 2) ?>
                            </span>
                        </li>
                        <li>Comp_Off :
                            <span class="fw-600">
                                <?= number_format(floor(($myLeave['comp_off'] - $myLeave['sumComp']) * 100) / 100, 2) ?>
                            </span>
                        </li>
                        <li>Applied LWP :
                            <span class="fw-600">
                                <?= number_format(floor($myLeave['sumLWP'] * 100) / 100, 2) ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 col-md-10 col-sm-12">
                    <div class="page-content">
                        <div class="container">

                            <div class="card shadow-sm mt-4">
                                <div class="card-header">
                                    <h4 class="mb-0" style="font-weight:600; font-size: 15px;">Apply Leave</h4>
                                </div>

                                <div class="card-body">

                                    <?= $this->Form->create($leave, [
                                        'id' => 'leaveForm',
                                        'url' => [
                                            'controller' => 'Leaves',
                                            'action' => 'hrLeavesAdd'
                                        ]
                                    ]) ?>
                                    <input type="hidden" name="created_by" value="<?= !empty($user_data) ? $user_data->id : '' ?>">
                                    <!-- <input type="hidden" name="created_by" value="<?= $user_data->id ?>"> -->
                                    <input type="hidden" id="wfh_status" value="0">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label>To</label>
                                                <div class="adon-group res">
                                                    <select name="resources[]" class="form-control" multiple id="langOpt">
                                                        <?php foreach ($resources as $m) : ?>
                                                            <option value="<?= $m->id ?>"
                                                                <?= ($m->id == $rmId) ? 'selected' : '' ?>>
                                                                <?= $m->name ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <label>Subject</label>
                                                <?= $this->Form->control('subject', [
                                                    'label' => false,
                                                    'class' => 'form-control',
                                                    'autocomplete' => 'off',
                                                    'class' => 'form-control',
                                                    'type' => 'text',
                                                ]) ?>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <label>Leave Type</label>
                                                <?= $this->Form->control('leave_type', [
                                                    'label' => false,
                                                    'class' => 'form-control',
                                                    'onchange' => 'leaveType()',
                                                    'id' => 'leave',
                                                    'options' => [
                                                        'Casual Leave' => 'Casual Leave',
                                                        'Sick Leave' => 'Sick Leave',
                                                        'Paid Leave' => 'Paid Leave',
                                                        'Half Day' => 'Half Day',
                                                        'WFH' => 'Work From Home',
                                                        'Short Leave' => 'Short Leave',
                                                        'comp_off' => 'Comp_Off',
                                                        'LWP' => 'Leave Without Pay (LWP)',
                                                        'Forgot Card' => 'Forgot Card'
                                                    ]
                                                ]) ?>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <label>From Date</label>
                                                <?= $this->Form->control('from_date', [
                                                    'label' => false,
                                                    'id' => 'from_date',
                                                    'class' => 'form-control'
                                                ]) ?>
                                            </div>

                                            <div class="col-md-6">
                                                <label>To Date</label>
                                                <?= $this->Form->control('to_date', [
                                                    'label' => false,
                                                    'id' => 'to_date',
                                                    'class' => 'form-control'
                                                ]) ?>
                                            </div>

                                        </div>

                                    <span id="alert_shortleave"></span>
                                    <span id="alert_wfh"></span>

                                    <div class="row mt-3">
                                        <div class="col-md-12" id="wfhtyperadio" style="display:none;">
                                            <input type="radio" name="wfhtype" value="0" checked>
                                            My WFH should be considered as half working day
                                            <br>

                                            <input type="radio" name="wfhtype" value="1">
                                            My WFH should be considered as full working day
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12" id="halfdaytyperadio" style="display:none;">
                                            <input type="radio" name="halfdaytype" value="1">
                                            First Half (10:00 am - 1:30 pm)
                                            <br>

                                            <input type="radio" name="halfdaytype" value="2">
                                            Second Half (2:30 pm - 6:45 pm)
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12" id="reason" style="display:none;">
                                            <label>Reason</label>
                                            <?= $this->Form->control('reason', [
                                                'label' => false,
                                                'class' => 'form-control',
                                                'options' => [
                                                    '' => 'Select Reason',
                                                    'Personal' => 'Personal',
                                                    'Medical' => 'Medical'
                                                ]
                                            ]) ?>
                                        </div>

                                        <div class="col-md-12" id="sreason">
                                            <label>Reason</label>
                                            <?= $this->Form->control('sreason', [
                                                'label' => false,
                                                'class' => 'form-control',
                                                'options' => [
                                                    '' => 'Select Reason',
                                                    'Personal' => 'Personal',
                                                    'Client Call' => 'Client Call'
                                                ]
                                            ]) ?>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label>Message</label>
                                            <?= $this->Form->control('message', [
                                                'type' => 'textarea',
                                                'id' => 'textArea',
                                                'label' => false,
                                                'class' => 'form-control'
                                            ]) ?>
                                        </div>
                                    </div>

                                    <div class="text-right mt-4">
                                        <button class="btn btn-primary" type="submit">
                                            Apply Leave
                                        </button>
                                    </div>

                                    <?= $this->Form->end() ?>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12">
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
function FilterData() {
        var employeeId = $('#employee').val();

        window.location.href =
            "<?= $this->Url->build(['controller' => 'Leaves', 'action' => 'hrLeaves']) ?>" +
            "?employee_id=" + employeeId;
    }
</script>

<script type="text/javascript">

function leaveType() {
    let leaveType = document.querySelector('#leave').value;
    console.log(leaveType);

    let reason = document.querySelector('#reason');
    let sreason = document.querySelector('#sreason');
    let wfhtyperadio = document.querySelector('#wfhtyperadio');
    let halfdaytyperadio = document.querySelector('#halfdaytyperadio');
    let msg = document.querySelector('#msg');
    let opt = document.querySelector('#opt');

    if(leaveType=='Short Leave') {
        $("#opt_nal").html('(Optional)');
        reason.style.display = 'none';
        sreason.style.display = 'block';
        wfhtyperadio.style.display = 'none';
        halfdaytyperadio.style.display = 'none'; 
        $("#alert_shortleave").html('<span style="color:red; font-weight: 500; font-size: 11px;">Note: Only three short leaves are allowed per month. Additional short leaves will be treated as half-day leave.</span>');
        $("#textArea").removeAttr('required');
        $("#alert_wfh").html('');
    } 
    else if(leaveType == 'WFH') {
        $('#wfh_status').val('0');

        reason.style.display = 'block';
        wfhtyperadio.style.display = 'block';
        sreason.style.display = 'none';
        halfdaytyperadio.style.display = 'none'; 
        $("#alert_shortleave").html('');
        $("#alert_wfh").html('<span style="color:red; font-weight: 500; font-size: 11px;">Note: Officially, work from home constitutes a half-day. Approval for a full day requires manager consent and a valid reason stating why it should be considered as Full Day.</span>');
        // $("#opt_nal").html('<span style="color:gray; font-size:11px; font-weight: 600;">(Optional but mandatory for Full Day Work from Home.)</span>');
        $('input[name="wfhtype"]').change(function() {
        var selectedValue = $('input[name="wfhtype"]:checked').val();
        console.log('selectedValue',selectedValue);
        if(selectedValue==1) {
            // console.log('fgh');
        $("#opt_nal").html('<span style="color:gray; font-size:12px; color:red;">* (Why your WFH should be considered as Full Day WFH?)</span>');
        $("#textArea").attr('required', 'required');
        $(".fa-info-circle").hide();
        } else {
            $("#opt_nal").html('(Optional)');
            $("#textArea").removeAttr('required');
            $(".fa-info-circle").show();
        }
        });
    }else if(leaveType == 'Half Day') {
        reason.style.display = 'block';
        wfhtyperadio.style.display = 'none';
        halfdaytyperadio.style.display = 'block';
        sreason.style.display = 'none';
        $("#alert_wfh").html('');
        $("#alert_shortleave").html('');
    }
    else {
        $("#opt_nal").html('(Optional)');
        reason.style.display = 'block';
        sreason.style.display = 'none';
        wfhtyperadio.style.display = 'none';
        halfdaytyperadio.style.display = 'none'; 
        $("#textArea").removeAttr('required');
        $("#alert_wfh").html('');
        $("#alert_shortleave").html('');
        $(".fa-info-circle").show();
    }
}


$(document).ready(function() {
    // end select date
    $('#example1').DataTable({
        responsive: true,
        scrollX: true,
        "ordering": true,
        "pageLength": 10
    });
});
</script>