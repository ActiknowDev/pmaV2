<?php 
if (!empty($projects)) extract($projects[0]);
?>
<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-project-diagram"></i></span>Edit New Opportunity
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="actions-ctrl text-md-right">
                        <?= $this->Html->link('<i class="fa fa-list"></i><span>List Opportunity</span>', '/list-opportunity', ['class' => 'v-btn v-btn-secondary', 'escape' => false]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- PAGE-CONTENT -->
    <div class="page-content">
        <div class="container">
            <!-- Opportunity ADD -->
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" id="url_id" value="<?= WEBURL; ?>">
                    <?= $this->Form->create(null, array('id' => 'opportunity-edit')) ?>

                    <!-- Edit block start  -->
                    <div class="block">
                    <div class="header">
                        <h4 class="title">Edit Opportunity Details</h4>
                    </div>
                    <div class="content">
                    <div class="row" id="pro_data">
                    
                    <div class="col-md-6">
                    <div class="form-group">
                    <label for="opportunity-name" class="form-label">Opportunity Name</label>
                    <input type="text" value="<?= $list['opportunity_name']; ?>" class="form-control" id="opportunity-name" name="opportunity_name" required>
                    </div>
                    </div>

                    <div class="col-md-6">
                    <div class="form-group">
                    <label for="">Client Name</label>
                    <div class="adon-group cname">
                    <input id="tags" class="form-control client" name="client_name"
                    value="<?= $list['client_name']; ?>" required>
                    <a href="#" data-target="#add_client" data-toggle="modal"
                     class="v-btn  v-btn-primary"><i class="fa fa-plus"></i><span>Add
                    Client</span></a>
                    </div>
                    <label id="tags-error-empty" class="error" for="tags"></label>
                    </div>
                    </div>

                    <div class="col-md-4 mb-2">
                    <div class="form-group mb-0">
                    <label for="">Expected Close Date</label>
                    <div class="adon-group award">
                    <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span>
                    <input value="<?= date_format(date_create($list['expected_closed_date']), "m/d/Y") ?>" class="datepicker form-control" type="text" name="expected_closed_date" autocomplete="off" required readonly>
                    </div>
                    </div>
                    </div>

                    <div class="col-md-4 mb-2">
                    <div class="form-group mb-0">
                    <label for="">Amount</label>
                    <div class="adon-group amt">
                    <span class="icon ft-primary"><i class="fa fa-dollar-sign"></i></span>
                    <input value="<?= $list['expected_amount']; ?>" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || event.charCode == 44 || (event.charCode >= 48 && event.charCode <= 57)))" type="text" placeholder="" name="expected_amount" required>
                    </div>
                    </div>
                    </div>

                    <div class="col-md-4 mb-2">
                    <div class="form-group mb-0">
                    <label for="" class="form-label">Lead Source</label>
                    <input value="<?= $list['lead_source']; ?>" type="text" class="form-control" placeholder="" name="lead_source" required>
                    </div>
                    </div>

                    <div class="col-md-4">
                    <div class="mb-2">
                    <div class="form-group mb-0">
                    <label for="" class="form-label">Type of Project</label>
                    <select class="form-control" name="type" required>
                    <option value="">Select Type</option>
                    <option value="New Project" <?php if($list['type'] == "New Project"){ echo 'selected'; } ?>>New Project</option>
                    <option value="Existing Project" <?php if($list['type'] == "Existing Project"){ echo 'selected'; } ?>>Existing Project</option>
                    </select>
                    </div>
                    </div>
                    </div>

                    <div class="col-md-4">
                    <div class="form-group">
                    <label for="">Assigned To</label>
                    <div class="adon-group res">
                    <select name="assigned_to" class="form-control" id="assigned" required>
                    <option value="" disabled selected>Select an option</option>
                     <?php foreach($assignedList as $row): ?>
                    <option value="<?= $row['id']; ?>" <?php if($list['assigned_to'] == $row['id']){ echo 'selected'; } ?>><?= $row['name']; ?></option>
                    <?php endforeach; ?>
                    </select>
                    </div>
                    </div>
                    </div>

                    <div class="col-md-4">
                    <div class="mb-2">
                    <div class="form-group">
                    <label for="">Probability</label>
                    <!-- <input type="text" value="<?= $list['probability']; ?>" class="form-control" id="" placeholder="%" name="probability" required> -->
                        <div class="pro" style="display:flex;">
                            <select class="form-control" name="probability" required>
                                <option value="" selected disabled>Select</option>
                                <?php foreach($probability_list as $row) { ?>
                                    <option value="<?= $row['id']; ?>" <?php if($list['probability'] == $row['id']){ echo 'selected'; } ?>><?= $row['name']; ?></option>
                                <?php } ?>
                            </select>
                            <?= $this->Html->link('<i class="fa fa-eye"></i>', '/Companies/probability', ['class' => 'v-btn v-btn-primary','title' => 'List Probability', 'escape' => false]); ?>
                        </div>
                    </div>
                    </div>
                    </div>

                    <div class="col-md-12">
                    <div class="mb-2">
                    <div class="form-group">
                    <label for="">Description</label>
                    <textarea class="form-control" rows="3" name="description"><?= $list['description']; ?></textarea>
                    </div>
                    </div>
                    </div>

                    <div class="col-md-3">
                    <div class="mb-3">
                    <label for="">Stage</label>
                    <select class="form-control" name="stage" required>
                        <?php foreach($oppstage as $st){ ?>
                            <option value="<?= $st['id']; ?>" <?php if($list['stage'] == $st['id']){ echo 'selected'; } ?>><?= $st['name']; ?></option>
                        <?php } ?>
                    </select>
                    </div>
                    </div>

                    <div class="col-md-3">
                    <div class="mb-3">
                    <label for="">Forecast Category</label>
                    <select class="form-control" name="forecast_category" required>
                    <option value="Omitted" <?php if($list['forecast_category'] == "Omitted"){ echo 'selected'; } ?>>Omitted</option>
                    <option value="Pipeline" <?php if($list['forecast_category'] == "Pipeline"){ echo 'selected'; } ?>>Pipeline</option>
                    <option value="Best Case" <?php if($list['forecast_category'] == "Best Case"){ echo 'selected'; } ?>>Best Case</option>
                    <option value="Commit" <?php if($list['forecast_category'] == "Commit"){ echo 'selected'; } ?>>Commit</option>
                    <option value="Closed" <?php if($list['forecast_category'] == "Closed"){ echo 'selected'; } ?>>Closed</option>
                    </select>
                    </div>
                    </div>

                    <div class="col-md-6 mb-3">
                    <div class="form-group mb-0">
                    <label for="" class="form-label">Next Step</label>
                    <input type="text" class="form-control" value="<?= $list['next_step']; ?>" name="next_step" required>
                    </div>
                    </div>

                    <div class="col-md-12 mb-3">
                    <div class="d-flex flex-row-reverse">
                    <div class="form-group" style="margin-top:22px;">
                    <button type="submit" class="v-btn v-btn-secondary float-right" id="edit_opportunity"><span>Update</span>
                    </button>
                    </div>
                    </div>
                    <?= $this->Form->end() ?>
                    </div>
                    </div>

                    </div>
                    </div>
                    </div></div>
                    <!-- End -->

                    <!-- Activity section start -->
                    <div class="block">
                    <?= $this->Flash->render() ?>
                    <div class="header">
                        <h4 class="title">Activity
                            <a href="#" data-target="#add_activity"
                            data-toggle="modal" class="v-btn v-btn-primary float-right"><i
                                class="fa fa-plus"></i> <span>Add
                                Activity</span></a>
                        </h4>
                    </div>
                    <div class="content">
                        <div class="table-responsive">
                        <table class="table table-default" id="table_data" style="white-space:nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Email / Call to</th>
                                    <th>Contacted By</th>
                                    <th>Notes</th>
                                    <th>Reference</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; foreach($activityList as $value): $i++; ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $value->type_of_activity ?></td>
                                    <td><?= date("d-m-Y", strtotime($value->date_of_activity)) ?></td>
                                    <td><?= $value->email_to ?></td>
                                    <td><?= $value->contacted_by ?></td>
                                    <td>
                                    <?php
                                        if (empty($value->notes)):
                                            echo "-";
                                        else:
                                            $content = $value->notes;
                                            $contentId = 'content_'.$value->id;
                                            if (strlen($content) > 25):
                                                echo '<span class="content-preview" data-content-id="' . $contentId . '">' . substr($content, 0, 25) . '<a href="#" class="view-more-link" data-id="'.$value->id.'"> View More</a></span>';
                                            else:
                                                echo $content;
                                            endif;
                                        endif;
                                    ?>

                                   </td>
                                    <td>
                                        <?php if(!empty($value->reference)): ?>
                                            <a href="<?= WEBURL ?>/uploads/<?= $value->reference ?>" class="d-flex align-items-center justify-content-center h6 text-white rounded-circle" style="width:35px; height:35px;background-color:#28a745;cursor:pointer;" download>
                                            <i class="fas fa-download"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                   
                                    <td>
                                        <div class="d-flex align-items-center">

                                            <a class="d-flex align-items-center justify-content-center h6 text-white rounded-circle" style="width:35px; height:35px;background-color:#3fd5db;cursor:pointer;" onclick="editActivity(<?= $value->id; ?>)">
                                            <i class="fas fa-edit"></i>
                                            </a>

                                            <a href="#" class="d-flex align-items-center justify-content-center h6 text-white bg-danger rounded-circle" style="width:35px; height:35px;margin-left:5px;cursor:pointer;" onclick="deleteActivity(<?= $value->id; ?>)">
                                            <i class="far fa-trash-alt"></i>
                                            </a>

                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                    <!-- End -->
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Full Notes -->
<div class="modal fade" id="viewMoreModal" tabindex="-1" role="dialog" aria-labelledby="viewMoreModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Activity Notes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
            <p id="fullContent"></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base cancel" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<!-- End -->

