<style>
    .fa-comment-dots {
        color: #3fd5db;
        cursor: pointer;
    }
</style>
<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-building"></i></span>My Team
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- PAGE-CONTENT -->
    <div class="page-content">
        <div class="container">
            <!-- FILTER -->
            <div class="row">

                <div class="col-md-4 offset-md-10 mb-1">
                     <a  href="#" data-target="#add_company" data-toggle="modal" class="v-btn v-btn-secondary">
                        <i class="fa fa-plus"></i><span>Add Team</span>
                    </a>
                </div>
            </div>
            <!-- TABLE -->
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive skin-light">
                        <table class="table table-default table-according allocation-table " >
                            <thead>
                                <tr>
                                    <th>#Team Name</th>
                                    <th></th>
                                    <th>Action</th>

                                    
                                </tr>
                            </thead>
                            <tbody>

                                <?php if(count($my_team_data)>0):
                                    $i=1;foreach($my_team_data as $mtd): ?>

                                <tbody>
                                    <tr class="active">
                                        <td>
                                            <label class="labels collaps-icon" for="milestoneOne-<?=$i?>"><i class="fa fa-chevron-up"></i></label>
                                            <input type="checkbox" name="milestoneOne" id="milestoneOne-<?=$i?>" data-toggle="toggle">
                                            <?=$mtd["team_name"]?>
                                        </td>
                                        
                                      

                                        <td>
                                           
                                          
                                        </td>


                                        <td>

                                            <a  href="#" data-target="#edit_company" data-toggle="modal" class="edit-team-button" data-id="<?=$mtd['id']?>" >
                        <i class="fa fa-pencil-alt " ></i><span></span>
                                                                     </a>


                                            <a href='<?=$this->Url->build([
                                        "action"=>"deleteteam",
                                        "controller"=>"Users",
                                        $mtd["id"]
                                        ]);?>' onclick="return confirm('Are You Sure');"><i class="fa fa-trash-alt"></i></a></td>

                                   



                                    </tr>
                                </tbody>

                                <tbody class="hide" style="display: none;">
                                   

                                        <tr>
                                            <td>1</td>
                                            <td><?=$mtd['project_manager_data']["name"]?></td>
                                            <td>Project Manager</td>
                                        </tr>

                                        <tr>
                                            <td>2</td>
                                            <td><?=$mtd['tech_lead_data']['name']?></td>
                                            <td>Tech Lead</td>
                                        </tr>

                                         <?php if(count($mtd["my_team_resources"])>0): ?>


                                        <?php $j=3;foreach($mtd["my_team_resources"] as $mtr):?>
                                    
                                    <tr>
                                        <td><?=$j;?></td>
                                        <td><?=$mtr["resource"]['name']?></td>
                                        <td>
                                            Resource
                                            
                                        </td>
                                       
                                    </tr>

                                <?php $j++;endforeach;endif?>


                                </tbody> 

                            <?php $i++;endforeach;endif; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <hr>

<div class="title text-center">
    
    Team TimeSheet

