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
</style>
<section class="page page-dashboard">
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

    <div class="page-tab">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="v-tab">

                        <li class="active">
                            <!--  <a href="#">My Leaves</a>
                            -->
                            <?= $this->Html->link('My Leaves', [
                                'controller' => 'Leaves',
                                'action' => 'index'
                            ]); ?>
                        </li>

                        <li>
                            <?= $this->Html->link('Requested Leaves', [
                                'controller' => 'Leaves',
                                'action' => 'requestleave'
                            ]); ?>
                        </li>

                        <?php
                        if (($leaveSession['role'] != 3) || ($leaveSession['role'] == 3 &&
                            array_intersect($leaveSession['role_name'], array(12)))) {
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

            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h1 class="heading" style="font-weight:800">
                        <?= @$user_data->name ?>
                    </h1>
                    <p class="lead"><i class="fa fa-envelope"></i>
                        <?= @$user_data->email ?>
                    </p>
                    <p class="lead"><i class="fa fa-phone"></i>
                        <?= @$user_data->emp_detail->mobile_no ?>
                    </p>
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
                <div class="col-md-12">
                    <div class="block">
                        <div class="header">
                            <h4 class="title">Leave Management</h4>

                        </div>
                        <div class="content ">
                            <table id="example1" style="width:100%" class="table table-default table-striped block">
                                <thead>
                                    <tr>
                                        <th>Leave Type</th>
                                        <th style="width:200px;">Subject</th>
                                        <th>Applied on</th>
                                        <th>Leave Date</th>
                                        <th>Approved By</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $current_date = date("Y-m-d");
                                    foreach ($leaved_data as $key) {
                                    ?>
                                    <?php if (!in_array($key['status'], ['cancelled'])) : ?>

                                    <tr>
                                        <td><?= $key['leave_type'] ?></td>
                                        <td><?= $key['subject'] ?></td>
                                        <td><span style="display:none;"><?= date('Ymd',strtotime($key['applied_on'])) ?></span><?= date('d-m-Y',strtotime($key['applied_on'])) ?></td>
                                        <td><span style="display:none;"><?= date('Ymd',strtotime($key['from_date'])) ?></span>
                                            <?= date('d-m-Y',strtotime($key['from_date'])) ?>
                                            to
                                            <?= date('d-m-Y',strtotime($key['to_date'])) ?>
                                        </td>
                                        <td><?= @$key['user']['name'] ?></td>
                                        <td>

                                            <?php
                                                    if (@$key['status'] == 'cancelled' || @$key['status'] == 'Rejected') :
                                                    ?>
                                            <span class="badge badge-danger">
                                                <?= @$key['status'] ?>
                                            </span>
                                            <?php
                                                    endif;
                                                    ?>
                                            <?php
                                                    if (@$key['status'] == 'Approved') :
                                                    ?>
                                            <span class="badge badge-success">
                                                <?= @$key['status'] ?>
                                            </span>
                                            <?php
                                                    endif;
                                                    ?>
                                            <?php
                                                    if (@$key['status'] == 'Pending') :
                                                    ?>
                                            <span class="badge badge-info">
                                                <?= @$key['status'] ?>
                                            </span>
                                            <?php
                                                    endif;
                                                    ?>
                                        </td>

                                        <?php
                                                if ($key['status'] != "Rejected") :
                                                ?>
                                        <td>
                                            <?php
                                                        if (date("Y-m-d", strtotime($key['from_date'])) > $current_date) :
                                                        ?>
                                            <a class="icon icon-sm icon-primary cancel-leave"
                                                data-id="<?= $key['id'] ?>" style="cursor: pointer;">
                                                <i class="fa fa-archive"></i>
                                            </a>
                                            <?php
                                                            if (!in_array($key['status'], ['Approved'])) :
                                                            ?>
                                            <!-- <a href="#" data-target="#editLeave" onclick="editLeave(<?= $key['id'] ?>)"
                                                data-toggle="modal" class="icon icon-sm icon-secondary">
                                                <i class="fa fa-pencil-alt"></i>
                                            </a> -->
                                            <!-- <a href="#" onclick="loadModalData(this)" data-toggle="modal" data-id="<?= $key['id'] ?>" data-type="<?= $key['leave_type'] ?>" data-subject="<?= $key['subject'] ?>" data-from="<?= $key['from_date'] ?>" data-to_date="<?= $key['to_date'] ?>" data-target="#editLeave" data-message="<?= $key['message'] ?>" class="v-btn v-btn-dark btn-sm">Edit</a> -->
                                            <?php //else : echo "---";
                                                            endif;
                                                        else : echo "---";
                                                        endif; ?>

                                            <!-- <a href="#" class="v-btn v-btn-dark btn-sm">Re-send</a> -->


                                        </td>
                                        <?php else : echo "<td>---</td>";
                                                endif; ?>
                                    </tr>
                                    <?php endif; ?>

                                    <?php } ?>
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

<div class="modal fade" tabindex="-1" role="dialog" id="applyLeave">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= $this->Form->create($leave, ['url' => [
                'Controller' => 'Leaves',
                'action' => 'add'
            ]]) ?>

            <input type="hidden" name="created_by" value="<?= $user_data->id ?>">
            <div class="modal-header">
                <h5 class="modal-title">Apply Leave</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
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
                <button class="v-btn v-btn-primary" type="submit" id="submit">Apply Leave</a>
            </div>

            <?= $this->Form->end() ?>

        </div>
    </div>
</div>

<!-- End Apply leave modal -->


<!-- Edit leave model -->

<div class="modal fade" tabindex="-1" role="dialog" id="editLeave">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= $this->Form->create(null, ['url' => [
                'Controller' => 'Leaves',
                'action' => 'edit'
            ]]) ?>

            <input type="hidden" name="created_by" id="createdBy" value="">
            <input type="hidden" name="id" id="leaveId" value="">

            <div class="modal-header">
                <h5 class="modal-title">Edit Leave</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">To</label>
                            <div class="adon-group res">
                                <select name="resources[]" class="form-control" multiple id="langOpt1">
                                    <?php foreach ($resources as $m) : ?>
                                    <option value="<?= $m->id ?>">
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
                                'id' => 'editSubject',
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
                                'id' => 'editLeaveType',
                                'label' => false,
                                'class' => 'form-control',
                                'options' => [
                                    'Casual Leave' => 'Casual Leave',
                                    'Sick Leave' => 'Sick Leave',
                                    'Paid Leave' => 'Paid Leave',
                                    'Half Day' => 'Half Day',
                                    'WFH' => 'Work From Home',
                                    'comp_off' => 'Comp_Off',
                                    'LWP' => 'Leave Without Pay (LWP)',
                                    'Forgot Card' => 'Forgot Card',
                                ]
                            ]); ?>

                        </div>
                        <div class="col-md-4 mt-2">
                            <label for="">From Date</label>
                            <div class="adon-group">
                                <span class="icon ft-primary">
                                    <i class="fa fa-calendar-alt"></i>
                                </span>
                                <?= $this->Form->control('from_date', [
                                    'required' => true,
                                    'autocomplete' => 'off',
                                    'id' => 'editFromDate',
                                    'label' => false,
                                    'type' => 'text',
                                    'required' => true,
                                    'class' => 'form-control datepicker1'
                                ]); ?>
                            </div>
                        </div>
                        <div class="col-md-4 mt-2">
                            <label for="">To Date</label>
                            <div class="adon-group">
                                <span class="icon ft-primary">
                                    <i class="fa fa-calendar-alt"></i>
                                </span>
                                <?= $this->Form->control('to_date', [
                                    'required' => true,
                                    'autocomplete' => 'off',
                                    'label' => false,
                                    'id' => 'editToDate',
                                    'type' => 'text',
                                    'required' => true,
                                    'class' => 'form-control datepicker1'
                                ]); ?>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="">Reason</label>
                            <?= $this->Form->control('reason', [
                                'required' => false,
                                'id' => 'editReason',
                                'label' => false,
                                'class' => 'form-control',
                                'options' => [
                                    '' => 'Select Reason',
                                    'Personal' => 'Personal',
                                    'Medical' => 'Medical',
                                ]
                            ]); ?>

                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Message</label>
                            <?= $this->Form->control('message', [
                                'type' => 'textarea',
                                'required' => true,
                                'id' => 'editMessage',
                                'label' => false,
                                'required' => false,
                                'class' => 'form-control'
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal" aria-label="Close">Close</button>
                <button class="v-btn v-btn-primary submit" onclick="setTimeout(() => {
                    this.disabled = true;
                    // $('#editLeave').hide();
                    }, 1);" type=" submit">Edit Leave</a>
            </div>

            <?= $this->Form->end() ?>

        </div>
    </div>
