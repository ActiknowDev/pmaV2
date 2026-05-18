<section class="page page-dashboard">
   <!-- PAGE-TITLE -->
   <div class="page-title skin-light">
      <div class="container">
         <div class="row">
            <div class="col-6">
               <div class="heading ft-secondary">
                  <span class="icon"><i class="fa fa-building"></i></span>Company List
               </div>
            </div>
            <div class="col-6">
               <div class="actions-ctrl text-md-right">
                  <a href="" data-toggle="modal" data-target="#add_company" class="v-btn v-btn-secondary">
                  <i class="fa fa-plus"></i><span>Add New Company</span>
                  </a>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- PAGE-CONTENT -->
   <div class="page-content">
      <div class="container">
         <div class="row">
            <div class="col-md-3">
               <div class="block primary">
                  <div class="content">
                     <h4 class="title">Total Company</h4>
                     <span><?= $totalCompanies ?></span>
                  </div>
               </div>
            </div>
            <div class="col-md-3">
               <div class="block primary">
                  <div class="content">
                     <h4 class="title">Total Active</h4>
                     <span><?= $totalActiveCompanies?></span>
                  </div>
               </div>
            </div>
            <div class="col-md-3">
               <div class="block primary">
                  <div class="content">
                     <h4 class="title">Total Inactive</h4>
                     <span><?= $totalInactiveCompanies?></span>
                  </div>
               </div>
            </div>
         </div>
         <hr class="dark">
         <!-- FILTER -->
         <!-- <div class="row">
            <div class="col-md-4">
                <div class="adon-group form-group">
                    <span class="icon icon-light ft-primary"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search for company here..">
                </div>
            </div>
            <div class="col-md-2 offset-md-6 text-md-right">
                <div class="adon-group form-group">
                    <span class="icon icon-light ft-primary"><i class="fa fa-filter"></i></span>
                    <select name="sortBy" id="sortBy" class="form-control">
                        <option value="all"  <?php if($status_form == 'all'){ echo 'selected';}?>>All</option>
                        <option value="1"  <?php if($status_form == '1'){ echo 'selected';}?>>Active</option>
                        <option value="0" <?php if($status_form == '0'){ echo 'selected';}?>>Inactive</option>
                    </select>
                </div>
            </div>
            </div> -->
         <!-- TABLE -->
         <div class="row">
            <div class="col-md-12">
               <table  class="table table-light nowrap"  id="example" style="width:100%">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>Company Name</th>
                        <th>Contact Person</th>
                        <th>Email ID</th>
                        <th>Phone Number</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php  $i=1; ?>
                     <?php foreach ($companies as $key => $company) {?>
                     <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $company->company_name; ?></td>
                        <td><?php echo $company->contact_person_name; ?></td>
                        <td><?php echo $company->email ?></td>
                        <td>+91-<?php echo $company->contact_no; ?></td>
                        <td><?php echo $company->location; ?></td>
                        <td>
                           <input class="tgl tgl-light change-status" id="<?php echo $company->id ?>" type="checkbox" value="<?php echo $company->status; ?>"  <?= $company->status == '1'?'checked':'' ?>/>
                           <label class="tgl-btn" for="<?php echo $company->id ?>"></label>
                        </td>
                        <td>
                           <?php echo $this->Html->link('<i class="fa fa-sign-in-alt"></i>', array('controller' => 'Companies', 'action' => 'loginVendor', $company->id), array('class' => 'icon icon-sm icon-primary', 'escape' => false, 'title' => 'Login as'));?>
                           <a href="#" data-toggle="modal" data-target="#edit_company"class="icon icon-sm ft-primary " title="Edit Use" onclick="passValue('<?php echo $company->id ?>')"><i class="fa fa-pencil-alt"></i></a>
                           <!-- <?php echo $this->Html->link('<i class="fa fa-times"></i>', array('controller' => 'Companies', 'action' => 'delete', $company->id), array('class' => 'icon icon-sm icon-primary', 'escape' => false, 'title' => 'Delete'));?> -->
                        </td>
                     </tr>
                     <?php $i++; ?>
                     <?php } ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- ADD COMPANY MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="add_company">
   <?= $this->Form->create(null,array('id'=>'companies')) ?> 
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Add Company</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="content">
               <div class="form-group row">
                  <div class="col-md-12">
                     <label for="">Company Name</label>
                     <div class="adon-group cname">
                        <span class="icon ft-primary"><i class="fa fa-building"></i></span>
                        <input type="text" name="company_name" id="company_name" class="form-control" placeholder="" autocomplete="off">
                        <input type="hidden" name="password" value="password">
                     </div>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-md-12">
                     <label for="">Company Website Link</label>
                     <div class="adon-group cblink">
                        <span class="icon ft-primary"><i class="fa fa-globe"></i></span>
                        <input type="text" name="company_website_link" id="company_website_link" class="form-control" placeholder="" autocomplete="off">
                     </div>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-md-12">
                     <label for="">Contact Person Name</label>
                     <div class="adon-group cpname">
                        <span class="icon ft-primary"><i class="fa fa-user"></i></span>
                        <input type="text" name="contact_person_name" id="contact_person_name" class="form-control" placeholder="" autocomplete="off">
                     </div>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-md-6">
                     <label for="">Email Id</label>
                     <div class="adon-group addemail">
                        <span class="icon ft-primary"><i class="fa fa-envelope"></i></span>
                        <input type="Email Id" name="email" id="email" class="form-control" placeholder="" autocomplete="off">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <label for="">Phone Number</label>
                     <div class="adon-group addpn addcon">
                        <a href="#" class="v-btn v-btn-light">+91</a>
                        <input type="text" name="contact_no" id="contact_no" class="form-control" placeholder="" autocomplete="off">
                     </div>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-md-12">
                     <label for="">Location</label>
                     <div class="adon-group addln">
                        <span class="icon ft-primary"><i class="fa fa-map-marker-alt"></i></span>
                        <input type="text" name="location" id="location" class="form-control" placeholder="" autocomplete="off">
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="v-btn v-btn-base cancel" data-dismiss="modal">Close</button>
            <button type="submit" name="submit" class="v-btn v-btn-primary" id="savecompany">Save Comapny</button>
         </div>
      </div>
   </div>
   <?= $this->Form->end() ?>
