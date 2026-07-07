<style>
    #sreason {
        display: none;
    }
    #wfhtyperadio {
        display: none;
    }
    #halfdaytyperadio {
        display: none;
    }
    #wfhtyperadio {
        display: none;
    }
    .rd-input {
        position: relative;
        height: 20px;
        top: 6px;
    }

    /* wfh instruction css */
    .instruction-item{
    display:flex;
    align-items:flex-start;
    margin-bottom:20px;
    }

    .instruction-number{
        width:35px;
        height:35px;
        min-width:35px;
        border-radius:50%;
        background:#3fd5db;
        color:#fff;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:bold;
        margin-right:15px;
    }

    .modal-content{
        border-radius:15px;
    }

    .modal-body{
        font-size:15px;
        line-height:1.8;
    }

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

                        <li class="active">
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

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-user-tie"></i></span>
                        Leave Management
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="actions-ctrl text-md-right">

                        <!-- <a href="#" data-target="#addHoli" data-toggle="modal" class="v-btn v-btn-secondary">
                            <span>Annual Holiday</span>
                        </a> -->

                        <a href="#" data-target="#addCompOff" data-toggle="modal" class="v-btn v-btn-primary">
                            <!-- <i class="fa fa-list"></i> -->
                            <span>Add Comp-Off</span>
                        </a>
                        <a href="#" data-target="#applyLeave" data-toggle="modal" class="v-btn v-btn-secondary">
                            <i class="fa fa-list"></i><span>Apply Leave</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- APPLY LEAVE MODAL -->