</div>

<!-- End Edit leave model -->


<!-- Start Comp off -->

<div class="modal fade" tabindex="-1" role="dialog" id="addCompOff">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= $this->Form->create($leave, [
                'url' => [
                    'Controller' => 'Leaves',
                    'action' => 'addCompOff'
                ],
                'id' => 'my-form'
            ]) ?>

            <div class="modal-header">
                <h5 class="modal-title">Add Comp-Off</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Date</label>
                            <div class="adon-group">
                                <span class="icon ft-primary">
                                    <i class="fa fa-calendar-alt"></i>
                                </span>
                                <?= $this->Form->control('request_date', [
                                    'required' => true,
                                    'autocomplete' => 'off',
                                    'label' => false,
                                    'id' => 'reqDate',
                                    'type' => 'text',
                                    'required' => true,
                                    'class' => 'form-control datepicker'
                                ]); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Description</label>
                            <?= $this->Form->control('description', [
                                'required' => true,
                                'type' => 'textarea',
                                'label' => false,
                                'required' => true,
                                'class' => 'form-control'
                            ]); ?>
                        </div>
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

<!-- End Comp off -->

<!-- Start Holiday -->

<div class="modal fade" tabindex="-1" role="dialog" id="addHoli">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= $this->Form->create($leave, [
                'url' => [
                    'Controller' => 'Leaves',
                    'action' => 'addAnnualHoliday'
                ],
                'id' => 'my-form'
            ]) ?>

            <div class="modal-header">
                <h5 class="modal-title">Add Annual Holiday</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Holiday Date</label>
                            <div class="adon-group">
                                <span class="icon ft-primary">
                                    <i class="fa fa-calendar-alt"></i>
                                </span>
                                <input type="text" class="form-control datepicker" name="holiday_date"
                                    autocomplete="off" required>

                            </div>
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Description</label>
                            <?= $this->Form->control('description', [
                                'required' => true,
                                'type' => 'textarea',
                                'label' => false,
                                'required' => true,
                                'class' => 'form-control'
                            ]); ?>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal" aria-label="Close">Close</button>
                <button class="v-btn v-btn-primary" type="submit">Add Holiday</a>
            </div>

            <?= $this->Form->end() ?>

        </div>
    </div>