<!-- ADD COMPANY MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="add_client">
    <?= $this->Form->create(null, array('id' => 'clients')) ?>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Client</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Company Name</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-building"></i></span>

                                <input type="text" class="form-control" name="company_name" placeholder=""
                                    autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Client Name</label>
                            <div class="adon-group clname">
                                <span class="icon ft-primary"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" name="client_name" 
                                    autocomplete="off" required>
                                <input type="hidden" name="password" value="password">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Email Id</label>
                            <div class="adon-group emailclass">
                                <span class="icon ft-primary"><i class="fa fa-envelope"></i></span>
                                <input type="text" name="email" class="form-control" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="">Phone Number</label>
                            <div class="adon-group">
                                <input type="text" name="country_code" class="form-control text-center"
                                    value="+91" autocomplete="off" style="border-right: 1px solid #eee; width:45px;">
                                <input type="text" name="contact_no" class="form-control" 
                                    autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Location</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-map-marker-alt"></i></span>
                                <input type="text" name="location" class="form-control"
                                    autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base cancel" data-dismiss="modal">Close</button>
                <button type="submit" class="v-btn v-btn-primary" id="saveclient">Save Client</a>
            </div>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>

<!--  Add Activity modal  -->
<div class="modal fade" tabindex="-1" role="dialog" id="add_activity">
<?= $this->Form->create(null, array('id' => 'activity', 'enctype' => 'multipart/form-data')) ?>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Activity</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">

                    <div class="form-group row">
                        <div class="col-md-12">
                            <input type="hidden" value="<?= $id ?>" name="opportunity_id" required>
                            <label for="">Type <span class="required">*</span></label>
                            <div class="adon-group atype">
                            <select class="form-control" name="type_of_activity" required>
                            <option value="" disabled selected>Select Type</option>
                            <option value="Email">Email</option>
                            <option value="Call">Call</option>
                            </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="" class="form-label">Date <span class="required">*</span></label>
                            <div class="adon-group adate">
                            <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span>
                            <input class="datepicker form-control" type="text"
                            name="date_of_activity" autocomplete="off" required readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                        <label for="" class="form-label">Email / Call To <span class="required">*</span></label>
                        <div class="adon-group aemaiTo">
                        <span class="icon ft-primary"><i class="fa fa-envelope"></i></span>
                        <input type="text" class="form-control" name="email_to" required>
                        </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-md-12">
                        <label for="" class="form-label">Contacted By <span class="required">*</span></label>
                        <div class="adon-group acont">
                        <input type="text" class="form-control" name="contacted_by" required>
                        </div>
                        </div>
                    </div>

                    <div class="form-group row">
                    <div class="col-md-12">
                    <label for="" class="form-label">Notes <span class="required">*</span></label>
                    <div class="adon-group anotes">
                    <textarea class="form-control" rows="4" name="notes" required style="height: 80px"></textarea>
                    </div>
                    </div>
                    </div>

                    <div class="form-group row">
                    <div class="col-md-12">
                        <label for="" class="form-label">Reference</label>
                        <input type="file" name="reference" class="form-control fileInput">
                        <span id="fileNameDisplay" class="text-success mt-2"></span>
                    </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base cancel" data-dismiss="modal">Close</button>
                <button type="submit" class="v-btn v-btn-primary" id="saveactivity">Save Activity</a>
            </div>
        </div>
    </div>
