<style>
    .hide {
        display:none;
    }
</style>
<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-user-tie"></i></span>Leave Management
                    </div>
                </div>
                <!--  <div class="col-6">
                    <div class="actions-ctrl text-md-right">
                        <a href="#" data-target="#applyLeave" data-toggle="modal" class="v-btn v-btn-secondary">
                            <i class="fa fa-list"></i><span>Apply Leave</span>
                        </a>
                    </div>
                </div> -->
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
                            <?= $this->Html->link('My Leaves', [
                                'controller' => 'Leaves',
                                'action' => 'index'
                            ]); ?>
                        </li>

                        <li class="active">
                            <?= $this->Html->link('Requested Leaves', [
                                'controller' => 'Leaves',
                                "action" => 'requestleave'
                            ]); ?>
                        </li>

                        <?php
                        if (($leaveSession['role'] != 3) || ($leaveSession['role'] == 3 && array_intersect($leaveSession['role_name'], array(12)))) {
                        ?>
                        <li>
                            <?= $this->Html->link('All Leaves', [
                                    'controller' => 'Leaves',
                                    'action' => 'allLeaves'
                                ]) ?>
                        </li>
                        <?php } ?>

                        <li>
                            <?= $this->Html->link('Comp-Off', [
                                'controller' => 'Leaves',
                                'action' => 'addCompOff'
                            ]); ?>
                        </li>

                        <li>
                            <?= $this->Html->link('Requested Comp-Off', [
                                'controller' => 'Leaves',
                                'action' => 'requestCompOff'
                            ]); ?>
                        </li>


                    </ul>
                </div>
            </div>
        </div>
    </div>


    <!-- PAGE-CONTENT -->
    <div class="page-content">
        <div class="container">
            <?= $this->Flash->render() ?>
            <div id="alertWfh">

            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="block">
                        <div class="header">
                            <h4 class="title">Leave Management</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-3 ml-md-3">
                                <select name="change" id="changeStatus" class="form-control"
                                    onchange="location = this.value;">
                                    <option value="<?php echo $this->Url->build([
                                                        'controller' => 'leaves',
                                                        'action' => 'requestleave',
                                                    ]) ?>" <?php if ($selectStatus == '') echo "selected" ?>>All Leave
                                        Details</option>
                                    <option value="<?php echo $this->Url->build([
                                                        'controller' => 'leaves',
                                                        'action' => 'requestleave',
                                                        '?' => ['status' => 'Approved']
                                                    ]) ?>" <?php if ($selectStatus == 'Approved') echo "selected" ?>>
                                        Approved</option>
                                    <option value="<?php echo $this->Url->build([
                                                        'controller' => 'leaves',
                                                        'action' => 'requestleave',
                                                        '?' => ['status' => 'Pending']
                                                    ]) ?>" <?php if ($selectStatus == 'Pending') echo "selected" ?>>
                                        Pending
                                    </option>
                                    <option value="<?php echo $this->Url->build([
                                                        'controller' => 'leaves',
                                                        'action' => 'requestleave',
                                                        '?' => ['status' => 'cancelled']
                                                    ]) ?>" <?php if ($selectStatus == 'cancelled') echo "selected" ?>>
                                        Cancelled</option>
                                    <option value="<?php echo $this->Url->build([
                                                        'controller' => 'leaves',
                                                        'action' => 'requestleave',
                                                        '?' => ['status' => 'Rejected']
                                                    ]) ?>" <?php if ($selectStatus == 'Rejected') echo "selected" ?>>
                                        Rejected</option>
                                </select>
                            </div>

                            <div class="col-md-3 hide">
                                <input type="text" class="form-control" onkeyup="filterNameLeave(this)"
                                    placeholder="Name & Leave Type Filter...">
                                <input type="hidden" id="url" value="<?= WEBURL; ?>">
                            </div>
                        </div>
                        <div class="content ">
                            <table id="example1" style="width:100%" class="table table-default table-striped block">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Manager Name</th>
                                        <th>Leave Type</th>
                                        <th style="width:200px;">Subject</th>
                                        <th>Applied on</th>
                                        <th>Leave Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="filterLeave">
                                    <?php
                                    if ($selectStatus != "") {
                                        foreach ($leaved_data as $key) {
                                            // if ($key['status'] == 'cancelled') {
                                    ?>
                                    <tr>
                                        <td>
                                            <a href="#" onclick="loadModalData(this);"
                                                data-name="<?= $key['CreatedBy']['name'] ?>"
                                                data-type="<?= $key['leave_type'] ?>"
                                                data-applied="<?= $key['applied_on'] ?>"
                                                data-from="<?= $key['from_date'] ?>"
                                                data-to_date="<?= $key['to_date'] ?>" data-id="<?= $key['id'] ?>"
                                                data-subject="<?= $key['subject'] ?>"
                                                data-status="<?= $key['status'] ?>"
                                                data-message="<?= $key['message'] ?>" data-toggle="modal"
                                                data-target="#leaveDetail"><?= $key['CreatedBy']['name'] ?></a>
                                        </td>
                                        <td><?= $key['Manager'] ?></td>
                                        <td><?= $key['leave_type'] ?></td>
                                        <td><?= $key['subject'] ?></td>
                                        <td><span style="display:none;"><?= date('Ymd',strtotime($key['applied_on'])) ?></span><?= date('d-m-Y',strtotime($key['applied_on'])) ?></td>
                                        <td><span style="display:none;"><?= date('Ymd',strtotime($key['from_date'])) ?></span><?= date('d-m-Y',strtotime($key['from_date'])) ?> to <?= date('d-m-Y',strtotime($key['to_date'])) ?></td>
                                        <td>
                                            <?php
                                                    if ($key['status'] == 'Approved') :
                                                    ?>
                                            <span class="badge badge-success"><?= $key['status'] ?></span>
                                            <?php
                                                    endif;
                                                    ?>
                                            <?php
                                                    if ($key['status'] == 'Rejected' || $key['status'] == 'cancelled') :
                                                    ?>
                                            <span class="badge badge-danger"><?= $key['status'] ?></span>
                                            <?php
                                                    endif;
                                                    ?>
                                            <?php
                                                    if ($key['status'] == 'Pending') :
                                                    ?>
                                            <span class="badge badge-info"><?= $key['status'] ?></span>
                                            <?php
                                                    endif;
                                                    ?>
                                        </td>
                                        <?php
                                                if (!in_array($key['status'], ['Rejected', 'cancelled'])) {
                                                ?>
                                        <td>
                                            <?php
                                                        if (!in_array($key['status'], ["cancelled", "Approved"])) :
                                                            if ($key['leave_type'] == "WFH") {
                                                        ?>
                                            <?php if($key['wfh_flag'] == 1) { ?>
                                            <button class="v-btn v-btn-success btn-sm" title="WFH" id="wfh1"
                                            href="#" data-id="<?= $key['id'] ?>" data-message="<?= $key['message'] ?>" data-empId="<?= $key['created_by'] ?>" data-target="#wfh_Approved" data-toggle="modal"><i
                                                    class="fa fa-home"></i></button>
                                                    <?php } ?>
                                            <button class="v-btn v-btn-secondary btn-sm" title="Half Day" id="wfh5"
                                                onclick="workFromHomeLeave(this.id,<?= $key['id'] ?>, 'Half Day')"><i
                                                    class="fa fa-adjust"></i></button>
                                            <!-- <a class="v-btn v-btn-success btn-sm" onclick="workFromHomeLeave(<?= $key['id'] ?>)" data-target="#wfh" data-toggle="modal" title="Approved"><i class="fa fa-check"></i></a> -->
                                            <?php
                                                            } elseif ($key['leave_type'] == "Half Day") {
                                                            ?>
                                            <button class="v-btn v-btn-secondary btn-sm" title="Half Day" id="whf3"
                                                onclick="workFromHomeLeave(this.id,<?= $key['id'] ?>, 'Half Day')"><i
                                                    class="fa fa-adjust"></i></button>
                                            <?php
                                                            } else { ?>
                                            <button class="v-btn v-btn-success btn-sm approved-leaves-class"
                                                data-id="<?= $key['id'] ?>" id="appro" onclick="btnDisabled(this.id)"
                                                data-status="Approved" title="Approved"><i
                                                    class="fa fa-check"></i></button>
                                            <?php    }
                                                        // else : echo "---";
                                                        endif; ?>

                                            <?php if ($key['status'] != "Approved") : ?>

                                            <a class="v-btn v-btn-danger btn-sm approved-leaves-class"
                                                data-id="<?= $key['id'] ?>" data-status="Rejected" title="Rejected"><i
                                                    class="fa fa-times"></i></a>


                                            <?php else : ?>
                                            <a class="v-btn v-btn-danger btn-sm  cancel-leave"
                                                data-id="<?= $key['id'] ?>" title="Cancelled"><i
                                                    class="fa fa-times"></i></a>
                                            <?php
                                                        endif; ?>
                                        </td>
                                        <?php } else {
                                                    echo "<td>---</td>";
                                                } ?>
                                    </tr>
                                    <?php }
                                    } else {
                                        foreach ($result_data as $key) :
                                        ?>
                                    <tr>
                                        <td>
                                            <a href="#" onclick="loadModalData(this);"
                                                data-name="<?= $key['CreatedBy'] ?>"
                                                data-type="<?= $key['leave_type'] ?>"
                                                data-applied="<?= $key['applied_on'] ?>"
                                                data-from="<?= $key['from_date'] ?>"
                                                data-to_date="<?= $key['to_date'] ?>" data-id="<?= $key['id'] ?>"
                                                data-subject="<?= $key['subject'] ?>"
                                                data-status="<?= $key['status'] ?>"
                                                data-message="<?= $key['message'] ?>" data-toggle="modal"
                                                data-target="#leaveDetail"><?= $key['CreatedBy'] ?></a>
                                        </td>
                                        <td><?= $key['Manager'] ?></td>
                                        <td><?= $key['leave_type'] ?></td>
                                        <td><?= $key['subject'] ?></td>
                                        <td><span style="display:none;"><?= date('Ymd',strtotime($key['applied_on'])) ?></span><?= date('d-m-Y',strtotime($key['applied_on'])) ?></td>
                                        <td><span style="display:none;"><?= date('Ymd',strtotime($key['from_date'])) ?></span><?= date('d-m-Y',strtotime($key['from_date'])) ?> to <?= date('d-m-Y',strtotime($key['to_date'])) ?></td>
                                        <td>
                                            <?php
                                                    if ($key['status'] == 'Approved') :
                                                    ?>
                                            <span class="badge badge-success"><?= $key['status'] ?></span>
                                            <?php
                                                    endif;
                                                    ?>
                                            <?php
                                                    if ($key['status'] == 'Rejected' || $key['status'] == 'cancelled') :
                                                    ?>
                                            <span class="badge badge-danger"><?= $key['status'] ?></span>
                                            <?php
                                                    endif;
                                                    ?>
                                            <?php
                                                    if ($key['status'] == 'Pending') :
                                                    ?>
                                            <span class="badge badge-info"><?= $key['status'] ?></span>
                                            <?php
                                                    endif;
                                                    ?>
                                        </td>
                                        <?php
                                                if (!in_array($key['status'], ['Rejected', 'cancelled'])) {
                                                ?>
                                        <td>
                                            <?php
                                                        if (!in_array($key['status'], ["cancelled", "Approved"])) :
                                                            if ($key['leave_type'] == "WFH") {
                                                        ?>
                                                        <?php if($key['wfh_flag'] == 1) { ?>
                                            <button class="v-btn v-btn-success btn-sm" title="WFH" id="wfh4"
                                            href="#" data-id="<?= $key['id'] ?>" data-message="<?= $key['message'] ?>" data-empId="<?= $key['created_by'] ?>" data-target="#wfh_Approved" data-toggle="modal"><i
                                                    class="fa fa-home"></i></button>
                                                    <?php } ?>
                                            <button class="v-btn v-btn-secondary btn-sm" title="Half Day" id="wfh5"
                                                onclick="workFromHomeLeave(this.id,<?= $key['id'] ?>, 'Half Day')"><i
                                                    class="fa fa-adjust"></i></button>
                                            <!-- <a class="v-btn v-btn-success btn-sm" onclick="workFromHomeLeave(<?= $key['id'] ?>)" data-target="#wfh" data-toggle="modal" title="Approved"><i class="fa fa-check"></i></a> -->
                                            <?php
                                                            } elseif ($key['leave_type'] == "Half Day") {
                                                            ?>
                                            <button class="v-btn v-btn-secondary btn-sm" title="Half Day" id="wfh6"
                                                onclick="workFromHomeLeave(this.id,<?= $key['id'] ?>, 'Half Day')"><i
                                                    class="fa fa-adjust"></i></button>
                                            <?php
                                                            } else { ?>
                                            <button class="v-btn v-btn-success btn-sm approved-leaves-class"
                                                id="approve" onclick="btnDisabled(this.id)" data-id="<?= $key['id'] ?>"
                                                data-status="Approved" title="Approved"><i
                                                    class="fa fa-check"></i></button>
                                            <?php    }
                                                        // else : echo "---";
                                                        endif; ?>

                                            <?php if ($key['status'] != "Approved") : ?>

                                            <a class="v-btn v-btn-danger btn-sm approved-leaves-class"
                                                data-id="<?= $key['id'] ?>" data-status="Rejected" title="Rejected"><i
                                                    class="fa fa-times"></i></a>


                                            <?php else : ?>
                                            <a class="v-btn v-btn-danger btn-sm  cancel-leave"
                                                data-id="<?= $key['id'] ?>" title="Cancelled"><i
                                                    class="fa fa-times"></i></a>
                                            <?php
                                                        endif; ?>
                                        </td>
                                        <?php } else {
                                                    echo "<td>---</td>";
                                                } ?>
                                    </tr>
                                    <?php endforeach;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- <a href="#" data-target="#confirmation_assign_project" data-toggle="modal"
                        class="v-btn v-btn-secondary float-right"><span>Save Project</span></a> -->
                </div>
            </div>
        </div>
    </div>
</section>

<!-- APPLY LEAVE MODAL -->

<div class="modal fade" tabindex="-1" role="dialog" id="leaveDetail">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Leave Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Name</label>
                            <input id='name_modal' type="text" class="form-control" readonly>

                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Subject</label>
                            <input id='subject_modal' type="text" class="form-control" readonly>

                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Leave Type</label>
                            <input id='leave_type_modal' type="text" class="form-control" readonly>
                            <!-- 
                            <select name="" class="form-control" id="">
                                <option value="">Casual Leave (5)</option>
                                <option value="">Sick Leave(4)</option>
                                <option value="">Paid Leave(2)</option>
                            </select> -->
                        </div>
                        <div class="col-md-4">
                            <label for="">From Date</label>
                            <div class="adon-group">
                                <!-- <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span> -->
                                <input id='from_date_modal' type="text" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="">To Date</label>
                            <div class="adon-group">
                                <!--  <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span> -->
                                <input id='to_date_modal' type="text" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Message</label>
                            <textarea id='message_modal' class="form-control" readonly></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base approved-leaves-class" id="approved_button_modal" data-status="Approved">Approve</button>
                <button class="v-btn v-btn-primary approved-leaves-class" id="reject_button_modal" data-status="Rejected">Reject</a>
            </div> -->

        </div>
    </div>
</div>




<div class="modal fade" tabindex="-1" role="dialog" id="editLeave">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="edit_body_leaves">


        </div>
    </div>
</div>


<!-- WFH Approve leave -->

<div class="modal fade" tabindex="-1" role="dialog" id="wfh">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= $this->Form->create(null, ['url' => ['Controller' => 'Leaves', 'action' => 'workFromHome']]) ?>
            <!-- <input type="hidden" id="id" name="id"> -->
            <input type="hidden" id="leave_type" name="leave_type">
            <input type="hidden" id="from_date" name="from_date">
            <input type="hidden" id="applied_on" name="applied_on">
            <input type="hidden" id="to_date" name="to_date">
            <input type="hidden" id="created_by" name="created_by">
            <input type="hidden" id="approved_by" name="approved_by">
            <input type="hidden" id="status" name="status">
            <input type="hidden" id="subject" name="subject">
            <input type="hidden" id="message" name="message">

            <div class="modal-header">
                <h5 class="modal-title">Select One Option</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="wfh_day" id="inlineRadio1" value="1">
                        <label class="form-check-label" for="inlineRadio1">One Full Day</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="wfh_day" id="inlineRadio2" value="2">
                        <label class="form-check-label" for="inlineRadio2">Two Full Days</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="wfh_day" id="inlineRadio3" value="0.5">
                        <label class="form-check-label" for="inlineRadio3">Half Days</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal" aria-label="Close">Close</button>
                <button class="v-btn v-btn-primary" type="submit" onclick="setTimeout(() => {
                    this.disabled = true;
                    }, 1);">Submit</a>
            </div>

            <?= $this->Form->end() ?>

        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="wfh_Approved">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
                <h5 class="modal-title">Please Submit Reason</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= $this->Form->create(null, ['id'=>'ApprovedworkFromHomeform']) ?>
            <p id="emp_msg" style="padding-top: 4px; padding-left: 18px;"></p>
            <input type="hidden" id="id" name="id">
            <input type="hidden" id="emp_id" name="emp_Id">

            
            <div class="modal-body">
                <div class="content">
                    <textarea class="form-control" id="manager_comment" name="manager_comment" rows="4" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal" aria-label="Close">Close</button>
                <a class="v-btn v-btn-primary" onclick = "ApprovedworkFromHomeLeave();">Submit</a>
            </div>

            <?= $this->Form->end() ?>

        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script type="text/javascript">
