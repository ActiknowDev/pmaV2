<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-user"></i></span>Client List
                    </div>
                </div>
                <div class="col-6">
                    <div class="actions-ctrl text-md-right">
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#add_client"
                            class="v-btn v-btn-secondary">
                            <i class="fa fa-plus"></i><span>Add New Client</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- PAGE-CONTENT -->
    <div class="page-content">
        <div class="container">
            <!-- <div class="row">
                <div class="col-md-3">
                    <div class="block primary">
                        <div class="content">
                            <h4 class="title">Total Client</h4>
                            <span><?= $totalClients ?></span>
                        </div>
                    </div>
                </div> 
                <div class="col-md-3">
                    <div class="block primary">
                        <div class="content">
                            <h4 class="title">Total Active</h4>
                            <span><?= $totalActiveClients ?></span>
                        </div>
                    </div>
                </div> 
                <div class="col-md-3">
                    <div class="block primary">
                        <div class="content">
                            <h4 class="title">Total Inactive</h4>
                            <span><?= $totalInactiveClients ?></span>
                        </div>
                    </div>
                </div> 
            </div> -->
            <hr class="dark">

            <!-- TABLE -->
            <div class="row">
                <div class="col-md-12">
                    <?= $this->Flash->render() ?>
                    <div class="content table-responsive">
                        <table class="table nowrap table-light" id="example1" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Client Name</th>
                                    <th>Company Name</th>
                                    <th>Email ID</th>
                                    <th>Phone Number</th>
                                    <th>Location</th>
                                    <th>Pt.o Contact</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach ($clients as $key => $client) { ?>
                                <tr id="tr<?= $client->id  ?>">
                                    <td><?= $i; ?></td>
                                    <td>
                                        <a href="#" data-toggle="modal" data-target="#edit_client"
                                            class="icon icon-sm ft-primary " title="Edit Client"
                                            onclick="passValue('<?php echo $client->id ?>')"><?= $client->client_name; ?></a>
                                    </td>
                                    <!-- <td><?= $client->client_name; ?></td> -->
                                    <td><?php echo $client->company_name; ?></td>
                                    <td><?php echo $client->email; ?></td>

                                    <td><?= ($client->country_code == '') ? '+91' : $client->country_code; ?>-<?php echo $client->contact_no ?>
                                    </td>
                                    <td><?php echo $client->location; ?></td>

                                    <!-- POCName is a custom helper -->
                                    <td><?= $this->POCName->pocData($client->point_of_contact); ?></td>

                                    <td>
                                        <input class="tgl tgl-light change-status" id="<?php echo $client->id ?>"
                                            type="checkbox" value="<?php echo $client->status; ?>"
                                            <?= $client->status == '1' ? 'checked' : '' ?> />
                                        <label class="tgl-btn" for="<?php echo $client->id ?>"></label>
                                    </td>
                                    <td>
                                        <a href="#" data-toggle="modal" data-target="#edit_client"
                                            class="icon icon-sm ft-primary " title="Edit Client"
                                            onclick="passValue('<?php echo $client->id ?>')"><i
                                                class="fa fa-pencil-alt"></i></a>
                                        <a href="#" class="fas fa-archive" onclick="deleteClient(<?= $client->id ?>)"
                                            title='Archive'>
                                            <i class='icon icon-sm'></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                                <?php }
                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- ADD CLIENT MODAL -->

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
                                    autocomplete="off">

                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Client Name</label>
                            <div class="adon-group cname">
                                <span class="icon ft-primary"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" name="client_name" placeholder=""
                                    autocomplete="off">
                                <input type="hidden" name="password" value="password">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Point of Contact</label>
                            <div class="adon-group res">
                                <select name="pointOfContant" class="form-control" id="pocData">
                                    <option value="">Select</option>
                                    <?php
                  if (count($pointContact) > 0) :
                    foreach ($pointContact as $value) :
                  ?>

                                    <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>

                                    <?php
                    endforeach;
                  endif;
                  ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Email Id</label>
                            <div class="adon-group emailadd">
                                <span class="icon ft-primary"><i class="fa fa-envelope"></i></span>
                                <input type="text" name="email" class="form-control" placeholder="" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="">Phone Number</label>
                            <div class="adon-group addcon phNo">

                                <input type="text" name="country_code" class="form-control text-center" placeholder=""
                                    value="+91" autocomplete="off" style="border-right: 1px solid #eee; width:45px;">

                                <input type="text" name="contact_no" id="contact_no" minlength="10" maxlength="10"
                                    class="form-control" placeholder="" autocomplete="off">

                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Location</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-map-marker-alt"></i></span>
                                <input type="text" name="location" id="location" class="form-control" placeholder=""
                                    autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Potential (Annual Business)</label>
                            <select name="potential" id="potential" required="required" class="form-control">
                                <option value="0">Select</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Relationship with Actiknow</label>
                            <select name="relationship" id="relationship" required="required" class="form-control">
                                <option value="0">Select</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Last Followup Date</label>
                            <div class="adon-group emailadd">
                                <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span>
                                <input type="text" name="last_followup_date" autocomplete="off"
                                    class="datepicker form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="">Next Followup Date</label>
                            <div class="adon-group addcon">
                                <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span>
                                <input type="text" name="next_followup_date" autocomplete="off"
                                    class="datepicker form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Notes</label>
                            <textarea id="description" name="description" class="form-control"></textarea>
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
<!-- End Client Modal -->