</div>

<!-- End Holiday -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script type="text/javascript">
// disable button after apply leave
document.querySelector("#submit").addEventListener('click', (e) => {
    let input = e.target;
    console.log('submited');
    // return false;
    setTimeout(() => {
        input.disabled = true;
    }, 1000);

    setTimeout(() => {
        input.disabled = false;
    }, 5000); // 5000 milliseconds = 5 seconds
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

    // end select date
    $('#example1').DataTable({
        responsive: true,
        scrollX: true,
        // "columnDefs": [
        //     { "orderSequence": [ "asc" ], "targets": [ 1 ] },
        //     { "orderSequence": [ "desc", "asc", "asc" ], "targets": [ 2 ] },
        //     { "orderSequence": [ "desc" ], "targets": [ 3 ] }
        // ],
        "ordering": true,
        "pageLength": 10
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


function editLeave(leaveId) {
    // console.log(leaveId);
    $.ajax({
        type: "GET",
        url: "<?= $this->Url->build('/leaves/edit/') ?>" + leaveId,
        success: function(data) {
            // console.log(JSON.parse(data));
            let leaveData = JSON.parse(data);
            let resources = JSON.parse(leaveData.resources)
            let resData = [];
            resources.forEach(element => {
                resData.push(element);
            });
            $('#langOpt1').val(resData);
            if (Object.keys(leaveData).length > 0) {
                $("#leaveId").val(leaveData.id);
                $("#langOpt1").multiselect('reload');
                $("#editSubject").val(leaveData.subject);
                $("#editLeaveType").val(leaveData.leave_type);
                $("#editFromDate").val(leaveData.from_date);
                $("#editToDate").val(leaveData.to_date);
                $("#editMessage").val(leaveData.message);
                $("#createdBy").val(leaveData.created_by);
                $("#editReason").val(leaveData.reason);
            }
        }
    });
}
</script>