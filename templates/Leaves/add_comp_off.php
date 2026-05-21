<section class="page page-dashboard">
  <!-- PAGE-TITLE -->
  <div class="page-title skin-light">
    <div class="container">
      <div class="row">
        <div class="col-6">
          <div class="heading ft-secondary">
            <span class="icon"><i class="fa fa-user-tie"></i></span>Comp-Off Management
          </div>
        </div>
        <div class="col-6">
          <div class="actions-ctrl text-md-right">
            <a href="#" data-target="#addCompOff" data-toggle="modal" class="v-btn v-btn-primary">
              <!-- <i class="fa fa-list"></i> -->
              <span>Add Comp-Off</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="page-tab">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <ul class="v-tab">

            <li>
              <?= $this->Html->link('My Leaves', [
                'controller' => 'Leaves',
                'action' => 'index'
              ]); ?>
            </li>

            <li>
              <?= $this->Html->link('Requested Leaves', [
                'controller' => 'Leaves',
                "action" => 'requestleave'
              ]); ?>
            </li>

            <?php
            if (($userSession['role'] != 3) || ($userSession['role'] == 3 &&
              array_intersect($userSession['role_name'], array(12)))) {
            ?>
              <li>
                <?= $this->Html->link('All Leaves', [
                  'controller' => 'Leaves',
                  'action' => 'allLeaves'
                ]) ?>
              </li>
            <?php } ?>

            <li class="active">
              <?= $this->Html->link('Comp-Off', [
                'controller' => 'Leaves',
                'action' => 'addCompOff'
              ]); ?>
            </li>

            <li>
              <?= $this->Html->link('Requested Comp-Off', [
                'controller' => 'Leaves',
                'action' => 'requestCompOff'
              ]); ?>
            </li>


          </ul>
        </div>
      </div>
    </div>
  </div>


  <!-- PAGE-CONTENT -->
  <div class="page-content">
    <div class="container">
      <?= $this->Flash->render() ?>

      <div class="row">
        <div class="col-md-12">
          <div class="block">
            <div class="header">
              <h4 class="title">Comp-Off Management</h4>

            </div>
            <div class="content ">
              <table id="example1" style="width:100%" class="table table-default table-striped 
              block">
                <thead>
                  <tr>
                    <th>Description</th>
                    <th>Requested Date</th>
                    <th>Approved By</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($compData as $value) :
                  ?>
                    <tr>
                      <td><?= $value['description'] ?></td>
                      <td><?= date('d-m-Y', strtotime($value['request_date'])) ?></td>
                      <td><?= $this->CompApproveName->approvedName($value['approved_by']) ?></td>
                      <td>
                        <?php
                        if ($value['status'] == 'Cancelled' || $value['status'] == 'Rejected') :
                        ?>
                          <span class="badge badge-danger">
                            <?= $value['status'] ?>
                          </span>
                        <?php
                        endif;
                        ?>
                        <?php
                        if ($value['status'] == 'Approved') :
                        ?>
                          <span class="badge badge-success">
                            <?= $value['status'] ?>
                          </span>
                        <?php
                        endif;
                        ?>
                        <?php
                        if ($value['status'] == 'Pending') :
                        ?>
                          <span class="badge badge-info">
                            <?= $value['status'] ?>
                          </span>
                        <?php
                        endif;
                        ?>
                      </td>
                      <?php
                      if (!in_array($value['status'], ['Approved', 'Cancelled'])) :
                      ?>
                        <td>
                          <a href="#" data-target="#editCompOff" onclick="editCompOff(<?= $value['id'] ?>)" data-toggle="modal" class="icon icon-sm icon-secondary">
                            <i class="fa fa-pencil-alt"></i>
                          </a>
                        </td>
                      <?php else :
                        echo "<td>--</td>";
                      endif;
                      ?>
                    </tr>
                  <?php
                  endforeach;
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Start Comp off -->

<div class="modal fade" tabindex="-1" role="dialog" id="addCompOff">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <!-- <?= $this->Form->create($leave, ['url' => [
        'Controller' => 'Leaves',
        'action' => 'addCompOff'
      ]]) ?> -->
      <?= $this->Form->create(null, [
          'url' => [
              'controller' => 'Leaves',
              'action' => 'addCompOff'
          ]
      ]) ?>

      <div class="modal-header">
        <h5 class="modal-title">Add Comp-Off</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="content">

          <div class="form-group row">
            <div class="col-md-12">
              <label for="">Date</label>
              <div class="adon-group">
                <span class="icon ft-primary">
                  <i class="fa fa-calendar-alt"></i>
                </span>
                <?= $this->Form->control('request_date', [
                  'required' => true,
                  'autocomplete' => 'off',
                  'label' => false,
                  'id' => 'reqDate',
                  'type' => 'text',
                  'required' => true,
                  'class' => 'form-control datepicker'
                ]); ?>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <label for="">Description</label>
              <?= $this->Form->control('description', [
                'required' => true,
                'type' => 'textarea',
                'label' => false,
                'required' => true,
                'class' => 'form-control'
              ]); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="v-btn v-btn-base" data-dismiss="modal" aria-label="Close">Close</button>
        <button class="v-btn v-btn-primary" type="submit" onclick="setTimeout(() => {
                    this.disabled = true;
                    }, 1);">Submit</a>
      </div>

      <?= $this->Form->end() ?>

    </div>
  </div>
</div>

<!-- End Comp off -->

<!-- Edit comp-off -->

<div class="modal fade" tabindex="-1" role="dialog" id="editCompOff">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?= $this->Form->create(null, ['url' => [
        'Controller' => 'Leaves',
        'action' => 'editCompOff'
      ]]) ?>

      <div class="modal-header">
        <h5 class="modal-title">Edit Comp-Off</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="content">
          <input type="hidden" id="compOffId" name="compOffId">
          <div class="form-group row">
            <div class="col-md-12">
              <label for="">Date</label>
              <div class="adon-group">
                <span class="icon ft-primary">
                  <i class="fa fa-calendar-alt"></i>
                </span>
                <?= $this->Form->control('request_date', [
                  'required' => true,
                  'autocomplete' => 'off',
                  'label' => false,
                  'id' => 'reqDate1',
                  'type' => 'text',
                  'required' => true,
                  'class' => 'form-control datepicker'
                ]); ?>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <label for="">Description</label>
              <?= $this->Form->control('description', [
                'required' => true,
                'type' => 'textarea',
                'id' => 'desc',
                'label' => false,
                'required' => true,
                'class' => 'form-control'
              ]); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="v-btn v-btn-base" data-dismiss="modal" aria-label="Close">Close</button>
        <button class="v-btn v-btn-primary" type="submit" onclick="setTimeout(() => {
                    this.disabled = true;
                    }, 1);">Edit Comp-off</a>
      </div>

      <?= $this->Form->end() ?>

    </div>
  </div>
</div>


<!-- End Edit comp-off -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>
  $(document).ready(function() {
    $('#example1').DataTable({
      responsive: true,
      "pageLength": 10
    });
  });

  function editCompOff(compId) {
    // console.log(compId);
    $.ajax({
      type: 'GET',
      url: '<?= $this->Url->build('/leaves/edit-comp-off/') ?>' + compId,
      success: function(data) {
        let editData = JSON.parse(data);
        console.log(editData.request_date);
        document.querySelector("#compOffId").value = editData.id;
        document.querySelector("#reqDate1").value = editData.request_date;
        document.querySelector("#desc").value = editData.description;
      }
    })
  }
</script>