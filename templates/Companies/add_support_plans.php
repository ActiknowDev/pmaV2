<?php
if(!isset($client)) {
    $client='';
}
?>
<style>
.file-upload-label {
    display: block;
    margin-bottom: 5px;
    /* font-weight: bold; */
}

.file-upload {
    position: relative;
    overflow: hidden;
    display: inline-block;
}

.file-upload-input {
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
}

.file-upload-button {
    width: 100%;
    background-color: #3fd5db;
    color: #fff;
    padding: 8px 15px;
    border-radius: 4px;
    cursor: pointer;
}

.file-upload-button:hover {
    background-color: #0056b3;
}
.file-name {
    display: block;
    margin-top: 5px;
    color: #333;
    font-size: 14px;
}
</style>
<input type="hidden" name="_csrfToken" id="token" value="<?= $this->request->getAttribute('csrfToken') ?>">
<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-project-diagram"></i></span>Add a Client on Support Plan
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="actions-ctrl text-md-right"><?= $this->Html->link('<i class="fa fa-list"></i><span>List All</span>', '/companies/support-plans', ['class' => 'v-btn v-btn-secondary', 'escape' => false]); ?></div>
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
                <?= $this->Flash->render() ?>
                    <input type="hidden" id="url_id" value="<?= WEBURL; ?>" />
                    <?= $this->Form->create(null, array('id' => 'support_plan')) ?>

                    <!-- Add block start  -->
                    <div class="block">
                        <div class="header">
                            <h4 class="title">Add a Client on Support Plan</h4>
                        </div>
                        <div class="content">
                            <div class="row" id="pro_data">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Client Name</label>
                                        <div class="adon-group cname">
                                            <select name="client_id" class="form-control" id="client_name" required>
                                                <option value="" disabled selected>Select an option</option>
                                                <?php foreach($client_list as $row): ?>
                                                <option value="<?= $row['id']; ?>"><?= $row['client_name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <a href="#" data-target="#add_client" data-toggle="modal" class="v-btn v-btn-primary"><i class="fa fa-plus"></i><span>Add Client</span></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Project Name</label>
                                        <div class="adon-group res">
                                            <select name="project_id" class="form-control" id="project_id" required>
                                                <option value="" disabled selected>Select an option</option>
                                               
                                            </select>
                                            <?= $this->Html->link('<i class="fa fa-plus"></i><span>Add Project </span>', '/add-project', ['class' => 'v-btn v-btn-primary', 'escape' => false]); ?>
                                            <!-- <a href="#" data-target="#add_project" data-toggle="modal" class="v-btn v-btn-primary"><i class="fa fa-plus"></i><span>Add Project</span></a> -->
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Plan</label>
                                        <select class="form-control" name="plan_id" id="plan" required>
                                                <option value="" disabled selected>Select</option>
                                                <?php foreach($plan_list as $row){ ?>
                                                <option data-id="<?= $row['price'] ?>" value="<?= $row['id'] ?>"><?= $row['plan_name'] ?></option>
                                                <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-4">
                                    <div class="form-group mb-0">
                                        <label for="">Client Email</label>
                                        <div class="adon-group award">
                                            <span class="icon ft-primary"><i class="fa fa-envelope"></i></span>
                                            <input class="form-control" type="email" name="client_email" id="client_email" autocomplete="off" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-0">
                                    <div class="form-group mb-0">
                                        <label for="">Start Date</label>
                                        <div class="adon-group award">
                                            <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span>
                                            <input class="datepicker form-control" type="text" name="start_date" autocomplete="off" required readonly />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-4">
                                    <div class="form-group mb-0">
                                        <label for="">Number of Months</label>
                                        <div class="adon-group award">
                                            <input class="form-control" type="text" name="number_of_months" id="number_of_months" autocomplete="off" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-4">
                                    <div class="form-group mb-0">
                                        <label for="">End Date</label>
                                        <div class="adon-group award" style="background: #dae0e0 !important;">
                                            <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span>
                                            <input class="form-control" type="text" name="end_date" required readonly />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Project Manager</label>
                                        <div class="adon-group res">
                                            <select name="project_manager_id" class="form-control" id="assigned" required>
                                                <option value="" disabled selected>Select an option</option>
                                                <?php foreach($assignedList as $row): ?>
                                                <option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <div class="mb-2">
                                        <div class="form-group mb-0">
                                            <label for="" class="form-label">Billing Frequency</label>
                                            <select class="form-control" name="billing_frequency" id="billing_frequency" required>
                                                <option value="" disabled selected>Select</option>
                                                <option value="Quarterly">Quarterly</option>
                                                <option value="Monthly">Monthly</option>
                                                <option value="Yearly">Yearly</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <div class="form-group mb-0">
                                        <label for="">Amount (Per Billing)</label>
                                        <div class="adon-group amt" style="background: #dae0e0 !important;">
                                            <span class="icon ft-primary"><i class="fa fa-dollar-sign"></i></span>
                                            <input class="form-control" type="text" placeholder="" name="amount" id="amount" readonly required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-4">
                                    <div class="form-group mb-0">
                                        <label for="file-upload" class="file-upload-label">Upload Related Documents/Files</label>
                                        <div class="file-upload">
                                            <input id="file-upload" class="file-upload-input" accept=".pdf" type="file" name="document" autocomplete="off" />
                                            <label for="file-upload" class="file-upload-button">Choose File</label>
                                        </div>
                                        <span id="file-name" class="file-name"></span>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <div class="form-group mb-0">
                                        <label for="">Notes</label>
                                        <div>
                                            <textarea class="form-control"  placeholder="" name="notes" rows="4"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="d-flex flex-row-reverse">
                                        <div class="form-group" style="margin-top: 22px;">
                                            <button type="submit" class="v-btn v-btn-secondary float-right" id="save_opportunity"><span>Save Details</span></button>
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

                    <!-- <div class="block">
                        <div class="header">
                            <h4 class="title">Payment History <a href="#" data-target="#add_payment_received"
                                    data-toggle="modal" class="v-btn v-btn-primary float-right"><i
                                        class="fa fa-plus"></i><span>Add Payment</span></a></h4>
                            </h4>
                        </div>
                        <div class="content table-responsive">
                            <table class="table table-default nowarp">
                                <thead>
                                    <tr>

                                        <th>Description</th>
                                        <th>start Date</th>
                                        <th>End Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="payment_data">
                                    <?php $payments=[]; if ($payments) : ?>
                                    <?php foreach ($payments as $p) : ?>
                                    <tr id="rowp<?= $p['id']; ?>">

                                        <td><?= $p['description']; ?></td>
                                        <td><?= $p['payment_date']; ?></td>
                                        <td>
                                            $<?= $p['receive_amt']; ?>
                                        </td>
                                        <td>
                                            <select name="pstatus" class="form-control status" id="<?= $p['id']; ?>"
                                                data-type="payment" data-url="<?= WEBURL; ?>">
                                                <option value="Billed"
                                                    <?php if ($p['status'] == 'Billed') echo 'selected'; ?>>Billed
                                                </option>
                                                <option value="Paid"
                                                    <?php if ($p['status'] == 'Paid') echo 'selected'; ?>>
                                                    Paid</option>
                                                <option value="Estimated"
                                                    <?php if ($p['status'] == 'Estimated') echo 'selected'; ?>>Estimated
                                                </option>

                                            </select>
                                        </td>
                                        <td>
                                            <a href="#" class="icon" data-toggle="modal" data-target="#edit_payment"
                                                onclick="passPayment('edit',<?= $p['id']; ?>)"> <i
                                                    class="fa fa-pencil-alt"></i> </a>
                                            <a href="#" class="icon" onclick="passPayment('delete',<?= $p['id']; ?>);">
                                                <i class="fa fa-trash-alt"></i> </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div> -->
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
                <p style="font-size: 12px; margin-left: 10%; color: red; font-weight: 600;" id="error_show"></p>
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

                                <input type="text" class="form-control" name="company_name" required autocomplete="off" />
                                <input type="hidden" name="addClientName" value="123" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Client Name</label>
                            <div class="adon-group clname">
                                <span class="icon ft-primary"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" name="client_name" autocomplete="off" required />
                                <input type="hidden" name="password" value="password" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Email Id</label>
                            <div class="adon-group emailclass">
                                <span class="icon ft-primary"><i class="fa fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="">Phone Number</label>
                            <div class="adon-group">
                                <input type="text" name="country_code" class="form-control text-center" value="+91" autocomplete="off" style="border-right: 1px solid #eee; width: 45px;" required />
                                <input type="text" name="contact_no" class="form-control" placeholder="Contact No" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Location</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-map-marker-alt"></i></span>
                                <input type="text" name="location" class="form-control" placeholder="" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base cancel" data-dismiss="modal">Close</button>
                <button type="submit" class="v-btn v-btn-primary" id="saveclient">Save Client</button>
            </div>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>

<!-- ADD PROJECT MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="add_project">
    <?= $this->Form->create(null, array('id' => 'projects')) ?>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Project</h5>
                <p style="font-size: 12px; margin-left: 10%; color: red; font-weight: 600;" id="error_show"></p>
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

                                <input type="text" class="form-control" name="company_name" required autocomplete="off" />
                                <input type="hidden" name="addClientName" value="123" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Client Name</label>
                            <div class="adon-group clname">
                                <span class="icon ft-primary"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" name="client_name" autocomplete="off" required />
                                <input type="hidden" name="password" value="password" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Email Id</label>
                            <div class="adon-group emailclass">
                                <span class="icon ft-primary"><i class="fa fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="">Phone Number</label>
                            <div class="adon-group">
                                <input type="text" name="country_code" class="form-control text-center" value="+91" autocomplete="off" style="border-right: 1px solid #eee; width: 45px;" required />
                                <input type="text" name="contact_no" class="form-control" placeholder="Contact No" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Location</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-map-marker-alt"></i></span>
                                <input type="text" name="location" class="form-control" placeholder="" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base cancel" data-dismiss="modal">Close</button>
                <button type="submit" class="v-btn v-btn-primary" id="saveproject">Save Project</button>
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
    .form-control:disabled,
    .form-control[readonly] {
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

$(document).ready(function() {
    // Add change event listener to start_date and number_of_months fields
    $('input[name="start_date"], input[name="number_of_months"]').on('change', function() {
        // Get the values of start_date and number_of_months
        var startDate = $('input[name="start_date"]').val();
        var numberOfMonths = parseInt($('input[name="number_of_months"]').val());

        // Calculate the end date based on start date and number of months
        if (startDate && !isNaN(numberOfMonths)) {
            var endDate = new Date(startDate);
            endDate.setMonth(endDate.getMonth() + numberOfMonths);
            var formattedEndDate = endDate.toISOString().slice(0, 10); // Format the date as YYYY-MM-DD

            // Set the value of end_date field
            $('input[name="end_date"]').val(formattedEndDate);
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
            // email: {
            //     required: "please enter correct email",
            // }
        },
        errorPlacement: function (error, element) {
            if (element.attr("name") == "client_name") error.insertAfter(".clname");
            else if (element.attr("name") == "email") error.insertAfter(".emailclass");
        },
        submitHandler: function (form) {
            $("#saveclient").html("sending..");
            $.ajax({
                url: "<?= $this->Url->build('/clients/add') ?>",
                type: "POST",
                data: $("#clients").serialize(),
                dataType: "json",
                success: function (response) {
                    if (response == 1) {
                        $("#saveclient").html("Save Client");
                        document.getElementById("clients").reset();
                        $(".close").click();
                    } else if (response == 0) {
                        $("#error_show").html("This Client already exist.");
                        $("#saveclient").html("Save Client");
                        //  alert('Client Already exist..');
                    }
                },
            });
        },
    });

    // project add 

    var clientvalidator = $("#projects").validate({
        rules: {
            client_name: {
                required: true,
            },
        },
        messages: {
            client_name: {
                required: "Please enter name",
            },
            // email: {
            //     required: "please enter correct email",
            // }
        },
        errorPlacement: function (error, element) {
            if (element.attr("name") == "client_name") error.insertAfter(".clname");
            else if (element.attr("name") == "email") error.insertAfter(".emailclass");
        },
        submitHandler: function (form) {
            $("#saveproject").html("Please Wait..");
            console.log('check');
            return false;
            $.ajax({
                url: "<?= $this->Url->build('/clients/add') ?>",
                type: "POST",
                data: $("#projects").serialize(),
                dataType: "json",
                success: function (response) {
                    if (response == 1) {
                        $("#saveproject").html("Save Project");
                        document.getElementById("projects").reset();
                        $(".close").click();
                    } else if (response == 0) {
                        $("#error_show").html("This project already exist.");
                        $("#saveclient").html("Save project");
                        //  alert('Client Already exist..');
                    }
                },
            });
        },
    });

    $(".cancel").click(function () {
        clientvalidator.resetForm();
    });

    $(document).ready(function() {
        // Add change event listener to file input
        $('#file-upload').on('change', function() {
            // Get the file name and display it
            var fileName = $(this).val().split('\\').pop(); // Extract file name from the file path
            $('#file-name').text(fileName);
        });
    });
</script>

<script>
    $(document).ready(function ($) {
        $("#assigned").select2({
            // placeholder: "Select Option",
        });
        $("#project_id").select2({
        placeholder: "Select Option"
        });
        $("#client_name").select2({
        placeholder: "Select Option"
        });
    });

    $("input[type=text]").on("focus", function () {
        if ($(this).val() == 0) $(this).val("");
    });
</script>

<script>
    $(".client").keyup(function () {
        var str = $(this).val();
        if (str != "") {
            $("#tags").autocomplete({
                source: "<?= $this->Url->build('/clients/listAll/') ?>" + str,
                response: function (event, ui) {
                    if (ui.content === null) {
                        console.log(ui);
                        $("#tags-error-empty").show();
                        $("#tags-error-empty").html("Client Does Not Exist");
                    } else {
                        $("#tags-error-empty").hide();
                    }
                },
            });
        }
    });
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

<script>
        $('#client_name').change(function() {
            // console.log('check');
            // return false;
            var client_id = $(this).val();
            var csrfToken = $("#token").val();
            if (client_id != '') {
                $.ajax({
                    url: '<?= $this->Url->build(['controller' => 'Companies', 'action' => 'getProjectsByClient']) ?>',
                    type: 'POST',
                    data: {client_id: client_id, _csrfToken: csrfToken},
                    dataType: 'json',
                    success:function(response) {
                        if(response.email.email !='') {
                            $("#client_email").val(response.email.email);
                        } else {
                            $("#client_email").val('');
                        }
                        // console.log(response,'response');
                        $('#project_id').empty().append('<option value="">Select an option</option>');
                        $.each(response.project, function(key, value) {
                            $('#project_id').append('<option value="' + value.id + '">' + value.project_name + '</option>');
                        });
                    }
                });
            } else {
                $('#project_id').empty().append('<option value="">Select a client first</option>');
            }
        });

        $('#project_id').change(function() {
            var project_id = $(this).val();
            var csrfToken = $("#token").val();
            if (project_id != '') {
                $.ajax({
                    url: '<?= $this->Url->build(['controller' => 'Companies', 'action' => 'getProjectManager']) ?>',
                    type: 'POST',
                    data: {project_id: project_id, _csrfToken: csrfToken},
                    dataType: 'json',
                    success:function(response) {
                        console.log(response,'response');
                        $('#assigned option').removeAttr('selected');
                        if (response) {
                            // console.log(response,'response');
                            // return false;
                            // $('#assigned').val(response.project_manager_id);
                            // $('#assigned option[value="' + response.project_manager_id + '"]').attr('selected', 'selected');
                            $('#assigned').append('<option selected style="display: none;" value="' + response.id + '">' + response.name + '</option>');
                        } else {
                            $('#assigned').val('');
                        }
                    }
                });
            } else {
                $('#assigned').val('');
            }
        });
</script>

<script>
    $(document).ready(function() {
        $('#plan, #number_of_months , #billing_frequency').change(function() {
            var plan = parseInt($('#plan option:selected').data('id'));
            var numberOfMonths = parseInt($('#number_of_months').val());
            var billing_frequency = $('#billing_frequency').val();
            // console.log(billing_frequency);
            var amount = plan * numberOfMonths;
            if (isNaN(amount)) {
                $('#amount').val('');  
            } else {
                if(billing_frequency=='Monthly') {
                    $('#amount').val(plan);
                } else if(billing_frequency=='Quarterly') {
                    $('#amount').val(plan*3); 
                } else if (billing_frequency=='Yearly'){
                    $('#amount').val(plan*12); 
                } else {
                    $('#amount').val(''); 
                }
                // $('#amount').val(amount);
            }
        });
    });
</script>