<?php $session = new \Cake\Http\Session();
$userSession = $session->read('data');
$role = $userSession['role'];
?>

<style>
    .color-show {
        width: 15px; 
        height: 15px; 
    }
</style>
<input type="hidden" name="_csrfToken" id="token" value="<?= $this->request->getAttribute('csrfToken') ?>">
<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-project-diagram"></i></span>
                        Probability List
                    </div>
                </div>
                <div class="col-6">
                    <?php if (($userSession['role'] != 3) || (($userSession['role'] == 3) && ($userSession['role_name'] != 'user' && $userSession['role_name'] != 'techlead'))) { ?>
                    <div class="actions-ctrl text-md-right">
                       
                    <a href="#" data-target="#add_new"
                            data-toggle="modal" class="v-btn v-btn-primary float-right"><i
                                class="fa fa-plus"></i> <span>Add
                                New</span></a>
                    </div>
                    <?php } ?>
                </div>

            </div>
        </div>
    </div>
    <!-- PAGE TAB -->
   
    <!-- PAGE-CONTENT -->
    <div class="page-content">
        <div class="container">
            <!-- TABLE -->
            <div class="row">
                <div class="col-md-12">
                <?= $this->Flash->render() ?>
                    <table id="example1" style="width:100%" class="table table-light table-sm  block">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th> Name</th>
                                <th>Percentage</th>
                                <th>Color Code</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($list as $row) : ?>
                            <tr id="tr<?= $row['id'] ?>">
                                <td><?= $i; ?></td>
                                <td><a href="#" onclick="EditData(<?= $row['id'] ?>);" class="link"><?= $row['name']; ?></a></td>
                                <td><?= $row['percentage']; ?> %</td>
                                <!-- <td><p class="color-show" style="background: <?= $row['color_code']; ?>;"></p></td> -->
                                <td><input type="color" onchange="ChangeColorCode(<?= $row['id'] ?>);" id="color_code<?= $row['id'] ?>" name="color_code" value="<?= $row['color_code']; ?>"></td>
                                
                                <td>
                                    <a href="#" onclick="deleteProbability(<?= $row['id'] ?>)" class="icon icon-sm delete"><i class="fa fa-archive"></i></a>
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

<!--   probability modal  -->
<div class="modal fade" tabindex="-1" role="dialog" id="add_new">
    <?= $this->Form->create(null, array('id' => 'activity', 'enctype' => 'multipart/form-data')) ?>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Probability</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="" class="form-label">Name <span class="required">*</span></label>
                            <div class="adon-group name">
                                <input type="text" class="form-control" name="name" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="" class="form-label">Probability (%) <span class="required">*</span></label>
                            <div class="adon-group acont">
                                <input type="text" class="form-control" name="percentage" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="" class="form-label">Choose Color <span class="required">*</span></label>
                            <div class="adon-group anotes">
                                <input type="color" class="form-control" name="color_code" required />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base cancel" data-dismiss="modal">Close</button>
                <button type="submit" class="v-btn v-btn-primary" id="saveNew">Save</button>
            </div>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="edit_data">
    <?= $this->Form->create(null, array('id' => 'activity', 'enctype' => 'multipart/form-data')) ?>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Probability</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                <input type="hidden" id="id" class="form-control" name="id" />
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="" class="form-label">Name <span class="required">*</span></label>
                            <div class="adon-group name">
                                <input type="text" id="name" class="form-control" name="name" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="" class="form-label">Probability (%) <span class="required">*</span></label>
                            <div class="adon-group acont">
                                <input type="text" id="percentage" class="form-control" name="percentage" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="" class="form-label">Choose Color <span class="required">*</span></label>
                            <div class="adon-group anotes">
                                <input type="color" id="color_code" class="form-control" name="color_code" required />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base cancel" data-dismiss="modal">Close</button>
                <button type="submit" class="v-btn v-btn-primary" id="saveNew">Save</button>
            </div>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>
<!-- End -->


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

function deleteProbability(id) {
    var csrfToken = $("#token").val();
    let condition = false;
    var msg='Do You Want to Delete this Probability?';
    if (confirm(msg)) condition = true;
    else condition = false;
    if (condition) {
        $.ajax({
            url: "<?= $this->Url->build('/Companies/deleteprobability') ?>",
            type: "POST",
            data: { id: id, _csrfToken: csrfToken },
            dataType: "json",
            success: function(response) {
                if (response == 1) $(`#tr${id}`).removeAttr("style").hide();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
}

function ChangeColorCode(id) {
      var colorpick =  $("#color_code"+id).val();
      var csrfToken = $("#token").val();

    $.ajax({
        url: "<?= $this->Url->build('/Companies/changeprobabilitycolor') ?>",
        type: "POST",
        data: { id: id, colorpick: colorpick, _csrfToken: csrfToken },
        dataType: "json",
        success: function(response) {
            console.log(response);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

function EditData(id) {
    $.ajax({
        type: "GET",
        url: "<?= $this->Url->build('/Companies/editprobability') ?>/"+ id,
        dataType: "json",
        success: function(response) {
            $('#id').val(response.id);
            $('#name').val(response.name);
            $('#percentage').val(response.percentage);
            $('#color_code').val(response.color_code);

            // Show the edit modal
            $("#edit_data").modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("AJAX Error:", textStatus, errorThrown);
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