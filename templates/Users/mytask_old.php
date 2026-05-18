<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-tasks"></i></span>Task
                    </div>
                </div>
                <div class="col-6">
                    <div class="actions-ctrl text-md-right">
                        <a href="#" data-toggle="modal" data-target="#add_task" class="v-btn v-btn-secondary">
                           <i class="fa fa-plus"></i><span>Add New Task</span>
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
                <div class="col-md-4">
                    <div class="block primary rounded mb-2">
                        <div class="content text-center">
                            <h4 class="title">MY TASKS</h4>
                        </div>
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="block primary rounded mb-2">
                        <div class="content text-center">
                            <h4 class="title">ASSIGNED TO ME</h4>
                        </div>
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="block primary rounded mb-2">
                        <div class="content text-center">
                            <h4 class="title">MY TEAM</h4>
                        </div>
                    </div>
                </div> 
            </div>
            
             <div class="row">
                <div class="col-md-4">
                    <div class="block bg-white rounded">
                        <div class="content text-center">
                           <?php $i=1;if(count($assigned_task)>0):
                                    foreach($assigned_task as $p): ?>

                                   <?php if($p['completed']==1): ?>

                             <div class="block bg-success text-white rounded mb-2">
                                <div class="content text-center p-1">

                                   <h3 class="title text-white"><?=substr($p['project_name'],0,20);?></h3>

                                    <h4 class="title text-white"><?= $p['task_name'];?></h4>

                                    <?php endif?>


                                 <?php if($p['completed']==0): ?>


                                  <div class="block primary text-white rounded mb-2">
                                <div class="content text-center p-1">


                                  <h3 class="title"><?=substr($p['project_name'],0,20);?></h3>

                                    <h4 class="title text-white"><?= $p['task_name'];?></h4>



                                  <?php endif?>

                                    

                                    <div class="round float-right mr-3">
                                        <?php if($p['completed']==1): ?>

                                      <input type="checkbox" onclick="ApprovedTask('unchecked','<?= $p['id']?>',this)" id="checkbox<?= $i;?>">
                                       <label for="checkbox<?= $i;?>"></label>
                                       <?php endif?>
     

                                       <?php if($p['completed']==0): ?>

                                       <input type="checkbox" onclick="{alert('Task is yet not completed by <?= $p['assigend_to'];?>'); return false;}" id="checkbox<?= $i;?>">
                                       <label for="checkbox<?= $i;?>"></label>

                                      <?php endif?>


                                    </div>
                                    <p class="mb-0">Due Date: <?= $p['due_date'];?></p>

                                    <p class="mb-0">To :<?= $p['assigend_to'];?></p>

                                   <div class="crud-icon">
                                     
                                     <a href="#" data-toggle="modal" data-target="#edit_task" title="Edit Task" onclick="edit_modal_data('<?php echo $p['id'] ?>')"><i class="fa fa-edit"></i></a>
                                     
                                     <a href="#" class="icon" onclick="deletetask(<?= $p['id'];?>)"> <i class="fa fa-trash"></i> </a>
                                     

                                   </div>

                                </div>
                            </div>

                              <?php $i++;endforeach; endif;?>
                        </div>
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="block bg-white rounded">
                        <div class="content text-center">
                          
                          <?php if(count($mytask)>0):
                                    $j=$i;foreach($mytask as $p): ?>

                             <?php if($p['completed']==1): ?>
                            <div class="block bg-success text-white  rounded mb-2">
                                <div class="content text-center p-1">

                                  <h3 class="title text-white"><?=substr($p['project_name'],0,20);?></h3>

                                <h4 class="title text-white"><?= $p['task_name'];?></h4>


                              <h2 class="title text-white"><?= $p['description'];?></h2>

                                   
                                    <div class="round float-right mr-3">

                                     

                                      <input type="checkbox" checked onclick="CompletedMyTask('checked','<?= $p['id']?>',this)" id="checkbox<?= $j;?>">
                                       <label for="checkbox<?= $j;?>"></label>
                                    </div>
                                    <p class="mb-0">Due on: <?= $p['due_date'];?></p>

                                    <p class="mb-0">By :<?= $p['assigned_by'];?></p>

                                    

                                </div>
                            </div>
                            <?php endif?>


                                       <?php if($p['completed']==0): ?>

                                         <div class="block primary text-white  rounded mb-2">
                                <div class="content text-center p-1">


                                  <h3 class="title text-white"><?=substr($p['project_name'],0,20);?></h3>

                                    <h4 class="title text-white"><?= $p['task_name'];?></h4>
                                   <h2 class="title text-white"><?= $p['task_description'];?></h2>

                                    <div class="round float-right mr-3">


                                       <input type="checkbox" onclick="CompletedMyTask('unchecked','<?= $p['id']?>',this)" id="checkbox<?= $j;?>">
                                       <label for="checkbox<?= $j;?>"></label>
                                    </div>
                                    <p class="mb-0">Due on: <?= $p['due_date'];?></p>

                                    <p class="mb-0">By :<?= $p['assigned_by'];?></p>

                                    

                                </div>
                            </div>


                                      <?php endif?>






                            <?php $j++;endforeach; endif;?>

                           

                        </div>
                         
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="block bg-white rounded">
                        <div class="content text-center">
                            <?php if(count($myTeamData)>0):
                                    $i=1;foreach($myTeamData as $p): ?>

                        <div class="block primary text-white  rounded mb-2">
                                <div class="content text-center p-1">
                                   
                                   <a href="#" data-toggle="collapse" data-target="#data<?= $i;?>" aria-expanded="false" style="color:white;"> <p class="mb-0" style="font-size: 1.2rem;"><?= $p['name'];?></p></a>

                                    <?php if(count($p['tasks'])>0):
                                    foreach($p['tasks'] as $task): ?>

                                   <div id="data<?= $i;?>" onmouseover="mouseDown(this)" onmouseout="mouseUp(this)"  class="collapse bg-white task-detail" style="margin: .5rem 0;padding: .5rem 0;color:#333;">

                                    <h3 class="title" style="padding: .2rem 0;color:black;"> <?= $task['project_name'];?></h3>

                                    <h4 class="title" style="padding: .2rem 0;color:black;"> <?= $task['task_name'];?></h4>
                                    <p>Due: <?= $task['due_date'];?></p>
                                    <p style="padding-bottom: .3rem;">By: <?= $task['assigned_by_name'];?></p>

                                    <p class="hover-text" style="display: none;">Description: <?= $task['description'];?></p>

                                    </div>

                                   <?php endforeach;endif;?>

                                      <?php if(count($p['tasks'])<=0): ?>

                                       <div id="data<?= $i;?>" class="collapse bg-white" style="margin: .5rem 0;padding: .5rem 0;color:#333;">
                                    <h3>No Task to Show</h3>
                                    </div>

                                  <?php endif ?>



                                  

                                </div>
                            </div>

                             <?php $i++;endforeach;endif;?>
                          </div>
                      
                    </div>
                </div> 

            </div>
          
            
        </div>
    </div>