// document.querySelector("#submit").addEventListener('click', (e) => {
//         let input = e.target;
//         setTimeout(() => {
//             input.disabled = true;
//         }, 1);
//     });

function btnDisabled(id) {
    // Link Hide
    // document.getElementById(`${id}`).style.display = 'none';

    // Btn disasbled
    document.getElementById(`${id}`).disabled = true;
}

$(document).ready(function() {
    $('#example1').dataTable({
        /* Disable initial sort */
        "aaSorting": [],
        "lengthMenu": [[100, "All", 50, 25], [100, "All", 50, 25]],
        stateSave: true
    });
});
// WFM leave function

function workFromHomeLeave(btnId, id, type, empId = null) {

    document.getElementById(`${btnId}`).disabled = true;
    // console.log(btnId, id, type, empId);
    $.ajax({
        url: "<?= $this->Url->build('/leaves/wfhLeave/') ?>" + id,
        type: "GET",
        data: {
            leaveType: type,
            empId: empId
        },
        success: function(data) {
            // console.log(data)
            // if (data == 1) {
            //     location.reload();
            // }
            location.reload();
        }
    });

}

function ApprovedworkFromHomeLeave() {
formData = $("#ApprovedworkFromHomeform").serialize();
var comment = $("#manager_comment").val();
if(comment==='') {
    alert('Please enter messege');
}
$.ajax({
    url: "<?= $this->Url->build('/leaves/wfhLeave/') ?>" + id,
    type: "post",
    data: formData,
    success: function(data) {
        // console.log(data)
        // if (data == 1) {
        //     location.reload();
        // }
        location.reload();
    }
});

}



