<?php $session = new \Cake\Http\Session();
$userSession = $session->read('data');

?>
<style>
    .bold-data {
        font-weight: 700;
    }
    .buttons-csv {
        display: none;
    }
    .buttons-pdf {
        display: none;
    }
    .buttons-print {
        display: none;
    }
</style>
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
    <!-- PAGE-TITLE -->
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

                        <li class="active">
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
                            <li>
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


    <!-- PAGE-CONTENT -->
    <div class="page-content">
        <div class="container">
            <!-- FILTER -->
                
            <!-- TABLE -->
            <div class="row">
                <div class="col-md-12">
                    <?= $this->Flash->render() ?>
                    <div class="content">
                        <table id="datatable" style="width:100%;" class="table table-default table-striped block table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Emp.Id</th>
                                                <th>Emp.Name</th>
                                                <th>Total CL</th>
                                                <th>Total SL</th>
                                                <th>Total EL</th>
                                                <th>Total Comp off</th>
                                                <?php 
                                                if($userSession['email']=='himani.duhan@actiknow.com' || $userSession['email']=='pinkey.yadav@actiknowbi.com' || $userSession['email']=='seema.rawat@actiknow.com' || $userSession['email']=='arpit.batham@actiknow.com' || $userSession['email']=='sumit.jhunjhunwala@actiknow.com') { ?>
                                                    <th>Action</th>
                                                <?php } ?>
                                            </tr>
                                            
                                        </thead>
                                    <tbody>
                                    <?php foreach ($leave_data as $data) : ?>
                                        <tr data-id="<?= $data['uid'] ?>">
                                            <td><?= $data['uid'] ?></td>
                                            <td><?= $data['name'] ?></td>
                                            <?php foreach ($data['leaves'] as $val) : ?>
                                            <td><?= number_format($val['cl']-$val['sumCL'],2) ?></td>
                                            <td><?= number_format($val['sl']-$val['sumSL'],2) ?></td>
                                            <td>
                                            <?php $total_EL = $val['el']-$val['sumEL']; ?>
                                            <?= number_format(min($total_EL, 30), 2) ?>
                                            </td>
                                            <td><?= ($val['comp_off']-$val['sumComp']) ?></td>
                                            <?php endforeach; ?>
                                            <?php 
                                                if($userSession['email']=='himani.duhan@actiknow.com' || $userSession['email']=='pinkey.yadav@actiknowbi.com' || $userSession['email']=='seema.rawat@actiknow.com' || $userSession['email']=='arpit.batham@actiknow.com' || $userSession['email']=='sumit.jhunjhunwala@actiknow.com') { ?>
                                            <td><a href="javascript::void(0);" onclick="loadModelData(this)" data-id="<?= $data['uid'] ?>" data-target="#leave_module" data-toggle="modal"><i class="fa fa-edit"></i></a></td>
                                            <?php } ?>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
  <div class="modal fade" tabindex="-1" role="dialog" id="leave_module">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= $this->Form->create(null, ['url' => [
                'Controller' => 'Users',
                'action' => 'leaveModule'
            ]]) ?>

           
            <div class="modal-header">
                <h5 class="modal-title">Leave Management</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                    <input type="hidden" name="id" id="uid">
                    <table class="table table-bordered">
                        <tr>
                            <td><input type="checkbox" name="cl" id="cl" class=""><span style="margin-left: 10px;">Casual Leave</span></td>
                            <td style="width: 100px;"><input type="text" min="0" max="100" class="form-control" name="cl_qty"></td>
                            <td>
                            <input type="radio" name="leave_source_cl" value="Addon" id="type-0" class="rd-input" checked /><label for="type-0">&nbsp;Addon</label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="leave_source_cl" value="Delete" id="type-1" class="rd-input" /><label for="type-1">&nbsp;Delete</label>
                            </td>
                            
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="sl" id="sl" class=""><span style="margin-left: 10px;">Sick Leave</span></td>
                            <td style="width: 100px;"><input type="text" min="0" max="100" class="form-control" name="sl_qty"></td>
                            <td>
                            <input type="radio" name="leave_source_sl" value="Addon" id="type-0" class="rd-input" checked /><label for="type-0">&nbsp;Addon</label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="leave_source_sl" value="Delete" id="type-1" class="rd-input" /><label for="type-1">&nbsp;Delete</label>
                            </td>
                            
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="el" id="el" class=""><span style="margin-left: 10px;">Paid Leave</span></td>
                            <td style="width: 100px;"><input type="text" min="0" max="100" class="form-control" name="el_qty"></td>
                            <td>
                            <input type="radio" name="leave_source_el" value="Addon" id="type-0" class="rd-input" checked /><label for="type-0">&nbsp;Addon</label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="leave_source_el" value="Delete" id="type-1" class="rd-input" /><label for="type-1">&nbsp;Delete</label>
                            </td>
                            
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="comp_off" id="comp_off" class=""><span style="margin-left: 10px;">comp_off</span></td>
                            <td style="width: 100px;"><input type="text" min="0" max="100" class="form-control" name="comp_off_qty"></td>
                            <td>
                            <input type="radio" name="leave_source_comp_off" value="Addon" id="type-0" class="rd-input" checked /><label for="type-0">&nbsp;Addon</label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="leave_source_comp_off" value="Delete" id="type-1" class="rd-input" /><label for="type-1">&nbsp;Delete</label>
                            </td>
                            
                        </tr>
                    </table>

                    <div id="leave-error" class="text-danger" style="display:none; margin-top:10px;">
                        Please select at least one leave type.
                    </div>

                    
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal" aria-label="Close">Close</button>
                <button class="v-btn v-btn-primary" type="submit" id="submit">Submit</a>
            </div>

            <?= $this->Form->end() ?>

        </div>
    </div>
</div>

<style>
    .select2-container--default .select2-selection--single {
        border: unset !important;
        border-radius: unset !important;
        height: 33px !important;
        
    }
    .select2-container {
        width: 100% !important;
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function ($) {
        $("#emp").select2({
            placeholder: "Select Option",
        });
    });

    function loadModelData(ele) {
        var id = $(ele).attr("data-id");
        $("#uid").val(id);
    }
</script>

<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': '<?= $this->request->getAttribute('csrfToken') ?>'
        }
    });
});

$(document).ready(function () {
    $("#submit").on("click", function (e) {
        if (!$("input[name=cl]").is(":checked") &&
            !$("input[name=sl]").is(":checked") &&
            !$("input[name=el]").is(":checked") &&
            !$("input[name=comp_off]").is(":checked")) {

            e.preventDefault();
            $("#leave-error").show();
            return false;
        } else {
            $("#leave-error").hide();
        }
    });

    // remove inline error as soon as user selects a checkbox
    $("input[type=checkbox]").on("change", function () {
        if ($("input[type=checkbox]:checked").length > 0) {
            $("#leave-error").hide();
        }
    });
});
</script>