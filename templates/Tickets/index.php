<?php 
$session = new \Cake\Http\Session();
$userSession = $session->read('data');
$role = $userSession['role'];
?>
<link rel="stylesheet" href="css/kanban.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
                <div class="col-10">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-project-diagram"></i></span>
                        Tickets List
                    </div>
                </div>
                <div class="col-2">
                <div class="actions-ctrl text-md-right">
                <a href="#" class="v-btn v-btn-primary btn-block" data-target="#add_ticket" data-toggle="modal">
                    <i class="fa fa-plus"></i><span>Add New Ticket</span>
                </a>
                </div>
                </div>
            </div>
        </div>
    </div>
   
    <!-- PAGE-CONTENT -->
    <div class="page-content">
        <div class="container">
            <!-- TABLE -->
            <?= $this->Flash->render() ?>
            <!-- Start Board  -->
            <?php if(!empty($ticket)): ?>
            <section class="main-content p-3" id="main-content">
            <div class="main">
                <div class="ss-col" id="ss-col">
                    <div class="ss-overflow">
                    <?php if(in_array(3,$ticket_status)) { ?>
                    <!-- Created Ticket Status Card start -->
                        <div class="ss-width ticketDrag">
                            <div class="card border-0 shadow">
                                <div class="card-body card-body-padding bg-color">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="fs-12 fw-semibold">Created <span
                                                class="text-secondary">(<?= $ticket1 ?>)</span></span>
                                    </div>
                                    <div class="scroll mt-2 px-2" id="ticket1" data-status="3">
                                        <?php
                                        if ($ticket1 > 0){
                                            foreach ($ticket as $ticketValue) :
                                                if($ticketValue->status==3){
                                        ?>
                                        <!-- Card Design 1 use loop in it-->
                                        <div class="card ss-card border-0 mb-2 box" draggable="true" data-ticket-id="<?= $ticketValue->id ?>">
                                            <div class="card-body fs-12">
                                                <div class="mb-2">
                                                    <ul class="list-unstyled m-0 d-flex align-items-center">
                                                    <?php 
                                                        $ticket_type = ($ticketValue['ticket_type'] == 1 ? 'Bug' : ($ticketValue['ticket_type'] == 2 ? 'Feature Enhancement' : ($ticketValue['ticket_type'] == 3 ? 'Change' : 'Unknown')));

                                                        $class_name = ($ticketValue['ticket_type'] == 1 ? 'bg-danger-subtle text-danger' : ($ticketValue['ticket_type'] == 2 ? 'bg-info-subtle text-info' : ($ticketValue['ticket_type'] == 3 ? 'bg-success-subtle text-success' : 'Unknown')));

                                                    ?>
                                                        <li class="<?= $class_name ?> py-1 px-2 fs-12 fw-semibold rounded me-1">
                                                            <?= $ticket_type ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="d-flex align-items-center ss-hover mb-2">
                                                <p class="fs-12 mb-0 stretched-link" onclick="kanban(<?= $ticketValue->id ?>,<?= $ticketValue->client_id ?>)">
                                                        <span class="fw-bold" title="<?= $ticketValue->title ?>">
                                                        <?= (strlen($ticketValue->title) < 70 ? $ticketValue->title: substr_replace($ticketValue->title, "...", 70)); ?>
                                                        </span><br>
                                                        <?= substr_replace($ticketValue->description, "...", 60); ?>    
                                                </p>
                                                        
                                                </div>

                                                <!-- Client Name -->
                                                <div class="d-flex align-items-center avatar-group z-2">
                                                    <div class="d-flex align-items-center z-2">
                                                        <span class="me-2 fs-13 text-secondary">
                                                            <i class="fa fa-user"></i> 
                                                        </span>
                                                        <span href="" class="d-flex align-items-center text-secondary">&nbsp;
                                                            <span class="fs-12 fw-bold">
                                                                <?= ucfirst($ticketValue['client_name']) ?>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <!-- End -->

                                                
                                                <!-- Project -->
                                                <div class="d-flex align-items-center avatar-group z-2">
                                                    <div class="d-flex align-items-center z-2">
                                                        <span class="me-2 fs-13 text-secondary">
                                                            <i class="fa fa-building"></i> 
                                                        </span>
                                                        <span href="" class="d-flex align-items-center text-secondary">&nbsp;
                                                            <span class="fs-12 fw-bold">
                                                                <?= ucfirst($ticketValue['project_name']) ?>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <!-- End -->

                                                <!-- Time  -->
                                                <div class="d-flex align-items-center avatar-group z-2">
                                                    <div class="d-flex align-items-center z-2">
                                                        <span class="me-2 fs-13 text-secondary">
                                                            <i class="bi bi-clock-fill"></i>
                                                        </span>&nbsp;
                                                        <span href="" class="d-flex align-items-center text-secondary">
                                                            <span class="fs-12">
                                                                <?= date('m-d-Y H:i:s', strtotime($ticketValue['created_at'])) ?>
                                                            </span>
                                                        </span>
                                                    </div>
                                                   
                                                </div>
                                                <!-- End -->
                                            </div>
                                        </div>
                                        <!-- End loop-->
                                        <?php } endforeach; } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- End  -->
                    <?php } ?>

                    <!-- In Progress Section -->
                    <?php if(in_array(1,$ticket_status)) { ?>
                        <div class="ss-width">
                            <div class="card border-0 shadow">
                                <div class="card-body card-body-padding bg-color">
                                <div class="d-flex align-items-center justify-content-between">
                                        <span class="fs-12 fw-semibold">In progress<span class="text-secondary">(<?= $ticket2 ?>)</span></span>
                                </div>

                                    <div class="scroll mt-2 px-2" id="ticket2" data-status="1">
                                    <?php
                                        if ($ticket2 > 0){
                                            foreach ($ticket as $ticketValue) :
                                                if($ticketValue->status==1){
                                    ?>
                                        <!-- Loop start -->
                                        <div class="card ss-card border-0 mb-2 box" draggable="true" data-ticket-id="<?= $ticketValue->id ?>">
                                            <div class="card-body fs-12">
                                                <div class="mb-2">
                                                    <ul class="list-unstyled m-0 d-flex align-items-center">

                                                    <?php 
                                                        $ticket_type = ($ticketValue['ticket_type'] == 1 ? 'Bug' : ($ticketValue['ticket_type'] == 2 ? 'Feature Enhancement' : ($ticketValue['ticket_type'] == 3 ? 'Change' : 'Unknown')));

                                                        $class_name = ($ticketValue['ticket_type'] == 1 ? 'bg-danger-subtle text-danger' : ($ticketValue['ticket_type'] == 2 ? 'bg-info-subtle text-info' : ($ticketValue['ticket_type'] == 3 ? 'bg-success-subtle text-success' : 'Unknown')));

                                                    ?>
                                                        <li class="<?= $class_name ?> py-1 px-2 fs-12 fw-semibold rounded me-1">
                                                            <?= $ticket_type ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="d-flex align-items-center ss-hover mb-2">
                                                <p class="fs-12 mb-0 stretched-link" onclick="kanban(<?= $ticketValue->id ?>,<?= $ticketValue->client_id ?>)">
                                                        <span class="fw-bold" title="<?= $ticketValue->title ?>">
                                                        <?= (strlen($ticketValue->title) < 70 ? $ticketValue->title: substr_replace($ticketValue->title, "...", 70)); ?>
                                                        </span><br>
                                                        <?= substr_replace($ticketValue->description, "...", 60); ?>    
                                                </p>
                                                        
                                                </div>
                                                <!-- Client Name -->
                                                <div class="d-flex align-items-center avatar-group z-2">
                                                    <div class="d-flex align-items-center z-2">
                                                        <span class="me-2 fs-13 text-secondary">
                                                            <i class="fa fa-user"></i> 
                                                        </span>
                                                        <span href="" class="d-flex align-items-center text-secondary">&nbsp;
                                                            <span class="fs-12 fw-bold">
                                                                <?= ucfirst($ticketValue['client_name']) ?>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <!-- End -->

                                                
                                                <!-- Project -->
                                                <div class="d-flex align-items-center avatar-group z-2">
                                                    <div class="d-flex align-items-center z-2">
                                                        <span class="me-2 fs-13 text-secondary">
                                                            <i class="fa fa-building"></i> 
                                                        </span>
                                                        <span href="" class="d-flex align-items-center text-secondary">&nbsp;
                                                            <span class="fs-12 fw-bold">
                                                                <?= ucfirst($ticketValue['project_name']) ?>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <!-- End -->

                                                <!-- Time  -->
                                                <div class="d-flex align-items-center avatar-group z-2">
                                                    <div class="d-flex align-items-center z-2">
                                                        <span class="me-2 fs-13 text-secondary">
                                                            <i class="bi bi-clock-fill"></i>
                                                        </span>&nbsp;
                                                        <span href="" class="d-flex align-items-center text-secondary">
                                                            <span class="fs-12">
                                                                <?= date('m-d-Y H:i:s', strtotime($ticketValue['created_at'])) ?>
                                                            </span>
                                                        </span>
                                                    </div>
                                                   
                                                </div>
                                                <!-- End -->
                                            </div>
                                        </div>
                                        <!-- End Loop-->
                                    <?php } endforeach; } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- End  -->

                    <!-- Resolved  -->
                    <?php if(in_array(2,$ticket_status)) { ?>
                        <div class="ss-width">
                            <div class="card border-0 shadow">
                                <div class="card-body card-body-padding bg-color">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="fs-12 fw-semibold">Resolved <span
                                                class="text-secondary">(<?= $ticket3 ?>)</span></span>
                                    </div>
                                    <div class="scroll mt-2 px-2" id="ticket3" data-status="2">
                                    <?php
                                        if ($ticket3 > 0){
                                            foreach ($ticket as $ticketValue) :
                                                if($ticketValue->status==2){
                                    ?>
                                        <!-- Loop Data -->
                                        <div class="card ss-card border-0 mb-2 box" draggable="true" data-ticket-id="<?= $ticketValue->id ?>">
                                            <div class="card-body fs-12">
                                                <div class="mb-2">
                                                    <ul class="list-unstyled m-0 d-flex align-items-center">

                                                    <?php 
                                                        $ticket_type = ($ticketValue['ticket_type'] == 1 ? 'Bug' : ($ticketValue['ticket_type'] == 2 ? 'Feature Enhancement' : ($ticketValue['ticket_type'] == 3 ? 'Change' : 'Unknown')));

                                                        $class_name = ($ticketValue['ticket_type'] == 1 ? 'bg-danger-subtle text-danger' : ($ticketValue['ticket_type'] == 2 ? 'bg-info-subtle text-info' : ($ticketValue['ticket_type'] == 3 ? 'bg-success-subtle text-success' : 'Unknown')));

                                                    ?>
                                                        <li class="<?= $class_name ?> py-1 px-2 fs-12 fw-semibold rounded me-1">
                                                            <?= $ticket_type ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="d-flex align-items-center ss-hover mb-2">
                                                <p class="fs-12 mb-0 stretched-link" onclick="kanban(<?= $ticketValue->id ?>,<?= $ticketValue->client_id ?>)">
                                                        <span class="fw-bold" title="<?= $ticketValue->title ?>">
                                                        <?= (strlen($ticketValue->title) < 70 ? $ticketValue->title: substr_replace($ticketValue->title, "...", 70)); ?>
                                                        </span><br>
                                                        <?= substr_replace($ticketValue->description, "...", 60); ?>    
                                                </p>
                                                        
                                                </div>
                                                <!-- Client Name -->
                                                <div class="d-flex align-items-center avatar-group z-2">
                                                    <div class="d-flex align-items-center z-2">
                                                        <span class="me-2 fs-13 text-secondary">
                                                            <i class="fa fa-user"></i> 
                                                        </span>
                                                        <span href="" class="d-flex align-items-center text-secondary">&nbsp;
                                                            <span class="fs-12 fw-bold">
                                                                <?= ucfirst($ticketValue['client_name']) ?>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <!-- End -->

                                                <!-- Project -->
                                                <div class="d-flex align-items-center avatar-group z-2">
                                                    <div class="d-flex align-items-center z-2">
                                                        <span class="me-2 fs-13 text-secondary">
                                                            <i class="fa fa-building"></i> 
                                                        </span>
                                                        <span href="" class="d-flex align-items-center text-secondary">&nbsp;
                                                            <span class="fs-12 fw-bold">
                                                                <?= ucfirst($ticketValue['project_name']) ?>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <!-- End -->

                                                <!-- Time  -->
                                                <div class="d-flex align-items-center avatar-group z-2">
                                                    <div class="d-flex align-items-center z-2">
                                                        <span class="me-2 fs-13 text-secondary">
                                                            <i class="bi bi-clock-fill"></i>
                                                        </span>&nbsp;
                                                        <span href="" class="d-flex align-items-center text-secondary">
                                                            <span class="fs-12">
                                                                <?= date('m-d-Y H:i:s', strtotime($ticketValue['created_at'])) ?>
                                                            </span>
                                                        </span>
                                                    </div>
                                                   
                                                </div>
                                                <!-- End -->
                                            </div>
                                        </div>
                                        <!-- End -->
                                    <?php } endforeach; } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- End -->

                    </div>
                </div>
            </div>
            </section>
            <!-- End  -->
            <?php else: ?>
                <h1 class="text-center heading ft-secondary">You Have No Ticket List</h1>
            <?php endif; ?>    

        </div>
    </div>
</section>


<!-- Modal - 0  -->
<div class="modal fade" id="kanban-modal-1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
      
</div>

<!-- Add Asset Data -->
<div class="modal fade" tabindex="-1" role="dialog" id="add_file">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <?= $this->Form->create(null, ['id' => 'addFile']) ?>
            <div class="modal-header">
                <h5 class="modal-title">Upload File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Upload Suppoting Attachment</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-upload"></i></span>
                                <input class="form-control" type="file" id="files" name="files[]">
                                <input type="text" name="ticketID" id="ticketID">
                            </div>
                        </div>
                    </div>
                 
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="commentAddFile()">File Upload</button>
            </div>
            <?= $this->Form->end() ?>

        </div>
    </div>
</div>


<!-- Add Tickets Data -->
<div class="modal fade" tabindex="-1" role="dialog" id="add_ticket">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= $this->Form->create(null, [
                'url' => [
                    'controller' => 'Tickets',
                    'action' => 'add',
                ],
                'enctype' => 'multipart/form-data',
            ]) ?>

            <div class="modal-header">
                <h5 class="modal-title">Add New Ticket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Client Name</label>
                            <div class="">
                                <select style="width:100%" name="client_name" class="form-select client_id" id="client_id" required>
                                    <option value="">Select Project</option>
                                    <?php foreach($clientList as $value){ ?>
                                    <option value="<?= $value['id'] ?>"><?= $value['client_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Project Name</label>
                            <div class="">
                            <select style="width:100%" name="project_id" id="project_id" class="form-control">
                                <option value="">Select Project</option>
                            </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Ticket Type</label>
                            <div class="">
                                <!-- <span class="icon ft-primary"><i class="">#</i></span> -->
                                <select name="ticket_type" class="form-select" required id="ticket_type" style="width:100%">
                                <option value="">Select Ticket Type</option>
                                <option value="1">Bug</option>
                                <option value="2">Feature Enhancement</option>
                                <option value="3">Change</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Title</label>
                            <div class="adon-group">
                                <!-- <span class="icon ft-primary"><i class="">#</i></span> -->
                                <?= $this->form->text("title", [
                                    "class" => "form-control",
                                    "required" => true,
                                    "autocomplete" => "off",
                                    "placeholder" => "Enter Title",
                                ]); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Status</label>
                            <div class="adon-group">
                            <!-- <span class="icon ft-primary"><i class="">#</i></span> -->
                                <select name="status" class="form-control" required style="width:100%">
                                <option value="1">In Progress</option>
                                <option value="3" selected>Created</option>
                                <option value="2">Resolved</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Notes</label>
                            <div class="adon-group">
                                <!-- <span class="icon ft-primary"><i class="">#</i></span> -->
                                <?= $this->form->textarea("notes", [
                                    "class" => "form-control",
                                    "required" => true,
                                    "autocomplete" => "off",
                                    "style" => "height:100px",
                                    "placeholder" => "Enter Notes",
                                ]); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Upload Suppoting Attachments</label>
                            <div class="adon-group">
                                <!-- <span class="icon ft-primary"><i class="fa fa-upload"></i></span> -->
                                <?= $this->form->file("files[]", [
                                    "class" => "form-control",
                                    "autocomplete" => "off",
                                    "multiple" =>true,
                                ]); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                <button class="v-btn v-btn-primary" type="submit">Save</button>
            </div>
            <?= $this->Form->end() ?>

        </div>
    </div>
</div>
<style>
    i{
        color: #3fd5db;
    }
</style>
<!-- Javascript Start -->
<script>
function kanban(id, clientId) {
    $("#ticketID").val(id);
    $.ajax({
        url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'kanbanModal']) ?>",
        method: "GET",
        data: {
            id: id,
            clientId: clientId
        },
        success: function(res) {
            $('#kanban-modal-1').html(res);
            $('#kanban-modal-1').modal('show');
        }
    })
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@shopify/draggable@1.0.0-beta.8/lib/draggable.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
<script>

$(document).ready(function($) {
    $('#project_id').select2({
        dropdownParent: $('#add_ticket .modal-content'),
        placeholder: 'Select option',
        width: 'resolve'
    });

    $('#client_id').select2({
        dropdownParent: $('#add_ticket .modal-content'),
        placeholder: 'Select option',
        width: 'resolve'
    });
    
    $('#ticket_type').select2({
        dropdownParent: $('#add_ticket .modal-content'),
        placeholder: 'Select option',
        width: 'resolve'
    });

});

$(init);
function init() {
    $("#ticket1, #ticket2, #ticket3").sortable({
        connectWith: ".scroll",
        stack: '.scroll .box',
        update: function(event, ui) {
            updateStatus(ui.item);
        }
    }).disableSelection();
}

function updateStatus(item) {
    var target = item.parent();
    var status = target.attr('data-status');
    var ticketId = item.attr('data-ticket-id');
    $.ajax({
        url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'ticketStatus']) ?>",
        method: "GET",
        data: {
            ticketId: ticketId,
            status: status
        },
        success: function(res) {
            location.reload();
        }
    });
}

$(document).ready(function($) {
    $(".client_id").on('change', function() {
      var client_id = $(this).val();
      if(client_id != '')
      {
        $.ajax({
            url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'clientProject']) ?>",
            data: { client_id: client_id },
            success: function(res) {
                var html = JSON.parse(res);
                $('#project_id').html(html);
            }
        });
      }
      else
      {
        $('#project_id').val('');
      }
    });
});
</script>