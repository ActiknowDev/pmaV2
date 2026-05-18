<?php
// error_reporting(E_ALL);
ini_set('display_errors', 0);
 ?>
<div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
        <h6 class="heading ft-secondary" >Timesheet and Allocation Report- <?php echo $projects[0]['username'] ?></h6>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
        <div class="modal-body">
          <table class="table table-default table-striped block table-bordered">
            <thead>
                       <tr>
                          <th rowspan="1" colspan="1"></th>
                          <th style="text-align:center;" colspan="2">Billable</th>
                          <th style="text-align:center;" colspan="2">Non Billable</th>
                      </tr>
                <tr>
                <th>Project Name</th>
                <th title="Week Total | Assigned Hours ">Allocated Hours</th>
                                    <th title="Week Total | Work Hours ">Timesheet Hours</th>
                                    <th title="Week Total | Assigned Hours ">Allocated Hours</th>
                                    <th title="Week Total | Work Hours ">Timesheet Hours</th>
                <tr>
            </thead>
            <tbody>
            <?php if (count($projects) > 0) :
                                    foreach ($projects as $p) : ?>
                <tr>
                    <td><?= $this->Html->link(substr($p['project_name'], 0, 20), '/edit-project/' . $p['project_id'], ['class' => 'link']); ?>
                     </td>
                    <?php
                                        $alt = 0;
                                        $utu=0;
                                        $un_alt = 0;
                                        $un_utu=0;
                                        
                                            if($p['bill']=='Billable'){
                                                $alt = $alt + (int)$p['time_slot'][0]['time_slot'];
                                                $utu = $utu + (int)$p['time_used'];
                                            } else {
                                                $un_alt = $alt + (int)$p['time_slot'][0]['time_slot'];
                                                $un_utu = $utu + (int)$p['time_used'];
                                            }
                                                
                                            ?>

                    <td><?php if ($alt > 0) : ?> <b class="bold-data"><?= $alt; ?></b> <?php else : ?> <?= "-" ?> <?php endif; ?></td>
                    <td><?php if ($utu > 0) : ?> <b class="bold-data"><?= $utu; ?> </b><?php else : ?> <?= "-" ?> <?php endif; ?></td>
                    <td><?php if ($un_alt > 0) : ?> <b class="bold-data"><?= $un_alt; ?></b> <?php else : ?> <?= "-" ?> <?php endif; ?></td>
                    <td><?php if ($un_utu > 0) : ?> <b class="bold-data"><?= $un_utu; ?></b> <?php else : ?> <?= "-" ?> <?php endif; ?></td>
                </tr>
                <?php endforeach;
                                endif; ?>
            </tbody>
            <table>
        </div>
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> -->
      </div>
      
    </div>