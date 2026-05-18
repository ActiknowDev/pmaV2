<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-building"></i></span>Users Daily Report
                    </div>
                </div>
            </div>
        </div>
    </div>
   

<!-- <div class="title text-center">
    
    

</div> -->


    <div class="page-content">
        <div class="container">
            <!-- FILTER -->
            <div class="row">
                <div class="col-md-4">
                     <div class="form-group">
                        
                        <?= $this->Html->link('<i class="fa fa-chevron-left"></i><span>Prev Week</span>','/report2/prev/'.$pdate,['class' => 'v-btn v-btn-light','escape' => false]); ?>
                        <?= $this->Html->link('<i class="fa fa-chevron-right"></i><span>Next Week</span>','/report2/next/'.$ndate,['class' => 'v-btn v-btn-light','escape' => false]); ?>
                    </div> 
                </div>
                <div class="col-md-4 offset-md-4">
                    <?php $select_data = ["Incomplete Timesheet", "Complete Timesheet" ]; ?>
                    <?=$this->form->select("team_filter",$select_data,["class"=>"form-control","id"=>"team_filter","empty"=>["all"=>"All"]]);?>
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
                                    foreach($project_user as $p): 
                                        $first=0;$second=0;$third=0;$fourth=0;$fifth=0;$sixth=0;foreach($p['projects'] as $project):  
                                        if(count($project['miles'])>0): 
                                            $first+=intval($project['miles']['first']);
                                            $second+=intval($project['miles']['second']);
                                            $third+=intval($project['miles']['third']);
                                            $fourth+=intval($project['miles']['fourth']);
                                            $fifth+=intval($project['miles']['fifth']);
                                            $sixth+=intval($project['miles']['sixth']);
                                        endif;
                                    endforeach;
                                        $totalTimeProject = $first+$second+$third+$fourth+$fifth+$sixth;
                                        if($totalTimeProject < 40){
                                            $filter = 0;
                                        }else if($totalTimeProject >= 40){
                                            $filter = 1;
                                        }
                                    ?>

                                <tbody class="shra user_filter_data user_<?= $filter ?>">
                                    <tr class="active">
                                        <td  style="text-align: center;">
                                            <label class="labels collaps-icon" for="milestoneOne_<?= $p['id'];?>"><i class="fa fa-chevron-up"></i></label>
                                            <input type="checkbox" name="milestoneOne" id="milestoneOne_<?= $p['id'];?>" data-toggle="toggle">
                                            <?= $p['name']; ?>
                                        </td>

                                        <td><?=$p['last_login'];?></td>

                                        
                                        
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
                                        </td>
                                         <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="<?= $project['miles']['second'];?>" data-hrs="<?= $project['miles']['second'];?>" data-count="2"> 
                                        </td>
                                         <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="<?= $project['miles']['third'];?>" data-hrs="<?= $project['miles']['third'];?>" data-count="3"> 
                                        </td>
                                       <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="<?= $project['miles']['fourth'];?>" data-hrs="<?= $project['miles']['fourth'];?>" data-count="4"> 
                                        </td>
                                        <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="<?= $project['miles']['fifth'];?>" data-hrs="<?= $project['miles']['fifth'];?>" data-count="5"> 
                                        </td>
                                        <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="<?= $project['miles']['sixth'];?>" data-hrs="<?= $project['miles']['sixth'];?>" data-count="6"> 
                                        </td>
                                   
                                        <td>
                                            <input type="text" required class="form-control aloc-input" placeholder="hrs" disabled value="<?= ($project['miles']['first']+$project['miles']['second']+$project['miles']['third']+$project['miles']['fourth']+$project['miles']['fifth']+$project['miles']['sixth']); ?>" data-count="7"> 
                                        
                                        </td> 

                                    <?php else: ?>

                                        <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="0" data-count="1"> 
                                        </td>
                                         <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="0"  data-count="2"> 
                                        </td>
                                         <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="0"  data-count="3"> 
                                        </td>
                                       <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="0" data-count="4"> 
                                        </td>
                                        <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="0"  data-count="5"> 
                                        </td>
                                        <td>
                                            <input disabled type="text" class="form-control aloc-input fillhrs" placeholder="hrs" value="0" data-count="6"> 
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


<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>
    
    $("#team_filter").change(function(){

        var selected = $(this).val();
        console.log(selected)
        if(selected=="all"){

            $(".user_filter_data").show();

        }
        else{

            $(".user_filter_data").hide();

            $(`.user_${selected}`).show();

        }

    });

</script>
