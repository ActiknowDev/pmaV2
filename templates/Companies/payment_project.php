<?php extract($projects[0]); ?>
<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="heading ft-secondary">
                        <?= $project_name;?>
                    </div>
                </div>
               <div class="col-6">
                    <div class="actions-ctrl text-md-right">
                        <?= $this->Html->link('<i class="fa fa-list"></i><span>List Project</span>','/list-project',['class' => 'v-btn v-btn-secondary','escape' => false]); ?>
                            
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- PAGE TAB -->
    <div class="page-tab">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="v-tab">
                         <li><?= $this->Html->link('Milestone','/project-view/'. $id); ?></li>
                        <li class="active"><?= $this->Html->link('Payment History','/project-payment/'. $id); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- PAGE-CONTENT -->
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <a href="#" data-target="#add_payment_received" data-toggle="modal" class="v-btn v-btn-primary mb-3"><i class="fa fa-plus"></i><span>Add Payment</span></a>
                    <table class="table table-light nowarp">
                        <thead>
                            <tr>
                                
                                <th>Description</th>
                                <th>Date</th>
                                <th>Amount Received</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($payments): ?>
                            <?php foreach($payments as $p): ?>
                            <tr>
                             
                                <td><?= $p['description']; ?></td>
                                <td><?= $p['payment_date']; ?></td>
                                <td>
                                    $<?= $p['receive_amt']; ?>
                                </td>
                                <td>
                                     <select name="pstatus" class="form-control status" id="<?= $p['id'];?>" data-type="payment" data-url="<?= WEBURL;?>">
                                                <option value="Billed" <?php if($p['status']=='Billed') echo 'selected';?>>Billed</option>
                                                <option value="Paid" <?php if($p['status']=='Paid') echo 'selected';?>>Paid</option>
                                                <option value="Estimated" <?php if($p['status']=='Estimated') echo 'selected';?>>Estimated</option>
                                                
                                            </select>
                                </td>
                                <td>
                                    <a href="#" class="icon" data-toggle="modal" data-target="#edit_payment" onclick="passPayment('edit',<?= $p['id'];?>)"> <i class="fa fa-pencil-alt"></i> </a>
                                    <a href="#" class="icon" onclick="passPayment('delete',<?= $p['id'];?>)"> <i class="fa fa-trash-alt"></i> </a>
                                   
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <div class="block project-info">
                        <div class="content">
                            <h4 class="sub-title">Project Info</h4>
                            <ul class="">
                                <li>Awarded Date <span class="date lead"><?= $award;?></span></li>
                                <li>Due Date <span class="date lead"><?= $due_date; ?></span></li>
                                <li>Total Milestone <span class="date lead"><?= count($miles); ?></span></li>
                                <li>Status <span class="badge badge-dark"><?= $status; ?></span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="block project-info">
                        <div class="content">
                            <h4 class="sub-title">Client Info</h4>
                           <!--  <a href=""> -->
                                <h5> <?= $client; ?></h5>
                                <p class="lead"><i class="fa fa-envelope"></i> <?= $email; ?></p>
                            <!-- </a> -->
                        </div>
                    </div>
                    <div class="block project-info">
                        <div class="content">
                            <h4 class="sub-title">Resources <!-- <span data-target="#add_resources" data-toggle="modal" class="icon icon-sm float-right"><i class="fa fa-user-plus"></i></span> --></h4>
                            <ul>
                                <?php if($manager == $lead)
                                { ?>
                                <li>
                                    <h5><?= $manager; ?><span class="lead">Manager,Tech Lead</span></h5>
                                </li>
                                 <?php foreach($res as $r):?>
                                    <li>  
                                <h5><?= $r['name']; ?><span class="lead"><?= $r['role']; ?></span></h5>
                                </li>
                            <?php endforeach; ?>

                            <?php } else{ ?>
                                <li>
                                    <h5><?= $manager; ?><span class="lead">Manager</span></h5>
                                </li>
                                 <?php foreach($res as $r):?>
                                    <?php if($lead==$r['name']){ ?>
                                <li>  
                                <h5><?= $r['name']; ?><span class="lead">Tech Lead,<?= $r['role']; ?></span></h5>
                                </li>
                            <?php } else{ ?>
                                <li>  
                                <h5><?= $r['name']; ?><span class="lead"><?= $r['role']; ?></span></h5>
                                </li>
                            <?php } ?>
                            <?php endforeach; ?>
                            <?php } ?>
                                
                               
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ADD MILESTONE MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="add_payment_received">
  <?= $this->Form->create(null,array('id'=>'payment')) ?>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Payment History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="content">
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Description</label>
                     <div class="adon-group ades">
                        <input type="hidden" name="project_id" value="<?= $id; ?>">
                        <input type="text" class="form-control" name="description" placeholder="" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="">Date</label>
                    <div class="adon-group pdate">
                        <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span>
                        <input type="text" class="form-control datepicker" name="payment_date" placeholder="" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="">Received Payment</label>
                    <div class="adon-group ramt">
                        <span class="icon"><i class="fa fa-dollar-sign"></i></span>
                        <input type="number" class="form-control" name="receive_amt" placeholder="" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="v-btn v-btn-base cancel" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" class="v-btn v-btn-primary">Add To Payment History</button>
      </div>
    </div>
  </div>
  <?= $this->Form->end() ?>