</div>
<!-- Edit COMPANY MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="edit_company">
   <?= $this->Form->create(null,array('id'=>'companiesEdit')) ?> 
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Edit Company</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="content">
               <div class="form-group row">
                  <div class="col-md-12">
                     <label for="">Company Name</label>
                     <div class="adon-group cnameedt">
                        <span class="icon ft-primary"><i class="fa fa-building"></i></span>
                        <input type="text" name="company_name" id="company_name_edit" class="form-control" placeholder="" autocomplete="off">
                        <input type="hidden" name="password" value="password">
                     </div>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-md-12">
                     <label for="">Company Website Link</label>
                     <div class="adon-group cblinkedt">
                        <span class="icon ft-primary"><i class="fa fa-globe"></i></span>
                        <input type="text" name="company_website_link" id="company_website_link_edit" class="form-control" placeholder="" autocomplete="off">
                     </div>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-md-12">
                     <label for="">Contact Person Name</label>
                     <div class="adon-group cpnameedt">
                        <span class="icon ft-primary"><i class="fa fa-user"></i></span>
                        <input type="text" name="contact_person_name" id="contact_person_name_edit" class="form-control" placeholder="" autocomplete="off">
                     </div>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-md-6">
                     <label for="">Email Id</label>
                     <div class="adon-group edtemail">
                        <span class="icon ft-primary"><i class="fa fa-envelope"></i></span>
                        <input type="text" name="email" id="email_edit" class="form-control" placeholder="" autocomplete="off">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <label for="">Phone Number</label>
                     <div class="adon-group edtpn">
                        <a href="#" class="v-btn v-btn-light">+91</a>
                        <input type="text" name="contact_no" id="contact_no_edit" class="form-control" placeholder="" autocomplete="off">
                     </div>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-md-12">
                     <label for="">Location</label>
                     <div class="adon-group edtln">
                        <span class="icon ft-primary"><i class="fa fa-map-marker-alt"></i></span>
                        <input type="text" name="location" id="location_edit" class="form-control" placeholder="" autocomplete="off">
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <input type="hidden" name="edit_id" id="edit_id" >
         <div class="modal-footer">
            <button type="button" class="v-btn v-btn-base cancel" data-dismiss="modal">Close</button>
            <button type="submit" class="v-btn v-btn-primary" id="editcompany">Update Comapny</button>
         </div>
      </div>
   </div>
   <?= $this->Form->end() ?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
   // getEdit data
   function passValue(company_id){
       $.ajax({
              
               type:'GET',
               url:"<?= $this->Url->build('/companies/edit/'); ?>"+company_id,
              
               beforeSend: function ()
               {
                  
               },
                   success:function(data){
                       var response = $.parseJSON(data);
                       $("#company_name_edit").val(response.company_name);
                       $("#company_website_link_edit").val(response.company_website_link);
                       $("#contact_person_name_edit").val(response.contact_person_name);
                       $("#email_edit").val(response.email);
                       $("#contact_no_edit").val(response.contact_no);
                       $("#location_edit").val(response.location);
                       $("#edit_id").val(response.id);
                     }
           });
   }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