<?= $this->Form->end() ?>
</div>
<!-- End -->

<!-- Edit Activity Modal  -->
<div class="modal fade" tabindex="-1" role="dialog" id="edit_activity">
<?= $this->Form->create(null, array('url' => array('controller' => 'Companies', 'action' => 'editActivityOpp'), 'enctype' => 'multipart/form-data')) ?>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Activity</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <input type="hidden" value="<?= $id ?>" name="opportunity_id">
                            <input type="hidden" value="" id="editId" name="editId">
                            <label for="">Type <span class="required">*</span></label>
                            <div class="adon-group">
                            <select class="form-control" name="type_of_activity" id="edit_type_of_activity" required>
                            <option value="" disabled selected>Select Type <span class="required">*</span></option>
                            <option value="Email">Email</option>
                            <option value="Call">Call</option>
                            </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="" class="form-label">Date <span class="required">*</span></label>
                            <div class="adon-group clname">
                            <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span>
                            <input class="datepicker form-control" type="text" id="edit_date_of_activity"
                            name="date_of_activity" autocomplete="off" required readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                        <label for="" class="form-label">Email / Call To <span class="required">*</span></label>
                        <div class="adon-group emailclass">
                        <span class="icon ft-primary"><i class="fa fa-envelope"></i></span>
                        <input type="text" class="form-control" id="edit_email_to" name="email_to" required>
                        </div>
                        </div>
                     </div>
                     <div class="form-group row">
                        <div class="col-md-12">
                        <label for="" class="form-label">Contacted By <span class="required">*</span></label>
                        <div class="adon-group">
                        <input type="text" class="form-control" id="edit_contacted_by" name="contacted_by" required>
                        </div>
                        </div>
                    </div>
                    <div class="form-group row">
                    <div class="col-md-12">
                    <label for="" class="form-label">Notes <span class="required">*</span></label>
                    <div class="adon-group">
                    <textarea class="form-control" id="edit_notes" rows="4" name="notes" required style="height: 80px"></textarea>
                    </div>
                    </div>
                    </div>
                   
                    <div class="form-group row">
                    <div class="col-md-12">
                        <label for="" class="form-label">Reference</label>
                        <input type="file" name="reference" class="form-control mb-2 fileInputEdit">
                        <span id="fileNameDisplayEdit" class="text-success mt-2"></span>
                        <a id="download_reference_link" href="#" style="display: none;">Download</a>
                    </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base cancel" data-dismiss="modal">Close</button>
                <button type="submit" class="v-btn v-btn-primary" id="updateActivity">Update</a>
            </div>
        </div>
    </div>