</section>


 <input type="hidden" id="url" value="<?= WEBURL;?>">
 
 
<div class="modal fade" tabindex="-1" role="dialog" id="add_task">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Task</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
       <?= $this->Form->create(null,array('id'=>'addTask','url'=>'/addTask/','type'=>'post')) ?>
      
      <div class="modal-body">
       
        <div class="content">

          <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Project Name</label>

               <select data-live-search="true" required name="project_id" class="form-control selectpicker" id="project_id">
                       <?php if(count($projects_data)>0):
                                    foreach($projects_data as $p): ?>
                        <option value="<?= $p['id'];?>"><?= substr($p['project_name'],0,20);?> - <?=$p["client"]["client_name"]?></option>
                       <?php endforeach; endif;?>
                    </select>
                </div>
            </div>


            <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Task Name</label>
                   <?= $this->Form->input('task_name', array('class' => 'form-control','type'=>'text','autocomplete'=>'off','required'=>true)) ?>
                   <!--  <input id="tags" name="task_name" type="text" class="form-control" placeholder=""> -->
                </div>
            </div>

            <div class="form-group row">
               <div class="col-md-12">
                   <label for="">Task Description</label>
                   <textarea required id="description" name="description" class="form-control"></textarea>
               </div>
           </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Due on</label>
                    <?= $this->Form->input('due_date', array('class' => 'form-control datepicker','type'=>'text','autocomplete'=>'off','required'=>true)) ?>
                  <!--   <input type="text" name="due_date" class="form-control datepicker" placeholder=""> -->
                </div>
            </div>
             <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Assign To</label>

               <select required name="assigned_to" id="" class="form-control">
                       <?php if(count($users)>0):
                                    foreach($users as $p): ?>
                        <option value="<?= $p['id'];?>"><?= $p['name'];?></option>
                       <?php endforeach; endif;?>
                    </select>
                </div>
            </div>


            
            
           
        </div>
     
      </div>
      <div class="modal-footer">
        <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
        <button class="v-btn v-btn-primary" type="submit"><!--  data-dismiss="modal"> -->Add Task</button>

      </div>

       <?= $this->Form->end() ?>
       
    </div>
  </div>
</div>


<div class="modal fade hide" tabindex="-1" role="dialog" id="edit_task">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Task</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    
    <?= $this->Form->create(null,array('id'=>'edit_task_form')) ?> 
      
      <div class="modal-body">
       
        <div class="content">

            <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Project Name</label>

               <select name="project_id" id="edit_task_project" class="form-control selectpicker" required data-live-search="true">
                      
                    </select>
                </div>
            </div>


            <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Task Name</label>
                   <?= $this->Form->input('task_name', array('class' => 'form-control','type'=>'text','id'=>'edit_task_name','required'=>true)) ?>
                    <input id="edit_task_id" name="task_id" type="text" hidden>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Due on</label>
                    <?= $this->Form->input('due_date', array('class' => 'form-control datepicker','type'=>'text','autocomplete'=>'off','id'=>'edit_due_date','required'=>true)) ?>
                  <!--   <input type="text" name="due_date" class="form-control datepicker" placeholder=""> -->
                </div>
            </div>
             <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Assign To</label>

               <select name="assigned_to" required id="edit_task_assign_to" class="form-control">
                      
                    </select>
                </div>
            </div>


            <div class="form-group row">
               <div class="col-md-12">
                   <label for="">Task Description</label>
                   <textarea required id="edit_task_description" name="description" class="form-control"></textarea>
               </div>
           </div>
            
           
        </div>
     
      </div>
      <div class="modal-footer">
        <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
        <button class="v-btn v-btn-primary" type="submit" id="edit_task_button"><!--  data-dismiss="modal"> -->Update Task</button>

      </div>

      </form>
       
    </div>
  </div>