<script>
   //add form
 var cmvalidator = $("#companies").validate({
     rules: {
       company_name:{
         required:true,
       },
       email: {
         required: true,
         maxlength: 50,
         email: true,
       }, 
       company_website_link:{
        required:true,
       },
       contact_no:{
        required:true,
        maxlength:10,
       }  
     },
     messages: {
       company_name: {
         required: "Please enter name",
         },
         email:{
          required: "please enter email"
         },
         company_website_link:{
          required:"Please enter website link"
         },
         contact_no:{
          required:"Please enter contact no"
         } 
     },
    errorPlacement: function(error, element) {
                  if (element.attr("name") == "company_name" )
                        error.insertAfter(".cname");
                  else if  (element.attr("name") == "email" )
                    error.insertAfter(".addemail");
                  else if  (element.attr("name") == "company_website_link" )
                    error.insertAfter(".cblink");
                  else if  (element.attr("name") == "contact_no" )
                    error.insertAfter(".addcon");
                   
                },
      submitHandler: function(form) {
       $('#savecompany').html('sending..');
        $.ajax({
          url: "<?= $this->Url->build('/companies/add')?>",
          type: "POST",
          data: $('#companies').serialize(),
          dataType: "json",
          success: function( response ) {
            $('#savecompany').html('Save Company');
            window.location.href = "<?= $this->Url->build('/companies')?>";
           }
        });
      }
   })


   $(".cancel").click(function() {
    cmvalidator.resetForm();
});
   
</script>
<script>
   //edit form
var cmdvalidator = $("#companiesEdit").validate({
     rules: {
       company_name:{
         required:true,
       },
       email: {
         required: true,
         maxlength: 50,
         email: true,
       },
       company_website_link:{
        required:true,
       },
       contact_no:{
        required:true,
        maxlength:10,
       }     
     },
     messages: {
       company_name: {
         required: "Please enter name",
         },
      email:{
        required: "Please enter email"
      },
         company_website_link:{
          required:"Please enter website link"
         },
         contact_no:{
          required:"Please enter contact no"
         }   
     },
    errorPlacement: function(error, element) {
                  if (element.attr("name") == "company_name" )
                        error.insertAfter(".cnameedt");
                  else if  (element.attr("name") == "email" )
                    error.insertAfter(".edtemail");
                  else if  (element.attr("name") == "contact_no" )
                    error.insertAfter(".edtpn");
                  else if  (element.attr("name") == "company_website_link" )
                    error.insertAfter(".cblinkedt");
                   
                },
      submitHandler: function(form) {
        $('#editcompany').html('sending..');
        var company_id = $("#edit_id").val();
        $.ajax({
          url: "<?= $this->Url->build('/companies/editData/')?>"+company_id,
          type: "POST",
          data: $('#companiesEdit').serialize(),
          dataType: "json",
          success: function( response ) {
            $('#editcompany').html('Update Company');
            window.location.href = "<?= $this->Url->build('/companies')?>";
           }
        });
      }
   })

   $(".cancel").click(function() {
    cmdvalidator.resetForm();
});
</script>
<script type="text/javascript">
   //Status Change
       $('.change-status').click(function() {
         var id = $(this).attr('id');
         var status = $(this).val();
               
               if(status == 1) {
                   status = 0; 
               } else {
                   status = 1; 
               }
   
               $.ajax({
                  
                   type:'GET',
                   url:"<?= $this->Url->build('/companies/updateStatus/'); ?>"+id+'/'+status,
                  
                   beforeSend: function ()
                   {
                     
                   },
                       success:function(data){
                         window.location.href = "<?= $this->Url->build('/companies')?>";
                   }
               });
       });
      
</script>
<script type="text/javascript">
   //drop down active inactive change
   $('select').on('change', function() {
    var status = $(this).val();
    if(status == '1' || status=='0') 
    {
      window.location.href = "<?= $this->Url->build('/companies?status=')?>"+status;
     } else {
         window.location.href = "<?= $this->Url->build('/companies')?>";
     }
       
   });
</script>