<?= $this->Form->end() ?>
</div>
<!-- End  -->

<style>
.select2-container--default .select2-selection--single {
    border: unset !important;
    border-radius: unset !important;
    height: 33px !important;
}
.form-control:disabled, .form-control[readonly] {
    background-color: unset !important;
    opacity: 1;
}
.required
{
    color: red;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('.view-more-link').on('click', function(e) {
        e.preventDefault();
        var contentId = $(this).closest('.content-preview').data('content-id');
        var addressId = $(this).data("id");
        $.ajax({
            url: "<?= $this->Url->build('/Companies/activityNotes/') ?>" + addressId,
            method: "GET",
            success: function(response) {
                $('#fullContent').text(response);
                $('#viewMoreModal').modal('show');
            },
            error: function() {
                alert('Error fetching content.');
            }
        });
        return false;
    });
});


document.addEventListener('DOMContentLoaded', function () {
    // Add image show
    var fileInput = document.querySelector('.fileInput');
    var fileNameDisplay = document.getElementById('fileNameDisplay');

    fileInput.addEventListener('change', function () {
        var file = fileInput.files[0];
        if (file) {
            var fileName = file.name;
            fileNameDisplay.textContent = fileName;
        }
    });

    //Edit image show
    var fileInputEdit = document.querySelector('.fileInputEdit');
    var fileNameDisplayEdit = document.getElementById('fileNameDisplayEdit');
    fileInputEdit.addEventListener('change', function () {
        var fileEdit = fileInputEdit.files[0];
        if (fileEdit) {
            var fileNameEdit = fileEdit.name;
            fileNameDisplayEdit.textContent = fileNameEdit;
            $('#download_reference_link').hide();
        }
    });

});


//add form
var clientvalidator = $("#clients").validate({
    rules: {
        client_name: {
            required: true,
        },

    },
    messages: {
        client_name: {
            required: "Please enter name",

        },
        email: {
            required: "please enter correct email",
        }
    },
    errorPlacement: function(error, element) {

        if (element.attr("name") == "client_name")
            error.insertAfter(".clname");
        else if (element.attr("name") == "email")
            error.insertAfter(".emailclass");
    },
    submitHandler: function(form) {
        $('#saveclient').html('sending..');
        $.ajax({
            url: "<?= $this->Url->build('/clients/add') ?>",
            type: "POST",
            data: $('#clients').serialize(),
            dataType: "json",
            success: function(response) {
                if (response == 1) {
                    $('#saveclient').html('Save Client');
                    document.getElementById("clients").reset();
                    $(".close").click();
                }
            }
        });
    }
})