</div>


    <div class="page-content">
        <div class="container">
            <!-- FILTER -->
            <div class="row">
                <div class="col-md-4">
                     <div class="form-group">
                        
                        <?= $this->Html->link('<i class="fa fa-chevron-left"></i><span>Prev Week</span>','/myteam/prev/'.$pdate,['class' => 'v-btn v-btn-light','escape' => false]); ?>
                        <?= $this->Html->link('<i class="fa fa-chevron-right"></i><span>Next Week</span>','/myteam/next/'.$ndate,['class' => 'v-btn v-btn-light','escape' => false]); ?>
                    </div> 
                </div>
                <div class="col-md-4 offset-md-4">
                    <?=$this->form->select("team_filter",$team_select_data,["class"=>"form-control","id"=>"team_filter","empty"=>["all"=>"All"]]);?>
                    <!-- <select  id="" class="form-control">
                        <option value="all">All</option>
                        <option value="">Project Management App</option>
                        <option value="">Exigo</option>
                    </select> -->
                </div>
            </div>
            <!-- TABLE -->
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive skin-light ">
                        <table class="table table-default table-according allocation-table ">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th></th>
                                     <th><?= $data['first'];?></th>
                                    <th><?= $data['second'];?></th>
                                    <th><?= $data['third'];?></th>
                                    <th><?= $data['fourth'];?></th>
                                    <th><?= $data['fifth'];?></th>
                                    <th><?= $data['sixth'];?></th>

                                    <th>Total</th>
                                </tr>
                                 <input type="hidden" id="url" value="<?= WEBURL;?>">
                            </thead>
                            <tbody>
                             <?php if(count($project_user)>0): 
                                    foreach($project_user as $p): ?>
                                <tbody class="team_filter_data team-<?=$p["teamid"];?>">
                                    <tr class="active">
                                        <td  style="text-align: center;">
                                            <label class="labels collaps-icon" for="milestoneOne_<?= $p['id'];?>"><i class="fa fa-chevron-up"></i></label>
                                            <input type="checkbox" name="milestoneOne" id="milestoneOne_<?= $p['id'];?>" data-toggle="toggle">
                                            <?= $p['name']; ?>
                                        </td>

                                        <td><?=$p['last_login'];?></td>

                                        <?php $first=0;$second=0;$third=0;$fourth=0;$fifth=0;$sixth=0;foreach($p['projects'] as $project): ?>
                                        <?php if(count($project['miles'])>0): ?>

                                            <?php $first+=intval($project['miles']['first']);$second+=intval($project['miles']['second']);$third+=intval($project['miles']['third']);$fourth+=intval($project['miles']['fourth']);$fifth+=intval($project['miles']['fifth']);$sixth+=intval($project['miles']['sixth'])?>

                                         <?php endif;?>
                                          <?php endforeach;?> 
                                        <td></td>
                                        <td><input class="form-control aloc-input" type="text" readonly value="<?=$first?>"></td>
                                        <td><input class="form-control aloc-input" type="text" readonly value="<?=$second?>"></td>
                                        <td><input class="form-control aloc-input" type="text" readonly value="<?=$third?>"></td>
                                        <td><input class="form-control aloc-input" type="text" readonly value="<?=$fourth?>"></td>
                                        <td><input class="form-control aloc-input" type="text" readonly value="<?=$fifth?>"></td>
                                        <td><input class="form-control aloc-input" type="text" readonly value="<?=$sixth?>"></td>
                                      



                                        <td>
                                         <input class="form-control aloc-input" type="text" readonly value="<?=$first+$second+$third+$fourth+$fifth+$sixth?>">
                                           
                                        </td>

                                     

                                    </tr>
                                </tbody>
                                <tbody class="hide" style="display:none;">


                                     <?php if(count($p['projects'])<=0): ?>
                                        <td colspan="9" style="text-align: center;">
                                           
                                            <input type="checkbox" name="milestoneOne" id="milestoneOne_<?= $p['id'];?>" data-toggle="toggle">
                                            No Project Yet!!!!
                                        </td>
                                     <?php endif ?>


                                    <?php if(count($p['projects'])>0):
                                        $i =1;foreach($p['projects'] as $project): ?>
                                    <tr>
                                  <td><?= $i;?></td> 
                                        <td ><?= substr($project['project_name'],0,20);?></td>

                                        <td><?=$project['client_name']?></td>

                                       <?php if(count($project['miles'])>0): ?>
                                        <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="<?= $project['miles']['first'];?>" data-hrs="<?= $project['miles']['first'];?>" data-count="1"> 
                                            <i class="fas fa-comment-dots" onclick="ViewNotes(<?=$project['id']?>,<?=$p['id']?>,'<?= $data['first']?>');"></i>
                                        </td>
                                         <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="<?= $project['miles']['second'];?>" data-hrs="<?= $project['miles']['second'];?>" data-count="2"> 
                                            <i class="fas fa-comment-dots" onclick="ViewNotes(<?=$project['id']?>,<?=$p['id']?>,'<?= $data['second']?>');"></i>
                                        </td>
                                         <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="<?= $project['miles']['third'];?>" data-hrs="<?= $project['miles']['third'];?>" data-count="3"> 
                                            <i class="fas fa-comment-dots" onclick="ViewNotes(<?=$project['id']?>,<?=$p['id']?>,'<?= $data['third']?>');"></i>
                                        </td>
                                       <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="<?= $project['miles']['fourth'];?>" data-hrs="<?= $project['miles']['fourth'];?>" data-count="4"> 
                                            <i class="fas fa-comment-dots" onclick="ViewNotes(<?=$project['id']?>,<?=$p['id']?>,'<?= $data['fourth']?>');"></i>
                                        </td>
                                        <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="<?= $project['miles']['fifth'];?>" data-hrs="<?= $project['miles']['fifth'];?>" data-count="5"> 
                                            <i class="fas fa-comment-dots" onclick="ViewNotes(<?=$project['id']?>,<?=$p['id']?>,'<?= $data['fifth']?>');"></i>
                                        </td>
                                        <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="<?= $project['miles']['sixth'];?>" data-hrs="<?= $project['miles']['sixth'];?>" data-count="6"> 
                                            <i class="fas fa-comment-dots" onclick="ViewNotes(<?=$project['id']?>,<?=$p['id']?>,'<?= $data['sixth']?>');"></i>
                                        </td>
                                   
                                        <td>
                                            <input type="text" required class="form-control aloc-input" placeholder="hrs" disabled value="<?= ($project['miles']['first']+$project['miles']['second']+$project['miles']['third']+$project['miles']['fourth']+$project['miles']['fifth']+$project['miles']['sixth']); ?>" data-count="7"> 
                                        
                                        </td> 

                                    <?php else: ?>

                                        <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="0" data-count="1"> 
                                            <i class="fas fa-comment-dots" onclick="ViewNotes(<?=$project['id']?>,<?=$p['id']?>,'<?= $data['first']?>');"></i>
                                        </td>
                                         <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="0"  data-count="2"> 
                                            <i class="fas fa-comment-dots" onclick="ViewNotes(<?=$project['id']?>,<?=$p['id']?>,'<?= $data['second']?>');"></i>
                                        </td>
                                         <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="0"  data-count="3"> 
                                            <i class="fas fa-comment-dots" onclick="ViewNotes(<?=$project['id']?>,<?=$p['id']?>,'<?= $data['third']?>');"></i>
                                        </td>
                                       <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="0" data-count="4"> 
                                            <i class="fas fa-comment-dots" onclick="ViewNotes(<?=$project['id']?>,<?=$p['id']?>,'<?= $data['fourth']?>');"></i>
                                        </td>
                                        <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="0"  data-count="5"> 
                                            <i class="fas fa-comment-dots" onclick="ViewNotes(<?=$project['id']?>,<?=$p['id']?>,'<?= $data['fifth']?>');"></i>
                                        </td>
                                        <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="0" data-count="6">
                                            <i class="fas fa-comment-dots" onclick="ViewNotes(<?=$project['id']?>,<?=$p['id']?>,'<?= $data['sixth']?>');"></i> 
                                        </td>
                                   
                                        <td>
                                            <input type="text" class="form-control aloc-input" placeholder="hrs" disabled value="0" data-count="7"> 
                                        
                                        </td> 

                                    <?php endif ?>
                                    
                                    </tr>
                                    <?php $i++; endforeach; endif;?>
                                </tbody>

                                <?php endforeach; endif; ?>      

                                <?php if(count($project_user)<=0):?> 

                                    <tbody>
                                    <tr class="active">
                                        <td colspan="9" style="text-align: center;padding: 1rem 1rem;">
                                           

                                            <div class="jumbotron">
                                                  <h1 class="display-4">No Data to Show!</h1>
                                                 <!--  <p class="lead">
                                                    Nothing to Show
                                                  </p> -->
                                                </div>

                                        </td>
                                    </tr>
                                </tbody>

                                <?php endif; ?>    



                            </tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</section>