<!-- Edit CLIENT MODAL -->

<div class="modal fade" tabindex="-1" role="dialog" id="edit_client">
    <?= $this->Form->create(null, array('id' => 'clientsEdit')) ?>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Client</h5>
                <button type="button" class="close" data-dismiss="modal" onclick="reset()" aria-label="Close">
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

                                <input type="text" class="form-control" name="company_name" id="company_name"
                                    placeholder="" autocomplete="off">

                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Client Name</label>
                            <div class="adon-group edtcn">
                                <span class="icon ft-primary"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" name="client_name" id="name_edit" placeholder=""
                                    autocomplete="off">
                                <input type="hidden" name="password" value="password">
                                <input type="hidden" name="parent_id" id="parent_id">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Point of Contact</label>
                            <div class="adon-group res">
                                <select name="pointOfContant" class="form-control" id="pocData1">
                                    <?php
                  if (count($pointContact) > 0) :
                    foreach ($pointContact as $value) :
                  ?>

                                    <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>

                                    <?php
                    endforeach;
                  endif;
                  ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Email Id</label>
                            <div class="adon-group edtemail">
                                <span class="icon ft-primary"><i class="fa fa-envelope"></i></span>
                                <input type="text" name="email" id="email_edit" class="form-control" placeholder=""
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="">Phone Number</label>
                            <div class="adon-group editPh">

                                <input type="text" name="country_code" id="country_code"
                                    class="form-control text-center" placeholder="" value="+91" autocomplete="off"
                                    style="border-right: 1px solid #eee; width:45px;">

                                <input type="text" name="contact_no" id="phone_edit" minlength="10" maxlength="10"
                                    class="form-control" placeholder="" autocomplete="off">


                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Location</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-map-marker-alt"></i></span>
                                <input type="text" name="location" id="location_edit" class="form-control"
                                    placeholder="" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Potential (Annual Business)</label>
                            <select name="potential" id="potential1" required="required" class="form-control">
                                <option value="0">Select</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Relationship with Actiknow</label>
                            <select name="relationship" id="relationship1" required="required" class="form-control">
                                <option value="0">Select</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Last Followup Date</label>
                            <div class="adon-group emailadd">
                                <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span>
                                <input type="text" name="last_followup_date" id="last_followup_date_edit"
                                    autocomplete="off" class="datepicker form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="">Next Followup Date</label>
                            <div class="adon-group addcon">
                                <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span>
                                <input type="text" name="next_followup_date" id="next_followup_date_edit"
                                    autocomplete="off" class="datepicker form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Notes</label>
                            <textarea name="description" id="description_edit" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="edit_id" id="edit_id">
            <input type="hidden" name="client_id" id="client_id">
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base cancel" onclick="reset()"
                    data-dismiss="modal">Close</button>
                <button type="submit" class="v-btn v-btn-primary" id="editclient">Update Client</a>
            </div>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>
<!-- End Edit Client Modal -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
// getEdit data
$('#potential1').on('change', function(e) {
    e.stopPropogation();
});
$('#relationship1').on('change', function(e) {
    e.stopPropogation();
});
$('#pocData1').on('change', function(e) {
    e.stopPropogation();
});