$(".cancel").click(function() {
    clientvalidator.resetForm();
});

// Edit Activity 
function editActivity(id) {
  $.ajax({
    url: "<?= $this->Url->build('/Companies/editActivity/') ?>" + id,
    method: "GET",
    dataType: 'json',
    success: function(res) {

        var typeOfActivity = res.type_of_activity;
        var dateOfActivity = res.date_of_activity;
        var emailTo        = res.email_to;
        var reference      = res.reference;
        var contactedBy    = res.contacted_by;
        var notes          = res.notes;
        var id             = res.id;
        var date = new Date(dateOfActivity).toLocaleDateString('en-US');

        $('#edit_notes').val(notes);
        $('#edit_email_to').val(emailTo);
        $('#edit_contacted_by').val(contactedBy);
        $('#edit_date_of_activity').val(date);
        $('#edit_type_of_activity').val(typeOfActivity);
        $('#editId').val(id);
        
         // Display the image
         if (reference) {
            var completeUrl = '<?= WEBURL ?>/uploads/' + reference;
            var imageName = reference.split('/').pop();
            $('#download_reference_link').text(imageName).attr('href', completeUrl).attr('download', imageName).show();
            } else {
            $('#download_reference_link').hide();
            }

        $('#edit_activity').modal('show');
    },
    });
}
// End 

// Delete Activity 
function deleteActivity(id) {
    let condition = false;
    if (confirm("Do You Want to Delete this Activity?")) condition = true;
    else condition = false;

    if (condition) {
        $.ajax({
            url: "<?= $this->Url->build('/Companies/deleteActivity/') ?>" + id,
            method: "GET",
            success: function(res) {
                if (res == 1) $(`#tr${id}`).removeAttr("style").hide();
                location.reload();
            },
        });
    }
}

// Add activity form
var activityValidator = $("#activity").validate({
    rules: {
        type_of_activity: {
            required: true,
        },
        date_of_activity: {
            required: true,
        },
        email_to: {
            required: true,
        },
        contacted_by: {
            required: true,
        },
        notes: {
            required: true,
        },
    },
    messages: {

        type_of_activity: {
            required: "Please Enter Type",
        },
        date_of_activity: {
            required: "Please Enter Date",
        },
        email_to: {
            required: "Please Enter Email / Call To",
        },
        contacted_by: {
            required: "Please Enter Contacted By",
        },
        notes: {
            notes: "Please Enter Notes",
        },
    },
    errorPlacement: function(error, element) {
        if (element.attr("name") == "type_of_activity")
            error.insertAfter(".atype");
        else if (element.attr("name") == "date_of_activity")
            error.insertAfter(".adate");
        else if (element.attr("name") == "email_to")
            error.insertAfter(".aemaiTo");
        else if (element.attr("name") == "contacted_by")
            error.insertAfter(".acont");
        else if (element.attr("name") == "notes")
            error.insertAfter(".anotes");
    },
    submitHandler: function(form) {
        $('#saveactivity').html('sending..');
        var formData = new FormData(form);

        $.ajax({
            url: "<?= $this->Url->build('/companies/addActivity') ?>",
            type: "POST",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function(response) {
                if (response == 1) {
                    $('#saveactivity').html('Save Activity');
                    document.getElementById("activity").reset();
                    $(".close").click();
                    location.reload();
                }
            }
        });
    }
});

$(".cancel").click(function() {
    activityValidator.resetForm();
});
// End

</script>

<script>
$(document).ready(function($) {
    $("#assigned").select2({
    placeholder: "Select Option"
    });
});

$("input[type=text]").on("focus", function() {
    if ($(this).val() == 0)
        $(this).val('');
});

function isareaNumber(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode != 190 && charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) return false;
    return true;
}

$('.client').keyup(function() {
    var str = $(this).val();
    if (str != '') {

        $("#tags").autocomplete({
            source: "<?= $this->Url->build('/clients/listAll/') ?>" + str,
            response: function(event, ui) {
                if (ui.content === null) {
                    console.log(ui);
                    $("#tags-error-empty").show();
                    $("#tags-error-empty").html('Client Does Not Exist');

                } else {
                    $("#tags-error-empty").hide();
                }
            }
        });

    }
});
</script>