<!-- ADD COMPANY MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="add_company">
  <div class="modal-dialog" role="document">

     <?= $this->form->create(null,[
                    'url' => [
                        'Controller' => 'Users',
                        'action' => 'addteam'
                    ],
                    
                ],
            )
            ?>


    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Team</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="content">
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Team Name</label>
                     <div class="adon-group">
                        <span class="icon ft-primary"><i class="fa fa-user"></i></span>

                        <?=$this->form->text("team_name",[
                            "class"=>"form-control",
                            "placeholder"=>"Enter Team Name",
                            "required"=>true,
                            "autocomplete"=>"off"
                        ])?>
                       <!--  <input type="text" name="team_name" class="form-control" placeholder="Enter Team name"> -->
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Project Manager</label>
                     <div class="adon-group">
                     <span class="icon ft-primary"><i class="fa fa-user"></i></span>
                        <!-- <input type="text" class="form-control" placeholder=""> -->

                        <?=$this->form->select("project_manager",$project_manager,[
                            "class"=>"form-control",
                            "required"=>true,
                            'empty' => 'Please Select'
                        ])?>

                    </div>
                </div>
            </div>
           
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Tech Lead</label>
                     <div class="adon-group">
                     <span class="icon ft-primary"><i class="fa fa-user"></i></span>
                        <!-- <input type="text" class="form-control" placeholder=""> -->

                        <?=$this->form->select("tech_lead",$project_manager,[
                            "class"=>"form-control",
                            "required"=>true,
                            'empty' => 'Please Select',
                        ])?>

                    </div>
                </div>
            </div>


            <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Resource</label>
                     <div class="adon-group">
                     <span class="icon ft-primary"><i class="fa fa-user"></i></span>
                        <!-- <input type="text" class="form-control" placeholder=""> -->

                        <?=$this->form->select("resources",$resource_arr,[
                            "class"=>"form-control custom-multiselect",
                            "required"=>true,
                            "multiple"=>true,
                            
                        ])?>

                    </div>
                </div>
            </div>





         
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
        <button class="v-btn v-btn-primary" type="submit">Save</button>
      </div>
    </div>

    <?= $this->form->end() ?>



  </div>
