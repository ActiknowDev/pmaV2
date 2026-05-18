<?php $session = new \Cake\Http\Session();
$userSession = $session->read('data');
$role = $userSession['role'];
?>
<style>
    .pro-color {
        width: 20px;
        height: 20px;
    }
    .center-item {
        display: flex;
        justify-content: center;
    }
</style>
<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-project-diagram"></i></span>
                        Clients on Support Plan
                    </div>
                </div>
                <div class="col-6">
                    <?php if (($userSession['role'] != 3) || (($userSession['role'] == 3) && ($userSession['role_name'] != 'user' && $userSession['role_name'] != 'techlead'))) { ?>
                    <div class="actions-ctrl text-md-right">
                        <!-- <?= $this->Html->link('<i class="fa fa-eye"></i><span>List Probability </span>', '/Companies/probability', ['class' => 'v-btn v-btn-primary', 'escape' => false]); ?> -->
                        <?= $this->Html->link('<i class="fa fa-plus"></i><span>Add New Support Project </span>', '/Companies/add-support-plans', ['class' => 'v-btn v-btn-secondary', 'escape' => false]); ?>
                    </div>
                    <?php } ?>
                </div>

            </div>
        </div>
    </div>
    <!-- PAGE TAB -->
    <div class="page-tab">
        <div class="container">
            <div class="row">
                <!-- <div class="col-md-12" style="display: flex; padding-top: 5px;">
                    <h3 style="padding: 15px; font-size: 16px; border-radius: 10px; background: #e8e6e6; text-align: center;">Total Revenue <br><span style="font-weight:600;"> $<?= number_format($total_expected_amm) ?></span></h3>
                    <h3 style="padding: 15px; font-size: 16px; border-radius: 10px; background: #e8e6e6; margin-left: 25px; text-align: center;">Expected Revenue <br><span style="font-weight:600;"> $<?=number_format($total_actually) ?></span></h3>
                    <h3 style="padding: 15px 45px; font-size: 16px; border-radius: 10px; background: #e8e6e6; margin-left: 20px; text-align: center;"> Active <br><span style="font-weight:600;"> <?= $totalActive  ?></span></h3>
                    <h3 style="padding: 15px 40px; font-size: 16px; border-radius: 10px; background: #e8e6e6; margin-left: 20px; text-align: center;"> Proposing <br><span style="font-weight:600;"> <?= $totalProposing  ?></span></h3>
                    <h3 style="padding: 15px 40px; font-size: 16px; border-radius: 10px; background: #e8e6e6; margin-left: 20px; text-align: center;"> Proposed <br><span style="font-weight:600;">  <?= $totalProposed  ?></span></h3>
                    <h3 style="padding: 15px 40px; font-size: 16px; border-radius: 10px; background: #e8e6e6; margin-left: 20px; text-align: center;"> Req.Gath <br><span style="font-weight:600;">  <?= $totalRg ?></span></h3>
                </div> -->
                <!-- <div class="col-md-2" style="padding-top: 10px;">
                    <select class="form-control" onchange="filterStage();" id="stageby" required>
                            <option value="" hidden selected> <?= $stage?></option>
                            <option value="">All</option>
                            <option value="Contact">Contact</option>
                            <option value="Req. Gath.">Req. Gath.</option>
                            <option value="Proposing">Proposing</option>
                            <option value="Proposed">Proposed</option>
                            <option value="Won">Won</option>
                            <option value="Lost">Lost</option>
                    </select>
                </div> -->
                <!-- <div class="col-md-2" style="padding-top: 10px;">
                    <select class="form-control" onchange="ArchiveData();" id="filterby" required>
                        <option value="" hidden selected> <?= $archive?></option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div> -->
            </div>
        </div>
    </div>
    <!-- PAGE-CONTENT -->
    <div class="page-content">
        <div class="container">
            <!-- TABLE -->
            <div class="row">
                <div class="col-md-12">
                <?= $this->Flash->render() ?>
                    <!-- <table id="example1" style="width:100%" class="table table-light table-sm  block table-responsive"> -->
                    <table id="example" style="width:100%" class="table table-light table-sm  block ">
                        <thead>
                            <tr>
                                <th>#</th>
                                <!-- <th>Opportunity Name</th> -->
                                <th style="width:150px !important;">Client Name</th>
                                <th style="width:200px !important;">Project Name</th>
                                <th style="width:100px !important;">Plan Name</th>
                                <th style="width:150px !important;">Manager's Name</th>
                                <th style="width:95px !important;">Start Date</th>
                                <th style="width:90px !important;">End Date</th>
                                <th style="width:40px !important;">Amount Billed</th>
                                <th style="width:10px !important;">Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($list as $row) : ?>
                            <tr id="tr<?= $row['id'] ?>">
                                <td><?= $i; ?></td>
                                <td>
                                    <a title="<?= $row['client_name']; ?>" href="<?= WEBURL ?>companies/edit-support-plans/<?= $row['id']; ?>"><?= substr($row['client_name'],0,20); ?></a>
                                </td>
                                <td><?= substr($row['project_name'],0,25); ?></td>
                                <td><?= $row['name']; ?></td>
                                <td><?= $row['assigne_name']; ?></td>
                                <td>
                                <?= date_format(date_create($row['start_date']), "d-m-Y") ?>
                                </td>
                                <td><?= date_format(date_create($row['end_date']), "d-m-Y") ?></td>
                                <td>$ <?= $row['amount']; ?></td>
                                
                                <!-- <td><?= (!empty($row['expected_closed_date']) ? date_format($row['expected_closed_date'],"d-m-Y"): '-'); ?></td>      -->
                                <!-- <td>
                                        <input class="tgl tgl-light change-status" id="<?= $row['id']; ?>" type="checkbox" value="<?= $row['deleted'] == '0' ? '0' : '1' ?>" <?= $row['deleted'] == '0' ? 'checked' : '' ?> />
                                        <label class="tgl-btn" for="<?= $row['id']; ?>"></label>
                                </td>                -->
                                <td>
                                    <select class="form-control change-status" id="<?= $row['id'] ?>" name="status" required style="width: 90px;">
                                        <option value="" hidden selected><?= $row['status'] == 1 ? 'Active' : 'Expired' ?></option>
                                        <option value="1">Active</option>
                                        <option value="0">Expired</option>
                                    </select>
                                </td>
                                <td>
                                <a href="#" onclick="deleteSupportPlan(<?= $row['id'] ?>)" class="icon icon-sm delete">
                                            <i class="fa fa-archive"></i>
                                        </a>
                                </td>
                                
                            </tr>
                            <?php $i++;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">

