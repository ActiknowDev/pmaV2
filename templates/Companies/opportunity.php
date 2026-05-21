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
                        <?php if (isset($_GET['archive']) && $_GET['archive'] == 'archive') {
                            echo 'Archive Data';
                        } else {
                            echo 'Opportunity List';
                        } ?>
                        <!-- Opportunity List -->
                    </div>
                </div>
                <div class="col-6">
                    <?php if (($userSession['role'] != 3) || (($userSession['role'] == 3) && ($userSession['role_name'] != 'user' && $userSession['role_name'] != 'techlead'))) { ?>
                    <div class="actions-ctrl text-md-right">
                        <!-- <?= $this->Html->link('<i class="fa fa-eye"></i><span>List Stage </span>', '/Companies/stage', ['class' => 'v-btn v-btn-primary', 'escape' => false]); ?> -->
                        <?= $this->Html->link('<i class="fa fa-plus"></i><span>Add New Opportunity </span>', '/add-opportunity', ['class' => 'v-btn v-btn-secondary', 'escape' => false]); ?>
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

                <?php 
                $total_expected_amm=0;
                $total_probability=0;
                $total_actually=0;
                foreach ($list as $row) {
                 $total_expected_amm += $row['expected_amount'];
                 $total_probability +=$row['probability_percentage'];
                 $total_actually_current = ($row['expected_amount'] * $row['probability_percentage']) / 100;
                 $total_actually += $total_actually_current;
                } 
                // $total_actually = ($total_expected_amm*$total_probability);
                ?>
                <div class="col-md-12" style="display: flex; padding-top: 5px;">
                    <h3 style="padding: 15px; font-size: 16px; border-radius: 10px; background: #e8e6e6; text-align: center;">Total Revenue <br><span style="font-weight:600;"> $<?= number_format($total_expected_amm) ?></span></h3>
                    <h3 style="padding: 15px; font-size: 16px; border-radius: 10px; background: #e8e6e6; margin-left: 25px; text-align: center;">Expected Revenue <br><span style="font-weight:600;"> $<?=number_format($total_actually) ?></span></h3>
                    <h3 style="padding: 15px 45px; font-size: 16px; border-radius: 10px; background: #e8e6e6; margin-left: 20px; text-align: center;"> Active <br><span style="font-weight:600;"> <?= $totalActive  ?></span></h3>
                    <h3 style="padding: 15px 40px; font-size: 16px; border-radius: 10px; background: #e8e6e6; margin-left: 20px; text-align: center;"> Proposing <br><span style="font-weight:600;"> <?= $totalProposing  ?></span></h3>
                    <h3 style="padding: 15px 40px; font-size: 16px; border-radius: 10px; background: #e8e6e6; margin-left: 20px; text-align: center;"> Proposed <br><span style="font-weight:600;">  <?= $totalProposed  ?></span></h3>
                    <h3 style="padding: 15px 40px; font-size: 16px; border-radius: 10px; background: #e8e6e6; margin-left: 20px; text-align: center;"> Req.Gath <br><span style="font-weight:600;">  <?= $totalRg ?></span></h3>
                </div>
                <div class="col-md-2" style="padding-top: 10px;">
                    <select class="form-control" onchange="filterStage();" id="stageby" required>
                        <?php foreach($oppstage as $st){ ?>
                            <option value="<?= $st['id']; ?>" <?php if($stage == $st['id']){ echo 'selected'; } ?>><?= $st['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-8" style="padding-top: 10px;">
                </div>
                <div class="col-md-2" style="padding-top: 10px;">
                    <!-- <select class="form-control" onchange="ArchiveData();" id="filterby" required>
                        <option value="" hidden selected> <?= $archive?></option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select> -->
                    
                    <?php if($archive=='Inactive'){ ?>
                        <div style="display: flex; float: right;">
                            <!-- <span class="bold" style="font-size: 15px; font-weight: 500; padding-right: 5px;">Inactive</span> -->
                            <input class="tgl tgl-light" onchange="ArchiveData('active');" id="status" type="checkbox"/>
                            <label class="tgl-btn" for="status"></label>
                        </div> 
                    <?php } else { ?>
                        <div style="display: flex; float: right;">
                            <span class="bold" style="font-size: 15px; font-weight: 500; padding-right: 5px;">Active</span> 
                            <input class="tgl tgl-light" onchange="ArchiveData('inactive');" id="status" type="checkbox" checked/>
                            <label class="tgl-btn" for="status"></label>
                        </div> 
                   <?php } ?>
                </div>
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
                    <table id="example1" style="width:100%" class="table table-light table-sm  block table-responsive">
                        <thead>
                            <tr>
                                <th>#</th>
                                <!-- <th>Opportunity Name</th> -->
                                <th style="width:220px !important;">Opportunity Name</th>
                                <th style="width:220px !important;">Client Name</th>
                               
                                <th style="width:140px !important;">Assign To</th>
                                <th style="width:140px !important;">Stage</th>
                                <!-- <th>Type</th> -->
                                <th>Amount</th>
                                <th>Probability</th>
                                <th>Next Step</th>
                                <th>Active</th>
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($list as $row) : ?>
                            <tr id="tr<?= $row['id'] ?>">
                                <td><?= $i; ?></td>
                                <td>
                                    <a title="<?= $row['opportunity_name']; ?>" href="<?= WEBURL ?>edit-opportunity/<?= $row['id']; ?>"><?= substr($row['opportunity_name'],0,20); ?></a>
                                </td>
                                <td style="width:100px !important;" title="<?= $row['client_name']; ?>"><?= substr($row['client_name'],0,20); ?></td>
                                <td><?= $row['assigne_name']; ?></td>
                                <td>
                                    <input type="hidden" name="_csrfToken" id="token" value="<?= $this->request->getAttribute('csrfToken') ?>">
                                    <select class="form-control" onchange="ChangeStage(<?= $row['id'] ?>);" id="stage_<?= $row['id'] ?>" name="stage" required style="width: 122px;">
                                    
                                    <option value="" hidden selected> <?= $row['stage']; ?></option>
                                    <?php foreach($oppstage as $st){ ?>
                                    <option value="<?=$st['id'] ?>"><?=$st['name'] ?></option>
                                    <?php } ?>
                                    </select>
                                </td>
                                <!-- <td><?= $row['type']; ?></td>    -->
                                <td>$ <?= $row['expected_amount']; ?></td>
                                <td class="center-item"><span style="display:none;"><?= $row['probability_percentage'] ?></span><p class="pro-color" title="<?= $row['probability_percentage'] ?>" style="background:<?= $row['probability_color_code'] ?>;"></p></td>
                                <td><i class="fa fa-eye" style="cursor:pointer; color: #017bf6;" onclick="loadModelData(this)" data-next="<?= $row['next_step'];?>" data-toggle="modal" data-target="#model_popup"></i></td>
                                <td>
                                        <input class="tgl tgl-light change-status" id="<?= $row['id']; ?>" type="checkbox" value="<?= $row['deleted'] == '0' ? '0' : '1' ?>" <?= $row['deleted'] == '0' ? 'checked' : '' ?> />
                                        <label class="tgl-btn" for="<?= $row['id']; ?>"></label>
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

<div class="modal fade" tabindex="-1" role="dialog" id="model_popup">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Next Step</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                    <p id="next_step_data"></p>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">

function loadModelData(ele) {
        var next_step = $(ele).attr("data-next");
        $("#next_step_data").html(next_step);
    }

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

    $('.change-status').click(function() {

        let id = $(this).attr('id');
        let status = $(this).val();
        // console.log('status',status);
        // console.log('id',id);
        // return false;

        if (status == 1) {
            status = 0;
        } else {
            status = 1;
        }

        $.ajax({

            type: 'GET',
            url: "<?= $this->Url->build('/Companies/deleteOpportunity/') ?>" + id+"/"+status,
            beforeSend: function() {},
            success: function(data) {
                // location.reload();
                if (data == 1 && status == 0) {
                    $(`#${id}`).prop('checked', true);
                } else if (data == 1 && status == 1) {
                    $(`#${id}`).prop('checked', false);
                }
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

function ArchiveData(data) {
    //    var data= $("#filterby").val();
    //    console.log(data);
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

$(document).ready(function() {
    $('#example1').DataTable({
        responsive: true,
        scrollX: true,
        "pageLength": 50
    });
});
</script>