</div>

<!-- EDIT PAYMENT RECEIVED MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="edit_payment">
     <?= $this->Form->create(null,array('id'=>'editpayment')) ?>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Payment History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="content">
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Description</label>
                     <div class="adon-group des">
                        <input type="hidden" name="project_id" value="<?= $id; ?>">
                        <input type="hidden" class="form-control" name="payment_id" id="payment_id" placeholder="">
                        <input type="text" class="form-control" name="description" id="description" placeholder="" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="">Date</label>
                    <div class="adon-group pdate">
                        <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span>
                        <input type="text" class="form-control datepicker" name="payment_date" placeholder="" id="payment_date" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="">Received Payment</label>
                    <div class="adon-group ramt">
                        <span class="icon"><i class="fa fa-dollar-sign"></i></span>
                        <input type="number" class="form-control" name="receive_amt" id="receive_amt" placeholder="" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="v-btn v-btn-base cancel" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" class="v-btn v-btn-primary">Update Payment History</button>
      </div>
    </div>
  </div>
  <?= $this->Form->end() ?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>

<script>
  //add form
var validator = $("#payment").validate({
    rules: {
      description:{
        required:true,
      },
      payment_date: {
        required: true,
      },
      receive_amt:{
        required:true
      },   
    },
    messages: {
      description: {
        required: "Please enter description",
        
        },
        payment_date: {
        required: "Please enter Date",
      },
      receive_amt:{
        required:"Please enter amount",
      },    
    },
    errorPlacement: function(error, element) {
                  if (element.attr("name") == "description" )
                        error.insertAfter(".ades");
                  else if  (element.attr("name") == "payment_date" )
                    error.insertAfter(".pdate");
                  else if  (element.attr("name") == "receive_amt" )
                    error.insertAfter(".ramt");  
                },
     submitHandler: function(form) {
      
       $.ajax({
         url: "<?= $this->Url->build('/companies/addPayment')?>",
         type: "POST",
         data: $('#payment').serialize(),
         dataType: "json",
         success: function( response ) {
           location.reload();
          }
       });
     }
  })

$(".cancel").click(function() {
    validator.resetForm();
});

var editvalidator = $("#editpayment").validate({
    rules: {
      description:{
        required:true,
      },
      payment_date: {
        required: true,
      },
      receive_amt:{
        required:true
      },   
    },
    messages: {
      description: {
        required: "Please enter description",
        
        },
        payment_date: {
        required: "Please enter Date",
      },
      receive_amt:{
        required:"Please enter amount",
      },    
    },
    errorPlacement: function(error, element) {
                  if (element.attr("name") == "description" )
                        error.insertAfter(".des");
                  else if  (element.attr("name") == "payment_date" )
                    error.insertAfter(".pdate");
                  else if  (element.attr("name") == "receive_amt" )
                    error.insertAfter(".ramt");  
                },
     submitHandler: function(form) {
      
       $.ajax({
         url: "<?= $this->Url->build('/companies/updatePayment')?>",
         type: "POST",
         data: $('#editpayment').serialize(),
         dataType: "json",
         success: function( response ) {
           
           location.reload();
          }
       });
     }
  })

$(".cancel").click(function() {
    editvalidator.resetForm();
});
</script>
<script type="text/javascript">
   // getEdit data
   function passPayment(type,id){
    $.ajax({
              
               type:'GET',
               url:"<?= $this->Url->build('/companies/paymentsaction/'); ?>"+type+'/'+id,
              
               beforeSend: function ()
               {
                  
               },
                   success:function(data){
                            if(type=='edit')
                            {
                                var response = $.parseJSON(data);

                               var d = response.payment_date.split('-');
                               var date = d[1]+'/'+d[2]+'/'+d[0];
                               $("#description").val(response.description);
                               $("#payment_date").val(date);
                               $("#receive_amt").val(response.receive_amt);
                               $("#payment_id").val(response.id);
                            }
                            else{
                                location.reload();
                            }
                       
                     }
           });
}

</script>