</div>

<input id="complete_task_url" type="text" value="<?= $this->Url->build([
    "controller" => "Users",
    "action" => "completedmytask",
]);?>" hidden>


<input id="approved_task_url" type="text" value="<?= $this->Url->build([
    "controller" => "Users",
    "action" => "approvedtask",
]);?>" hidden>

<input id="user_id" type="text" value="<?= $user_id;?>" hidden>

<style type="text/css">
    .round {
  position: relative;
}

.round label {
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: 50%;
  cursor: pointer;
  height: 28px;
  left: 0;
  position: absolute;
  top: 0;
  width: 28px;
}

.round label:after {
  border: 2px solid #3fd5db;
  border-top: none;
  border-right: none;
  content: "";
  height: 6px;
  left: 7px;
  opacity: 0;
  position: absolute;
  top: 8px;
  transform: rotate(-45deg);
  width: 12px;
}

.round input[type="checkbox"] {
  visibility: hidden;
}

.round input[type="checkbox"]:checked + label {
  background-color: #f7f7f7;
  border-color: #fff;
}

.round input[type="checkbox"]:checked + label:after {
  opacity: 1;
}

.crud-icon{
  display: flex;
  justify-content: center;
} 

.crud-icon a{
  color: white;
  font-size: 1.2rem;
  padding: .1rem 1rem;

}

</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
<script type="text/javascript">
  
function CompletedMyTask(condition,id,ele){

        var r = confirm("Are you Sure");
          if(r){
                var url = $("#complete_task_url").val();
                url = url+"/"+condition+"/"+id+"/";
              
            $.ajax({
                    type:"GET",
                    url:url,
                    success : function(data) {
                        location.reload();
                    },
                    error : function() {
                        alert("false");
                    }
                });
             $(ele).prop("checked", true);

          }
          else{
            $(ele).prop("checked", false);
          }


}




 
function ApprovedTask(condition,id,ele){

        var r = confirm("Are you Sure");
          if(r){
                var url = $("#approved_task_url").val();
                url = url+"/"+condition+"/"+id+"/";
              console.log(url);
            $.ajax({
                    type:"GET",
                    url:url,
                    success : function(data) {
                    location.reload();
                    },
                    error : function() {
                        alert("false");
                    }
                });
            $(ele).prop("checked", true);

          }
          else{
            $(ele).prop("checked", false);
          }


}


function mouseDown(ele) {
  console.log("mouseDown work");
  $(ele).find('.hover-text').show();
}

function mouseUp(ele) {
  console.log("mouseUp work");
  $(ele).find('.hover-text').hide();
}


function edit_modal_data(task_id)
{
  var url = 
       $.ajax({
               type:'GET',
               url:"<?= $this->Url->build('/users/editTask/'); ?>"+task_id,
               beforeSend: function ()
               {
               },
               success:function(data){
                
                  var response = $.parseJSON(data);

                
                       $("#edit_task_id").val(response.id);
                       $("#edit_task_name").val(response.task_name);
                       $("#edit_due_date").val(response.due_date);
                       $("#edit_task_assign_to").html(response.assigned_options);
                       $("#edit_task_description").val(response.task_description);
                       $("#edit_task_project").html(response.assigned_options_project);

                     
                       $('.selectpicker').selectpicker('refresh')
                         $(".filter-option-inner-inner").last().text(response.assigned_options_project_selected)

                      
                       
                       
               }
           });
  

}


function deletetask(task_id)
{

  res = confirm("Are you Sure");
  if(res){
  
// console.log("<?= $this->Url->build('/users/deleteTask/'); ?>");
  $.ajax({
          type:"GET",
          url:"<?= $this->Url->build('/users/deleteTask/'); ?>"+task_id,
          success : function(data) {
            location.reload();
          },
          error : function() {
            // alert("false");
          }
      });

  }
}

</script>

 <script>
  //edit form
var uservalid = $("#edit_task_form").validate({
    rules: {
      task_name:{
        required:true,
      },   
    },
    messages: {
      task_name: {
        required: "Please enter task",
        },
        
    },
     submitHandler: function(form) {
      $('#edit_task_button').html('sending..');
       var task_id = $("#edit_task_id").val();
      $.ajax({
         url: "<?= $this->Url->build('/users/updateTask/')?>"+task_id,
         type: "POST",
         data: $('#edit_task_form').serialize(),
         dataType: "json",
         success: function( response ) {
           location.reload();
          }
       });
     }
  })

$(".cancel").click(function() {
    location.reload();
});
</script>