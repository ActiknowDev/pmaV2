<?php $session = new \Cake\Http\Session();
        $userSession = $session->read('data');
        ?>

<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-building"></i></span>Allocation Table
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
                <div class="col-md-4">
                     <div class="form-group">
                        
                        <?= $this->Html->link('<i class="fa fa-chevron-left"></i><span>Prev Week</span>','/prev-week/'.$pdate,['class' => 'v-btn v-btn-light','escape' => false]); ?>
                        <?= $this->Html->link('<i class="fa fa-chevron-right"></i><span>Next Week</span>','/next-week/'.$ndate,['class' => 'v-btn v-btn-light','escape' => false]); ?>
                    </div> 
                </div>
                <!-- <div class="col-md-4 offset-md-4">
                    <select name="" id="" class="form-control">
                        <option value="">Select Project</option>
                        <option value="">Project Management App</option>
                        <option value="">Exigo</option>
                    </select>
                </div> -->
            </div>
            <!-- TABLE -->
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive skin-light timesheet-table-container">

                        <table class="table table-default table-according allocation-table table-timesheet">
                            <thead>
                                <tr>
                                    <th>PROJECT TITLE</th>
                                    <th style="width:250px">CLIENT NAME</th>
                                    <th><?= $data['monday'];?></th>
                                    <th><?= $data['tuesday'];?></th>
                                    <th><?= $data['wednesday'];?></th>
                                    <th><?= $data['thursday'];?></th>
                                    <th><?= $data['friday'];?></th>
                                    <th><?= $data['saturday'];?></th>
                                    <th title="Week Total | Assigned Hours " class="text-right pr-4">WT | AH</th>
                                </tr>
                                <input type="hidden" id="url" value="<?= WEBURL;?>">
                            </thead>
                            <tbody>
                                <?php if(count($projects)>0): 
                                    foreach($projects as $p): ?>
                                <tbody>
                                    <tr class="active">
                                        <td style="text-align: left;">
                                            <label class="labels collaps-icon" for="milestoneOne_<?= $p['id'];?>"><i class="fa fa-chevron-up"></i></label>
                                            <input type="checkbox" name="milestoneOne" id="milestoneOne_<?= $p['id'];?>" data-toggle="toggle">
                                             <?php if(($userSession['role'] != 3) || (($userSession['role']==3) && ($userSession['role_name'] != 'user'))) { ?>
                                            <?= $this->Html->link(substr($p['project_name'],0,20),'/edit-project/'. $p['id'],['class' => 'link']); ?>
                                        <?php } else{ echo substr($p['project_name'],0,20); } ?>
                                           
                                        </td>
                                        <td style="text-align:left;">
                                            
                                            <?=$p['client_name']?>
                                        </td>
                                        <?php 
                                        $monday = $tuesday = $wednesday = $thrusday = $friday = $saturday = $alt = 0;

                                        if (count($p['miles']) > 0):
                                            foreach ($p['miles'] as $pm):
                                                $monday += ($pm['mtime'] !== '-' && $pm['mtime'] !== '') ? (float)$pm['mtime'] : 0;
                                                $tuesday += ($pm['tutime'] !== '-' && $pm['tutime'] !== '') ? (float)$pm['tutime'] : 0;
                                                $wednesday += ($pm['wtime'] !== '-' && $pm['wtime'] !== '') ? (float)$pm['wtime'] : 0;
                                                $thrusday += ($pm['thtime'] !== '-' && $pm['thtime'] !== '') ? (float)$pm['thtime'] : 0;
                                                $friday += ($pm['ftime'] !== '-' && $pm['ftime'] !== '') ? (float)$pm['ftime'] : 0;
                                                $saturday += ($pm['stime'] !== '-' && $pm['stime'] !== '') ? (float)$pm['stime'] : 0;
                                                $alt += is_numeric($pm['alot']) ? (float)$pm['alot'] : 0;
                                            endforeach;
                                        endif;
                                        ?>
                                        <td><input class="form-control aloc-input fillhrs" type="text"

                                         <?php if($monday>0):?>
                                            <?="value=".$monday ?>
                                        <?php else: ?>
                                            <?="value=-"?>
                                        <?php endif; ?>

                                         disabled></td>
                                        <td><input class="form-control aloc-input fillhrs" type="text"
                                    
                                        <?php if($tuesday>0):?>
                                            <?="value=".$tuesday ?>
                                        <?php else: ?>
                                            <?="value=-"?>
                                        <?php endif; ?>
                                          disabled></td>

                                        <td><input class="form-control aloc-input fillhrs" type="text"
                                         
                                         <?php if($wednesday>0):?>
                                            <?="value=".$wednesday ?>
                                        <?php else: ?>
                                            <?="value=-"?>
                                        <?php endif; ?>

                                          disabled></td>

                                        <td><input class="form-control aloc-input fillhrs" type="text"
                                         
                                         <?php if($thrusday>0):?>
                                            <?="value=".$thrusday ?>
                                        <?php else: ?>
                                            <?="value=-"?>
                                        <?php endif; ?>


                                          disabled></td>
                                        
                                        <td><input class="form-control aloc-input fillhrs" type="text"
                                         
                                         <?php if($friday>0):?>
                                            <?="value=".$friday ?>
                                        <?php else: ?>
                                            <?="value=-"?>
                                        <?php endif; ?>


                                          disabled></td>

                                        <td><input class="form-control aloc-input fillhrs" type="text" 

                                            <?php if($saturday>0):?>
                                            <?="value=".$saturday ?>
                                        <?php else: ?>
                                            <?="value=-"?>
                                        <?php endif; ?> 

                                            disabled></td>


                                        <td>
                                            
                                               
                                            <input type="text" class="form-control aloc-input" disabled 

                                             <?php $total_val1 = ($monday+$tuesday+$wednesday+$thrusday+$friday+$saturday);?>

                                             <?php if($total_val1>0): ?>

                                             <?="value=".$total_val1;?>

                                         <?php else: ?>

                                            <?="value=-"?>

                                         <?php endif;?>

                                            >

                                             <input type="text" class="form-control aloc-input" disabled 
                                             <?php if($alt>0):?>
                                            <?="value=".$alt ?>
                                        <?php else: ?>
                                            <?="value=-"?>
                                        <?php endif; ?> 


                                             >
                                            
                                        </td>

                                         

                                    </tr>
                                </tbody>
                                <tbody class="hide" style="display:none;">
                                   <?php if(count($p['miles'])>0):
                                        $i =1;foreach($p['miles'] as $pm): ?>
                                    <tr>
                                        <td><?= $i;?></td>
                                        <td style="text-align: left;"><?= $pm['title'];?></td>
                                        <td>
                                            <input type="number" min="0" max="24" class="form-control validate-hrs aloc-input fillhrs hrs_<?= $pm['id'];?> hour1_<?= $pm['id'];?>" placeholder="hrs" data-id="<?= $pm['id'];?>" data-day="<?= $pm['monday'];?>" value="<?= $pm['mtime'];?>" data-hrs="<?= $pm['mtime'];?>" data-count="1"> 
                                            <a href="javascript:void(0)" title="<?= !empty($pm['mnotes']) ?  $pm['mnotes'] : 'notes' ?>" data-id="<?= $pm['id']; ?>" data-day="<?= $pm['monday']; ?>" value="<?= $pm['mtime']; ?>" data-hrs="<?= $pm['mtime']; ?>" class="create-notes note_<?= $pm['id'] . '_' . $pm['monday'] ?>"><i class="fas fa-comment-dots"></i></a>
                                        </td>
                                        <td> 
                                            <input type="number" min="0" max="24" class="validate-hrs form-control aloc-input fillhrs hrs_<?= $pm['id'];?> hour2_<?= $pm['id'];?>" placeholder="hrs" data-id="<?= $pm['id'];?>" data-day="<?= $pm['tuesday'];?>" value="<?= $pm['tutime'];?>" data-hrs="<?= $pm['tutime'];?>" data-count="2"> 
                                            <a href="javascript:void(0)" title="<?= !empty($pm['tunotes']) ? $pm['tunotes'] : 'notes' ?>" data-id="<?= $pm['id']; ?>" data-day="<?= $pm['tuesday']; ?>" value="<?= $pm['tutime']; ?>" data-hrs="<?= $pm['tutime']; ?>" class="create-notes note_<?= $pm['id'] . '_' . $pm['tuesday'] ?>"><i class="fas fa-comment-dots"></i></a>
                                        </td>
                                        <td>
                                            <input type="number" min="0" max="24" class="validate-hrs form-control aloc-input fillhrs hrs_<?= $pm['id'];?> hour3_<?= $pm['id'];?>" placeholder="hrs" data-id="<?= $pm['id'];?>" data-day="<?= $pm['wednesday'];?>" value="<?= $pm['wtime'];?>" data-hrs="<?= $pm['wtime'];?>" data-count="3"> 
                                            <a href="javascript:void(0)" title="<?= !empty($pm['wnotes']) ? $pm['wnotes'] : 'notes' ?>" data-id="<?= $pm['id']; ?>" data-day="<?= $pm['wednesday']; ?>" value="<?= $pm['wtime']; ?>" data-hrs="<?= $pm['wtime']; ?>" class="create-notes note_<?= $pm['id'] . '_' . $pm['wednesday'] ?>"><i class="fas fa-comment-dots"></i></a>
                                        </td>
                                        <td>
                                            <input type="number" min="0" max="24" class="validate-hrs form-control aloc-input fillhrs hrs_<?= $pm['id'];?> hour4_<?= $pm['id'];?>" placeholder="hrs" data-id="<?= $pm['id'];?>" data-day="<?= $pm['thursday'];?>" value="<?= $pm['thtime'];?>" data-hrs="<?= $pm['thtime'];?>" data-count="4"> 
                                            <a href="javascript:void(0)" title="<?= !empty($pm['thnotes']) ? $pm['thnotes'] : 'notes' ?>" data-id="<?= $pm['id']; ?>" data-day="<?= $pm['thursday']; ?>" value="<?= $pm['thtime']; ?>" data-hrs="<?= $pm['thtime']; ?>" class="create-notes note_<?= $pm['id'] . '_' . $pm['thursday'] ?>"><i class="fas fa-comment-dots"></i></a>
                                        </td>
                                        <td>
                                            <input type="number" min="0" max="24" class="form-control validate-hrs aloc-input fillhrs hrs_<?= $pm['id'];?> hour5_<?= $pm['id'];?>" placeholder="hrs" data-id="<?= $pm['id'];?>" data-day="<?= $pm['friday'];?>" value="<?= $pm['ftime'];?>" data-hrs="<?= $pm['ftime'];?>" data-count="5"> 
                                            <a href="javascript:void(0)" title="<?= !empty($pm['fnotes']) ? $pm['fnotes'] : 'notes' ?>" data-id="<?= $pm['id']; ?>" data-day="<?= $pm['friday']; ?>" value="<?= $pm['ftime']; ?>" data-hrs="<?= $pm['ftime']; ?>" class="create-notes note_<?= $pm['id'] . '_' . $pm['friday'] ?>"><i class="fas fa-comment-dots"></i></a>
                                        </td>
                                        <td>
                                            <input type="number" min="0" max="24" class="form-control validate-hrs aloc-input fillhrs hrs_<?= $pm['id'];?> hour6_<?= $pm['id'];?>" placeholder="hrs" data-id="<?= $pm['id'];?>" data-day="<?= $pm['saturday'];?>" value="<?= $pm['stime'];?>" data-hrs="<?= $pm['stime'];?>" data-count="6"> 
                                            <a href="javascript:void(0)" title="<?= !empty($pm['snotes']) ? $pm['snotes'] : 'notes' ?>" data-id="<?= $pm['id']; ?>" data-day="<?= $pm['saturday']; ?>" value="<?= $pm['stime']; ?>" data-hrs="<?= $pm['stime']; ?>" class="create-notes note_<?= $pm['id'] . '_' . $pm['saturday'] ?>"><i class="fas fa-comment-dots"></i></a>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control  aloc-input totalmgr_<?= $pm['id'];?> hour7_<?= $pm['id'];?>" placeholder="hrs" disabled value="<?= $pm['used']; ?>" data-count="7"> 
                                            <input type="text" class="form-control aloc-input" placeholder="hrs" disabled value="<?= $pm['alot'];?>"> 
                                        </td>
                                    </tr>
                                    <?php $i++; endforeach; endif;?>
                                </tbody>  
                                <?php endforeach; endif; ?>       
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="create_notes" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">CREATE NOTES</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <?php echo $this->Form->create(
                    Null,
                    [
                        'id' => 'form',
                        'url' => [
                            'Controller' => 'Users',
                            'action' => 'allotmentNotes',
                        ]
                    ]
                ) ?>
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="" rows="2">Notes</label>
                                <?php echo $this->Form->control(
                                    'notes',
                                    [
                                        'id' => 'notes',
                                        'label' => false,
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter notes',
                                        'required' => 'true'
                                    ]
                                );
                                ?>
                                <input type="hidden" id="milestoneid" name="milestoneid">
                                <input type="hidden" id="timesheetday" name="timesheetday">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="button" class="v-btn v-btn-base" data-dismiss="modal">CLOSE</button>
                            <button id="report-save-btn" type="button" class="v-btn v-btn-primary add_note">ADD NOTES</button>
                        </div>
                    </div>
                </div>
                <?php $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
     $("input[type=text]").on("focus", function() {
      if($(this).val()==0)
        $(this).val('');
});
</script>

