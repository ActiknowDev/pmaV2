<?php extract($projects[0]); ?>
<section class="page page-dashboard">
  <!-- PAGE-TITLE -->
  <div class="page-title skin-light">
    <div class="container">
      <div class="row">
        <div class="col-6">
          <div class="heading ft-secondary">
            <?= $project_name; ?>
          </div>
        </div>
        <div class="col-6">
          <div class="actions-ctrl text-md-right">
            <?= $this->Html->link('<i class="fa fa-list"></i><span>List Project</span>', '/list-project', ['class' => 'v-btn v-btn-secondary', 'escape' => false]); ?>

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
            <li class="active"><?= $this->Html->link('Milestone', '/project-view/' . $id); ?></li>
            <li><?= $this->Html->link('Payment History', '/project-payment/' . $id); ?></li>
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
          <a href="#" data-target="#add_milestone" data-toggle="modal" class="v-btn v-btn-primary mb-3"><i class="fa fa-plus"></i><span> Add Milestone</span></a></h4>
          <div class="content table-responsive">
            <table class="table table-default" id="table_data">
              <thead>
                <tr>
                  <th>#</th>
                  <th style="width:250px;">Title</th>
                  <th>Due Date</th>
                  <th>Amount</th>
                  <th>status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <?php if ($miles) : ?>
                <?php foreach ($miles as $m) : ?>
                  <tbody id="rowm<?= $m['id']; ?>">

                    <tr class="active">
                      <td>
                        <label class="labels" id="lm<?= $m['id']; ?>" onclick="mlabel(<?= $m['id']; ?>)"><i class="fa fa-chevron-up"></i></label>
                        <input type="checkbox" name="milestoneOne" id="m<?= $m['id']; ?>" data-toggle="toggle">
                      </td>
                      <td><?= $m['title']; ?></td>
                      <td><?= $m['due_date']; ?></td>
                      <td>$<?= $m['amount']; ?></td>
                      <td>
                        <select name="mstatus" class="form-control status" id="<?= $m['id']; ?>" data-type="miles" data-url="<?= WEBURL; ?>">
                          <option value="Yet to start" <?php if ($m['status'] == 'Yet to start') echo 'selected'; ?>>Yet to start</option>
                          <option value="Inprogress" <?php if ($m['status'] == 'Inprogress') echo 'selected'; ?>>In progress</option>
                          <option value="Completed" <?php if ($m['status'] == 'Completed') echo 'selected'; ?>>completed</option>
                        </select>
                      </td>
                      <td>
                        <a href="#" class="icon" data-toggle="modal" data-target="#add_task" onclick="taskValue(<?= $m['id']; ?>)" title="Add Task"><i class="fa fa-plus"></i></a>
                        <a href="#" class="icon" data-toggle="modal" data-target="#edit_milestone" onclick="passValue('edit',<?= $m['id']; ?>)"> <i class="fa fa-pencil-alt"></i> </a>
                        <a href="#" class="icon" onclick="passValue('delete',<?= $m['id']; ?>)"> <i class="fa fa-trash-alt"></i> </a>
                      </td>
                    </tr>
                  </tbody>
                  <tbody class="rowtm<?= $m['id']; ?>" id="rowtm<?= $m['id']; ?>">
                    <?php if (count($m['task_list']) > 0) : ?>
                      <?php foreach ($m['task_list'] as $mt) : ?>


                        <tr id="rowt<?= $mt['id']; ?>">
                          <td></td>
                          <td><?= $mt['task']; ?></td>
                          <td><?= $mt['due_date']; ?></td>
                          <td>-</td>
                          <td>
                            <select name="mtstatus" class="form-control status" id="<?= $mt['id']; ?>" data-type="tasks" data-url="<?= WEBURL; ?>">
                              <option value="Yet to start" <?php if ($mt['status'] == 'Yet to start') echo 'selected'; ?>>Yet to start</option>
                              <option value="Inprogress" <?php if ($mt['status'] == 'Inprogress') echo 'selected'; ?>>In progress</option>
                              <option value="Completed" <?php if ($mt['status'] == 'Completed') echo 'selected'; ?>>completed</option>
                            </select>
                          </td>
                          <td>
                            <a href="#" class="icon" data-toggle="modal" data-target="#edit_task" onclick="passtaskValue('edit',<?= $mt['id']; ?>)"> <i class="fa fa-pencil-alt"></i> </a><a href="#" class="icon delete-milestone" data-id="'+response.id+'" onclick="passtaskValue('delete',<?= $mt['id']; ?>)"> <i class="fa fa-trash-alt"></i> </a>
                          </td>
                        </tr>

                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                <?php endforeach; ?>
              <?php endif; ?>

            </table>
          </div>
        </div>
        <div class="col-md-4">
          <div class="block project-info">
            <div class="content">
              <h4 class="sub-title">Project Info</h4>
              <ul class="">
                <li>Awarded Date <span class="date lead"><?= $award; ?></span></li>
                <li>Due Date <span class="date lead"><?= $due_date; ?></span></li>
                <li>Total Milestone <span class="date lead"><?= count($miles); ?></span></li>
                <li>Status <span class="badge badge-dark"><?= $status; ?></span></li>
              </ul>
            </div>
          </div>
          <div class="block project-info">
            <div class="content">
              <h4 class="sub-title">Client Info</h4>
              <!-- <a href=""> -->
              <h5> <?= $client; ?></h5>
              <p class="lead"><i class="fa fa-envelope"></i> <?= $email; ?></p>
              <!-- </a> -->
            </div>
          </div>
          <div class="block project-info">
            <div class="content">
              <h4 class="sub-title">Resources
                <!-- <span data-target="#add_resources" data-toggle="modal" class="icon icon-sm float-right"><i class="fa fa-user-plus"></i></span> -->
              </h4>
              <ul>
                <?php if ($manager == $lead) { ?>
                  <li>
                    <h5><?= $manager; ?><span class="lead">Manager,Tech Lead</span></h5>
                  </li>
                  <?php foreach ($res as $r) : ?>
                    <li>
                      <h5><?= $r['name']; ?><span class="lead"><?= $r['role']; ?></span></h5>
                    </li>
                  <?php endforeach; ?>

                <?php } else { ?>
                  <li>
                    <h5><?= $manager; ?><span class="lead">Manager</span></h5>
                  </li>
                  <?php foreach ($res as $r) : ?>
                    <?php if ($lead == $r['name']) { ?>
                      <li>
                        <h5><?= $r['name']; ?><span class="lead">Tech Lead,<?= $r['role']; ?></span></h5>
                      </li>
                    <?php } else { ?>
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
<div class="modal fade" tabindex="-1" role="dialog" id="add_milestone">
  <?= $this->Form->create(null, array('id' => 'milestone')) ?>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Milestone</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="content">
          <div class="form-group row">
            <div class="col-md-12">
              <label for="">Milestone Title</label>
              <div class="adon-group mt">
                <input type="hidden" name="project_id" value="<?= $id; ?>">
                <input type="text" class="form-control" name="title" placeholder="" autocomplete="off">
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-6">
              <label for="">Due Date</label>
              <div class="adon-group mddate">
                <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span>
                <input type="text" class="form-control datepicker" name="due_date" placeholder="" autocomplete="off">
              </div>
            </div>
            <div class="col-md-6">
              <label for="">Amount</label>
              <div class="adon-group mamt">
                <span class="icon ft-primary"><i class="fa fa-dollar-sign"></i></span>
                <input type="number" class="form-control" name="amount" placeholder="" autocomplete="off">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="v-btn v-btn-base cancel" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" class="v-btn v-btn-primary" id="savemile">Add Milestone</button>
      </div>
    </div>
  </div>
  <?= $this->Form->end() ?>
</div>


<!-- Edit MILESTONE MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="edit_milestone">
  <?= $this->Form->create(null, array('id' => 'editmilestone')) ?>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Milestone</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="content">
          <div class="form-group row">
            <div class="col-md-12">
              <label for="">Milestone Title</label>
              <div class="adon-group mt">
                <input type="hidden" class="form-control" name="mile_id" id="mile_id" placeholder="">
                <input type="text" class="form-control" name="title" id="title" placeholder="" autocomplete="off">
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-6">
              <label for="">Due Date</label>
              <div class="adon-group mddate">
                <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span>
                <input type="text" class="form-control datepicker" name="due_date" id="due_date" placeholder="" autocomplete="off">
              </div>
            </div>
            <div class="col-md-6">
              <label for="">Amount</label>
              <div class="adon-group mamt">
                <span class="icon ft-primary"><i class="fa fa-dollar-sign"></i></span>
                <input type="number" class="form-control" name="amount" id="amount" placeholder="" autocomplete="off">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="v-btn v-btn-base cancel" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" class="v-btn v-btn-primary" id="editmile">Update Milestone</button>
      </div>
    </div>
  </div>
  <?= $this->Form->end() ?>
</div>


<!-- ADD TASK MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="add_task">
  <?= $this->Form->create(null, array('id' => 'task_form')) ?>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Task</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="content">
          <div class="form-group row">
            <div class="col-md-12">
              <label for="">Task</label>
              <div class="adon-group mtk">
                <input type="hidden" name="milestone_id" value="">
                <input type="text" class="form-control" name="task" placeholder="" autocomplete="off">
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-6">
              <label for="">Due Date</label>
              <div class="adon-group tdate">
                <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span>
                <input type="text" class="form-control datepicker" name="due_date" placeholder="" autocomplete="off">
              </div>
            </div>
            <div class="col-md-6">
              <label for="">Status</label>
              <div class="adon-group">
                <select name="status" class="form-control" id="">
                  <option value="Yet to start">Yet to start</option>
                  <option value="Inprogress">In progress</option>
                  <option value="Completed">completed</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="v-btn v-btn-base cancel" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" class="v-btn v-btn-primary" id="savetask">Add Task</button>
      </div>
    </div>
  </div>
  <?= $this->Form->end() ?>
</div>

<!-- EDIT TASK MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="edit_task">
  <?= $this->Form->create(null, array('id' => 'edittask_form')) ?>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Task</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="content">
          <div class="form-group row">
            <div class="col-md-12">
              <label for="">Task</label>
              <div class="adon-group mt">
                <input type="hidden" name="task_id" id="task_id">
                <input type="hidden" name="milestone_id" id="milestone_id">
                <input type="text" class="form-control" name="task" id="task" placeholder="" autocomplete="off">
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <label for="">Due Date</label>
              <div class="adon-group mddate">
                <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span>
                <input type="text" class="form-control datepicker" id="task_due_date" name="due_date" placeholder="" autocomplete="off">
              </div>
            </div>
            <!-- <div class="col-md-6">
                    <label for="">Status</label>
                    <div class="adon-group">
                        <select name="status" class="form-control" id="">
                            <option value="Yet to start">Yet to start</option>
                            <option value="Inprogress">In progress</option>
                            <option value="Completed">completed</option>
                        </select>
                    </div>
                </div> -->
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="v-btn v-btn-base cancel" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" class="v-btn v-btn-primary" id="edittask">Update Task</button>
      </div>
    </div>
  </div>
  <?= $this->Form->end() ?>
</div>



<!-- ADD RESOUCE MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="add_resources">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Resources</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="content">
          <div class="form-group row">
            <div class="col-md-12">
              <label for="">Select Team</label>
              <div class="adon-group">
                <select name="" class="form-control" id="">
                  <option value="">PHP TEAM</option>
                  <option value="">Android TEAM</option>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <label for="">Search Team Member</label>
              <div class="adon-group">
                <input type="text" class="form-control" placeholder="">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
        <a href="company_list.php" class="v-btn v-btn-primary" data-dismiss="modal">Add Resource</a>
      </div>
    </div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>

<script>
  //add form
  var mvalidator = $("#milestone").validate({
    rules: {
      title: {
        required: true,
      },
      due_date: {
        required: true,
      },
      amount: {
        required: true
      },
    },
    messages: {
      title: {
        required: "Please enter Title",

      },
      due_date: {
        required: "Please enter Date",
      },
      amount: {
        required: "Please enter amount",
      },
    },
    errorPlacement: function(error, element) {
      if (element.attr("name") == "title")
        error.insertAfter(".mt");
      else if (element.attr("name") == "due_date")
        error.insertAfter(".mddate");
      else if (element.attr("name") == "amount")
        error.insertAfter(".mamt");
    },
    submitHandler: function(form) {
      $('#savemile').html('sending..');
      $.ajax({
        url: "<?= $this->Url->build('/companies/addMilestone') ?>",
        type: "POST",
        data: $('#milestone').serialize(),
        dataType: "json",
        success: function(response) {
          $('#savemile').html('Save Milestone');
          document.getElementById("milestone").reset();
          $(".close").click();

          var html = '<tbody id="rowm' + response.id + '"><tr class="active"><td><label class="labels collaps-icon" id="lm' + response.id + '" onclick="mlabel(' + response.id + ')"><i class="fa fa-chevron-up"></i></label><input type="checkbox" name="milestoneOne" id="m' + response.id + '" data-toggle="toggle"></td>';
          html += '<td>' + response.title + '</td>';
          html += '<td>' + response.due_date + '</td>';
          html += '<td>$' + response.amount + '</td>';
          html += '<td><select name="" class="form-control" id=""><option value="">Yet to start</option><option value="">In progress</option><option value="">completed</option>                                           </select></td>';
          html += '<td><a href="#" class="icon mtask" data-toggle="modal" data-target="#add_task" onclick="taskValue(' + response.id + ')" title="Add Task"><i class="fa fa-plus"></i></a><a href="#" class="icon" data-toggle="modal" data-target="#edit_milestone" onclick="passValue(\'edit\',' + response.id + ')"> <i class="fa fa-pencil-alt"></i> </a><a href="#" class="icon delete-milestone" data-id="' + response.id + '" onclick="passValue(\'delete\',' + response.id + ')"> <i class="fa fa-trash-alt"></i> </a></td>';
          html += '</tr></tbody>';
          html += '<tbody class="hide rowtm' + response.id + '" id="rowtm' + response.id + '"></tbody>';

          $('#table_data').append(html);

          var html = '<input type="hidden" name="milesid[]" id="m_' + response.id + '" value="' + response.id + '">';
          $('#pro_data').prepend(html);

        }
      });
    }
  })

  $(".cancel").click(function() {
    mvalidator.resetForm();
  });

  var emvalid = $("#editmilestone").validate({
    rules: {
      title: {
        required: true,
      },
      due_date: {
        required: true,
      },
      amount: {
        required: true
      },
    },
    messages: {
      title: {
        required: "Please enter Title",

      },
      due_date: {
        required: "Please enter Date",
      },
      amount: {
        required: "Please enter amount",
      },
    },
    errorPlacement: function(error, element) {
      if (element.attr("name") == "title")
        error.insertAfter(".mt");
      else if (element.attr("name") == "due_date")
        error.insertAfter(".mddate");
      else if (element.attr("name") == "amount")
        error.insertAfter(".mamt");
    },
    submitHandler: function(form) {
      $('#editmile').html('sending..');
      $.ajax({
        url: "<?= $this->Url->build('/companies/updateMilestone') ?>",
        type: "POST",
        data: $('#editmilestone').serialize(),
        dataType: "json",
        success: function(response) {
          $('#editmile').html('Update Milestone');
          $(".close").click();
          document.getElementById("rowm" + response.id).remove();

          var html = '<tbody id="rowm' + response.id + '"><tr class="active"><td><label class="labels collaps-icon" for="m' + response.id + '"><i class="fa fa-chevron-up"></i></label><input type="checkbox" name="milestoneOne" id="m' + response.id + '" data-toggle="toggle"></td>';
          html += '<td>' + response.title + '</td>';
          html += '<td>' + response.due_date + '</td>';
          html += '<td>' + response.amount + '</td>';
          html += '<td><select name="" class="form-control" id=""><option value="">Yet to start</option><option value="">In progress</option><option value="">completed</option>                                           </select></td>';
          html += '<td><a href="#" class="icon mtask" data-toggle="modal" data-target="#add_task" onclick="taskValue(' + response.id + ')" title="Add Task"><i class="fa fa-plus"></i></a><a href="#" class="icon" data-toggle="modal" data-target="#edit_milestone" onclick="passValue(\'edit\',' + response.id + ')"> <i class="fa fa-pencil-alt"></i> </a><a href="#" class="icon delete-milestone" data-id="' + response.id + '" onclick="passValue(\'delete\',' + response.id + ')"> <i class="fa fa-trash-alt"></i> </a></td>';
          html += '</tr></tbody>';

          $('#table_data').prepend(html);
        }
      });
    }
  })

  $(".cancel").click(function() {
    emvalid.resetForm();
  });
</script>

<script>
  // $('.mtask').click(function(){
  //     var mid = $(this).attr('id');
  //     var input = $('<input name="milestone_id" type="hidden" value="'+mid+'">');
  //     $('#task_form').append(input);
  // });
  //add form
  var tmvalid = $("#task_form").validate({
    rules: {
      task: {
        required: true,
      },

    },
    messages: {
      task: {
        required: "Please enter Task",

      },

    },
    errorPlacement: function(error, element) {
      if (element.attr("name") == "task")
        error.insertAfter(".mtk");

    },
    submitHandler: function(form) {
      $('#savetask').html('sending..');
      $.ajax({
        url: "<?= $this->Url->build('/companies/addTask') ?>",
        type: "POST",
        data: $('#task_form').serialize(),
        dataType: "json",
        success: function(response) {
          $('#savetask').html('Save Task');
          document.getElementById("task_form").reset();
          $(".close").click();

          var html = '<tr class="active" id="rowt' + response.id + '"><td></td>';
          html += '<td>' + response.task + '</td>';
          html += '<td>' + response.due_date + '</td>';
          html += '<td>-</td>';
          html += '<td><select name="" class="form-control" id=""><option value="">Yet to start</option><option value="">In progress</option><option value="">completed</option>                                           </select></td>';
          html += '<td><a href="#" class="icon" data-toggle="modal" data-target="#edit_task" onclick="passtaskValue(\'edit\',' + response.id + ')"> <i class="fa fa-pencil-alt"></i> </a><a href="#" class="icon delete-milestone" data-id="' + response.id + '" onclick="passtaskValue(\'delete\',' + response.id + ')"> <i class="fa fa-trash-alt"></i> </a></td>';

          $('#rowtm' + response.milestone_id).prepend(html);



        }
      });
    }
  })

  $(".cancel").click(function() {
    tmvalid.resetForm();
  });
  var emvalid = $("#edittask_form").validate({
    rules: {
      task: {
        required: true,
      },

    },
    messages: {
      task: {
        required: "Please enter Task",

      },

    },
    errorPlacement: function(error, element) {
      if (element.attr("name") == "task")
        error.insertAfter(".mt");

    },
    submitHandler: function(form) {
      $('#edittask').html('sending..');
      $.ajax({
        url: "<?= $this->Url->build('/companies/updateTask') ?>",
        type: "POST",
        data: $('#edittask_form').serialize(),
        dataType: "json",
        success: function(response) {
          $('#edittask').html('Update Task');
          $(".close").click();
          document.getElementById("rowt" + response.id).remove();

          var html = '<tr class="active" id="rowt' + response.id + '"><td></td>';
          html += '<td>' + response.task + '</td>';
          html += '<td>' + response.due_date + '</td>';
          html += '<td>-</td>';
          html += '<td><select name="" class="form-control" id=""><option value="">Yet to start</option><option value="">In progress</option><option value="">completed</option>                                           </select></td>';
          html += '<td><a href="#" class="icon" data-toggle="modal" data-target="#edit_task" onclick="passtaskValue(\'edit\',' + response.id + ')"> <i class="fa fa-pencil-alt"></i> </a><a href="#" class="icon delete-milestone" data-id="' + response.id + '" onclick="passtaskValue(\'delete\',' + response.id + ')"> <i class="fa fa-trash-alt"></i> </a></td>';

          $('#rowtm' + response.milestone_id).prepend(html);
        }
      });
    }
  })
  $(".cancel").click(function() {
    emvalid.resetForm();
  });
</script>
<script type="text/javascript">
  // getEdit data
  function passValue(type, id) {

    $.ajax({

      type: 'GET',
      url: "<?= $this->Url->build('/companies/milesaction/'); ?>" + type + '/' + id,

      beforeSend: function() {

      },
      success: function(data) {
        if (type == 'edit') {
          var response = $.parseJSON(data);

          var d = response.due_date.split('-');
          var date = d[1] + '/' + d[2] + '/' + d[0];
          $("#title").val(response.title);
          $("#due_date").val(date);
          $("#amount").val(response.amount);
          $("#mile_id").val(response.id);
        } else {
          document.getElementById("rowm" + id).remove();
          // document.getElementById("m_"+id).remove();
          document.getElementById("rowtm" + id).remove();
        }

      }
    });

    // $.ajax({

    //            type:'GET',
    //            url:"<?= $this->Url->build('/companies/tasksaction/'); ?>"+type+'/'+id,

    //            beforeSend: function ()
    //            {

    //            },
    //            success:function(data){
    //                     if(type=='edit')
    //                     {
    //                         var response = $.parseJSON(data);

    //                        var d = response.due_date.split('-');
    //                        var date = d[1]+'/'+d[2]+'/'+d[0];
    //                        $("#task").val(response.task);
    //                        $("#due_date").val(date);
    //                        $("#milestone_id").val(response.milestone_id);
    //                        $("#task_id").val(response.id);
    //                     }
    //                     else{
    //                         location.reload();
    //                     }

    //                  }
    //        });
  }


  function mlabel(id) {
    var count = document.getElementsByClassName("rowtm" + id).length;
    for (var i = 0; i < count; i++) {
      var x = document.getElementsByClassName("rowtm" + id)[i];
      if (x.style.display === "none") {
        x.style.display = "";
        document.getElementById("lm" + id).innerHTML = '<i class="fa fa-chevron-up"></i>';
      } else {
        x.style.display = "none";
        document.getElementById("lm" + id).innerHTML = '<i class="fa fa-chevron-down"></i>';
      }
    }

  }

  function taskValue(id) {
    var input = $('<input name="milestone_id" type="hidden" value="' + id + '">');
    $('#task_form').append(input);
  }


  function passtaskValue(type, id) {
    $.ajax({

      type: 'GET',
      url: "<?= $this->Url->build('/companies/tasksaction/'); ?>" + type + '/' + id,

      beforeSend: function() {

      },
      success: function(data) {
        if (type == 'edit') {
          var response = $.parseJSON(data);

          if (response.due_date != '1800-01-01') {
            var d = response.due_date.split('-');
            var date = d[1] + '/' + d[2] + '/' + d[0];
          } else {
            date = '';
          }
          $("#task").val(response.task);
          $("#task_due_date").val(date);
          $("#milestone_id").val(response.milestone_id);
          $("#task_id").val(response.id);
        } else {
          document.getElementById("rowt" + id).remove();
        }

      }
    });
  }
</script>