// Delete Opportunity 
// function deleteOpportunity(id,type) {
//     let condition = false;
//     if(type==='delete') {
//         var msg='Do You Want to Archive this Opportunity?';
//     } else {
//         var msg='Do You Want to Unarchive this Opportunity?';
//     }
//     if (confirm(msg)) condition = true;
//     else condition = false;

//     if (condition) {
//         $.ajax({
//             url: "<?= $this->Url->build('/Companies/deleteOpportunity/') ?>" + id+"/"+type,
//             method: "GET",
//             success: function(res) {
//                 if (res == 1) $(`#tr${id}`).removeAttr("style").hide();
//             },
//         });
//     }
// }

    $('.change-status').change(function() {

        let id = $(this).attr('id');
        let status = $(this).val();
        // console.log('status',status);
        // console.log('id',id);
        // return false;

        // if (status == 1) {
        //     status = 0;
        // } else {
        //     status = 1;
        // }

        $.ajax({

            type: 'GET',
            url: "<?= $this->Url->build('/Companies/statusChangeSupportPlan/') ?>" + id+"/"+status,
            beforeSend: function() {},
            success: function(data) {
                // location.reload();
                // if (data == 1 && status == 0) {
                //     $(`#${id}`).prop('checked', true);
                // } else if (data == 1 && status == 1) {
                //     $(`#${id}`).prop('checked', false);
                // }
                // else
                //     1
                // location.reload();
            }
        });
    });

function ChangeStage(id) {
    var stage = $("#stage_" + id).val();
    var csrfToken = $("#token").val();

    $.ajax({
        url: "<?= $this->Url->build('/Companies/changeOpportunityStage') ?>",
        type: "POST",
        data: { id: id, stage: stage, _csrfToken: csrfToken },
        dataType: "json",
        success: function(response) {
            console.log(response);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

function filterStage() {
    var stageby = $("#stageby").val();

    $.ajax({
        url: "<?= $this->Url->build('/Companies/opportunity') ?>"+"?stage="+stageby,
        type: "get",
        // data: { id: id, stage: stage, _csrfToken: csrfToken },
        // dataType: "json",
        success: function(response) {
            location.href="<?= $this->Url->build('/Companies/opportunity') ?>"+"?stage="+stageby
        }
    });
}

function ArchiveData() {
       var data= $("#filterby").val();
       console.log(data);
    $.ajax({
        url: "<?= $this->Url->build('/Companies/opportunity') ?>",
        type: "get",
        // data: { id: id, stage: stage, _csrfToken: csrfToken },
        // dataType: "json",
        success: function(response) {
            location.href="<?= $this->Url->build('/Companies/opportunity') ?>"+"?status="+data
        }
    });

}

function deleteSupportPlan(id) {
        //     // console.log(id);
        let condition = false;
        if (confirm("Do You Want to Delete this?")) condition = true;
        else condition = false;

        if (condition) {
            $.ajax({
                url: "<?= $this->Url->build('/Companies/deleteSupportplan/') ?>" + id,
                method: "GET",
                success: function(res) {
                    if (res == 1) $(`#tr${id}`).removeAttr("style").hide();
                },
            });
        }
    }

$(document).ready(function() {
    $('#example1').DataTable({
        responsive: true,
        scrollX: true,
        "pageLength": 50
    });
});
</script>