<script>
    $(document).ready(function() {
        $("#report-save-btn").click(function() {
            //  console.log('hi');
            var form_data = new FormData();
            var token = $('input[name="_csrfToken"]').attr('value');
            var note = document.getElementById("notes").value;
            var milestone_id = document.getElementById("milestoneid").value;
            var timesheetday_id = document.getElementById("timesheetday").value;

            form_data.append("notes", note);
            form_data.append("milestoneid", milestone_id);
            form_data.append("timesheetday", timesheetday_id);

            //  console.log(form_data.get("notes"));
            //  console.log(form_data.get("milestoneid"));
            //  console.log(form_data.get("timesheetday"));

            $.ajax({
                type: 'POST',
                // url: "http://pma.actiknow.com/Users/notespop",
                url: "<?= $this->Url->build('/Users/notespop') ?>",
                data: form_data,
                headers: {
                    'X-CSRF-Token': token
                },
                processData: false,
                contentType: false,
                success: function(data) {
                    var obj = JSON.parse(data);
                    console.log(data);
                    if (obj.status == 'success') {
                        $('.hrs_' + obj.milestone_id + '_' + obj.work_date).val(obj.time_used);
                        $('.note_' + obj.milestone_id + '_' + obj.work_date).attr('title', obj.note);

                    } else {
                        alert("failed");
                    }
                    $('#create_notes').modal('hide');
                    $('#form').trigger('reset');

                }

            });


        });
    });
</script>