<div class="modal fade" tabindex="-1" role="dialog" id="applyLeave">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= $this->Form->create($leave, [
                'id' => 'leaveForm',
                'url' => [
                'Controller' => 'Leaves',
                'action' => 'add'
            ]]) ?>

            <input type="hidden" name="created_by" value="<?= $user_data->id ?>">
            <input type="hidden" id="wfh_status" value="0">
            <div class="modal-header">
                <h5 class="modal-title">Apply Leave</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                      <div class="col-md-2 pb-2">
                </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Employee</label>
                            <div class="adon-group res">
                                <select name="employee" class="form-control" id="employee" onchange="FilterData()">
                                    <?php if($employee==''){ ?>
                                        <option value="Select Employee" hidden selected>Select Employee</option>
                                <?php } else { ?>
                                        <option value="<?= $employee ?>" hidden selected><?= $employee ?></option>
                                <?php  } ?>
                                <?php foreach ($users_data as $user) : ?>
                                    <option value="<?= $user['name'] ?>"><?= $user['name'] ?></option>
                                    <?php endforeach; ?>
                            </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">To</label>
                            <div class="adon-group res">
                                <select name="resources[]" class="form-control" multiple id="langOpt">
                                    <?php foreach ($resources as $m) : ?>

                                    <option value="<?= $m->id; ?>" <?= ($m->id == $rmId) ? 'selected' : ""; ?>>

                                        <?= $m->name; ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Subject</label>
                            <?= $this->Form->control('subject', [
                                'required' => true,
                                'label' => false,
                                'class' => 'form-control',
                                'type' => 'text',
                                'autocomplete' => 'off'
                            ]); ?>

                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Leave Type</label>
                            <?= $this->Form->control('leave_type', [
                                'required' => true,
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
                                    'Forgot Card' => 'Forgot Card',
                                ]
                            ]); ?>

                        </div>
                    </div>
                    <span id="alert_shortleave"></span>
                    <span id="alert_wfh"></span>
                    <div class="form-group row">
                        <div class="col-md-12 mt-2" id="wfhtyperadio">
                            <input type="radio" name="wfhtype" value="0" id="type-0" class="rd-input" checked /><label for="type-0" style="font-weight:600;">&nbsp;My WFH should be considered as half working day</label><br/>
                            <input type="radio" name="wfhtype" value="1" id="type-1" class="rd-input" /><label for="type-1" style="font-weight:600;">&nbsp;My WFH should be considered as full working day</label>
                        </div>

                        <div class="col-md-12 mt-2" id="halfdaytyperadio">
                            <input type="radio" name="halfdaytype" value="1" class="rd-input" /><label style="font-weight:600;">&nbsp;First Half(10:00 am - 1:30 pm) </label><br/>
                            <input type="radio" name="halfdaytype" value="2"  class="rd-input" /><label  style="font-weight:600;">&nbsp;Second Half(2:30pm - 6:45pm)</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="">From Date</label>
                            <div class="adon-group">
                                <span class="icon ft-primary">
                                    <i class="fa fa-calendar-alt"></i>
                                </span>
                                <?= $this->Form->control('from_date', [
                                    'required' => true,
                                    'autocomplete' => 'off',
                                    'id' => 'from_date',
                                    'label' => false,
                                    'type' => 'text',
                                    'required' => true,
                                    'class' => 'form-control datepicker'
                                ]); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="">To Date</label>
                            <div class="adon-group">
                                <span class="icon ft-primary">
                                    <i class="fa fa-calendar-alt"></i>
                                </span>
                                <?= $this->Form->control('to_date', [
                                    'required' => true,
                                    'autocomplete' => 'off',
                                    'label' => false,
                                    'id' => 'to_date',
                                    'type' => 'text',
                                    'required' => true,
                                    'class' => 'form-control datepicker'
                                ]); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">

                        <div class="col-md-12" id="reason">
                            <label for="">Reason</label>
                            <?= $this->Form->control('reason', [
                                'label' => false,
                                'class' => 'form-control',
                                'id' => 'reasonType',
                                'options' => [
                                    '' => 'Select Reason',
                                    'Personal' => 'Personal',
                                    'Medical' => 'Medical',
                                ]
                            ]); ?>

                        </div>
                        <div class="col-md-12" id="sreason">
                            <label for="">Reason</label>
                            <?= $this->Form->control('sreason', [
                                'label' => false,
                                'class' => 'form-control',
                                'id' => 'reasonType',
                                'options' => [
                                    '' => 'Select Reason',
                                    'Personal' => 'Personal',
                                    'Client Call' => 'Client Call',
                                ]
                            ]); ?>

                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Message</label>
                            <span id="opt_nal" style="color: gray;">(Optional)</span>
                            <i class="fa fa-info-circle fa-lg" style="color: gray;"
                                title='This is not mandatory to be filled. However, giving a reason helps us decide and prioritise your leave on top of other requests.'
                                aria-hidden="true"></i>
                            <span id="msg" style="color: red;"></span>
                            <span id="opt"></span>
                            <?= $this->Form->control('message', [
                                'type' => 'textarea',
                                // 'title' => 'This is not mandatory to be filled. However, giving a reason helps us decide and prioritise your leave on top of other requests.',
                                'id' => 'textArea',
                                'label' => false,
                                'class' => 'form-control'
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal" aria-label="Close">Close</button>
                <button class="v-btn v-btn-primary" type="submit" id="leaveSubmitBtn">Apply Leave</a>
            </div>

            <?= $this->Form->end() ?>

        </div>
    </div>
</div>

<!-- End Apply leave modal -->
<!-- WFH Instruction -->

<div class="modal fade" id="leaveInstructionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header text-white" style="background: #3fd5db;">
                <h4 class="modal-title">
                    <i class="fa fa-info-circle mr-2"></i>
                    Important Instructions
                </h4>
            </div>

            <div class="modal-body p-4">

                <div class="alert alert-warning">
                    <strong>Please read carefully before proceeding.</strong>
                </div>

                <div class="instruction-list">

                    <div class="instruction-item">
                        <span class="instruction-number">1</span>
                        <div>
                            Please note that <strong>Work From Home (WFH)</strong>
                            means working from home and being fully available during office hours.
                        </div>
                    </div>

                    <div class="instruction-item">
                        <span class="instruction-number">2</span>
                        <div>
                            It should not be used for personal travel, leisure activities, or rest days.
                        </div>
                    </div>

                    <div class="instruction-item">
                        <span class="instruction-number">3</span>
                        <div>
                            If you are unable to work, you should apply for leave instead.
                        </div>
                    </div>

                </div>

            </div>

            <div class="modal-footer justify-content-center border-0 pb-4">

                <button type="button"
                        class="btn btn-outline-danger btn-sm px-5"
                     id="rejectInstruction">
                    Reject
                </button>

                <button type="button"
                        class="btn btn-success btn-sm px-5 ml-3"
                        id="acceptInstruction">
                    Accept
                </button>

            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="errorModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Submission Not Allowed</h5>
                <button type="button" id="errclose">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p id="errorMessage"></p>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        id="errclose">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- End WFH Instruction -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script type="text/javascript">
// disable button after apply leave
// document.querySelector("#submit").addEventListener('click', (e) => {
//     let input = e.target;
//     console.log('submited');

//     var leaveType = $('#leave_type').val();
//     var wfh_status = $('#wfh_status').val();

//     console.log('leaveType',leaveType);
//     console.log('wfh_status',wfh_status);

//     if (leaveType == 'WFH' && wfh_status != '1') {

//         e.preventDefault();
//         e.stopPropagation();
//         e.stopImmediatePropagation();

//         Swal.fire({
//             icon: 'warning',
//             title: 'WFH Instruction Not Accepted',
//             text: 'Please accept the instructions first.'
//         });

//         return false;
//     }
//     // return false;
//     setTimeout(() => {
//         input.disabled = true;
//     }, 1000);

//     setTimeout(() => {
//         input.disabled = false;
//     }, 5000); // 5000 milliseconds = 5 seconds
// });

$('#leaveForm').on('submit', function(e) {

    let leaveType = document.querySelector('#leave').value;
    var wfh_status = $('#wfh_status').val();

    if (  leaveType !== 'Short Leave' &&
    leaveType !== 'LWP' &&
    leaveType !== 'comp_off' &&
    leaveType !== 'Forgot Card'  ) {

        var fromDate = $('#from_date').val();

        if (fromDate) {

            var parts = fromDate.split('/');

            // MM/DD/YYYY
            var leaveDate = new Date(
                parseInt(parts[2]),     // year
                parseInt(parts[0]) - 1, // month
                parseInt(parts[1])      // day
            );

            var today = new Date();

            today.setHours(0, 0, 0, 0);
            leaveDate.setHours(0, 0, 0, 0);

            var diffDays = Math.floor(
                (today.getTime() - leaveDate.getTime()) /
                (1000 * 60 * 60 * 24)
            );

            // More than 2 days old
            if (diffDays > 2) {

                e.preventDefault();

                $('#errorMessage').text(
                    'This Leave/WFH request is past the allowed submission deadline. Applications can only be submitted within 2 days of the requested date. Please contact HR for further assistance.'
                );

                // $('#errorModal').modal('show');
                $('#applyLeave').modal('hide');

                $('#applyLeave').one('hidden.bs.modal', function () {
                    $('#errorModal').modal('show');
                });

                return false;
            }
        }

        // Instruction not accepted
        if (wfh_status != '1' && leaveType == 'WFH') {

            e.preventDefault();
            // alert('Please accept the WFH instructions before submitting.');

            $('#applyLeave').modal('hide');

            $('#applyLeave').one('hidden.bs.modal', function () {
                $('#leaveInstructionModal').modal('show');
            });

            return false;
        } else {
            return
        }
    }

});


$(document).on('click', '#errclose', function () {

    console.log('error modal closed');


    $('#errorModal').modal('hide');

    $('#errorModal').one('hidden.bs.modal', function () {
        $('#applyLeave').modal('show');
    });

});

$(document).on('click', '#acceptInstruction', function () {

    $('#wfh_status').val('1');

    $('#leaveInstructionModal').modal('hide');

    $('#leaveInstructionModal').one('hidden.bs.modal', function () {

        document.getElementById('leaveForm').submit();

    });

});

$(document).on('click', '#rejectInstruction', function () {

            $('#leaveInstructionModal').modal('hide');

            $('#leaveInstructionModal').one('hidden.bs.modal', function () {

                $('#applyLeave').modal('show');
                $('#wfh_status').val('0');

            });

});

$(function() {
    $(document).tooltip({
        position: {
            my: "center bottom",
            at: "center top",
        },
    });
});
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
        // wfh instruction reject/accept
        // $('#applyLeave').modal('hide');
        // $('#leaveInstructionModal').modal('show');

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
        // $("#alert_wfh").html('<span style="color:red; font-weight: 500; font-size: 11px;">Note: Officially, work from home constitutes a half-day. Approval for a full day requires manager consent and a valid reason stating why it should be considered as Full Day.</span>');
        // $("#opt_nal").html('<span style="color:gray; font-size:11px; font-weight: 600;">(Optional but mandatory for Full Day Work from Home.)</span>');
        // $('input[name="wfhtype"]').change(function() {
        // var selectedValue = $('input[name="wfhtype"]:checked').val();
        // console.log('selectedValue',selectedValue);
        // if(selectedValue==1) {
        //     // console.log('fgh');
        // $("#opt_nal").html('<span style="color:gray; font-size:12px; color:red;">* (Why your WFH should be considered as Full Day WFH?)</span>');
        // $("#textArea").attr('required', 'required');
        // $(".fa-info-circle").hide();
        // } else {
        //     $("#opt_nal").html('(Optional)');
        //     $("#textArea").removeAttr('required');
        //     $(".fa-info-circle").show();
        // }
        // });
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

    // if (leaveType == 'WFH') {
    //     $("#opt_nal").html('');
    // }
    //     reason.style.display = 'none';
    //     msg.innerText = '(* Write a message! Mandatory...)';
    //     opt.innerText = '';
    //     if (document.querySelector('#textArea').value != '') {
    //         document.querySelector('#my-form').submit();
    //     }
    // } else {
    //     reason.style.display = 'block';
    //     opt.innerText = '(Optional)';
    //     msg.innerText = '';
    // }

}


$(document).ready(function() {
    // $('#editLeaveType').attr("disabled", true);
    // select date function
    $(".datepicker1").datepicker({
        dateFormat: "yy-mm-dd",
        onSelect: function() {
            let selected = $(this).val();
            // alert(selected);
        }
    });
});

$(".cancel-leave").click(function() {
    var id = $(this).attr("data-id");
    var csrf = $("input[name='_csrfToken']").val();
    var url = "<?= $this->Url->build([
                        "controller" => "Leaves",
                        "action" => "changestatus"
                    ]) ?>";

    $.post(url, {
        'id': id,
        '_csrfToken': csrf
    }, function(data, status) {
        if (data == "Yes") {
            location.reload();
        }
    });
});
</script>