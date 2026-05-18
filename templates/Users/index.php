<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-user"></i></span>User List
                    </div>
                </div>
                <div class="col-6">
                    <div class="actions-ctrl text-md-right">

                        <?= $this->Html->link('<i class="fa fa-plus"></i><span>Add New User</span>', [
                            "controller" => "EmployeeDetails", "action" => "edit"
                        ], [
                            'class' => "v-btn v-btn-secondary",
                            "escape" => false,
                        ]); ?>

                        <!-- 
                  <a href="" data-toggle="modal" data-target="#add_user" class="v-btn v-btn-secondary">
                  <i class="fa fa-plus"></i><span>Add New User</span>
                  </a> -->
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
                        <div class="content text-center">
                            <h4 class="title">Total Users</h4>
                            <span><?= $totalUsers; ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="block primary">
                        <div class="content text-center">
                            <h4 class="title">Total Active</h4>
                            <span><?= $totalActiveUsers; ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="block primary">
                        <div class="content text-center">
                            <h4 class="title">Total Inactive</h4>
                            <span><?= $totalInactiveUsers; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <?php
            // echo "<pre>";
            // print_r($result);
            // die;
            ?>
            <!-- FILTER -->
            <div class="row">
                <!-- <div class="col-3">
               <div class="actions-ctrl text-md-right">
                  <?= $this->Html->link('<i class="fas fa-file-export"></i><span>Export Data</span>', [
                        "controller" => "users", "action" => "usersCSVReport"
                    ], [
                        'class' => "v-btn v-btn-primary",
                        "escape" => false,
                    ]); ?>
               </div>
            </div> -->
                <div class="col-md-3">
                    <?= $this->Form->create(null, ['url' => ['controller' => 'users', 'action' => 'usersCSVReport']]) ?>
                    <div class="form-group">
                        <div class="adon-group res">
                            <select name="resources[]" class="form-control" multiple id="langOpt">
                                <?php
                                foreach ($result as $col) :
                                    // if ($col == 'teamid') {
                                    //    $col = 'team_name';
                                    // }
                                ?>
                                    <option value="<?= $col ?>">
                                        <?= ucfirst($col) ?>
                                    </option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                            <?= $this->Form->button('Export', ['class' => 'v-btn v-btn-primary']) ?>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="actions-ctrl">
                        <div class="col-md-6">
                            <div class="adon-group form-group">
                                <select name="status" id="statusData" class="form-control" onchange="location = this.value;">
                                    <option value="<?= $this->Url->build(['controller' => 'users', 'action' => 'index', 'all']) ?>" <?= $status == 'all' ? 'selected' : '' ?>>
                                        All
                                    </option>
                                    <option value="<?= $this->Url->build(['controller' => 'users', 'action' => 'index', 'active']) ?>" <?= $status == 'active' ? 'selected' : '' ?>>
                                        Active
                                    </option>
                                    <option value="<?= $this->Url->build(['controller' => 'users', 'action' => 'index', 'inactive']) ?>" <?= $status == 'inactive' ? 'selected' : '' ?>>
                                        Inactive
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <?= $this->Form->end() ?>
                <div class="col-md-3">
                    <input type="text" class="form-control" onkeyup="filterData(this)" placeholder="Rep. Manager & Designation filter...">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $this->Flash->render() ?>
                    <table class="table table-light mb-3" id="example" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User Name</th>
                                <!-- <th>Email</th> -->
                                <th>Reporting Manager</th>
                                <th>Designation</th>
                                <th>Prev Appraisal</th>
                                <th>Next Appraisal</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="filterData">
                            <?php $i = 1; ?>
                            <?php foreach ($users as $key => $user) {  ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td>
                                        <?php echo $this->Html->link($user['name'], array('controller' => 'EmployeeDetails', 'action' => 'edit', $user['id'])); ?>


                                    </td>
                                    <!-- <td><?php echo $user['email']; ?></td> -->
                                    <td><?php echo $user['reporting_manager_data']["name"]; ?></td>

                                    <!-- <td><?php echo @$user["team_data"]["team_name"];  ?></td> -->
                                    <td><?php echo $user["designation"];  ?></td>
                                    <td>
                                        <?php

                                        if ("01/01/1970" != date("d/m/Y", strtotime(@$user["emp_detail"]["prev_appraisal"]))) {
                                            echo date("d/m/Y", strtotime(@$user["emp_detail"]["prev_appraisal"]));
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </td>
                                    <td><?php if ($user["emp_detail"]["next_appraisal"]) {
                                            if (date('m', strtotime(@$user["emp_detail"]["next_appraisal"])) == date('m')) { ?>
                                                <span class="badge badge-danger" title="coming" style="padding: .85em .84em;"><?php echo date("d/m/Y", strtotime(@$user["emp_detail"]["next_appraisal"])); ?></span>
                                        <?php } else {
                                                echo date("d/m/Y", strtotime(@$user["emp_detail"]["next_appraisal"]));
                                            }
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <input class="tgl tgl-light change-status" id="<?php echo $user['id'] ?>" type="checkbox" value="<?php echo $user['status']; ?>" <?= $user['status'] == '1' ? 'checked' : '' ?> />
                                        <label class="tgl-btn" for="<?php echo $user['id'] ?>"></label>
                                    </td>
                                    <td>
                                        <!-- <?= $this->Html->link('<i class="fa fa-eye"></i>', '/user-detail/' . $user['id'], ['class' => 'icon icon-sm icon-primary', 'escape' => false, 'title' => 'view profile']); ?> -->

                                        <!--  <a href="#" data-toggle="modal" data-target="#edit_user"class="icon icon-sm ft-primary " title="Edit User" onclick="passValue('<?php echo $user['id'] ?>')"><i class="fa fa-pencil-alt"></i></a>
                           -->

                                        <?php echo $this->Html->link('<i class="fa fa-pencil-alt"></i>', array('controller' => 'EmployeeDetails', 'action' => 'edit', $user['id']), [
                                            "escape" => false,
                                            "class" => "icon icon-sm icon-secondary"
                                        ]); ?>


                                        <?php echo $this->Html->link('<i class="fa fa-archive"></i>', array('controller' => 'Users', 'action' => 'delete', $user['id']), array(
                                            'class' => 'icon icon-sm icon-primary',
                                            'escape' => false,
                                            'title' => 'Delete', "onclick" => "return confirm('Are you sure')"
                                        )); ?>



                                        <?php if (
                                            in_array($this->request->getSession()->read("data")['email'], ['sumit.jhunjhunwala@actiknow.com', 'arpit.batham@actiknow.com', 'himani.duhan@actiknow.com'])
                                        ) : ?>

                                            <?php echo $this->Html->link('<i class="fa fa-dollar-sign"></i>', array('controller' => 'Salaries', 'action' => 'index', $user['id']), array(
                                                'class' => 'icon icon-sm icon-theme',
                                                'escape' => false,
                                                'title' => 'Salary'
                                            )); ?>


                                        <?php endif; ?>
                                        <!-- <a href="" class="icon icon-sm icon-secondary"><i class="fa fa-pencil-alt" data-toggle="tooltip" data-placement="right" title="" data-original-title="Edit"></i></a>
                              <a href="" class="icon icon-sm icon-primary"><i class="fa fa-archive" data-toggle="tooltip" data-placement="right" title="" data-original-title="Edit"></i></a>
                              <a href="" class="icon icon-sm icon-theme"><i class="fa fa-dollar-sign" data-toggle="tooltip" data-placement="right" title="" data-original-title="Edit"></i></a> -->
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
<!----Add user model--->
<div class="modal fade" tabindex="-1" role="dialog" id="add_user">
    <?= $this->Form->create(null, array('id' => 'users')) ?>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">User Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="" autocomplete="off">
                            <input type="hidden" name="password" value="password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Email</label>
                            <input type="text" name="email" id="email" class="form-control" placeholder="" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Team</label>
                            <select name="team" id="team" class="form-control">
                                <option value="">Select Team</option>
                                <option value="37">Sumit Team</option>
                                <option value="43">Deepika M Team </option>
                                <option value="46">Deepika R Team</option>
                                <option value="44">Pinkey Team</option>
                                <option value="45">Rana Team</option>
                                <option value="179">Vikrant Team</option>
                                <option value="57">Deepak Team</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">

                            <label for="">Reporting Manager</label>
                            <select name="reporting_manager" id="reporting_manager" class="form-control">
                                <option value="">Select</option>


                                <?php $c = 0;
                                foreach ($userReport as $key => $value) {
                                    if ($parent_name == $value->name) $c = 1; ?>

                                    <option value="<?php echo $value->id ?>"><?php echo $value->name ?></option>
                                <?php }
                                if ($c == 0) { ?>
                                    <option value="<?= $parent_id; ?>"><?= $parent_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Designation</label>
                            <input type="text" name="designation" id="designation" class="form-control" placeholder="" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Role</label>
                            <div class="adon-group">
                                <select name="role_name[]" class="form-control" multiple id="langOpt">
                                    <option value="4">Manager</option>
                                    <option value="5">Tech Lead</option>
                                    <option value="6">BD</option>
                                    <option value="7">Developer</option>
                                    <option value="8">Designer</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Technology</label>
                            <input type="contact" name="technology" id="technology" class="form-control" placeholder="" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base cancel" data-dismiss="modal">Close</button>
                <button type="submit" class="v-btn v-btn-primary" id="saveuser" name="submit">Add User</button>
            </div>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>
<!----edit user model--->
<div class="modal fade" tabindex="-1" role="dialog" id="edit_user">
    <?= $this->Form->create(null, array('id' => 'usersEdit')) ?>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">User Name</label>
                            <input type="text" name="name" id="name_edit" class="form-control" placeholder="" autocomplete="off">
                            <input type="hidden" name="password" value="password">
                            <input type="hidden" name="parent_id" id="parent_id">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Email</label>
                            <input type="text" name="email" id="email_edit" class="form-control" placeholder="" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Team</label>
                            <select name="team" id="team_edit" class="form-control">

                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Reporting Manager</label>
                            <select name="reporting_manager" id="reporting_manager_edit" class="form-control">


                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Designation</label>
                            <input type="text" name="designation" id="designation_edit" class="form-control" placeholder="" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row role">
                        <div class="col-md-12">
                            <label for="">Role</label>
                            <div class="adon-group">
                                <select name="role_name[]" class="form-control" multiple id="langOpt_edit1">

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Technology</label>
                            <input type="contact" name="technology" id="technology_edit" class="form-control" placeholder="" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="edit_id" id="edit_id">
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base cancel" data-dismiss="modal">Close</button>
                <button type="submit" class="v-btn v-btn-primary" id="edituser">Update User</button>
            </div>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>

<script type="text/javascript">
    //Status Change
    $('.change-status').click(function() {
        let id = $(this).attr('id');
        let status = $(this).val();

        if (status == 1) {
            status = 0;
        } else {
            status = 1;
        }

        $.ajax({

            type: 'GET',
            url: "<?= $this->Url->build('/users/updateStatus/'); ?>" + id + '/' + status,

            beforeSend: function() {

            },
            success: function(data) {
                window.location.href = "<?= $this->Url->build('/users'); ?>";
            }
        });
    });

    // getEdit data
    function passValue(user_id) {
        var url =
            $.ajax({
                type: 'GET',
                url: "<?= $this->Url->build('/users/editUser/'); ?>" + user_id,
                beforeSend: function() {},
                success: function(data) {
                    var response = $.parseJSON(data);
                    $("#edit_id").val(response.id);
                    $("#name_edit").val(response.name);
                    $("#email_edit").val(response.email);
                    $("#team_edit").html(response.team);
                    $("#parent_id").val(response.parent_id);
                    $("#reporting_manager_edit").html(response.reporting_manager_options);
                    $("#langOpt_edit1").html(response.role_name_options);
                    $("#designation_edit").val(response.designation);
                    $("#technology_edit").val(response.technology);
                    $('.role').append(response.src);

                }
            });
    }
    //add form
    var uservalid = $("#users").validate({
        rules: {
            name: {
                required: true,
            },
            email: {
                required: true,
                maxlength: 50,
                email: true,
            },
            team: {
                required: true,
            },
            reporting_manager: {
                required: true,
            }
        },
        messages: {
            name: {
                required: "Please enter name",
            },
            email: {
                required: "Please enter email",
            },
            team: {
                required: "Please select team",
            },
            reporting_manager: {
                required: "Please Select any option",
            }
        },
        submitHandler: function(form) {
            $('#saveuser').html('sending..');
            $.ajax({
                url: "<?= $this->Url->build('/users/add') ?>",
                type: "POST",
                data: $('#users').serialize(),
                dataType: "json",
                success: function(response) {
                    window.location.href = "<?= $this->Url->build('/users/') ?>";
                }
            });
        }
    })

    $(".cancel").click(function() {
        uservalid.resetForm();
    });

    //edit form
    var uservalid = $("#usersEdit").validate({
        rules: {
            name_edit: {
                required: true,
            },
            email_edit: {
                required: true,
                maxlength: 50,
                email: true,
            },
        },
        messages: {
            name_edit: {
                required: "Please enter name",
            },
            email_edit: {
                required: "Please enter email",
            },
        },
        submitHandler: function(form) {
            $('#edituser').html('sending..');
            var user_id = $("#edit_id").val();
            $.ajax({
                url: "<?= $this->Url->build('/users/edit/') ?>" + user_id,
                type: "POST",
                data: $('#usersEdit').serialize(),
                dataType: "json",
                success: function(response) {
                    window.location.href = "<?= $this->Url->build('/users') ?>";
                }
            });
        }
    })

    $(".cancel").click(function() {
        location.reload();
    });

    function filterData(data) {
        let value = data.value;
        let status = $("#statusData").val().split("/");
        status = status[status.length - 1];
        // console.log(status);

        let url = "<?= $this->Url->build("/employee-details/edit/") ?>";
        let deleteUrl = "<?= $this->Url->build("/Users/delete/") ?>";
        let salaryUrl = "<?= $this->Url->build("/Salaries/index/") ?>";

        let checkEmail =
            "<?= in_array($this->request->getSession()->read('data')['email'], ['sumit.jhunjhunwala@actiknow.com', 'arpit.batham@actiknow.com', 'himani.duhan@actiknow.com']) ?>";
        // console.log("val ==> " + checkEmail);

        $.ajax({
            url: "<?= $this->Url->build("/Users/filterData") ?>",
            method: "GET",
            data: {
                value,
                status
            },
            success: function(res) {
                let data = JSON.parse(res);
                // console.log(data);
                let row = "";
                $("#filterData").html("");
                let i = 1;
                data.forEach(elm => {
                    let prev_appraisal = new Date(elm.prev_appraisal);
                    let prevYear = prev_appraisal.getFullYear();
                    let prevMonth = prev_appraisal.getMonth();
                    let prevDay = prev_appraisal.getDay();

                    if ("1970" != `${prev_appraisal.getFullYear()}`) {
                        prev_appraisal = `${prevDay}/${prevMonth}/${prevYear}`;
                    } else {
                        prev_appraisal = "-";
                    }

                    let next_appraisal = new Date(elm.next_appraisal);
                    let nextYear = "";
                    let nextMonth = "";
                    let nextDay = "";
                    if (elm.next_appraisal) {
                        nextYear = next_appraisal.getFullYear();
                        nextMonth = next_appraisal.getMonth();
                        nextDay = next_appraisal.getDay();
                        next_appraisal = `${nextDay}/${nextMonth}/${nextYear}`;
                    } else {
                        next_appraisal = "-";
                    }
                    row += `<tr>
                           <td>${i++}</td>
                           <td>
                              <a href="${url}${elm.id}">${elm.user_name}</a>
                           </td>
                           <td>${elm.rm_name}</td>
                           <td>${elm.designation}</td>
                           <td>${prev_appraisal}</td>
                           <td>${next_appraisal}</td>
                           <td>
                              <input class="tgl tgl-light" onclick="statusActInAct(${elm.id},${elm.status})" id="${elm.id}" type="checkbox" ${elm.status == '1' ? 'checked' : '' } />
                              <label class="tgl-btn" for="${elm.id}"></label>
                           </td>
                           <td>
                              <a href="${url}${elm.id}" class="icon icon-sm icon-secondary"><i class="fa fa-pencil-alt"></i>
                              </a>

                              <a href="${deleteUrl}${elm.id}" class="icon icon-sm icon-primary" onclick="return confirm('Are you sure want to delete.')"><i class="fa fa-archive"></i>
                              </a>

                            ${checkEmail ? `<a href="${salaryUrl}${elm.id}" class="icon icon-sm icon-theme" title="Salary"><i class="fa fa-dollar-sign"></i>
                            </a>` : ''}
                           </td>
                        </tr>`
                });
                $("#filterData").html(row);
            }
        });
    }

    function statusActInAct(id, status) {
        // console.log(id, status);
        if (status == 1) {
            status = 0;
        } else {
            status = 1;
        }

        $.ajax({

            type: 'GET',
            url: "<?= $this->Url->build('/users/updateStatus/'); ?>" + id + '/' + status,

            beforeSend: function() {

            },
            success: function(data) {
                window.location.href = "<?= $this->Url->build('/users'); ?>";
            }
        });
    }
</script>