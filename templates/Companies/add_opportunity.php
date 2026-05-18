<?php
if(!isset($client)) {
    $client='';
}
?>
<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-project-diagram"></i></span>Add New Opportunity
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
                    <?= $this->Form->create(null, array('id' => 'opportunity')) ?>

                    <!-- Add block start  -->
                    <div class="block">
                    <div class="header">
                        <h4 class="title">Add Opportunity Details</h4>
                    </div>
                    <div class="content">
                    <div class="row" id="pro_data">
                    
                    <div class="col-md-6">
                    <div class="form-group">
                    <label for="opportunity-name" class="form-label">Opportunity Name</label>
                    <input type="text" class="form-control" id="opportunity-name" name="opportunity_name" required>
                    </div>
                    </div>

                    <div class="col-md-6">
                    <div class="form-group">
                    <label for="">Client Name</label>
                    <div class="adon-group cname">
                    <input id="tags" class="form-control client" name="client_name"
                    value="<?= $client; ?>" required>
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
                    <input class="datepicker form-control" type="text" name="expected_closed_date" autocomplete="off" required readonly>
                    </div>
                    </div>
                    </div>

                    <div class="col-md-4 mb-2">
                    <div class="form-group mb-0">
                    <label for="">Amount</label>
                    <div class="adon-group amt">
                    <span class="icon ft-primary"><i class="fa fa-dollar-sign"></i></span>
                    <input class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || event.charCode == 44 || (event.charCode >= 48 && event.charCode <= 57)))" type="text" placeholder="" name="expected_amount" required>
                    </div>
                    </div>
                    </div>

                    <div class="col-md-4 mb-2">
                    <div class="form-group mb-0">
                    <label for="" class="form-label">Lead Source</label>
                    <input type="text" class="form-control" placeholder="" name="lead_source" required>
                    </div>
                    </div>

                    <div class="col-md-4">
                    <div class="mb-2">
                    <div class="form-group mb-0">
                    <label for="" class="form-label">Type of Project</label>
                    <select class="form-control" name="type" required>
                    <option value="" disabled selected>Select Type</option>
                    <option value="New Project">New Project</option>
                    <option value="Existing Project">Existing Project</option>
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
                    <option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
                    <?php endforeach; ?>
                    </select>
                    </div>
                    </div>
                    </div>

                    <div class="col-md-4">
                    <div class="mb-2">
                    <div class="form-group">
                    <label for="">Probability</label>
                    <!-- <input type="text" class="form-control" id="" placeholder="%" name="probability" required> -->
                        <div class="pro" style="display:flex;">
                            <select class="form-control" name="probability" required>
                                <option value="" disabled selected>Select Probability</option>
                                <?php foreach($probability_list as $data) { ?>
                                    <option value="<?= $data['id'] ?>"><?= $data['name'] ?></option>
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
                    <textarea class="form-control" rows="3" name="description"></textarea>
                    </div>
                    </div>
                    </div>

                    <div class="col-md-3">
                    <div class="mb-3">
                    <label for="">Stage</label>
                    <select class="form-control" name="stage" required>
                        <option value="" disabled selected>Select Stage</option>
                        <?php foreach($oppstage as $st){ ?>
                            <option value="<?=$st['id'] ?>"><?=$st['name'] ?></option>
                        <?php } ?>
                    </select>
                    </div>
                    </div>

                    <div class="col-md-3">
                    <div class="mb-3">
                    <label for="">Forecast Category</label>
                    <select class="form-control" name="forecast_category" required>
                    <option value="" disabled selected>Select Forecast Category</option>
                    <option value="Omitted">Omitted</option>
                    <option value="Pipeline">Pipeline</option>
                    <option value="Best Case">Best Case</option>
                    <option value="Commit">Commit</option>
                    <option value="Closed">Closed</option>
                    </select>
                    </div>
                    </div>

                    <div class="col-md-6 mb-3">
                    <div class="form-group mb-0">
                    <label for="" class="form-label">Next Step</label>
                    <input type="text" class="form-control" placeholder="" name="next_step" required>
                    </div>
                    </div>

                    <div class="col-md-12 mb-3">
                    <div class="d-flex flex-row-reverse">
                    <div class="form-group" style="margin-top:22px;">
                    <button type="submit" class="v-btn v-btn-secondary float-right" id="save_opportunity"><span>Save Opportunity</span>
                    </button>
                    </div>
                    </div>
                    <?= $this->Form->end() ?>
                    </div>
                    </div>

                    </div>
                    </div>
                    </div>
                    <!-- End -->
                    
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ADD COMPANY MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="add_client">
    <?= $this->Form->create(null, array('id' => 'clients')) ?>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Client</h5>
                <p style="font-size: 12px; margin-left: 10%; color:red; font-weight: 600;" id="error_show"></p>
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

                                <input type="text" class="form-control" name="company_name" required
                                    autocomplete="off">
                                <input type="hidden" name="addClientName" value="123">
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
                                <input type="email" name="email" class="form-control" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="">Phone Number</label>
                            <div class="adon-group">
                                <input type="text" name="country_code" class="form-control text-center"
                                    value="+91" autocomplete="off" style="border-right: 1px solid #eee; width:45px;" required>
                                <input type="text" name="contact_no" class="form-control" placeholder="Contact No"
                                    autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Location</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-map-marker-alt"></i></span>
                                <input type="text" name="location" class="form-control" placeholder=""
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
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
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

        }
        // email: {
        //     required: "please enter correct email",
        // }
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
                } else if(response == 0) {
                    $("#error_show").html('This Client already exist.');
                    $('#saveclient').html('Save Client');
                    //  alert('Client Already exist..');
                }
            }
        });
    }
})

$(".cancel").click(function() {
    clientvalidator.resetForm();
});
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
</script>

<script>
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

function formatCurrency(input) {
    // Get the input value without commas
    var value = input.value.replace(/,/g, '');

    // Format the value as currency
    var formattedValue = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(value);

    // Update the input value with formatted currency
    input.value = formattedValue;
}
</script>

<script>
//add form
// var clientvalidator = $("#clients").validate({
//     rules: {
//         client_name: {
//             required: true,
//         },

//     },
//     messages: {
//         client_name: {
//             required: "Please enter name",

//         },
//         email: {
//             required: "please enter correct email",
//         }
//     },
//     errorPlacement: function(error, element) {

//         if (element.attr("name") == "client_name")
//             error.insertAfter(".clname");
//         else if (element.attr("name") == "email")
//             error.insertAfter(".emailclass");
//     },
//     submitHandler: function(form) {
//         $('#saveclient').html('sending..');
//         $.ajax({
//             url: "<?= $this->Url->build('/clients/add') ?>",
//             type: "POST",
//             data: $('#clients').serialize(),
//             dataType: "json",
//             success: function(response) {
//                 if (response == 1) {
//                     $('#saveclient').html('Save Client');
//                     document.getElementById("clients").reset();
//                     $(".close").click();
//                 }
//             }
//         });
//     }
// })

// $(".cancel").click(function() {
//     clientvalidator.resetForm();
// });
</script>