$(".approved-leaves-class").click(function() {

    var id = $(this).attr("data-id");
    var status = $(this).attr("data-status");
    // console.log(status);
    if (status == 'Rejected') {
        if (confirm("Are you sure you want to Rejected leave.")) {
            var csrf = '<?= $this->request->getAttribute('csrfToken'); ?>';


            var url = "<?= $this->Url->build([
                                "controller" => "Leaves",
                                "action" => "approvedleaves"
                            ]) ?>";

            $.post(url, {
                'id': id,
                '_csrfToken': csrf,
                'status': status
            }, function(data, status) {
                // console.log(data);
                if (data == "Yes") {
                    location.reload();
                }
            });
        } else {
            location.reload();
        }

    } else {

        var csrf = '<?= $this->request->getAttribute('csrfToken'); ?>';


        var url = "<?= $this->Url->build([
                            "controller" => "Leaves",
                            "action" => "approvedleaves"
                        ]) ?>";

        $.post(url, {
            'id': id,
            '_csrfToken': csrf,
            'status': status
        }, function(data, status) {
            // console.log(data);
            if (data == "Yes") {
                location.reload();
            }
        });
    }

});

function loadModalData(ele) {

    var id = $(ele).attr("data-id");
    var name = $(ele).attr("data-name");
    var subject = $(ele).attr("data-subject");
    var type = $(ele).attr("data-type");
    var from = $(ele).attr("data-from");
    var to_date = $(ele).attr("data-to_date");
    var msg = $(ele).attr("data-message");
    // console.log(to_date);
    $("#name_modal").val(name);
    $("#subject_modal").val(subject);
    $("#leave_type_modal").val(type);
    $("#from_date_modal").val(from);
    $("#to_date_modal").val(to_date);
    $("#message_modal").val(msg);

    $("#approved_button_modal").attr("data-id", id);
    $("#reject_button_modal").attr("data-id", id);

}