</div>




<!-- ADD COMPANY MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="edit_company">
  <div class="modal-dialog" role="document">

     <?= $this->form->create(null,[
                    'url' => [
                        'Controller' => 'Users',
                        'action' => 'editteam'
                    ],
                    
                ],
            )
            ?>


            <input type="hidden" id="edit_my_team_id" name="id">


    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Team</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="content">
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Team Name</label>
                     <div class="adon-group">
                        <span class="icon ft-primary"><i class="fa fa-user"></i></span>

                        <?=$this->Form->text("team_name",[
                            "class"=>"form-control",
                            "placeholder"=>"Enter Team Name",
                            "required"=>true,
                            "id"=>"edit_my_team_name",
                            "autocomplete"=>"off"
                        ])?>
                       <!--  <input type="text" name="team_name" class="form-control" placeholder="Enter Team name"> -->
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Project Manager</label>
                     <div class="adon-group">
                     <span class="icon ft-primary"><i class="fa fa-user"></i></span>
                        <!-- <input type="text" class="form-control" placeholder=""> -->

                        <?=$this->form->select("project_manager",$project_manager,[
                            "class"=>"form-control",
                            "required"=>true,
                            'empty' => 'Please Select',
                            "id"=>"edit_project_mang"
                        ])?>

                    </div>
                </div>
            </div>
           
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Tech Lead</label>
                     <div class="adon-group">
                     <span class="icon ft-primary"><i class="fa fa-user"></i></span>
                        <!-- <input type="text" class="form-control" placeholder=""> -->

                        <?=$this->form->select("tech_lead",$project_manager,[
                            "class"=>"form-control",
                            "required"=>true,
                            'empty' => 'Please Select',
                            "id"=>"edit_tech_lead"
                        ])?>

                    </div>
                </div>
            </div>


            <div class="form-group row" id="resource_parent_div">
                <div class="col-md-12">
                    <label for="">Resource</label>
                     <div class="adon-group">
                     <span class="icon ft-primary"><i class="fa fa-user"></i></span>
                        <!-- <input type="text" class="form-control" placeholder=""> -->

                        <?=$this->form->select("resources",$edit_res_arr,[
                            "class"=>"form-control",
                            "required"=>true,
                            "multiple"=>true,
                            "id"=>"edit_resource_id"
                        ])?>

                    </div>
                </div>
            </div>





         
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
        <button class="v-btn v-btn-primary" type="submit">Update</button>
      </div>
    </div>

    <?= $this->form->end() ?>



  </div>
</div>

<div class="modal fade" id="model_show" role="dialog">
    
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>
    

$("#team_filter").change(function(){

    var selected = $(this).val();
    console.log(selected)
    if(selected=="all"){

        $(".team_filter_data").show();

    }
    else{

         $(".team_filter_data").hide();

         $(`.team-${selected}`).show();

    }

});


    $(".edit-team-button").click(function(){




         // $("#edit_resource_id").multiselect("reload");

        var id = $(this).attr("data-id");
        // console.log(id);

        $("#edit_my_team_id").val(id);
        
        var url = 
       $.ajax({
               type:'GET',
               url:"<?= $this->Url->build('/users/getteameditdata/'); ?>"+id,
               beforeSend: function ()
               {
               },
               success:function(data){
               
                
              
                  var response = $.parseJSON(data); 
                  console.log(response);
        

                  $("#edit_my_team_name").val(response["team_name"]);
                  $("#edit_project_mang").val(response["project_manager"]);
                  $("#edit_tech_lead").val(response["tech_lead"]);


                   $("#edit_resource_id option").removeAttr("selected")
                   $("#resource_parent_div input").prop("checked",false);


                  for(var i=0;i<response["my_team_resources"].length;i++){
                    var team_id = response["my_team_resources"][i]["resid"];
                   
                    $("#edit_resource_id option[value="+team_id+"]").attr("selected","selected")





                     $("#resource_parent_div input[value="+team_id+"]").prop("checked",true);


                     

                  }




                  $("#edit_resource_id").multiselect("reload");
                  



                       
               }
           });



    });

</script>

<script>
    function ViewNotes(projectid,userid,date) {
        $.ajax({
            url:"<?= $this->Url->build(['controller'=>'Users', 'action'=>'empNotesData']) ?>/"+projectid+"/"+userid+"/"+date,
			method:"get",
         success : function(resp){
             $('#model_show').html(resp);
             $('#model_show').modal('show');
			 	
            }
       });
    }
</script>