


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
                <div class="col-6">
                    <div class="actions-ctrl text-md-right">
                        <a href="#" data-target="#applyLeave" data-toggle="modal" class="v-btn v-btn-secondary">
                            <i class="fa fa-list"></i><span>Apply Leave</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- PAGE-CONTENT -->
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="heading" style="font-weight:800"><?= $user_data->name ?></h1>
                    <p class="lead"><i class="fa fa-envelope"></i> <?= $user_data->email ?></p>
                    <p class="lead"><i class="fa fa-phone"></i> <?= $user_data->emp_detail->mobile_no ?></p>
                </div>
                <div class="col-md-6 text-right">
                    <ul class="mb-0">
                        <li>Total Leave: <span class="fw-600"><?= ($user_data->cl+$user_data->sl+$user_data->el) ?></span></li>
                        <li>Casual Leave: <span class="fw-600"><?=$user_data->cl?>/4</span></li>
                        <li>Sick Leave: <span class="fw-600"><?=$user_data->sl?>/5</span></li>
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
                            <table id="example" style="width:100%" class="table table-default table-striped block">
                                <thead>
                                    <tr>
                                        <th>Leave Type</th>
                                        <th>Subject</th>
                                        <th>Applied on</th>
                                        <th>Leave Date</th>
                                        <th>Approved By</th>
                                        <th>Staus</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Casual Leave</td>
                                        <td>Urgent work at home</td>
                                        <td>12 Dec 2021</td>
                                        <td>1 Jan to 5 Jan</td>
                                        <td>Arpit</td>
                                        <td><span class="badge badge-success">Approved</span></td>
                                        <td>
                                            <a href="#" class="v-btn v-btn-danger btn-sm">Cancel</a>
                                            <a href="#" class="v-btn v-btn-dark btn-sm">Re-send</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Casual Leave</td>
                                        <td>Urgent work at home</td>
                                        <td>12 Dec 2021</td>
                                        <td>1 Jan to 5 Jan</td>
                                        <td>Arpit</td>
                                        <td><span class="badge badge-success">Approved</span></td>
                                        <td>
                                            <a href="#" class="v-btn v-btn-danger btn-sm">Cancel</a>
                                            <a href="#" class="v-btn v-btn-dark btn-sm">Re-send</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Casual Leave</td>
                                        <td>Urgent work at home</td>
                                        <td>12 Dec 2021</td>
                                        <td>1 Jan to 5 Jan</td>
                                        <td>Arpit</td>
                                        <td><span class="badge badge-success">Approved</span></td>
                                        <td>
                                            <a href="#" class="v-btn v-btn-danger btn-sm">Cancel</a>
                                            <a href="#" class="v-btn v-btn-dark btn-sm">Re-send</a>
                                        </td>
                                    </tr>
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
               <?= $this->Form->create($leave) ?>

               <input type="hidden" name="created_by" value="<?=$user_data->id?>">


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
                            <label for="">Subject</label>
                            <input type="text" class="form-control" placeholder="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="">Leave Type</label>
                            <?=$this->Form->control('leave_type',['label'=>false,'class'=>'form-control','options'=>['CL'=>'Casual Leave (5)','SL'=>'Sick Leave(4)','Paid Leave(2)']]);?>
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
                              <?=$this->Form->control('from_date', ['label'=>false,'type'=>'date','empty' => false,'class'=>'form-control']);?>
                </div>
                        </div>
                        <div class="col-md-4">
                            <label for="">To Date</label>
                            <div class="adon-group">
                               <!--  <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span> -->
                                 <?=$this->Form->control('to_date', ['label'=>false,'type'=>'date','empty' => false,'class'=>'form-control']);?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Message</label>
                            <textarea name="" class="form-control" id="" cols="30" rows="4"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base">Close</button>
                <button  class="v-btn v-btn-primary" type="submit">Apply Leave</a>
            </div>

            <?= $this->Form->end() ?>

        </div>
    </div>
</div>