// cancel leave

$(".cancel-leave").click(function() {

    var id = $(this).attr("data-id");

    if (confirm("Are you sure you want to cancelled leave.")) {


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
    } else {
        location.reload();
    }

});

function filterNameLeave(data) {

    const val = data.value;
    // console.log(val);
    $.ajax({
        url: "<?= $this->Url->build('/leaves/filterNameLeave') ?>",
        method: "GET",
        data: {
            val
        },
        success: function(res) {
            let leaveData = JSON.parse(res);
            // console.log(leaveData);
            let row = "";

            $("#filterLeave").html("");
            let canAppRej = ["cancelled", "Approved", "Rejected"];
            leaveData.forEach(element => {
                let appliedIOn = new Date(element.applied_on).toLocaleDateString('en-US', {
                    year: '2-digit',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    // hour12: false
                });

                let fromDate = new Date(element.from_date).toLocaleDateString('en-US', {
                    year: '2-digit',
                    month: '2-digit',
                    day: '2-digit'
                });

                let toDate = new Date(element.from_date).toLocaleDateString('en-US', {
                    year: '2-digit',
                    month: '2-digit',
                    day: '2-digit'
                });
                row += `<tr>
                            <td>
                                <a href="#" onclick="loadModalData(this);"
                                    data-name="${element.name}"
                                    data-type="${element.leave_type}"
                                    data-applied="${element.applied_on}"
                                    data-from="${element.from_date}"
                                    data-to_date="${element.to_date}"
                                    data-subject="${element.subject}"
                                    data-status="${element.status}"
                                    data-toggle="modal"
                                    data-message="${element.message}"
                                    data-target="#leaveDetail"
                                    >
                                    ${element.name}
                                </a>
                            </td>
                            <td>
                                ${element.leave_type}
                            </td>
                            <td>
                                ${element.subject}
                            </td>
                            <td>
                                ${appliedIOn}
                            </td>
                            <td>
                                ${fromDate} to ${toDate}
                            </td>
                            <td>
                                ${element.leave_status === "Pending" 
                                    ? `<span class="badge badge-info">${element.leave_status}</span>` 
                                    : element.leave_status === "cancelled" || element.leave_status === "Rejected" 
                                    ? `<span class="badge badge-danger">${element.leave_status}</span>` 
                                    : `<span class="badge badge-success">${element.leave_status}</span>`}
                            </td>
                            <td>
                                ${!canAppRej.includes(element.leave_status) 
                                    ? element.leave_type == "WFH" 
                                    ? `<button class="v-btn v-btn-success btn-sm" title="WFH" id="wfh1" onclick="workFromHomeLeave(this.id,${element.leave_id}, 'WFH', ${element.created_by})"><i class="fa fa-home"></i></button>
                                    <button class="v-btn v-btn-secondary btn-sm" title="Half Day" id="wfh2" onclick="workFromHomeLeave(this.id,${element.leave_id}, 'Half Day')"><i class="fa fa-adjust"></i></button>
                                    <a class="v-btn v-btn-danger btn-sm" onclick="filterLeaveCancel(${element.leave_id})" data-id="${element.leave_id}" title="Cancelled"><i class="fa fa-times"></i></a>`
                                    : element.leave_type == "Half Day" 
                                    ?  `<button class="v-btn v-btn-secondary btn-sm" title="Half Day" id="whf3" onclick="workFromHomeLeave(this.id,${element.leave_id}, 'Half Day')"><i class="fa fa-adjust"></i></button>`
                                    : `<button class="v-btn v-btn-success btn-sm" onclick="approvedFilterLeave(${element.leave_id}, 'Approved')"  title="Approved"><i class="fa fa-check"></i></button>
                                    <a class="v-btn v-btn-danger btn-sm" onclick="filterLeaveCancel(${element.leave_id})" data-id="${element.leave_id}" title="Cancelled"><i class="fa fa-times"></i></a>`
                                    : element.leave_status == "Approved" 
                                    ? `<a class="v-btn v-btn-danger btn-sm" onclick="filterLeaveCancel(${element.leave_id})" title="Cancelled"><i class="fa fa-times"></i></a>` 
                                    : "---"}
                            </td>
                        </tr>
                        `
            })

            $("#filterLeave").html(row);
        },
    });
}