function passValue(client_id) {
    var url =
        $.ajax({

            type: 'GET',
            url: "<?= $this->Url->build('/clients/edit/'); ?>" + client_id,

            beforeSend: function() {

            },
            success: function(data) {
                var response = $.parseJSON(data);

                if (response.country_code == '') {
                    code = "+91";
                } else {

                    response.forEach(element => {
                        // let poc = element['point_of_contact'].split(",");
                        console.log(element['client_data']);

                        code = element['country_code'];
                        $("#name_edit").val(element['client_name']);

                        $("#company_name").val(element['company_name']);
                        $("#email_edit").val(element['email']);

                        $('#pocData1').val(element['point_of_contact']);

                        // code for multiselector
                        // let poc1 = [];
                        // $.each(poc, function(key, value) {
                        //   poc1.push(value);
                        // });
                        // $('#langOpt1').val(poc1);
                        // $("#langOpt1").multiselect('reload');

                        $("#parent_id").val(element['parent_id']);
                        $("#country_code").val(code);
                        $("#phone_edit").val(element['contact_no']);
                        $("#location_edit").val(element['location']);
                        $("#edit_id").val(element['id']);
                        $("#client_id").val(element['client.id']);
                        if (element['client_data'] != null) {
                            $("#last_followup_date_edit").val(element['client_data'][
                                'last_followup_date'
                            ]);
                            $("#next_followup_date_edit").val(element['client_data'][
                                'next_followup_date'
                            ]);
                            $("#description_edit").val(element['client_data']['description']);
                            $("#potential1").val(element['client_data']['potential']);
                            $("#relationship1").val(element['client_data']['relationship']);
                        }
                    });
                }
            }
        });
}


function reset() {
    document.getElementById("clientsEdit").reset();
}
// $("#clientsEdit").trigger('reset');
</script>
<script type="text/javascript">
//Status Change
$('.change-status').click(function() {

    let id = $(this).attr('id');
    let status = $(this).val();
    if (status == 1) {
        status = 0;
    } else {
        status = 1;
    }
    $.ajax({
        url: "<?= $this->Url->build('/clients/updateStatus/'); ?>" + id + '/' + status,
        type: 'GET',
        beforeSend: function() {},
        success: function(data) {
            if (status == 1)
                $(`${id}`).prop('checked', true);
            else
                $(`${id}`).prop('checked', false);
            // window.location.href = "<?= $this->Url->build('/clients'); ?>";
        }
    });
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
<script>
//add form
$('#potential').on('change', function(e) {
    e.stopPropogation();
});
$('#pocData').on('change', function(e) {
    e.stopPropogation();
});
$('#relationship').on('change', function(e) {
    e.stopPropogation();
});
var cvalid = $("#clients").validate({
    rules: {
        client_name: {
            required: true,
        }
    },
    messages: {
        client_name: {
            required: "Please enter name",
        },

    },
    errorPlacement: function(error, element) {
        if (element.attr("name") == "client_name")
            error.insertAfter(".cname");
        else if (element.attr("name") == "contact_no")
            error.insertAfter(".phNo");

    },
    submitHandler: function(form) {
        // console.log($('#clients').serialize());
        $('#saveclient').html('sending..');
        // console.log($('#clients').serialize());
        $.ajax({
            url: "<?= $this->Url->build('/clients/add') ?>",
            type: "POST",
            data: $('#clients').serialize(),
            dataType: "json",
            success: function(response) {
                window.location.href = "<?= $this->Url->build('/clients'); ?>";
            }
        });
    }
})


$(".cancel").click(function() {
    cvalid.resetForm();
});
</script>
<script>
//edit form
var cevalid = $("#clientsEdit").validate({
    rules: {
        client_name: {
            required: true,
        },
    },
    messages: {
        client_name: {
            required: "Please enter name",
        },
        contact_no: {
            required: "Please enter at least 10 number digit.",
        }

    },
    errorPlacement: function(error, element) {
        if (element.attr("name") == "client_name")
            error.insertAfter(".edtcn");
        if (element.attr("name") == "contact_no")
            error.insertAfter(".editPh");

    },
    submitHandler: function(form) {
        $('#editclient').html('sending..');
        var client_id = $("#edit_id").val();
        $.ajax({
            url: "<?= $this->Url->build('/clients/editData/') ?>" + client_id,
            type: "POST",
            data: $('#clientsEdit').serialize(),
            dataType: "json",
            success: function(response) {
                // console.log(response)
                window.location.href = "<?= $this->Url->build('/clients') ?>";
            }
        });
    }
})


$(".cancel").click(function() {
    cevalid.resetForm();
});
</script>
<script type="text/javascript">
$(document).ready(function() {
    $('#example1').DataTable({
        responsive: true,
        "pageLength": 10
    });
});
//drop down active inactive change
$('select').on('change', function() {
    var status = $(this).val();
    if (status == '1' || status == '0') {
        window.location.href = "<?= $this->Url->build('/clients?status=') ?>" + status;
    } else {
        window.location.href = "<?= $this->Url->build('/clients') ?>";
    }

});


function deleteClient(id) {
    $.ajax({
        url: "<?= $this->Url->build("/Clients/delete/") ?>" + id,
        method: "GET",
        success: function(res) {
            console.log(res);
            if (res == 1)
                $(`#tr${id}`).removeAttr("style").hide();
        }
    });

}
</script>