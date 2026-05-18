<?php
// error_reporting(E_ALL);
ini_set('display_errors', 0);
 ?>
<div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
        <h6 class="heading ft-secondary" style="font-weight:600; font-size:15px;">Notes</h6>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
        <div class="modal-body">
          <table class="table table-default block table-bordered">
            <thead>
                       <tr>
                          <th>Milestone</th>
                          <th>Notes</th>
                      </tr>
            </thead>
            <tbody>
            <?php if (count($data) > 0) :
                                    foreach ($data as $data) : ?>
                <tr>
                  <td style="font-weight:600;"><?=$data['title']; ?></td>
                  <td><?=$data['notes']; ?></td>
                </tr>
                <?php endforeach;
                                endif; ?>
            </tbody>
            <table>
        </div>
      </div>
      
    </div>