function filterLeaveCancel(id) {

    if (confirm("Are you sure you want to cancelled leave.")) {

        let csrf = $("input[name='_csrfToken']").val();

        let url = "<?= $this->Url->build([
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
    }
    //  else {
    //     location.reload();
    // }
}

function approvedFilterLeave(id, status) {
    // console.log(id, status)
    if (status == 'Rejected') {
        if (confirm("Are you sure you want to Rejected leave.")) {
            let csrf = '<?= $this->request->getAttribute('csrfToken'); ?>';
            let url = "<?= $this->Url->build([
                                "controller" => "Leaves",
                                "action" => "approvedleaves"
                            ]) ?>";

            $.post(url, {
                'id': id,
                '_csrfToken': csrf,
                'status': status
            }, function(data, status) {
                // console.log(data);
                if (data == "Yes") {
                    location.reload();
                }
            });
        }

    } else {

        let csrf = '<?= $this->request->getAttribute('csrfToken'); ?>';
        let url = "<?= $this->Url->build([
                            "controller" => "Leaves",
                            "action" => "approvedleaves"
                        ]) ?>";

        $.post(url, {
            'id': id,
            '_csrfToken': csrf,
            'status': status
        }, function(data, status) {
            // console.log(data);
            if (data == "Yes") {
                location.reload();
            }
        });
    }
}
</script>

<script>
    $(document).ready(function() {
        $('#wfh_Approved').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id'); // Extract info from data-* attributes
            var msg = button.data('message');
            var empId = button.data('empid');
            // console.log(empId);
            $('#id').val(id); // Set the value of the input field
            $('#emp_id').val(empId);
            $('#emp_msg').html(msg);
        });
    });
</script>