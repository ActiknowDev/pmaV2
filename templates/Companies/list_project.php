<?php $session = new \Cake\Http\Session();
$userSession = $session->read('data');
$role = $userSession['role'];
?>
<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-project-diagram"></i></span>Project List
                    </div>
                </div>
                <div class="col-6">
                    <div class="actions-ctrl text-md-right">
                        <?= $this->Html->link('<i class="fa fa-plus"></i><span>Add New Project </span>', '/add-project', ['class' => 'v-btn v-btn-secondary', 'escape' => false]); ?>
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
                        <?php if ($role == 3) { ?>
                            <li>
                                <?= $this->Html->link('My Projects(' . $my . ')', '/my-project/', ['class' => '']); ?>
                            </li>
                        <?php } ?>
                        <li class="active">
                            <?= $this->Html->link('All Projects(' . $count . ')', '/list-project', ['class' => '']); ?>
                        </li>
                        <li>
                            <?= $this->Html->link('Active Projects(' . $active . ')', '/active-project', ['class' => '']); ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- PAGE-CONTENT -->
    <div class="page-content">
        <div class="container">
            <!-- <div class="row">
                <div class="col-md-3">
                    <div class="block primary">
                        <div class="content text-center">
                            <h4 class="title">Total Projects</h4>
                            <span><?php // $total; 
                                    ?></span>
                        </div>
                    </div>
                </div> 
                <div class="col-md-3">
                    <div class="block primary">
                        <div class="content text-center">
                            <h4 class="title">Completed Projects</h4>
                            <span><?= $complete; ?></span>
                        </div>
                    </div>
                </div> 
                <div class="col-md-3">
                    <div class="block primary">
                        <div class="content text-center">
                            <h4 class="title">Active Projects</h4>
                            <span><?= $active; ?></span>
                        </div>
                    </div>
                </div> 
            </div> -->
            <!-- <hr class="dark"> -->
            <!-- <div class="alert alert-dismissible fade show hide" id="activeInactive">

            </div> -->
            <div class="row align-center">
                <div class="col-md-3">
                    <div class="adon-group form-group">
                        <!--    <span class="icon icon-light ft-primary"><i class="fa fa-filter"></i></span> -->
                        <select name="sortBy" id="sortBy" class="form-control">
                            <option value="all-project">All</option>
                            <?php foreach ($projectManagers as $key => $projectManager) { ?>
                                <option value="<?php echo $projectManager['id'] ?>" <?php if ($manager_id == $projectManager['id']) {
                                                                                        echo "Selected";
                                                                                    } ?>>
                                    <?php echo $projectManager['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 offset-md-1">
                    <p><label>Total Amount</label></p>
                    $<?php echo number_format($totalAmount); ?>
                </div>
                <div class="col-md-3">
                    <p><label>Total Paid</label></p>
                    $<?php echo number_format($totalPaid); ?>
                </div>
            </div>
            <!-- TABLE -->
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-light nowrap table-sm  block" id="example1" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Project Name</th>
                                <th>Client</th>
                                <th>Project Manager</th>
                                <th>Due Date</th>
                                <th>OD</th>
                                <th>Due</th>
                                <th>Total Amt</th>
                                <th>Paid</th>
                                <th>Active</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($projects as $p) : ?>
                                <tr id="tr<?= $p['id'] ?>">
                                    <td><?= $i; ?></td>
                                    <td><?= $this->Html->link(substr($p['project_name'], 0, 20), '/edit-project/' . $p['id'], ['class' => 'link']); ?>
                                    </td>
                                    <td><?= $p['client']; ?></td>
                                    <td><?= $p['project_manager']; ?></td>
                                    <td><?= $p['due_date']; ?></td>
                                    <td><?php if ($p['overdue'] > 0) { ?><span class="badge badge-danger" title="Overdue" style="padding: .85em .84em;"><?= $p['overdue']; ?></span><?php } else echo '-'; ?>
                                    </td>
                                    <td><?php if ($p['due'] > 0) { ?><span title="due" class="badge badge-warning" style="padding: .85em .84em;"><?= $p['due']; ?></span><?php } else echo '-'; ?>
                                    </td>
                                    <td>$<?= number_format($p['amount']); ?></td>
                                    <td>$<?= number_format($p['paid']); ?></td>
                                    <td>
                                        <input class="tgl tgl-light change-status" id="<?= $p['id']; ?>" type="checkbox" value="<?php echo $p['active']; ?>" <?= $p['active'] == '1' ? 'checked' : '' ?> />
                                        <label class="tgl-btn" for="<?= $p['id']; ?>"></label>
                                    </td>

                                    <td>
                                        <select name="" class="form-control input-sm" id="comPenStatus<?= $p['id'] ?>" onchange="changeStatus(<?= $p['id']; ?>,'<?= $p['status'] ?>','project')">
                                            <option value="Completed" <?php if ($p['status'] == "Completed") echo 'selected'; ?>>Completed
                                            </option>
                                            <option value="Pending" <?php if ($p['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                        </select>
                                    </td>
                                    <td>
                                        <?= $this->Html->link('<i class="fa fa-pencil-alt"></i>', '/edit-project/' . $p['id'], ['class' => 'icon ft-primary icon-sm', 'escape' => false]); ?>
                                        <a href="#" onclick="deleteProject(<?= $p['id'] ?>)" class="icon icon-sm delete">
                                            <i class="fa fa-archive"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php $i++;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="modal" id="confirm">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <input type="hidden" name="p_url" id="p_url" value="">
                <form id='delete-data'>
                    <input type="hidden" name="p_id" id="p_id" value="">

                </form>
                <div class="modal-body no-padding">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="widget">
                                    <div class="widget-content">
                                        <h2>Do You Want to Archive this Project?<span class="fw-600 name"></span>?</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Cancel</button>
                    <button type="button" id="deleteConfirm" class="v-btn v-btn-primary"
                        data-dismiss="modal">Yes</button>
                </div>
            </div>
        </div>
    </div> -->
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#example1').DataTable({
            responsive: true,
            scrollX: true,
            "pageLength": 10
        });
    });

    function changeStatus(id, val, type) {
        if (val == `Completed`) {
            val = 'Pending';
        } else if (val == `Pending`) {
            val = 'Completed';
        }

        // console.log(id, val, type);
        url = '<?= $this->Url->build('/companies/status/') ?>' + id + '/' + val + '/' + type;
        // console.log(url);
        $.ajax({
            url: url,
            method: 'GET',
            success: function(data) {
                if (data == 1 && val == `Completed`)
                    $(`#comPenStatus${id} [value=${val}]`).attr('selected', 'true');
                else if (data == 1 && val == `Pending`)
                    $(`#comPenStatus${id} [value=${val}]`).attr('selected', 'true');
                // location.reload();
            }
        });
    }

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
            url: "<?= $this->Url->build('/companies/updateActive/'); ?>" + id + '/' + status,
            beforeSend: function() {},
            success: function(data) {
                // location.reload();
                if (data == 1 && status == 0) {
                    $(`#${id}`).prop('checked', false);
                } else if (data == 1 && status == 1) {
                    $(`#${id}`).prop('checked', true);
                }
                // else
                //     1
                // location.reload();
            }
        });
    });

    $('#sortBy').on('change', function() {
        var manager_id = $(this).val();
        var target_url = "<?= $this->Url->build('/companies/listProject/') ?>" + manager_id;
        if (target_url != null) {
            window.location.href = target_url;
        } else {
            window.location.href = "<?= $this->Url->build('/companies/listProject/all-project') ?>"
        }
    });

    function deleteProject(id) {
        //     // console.log(id);
        let condition = false;
        if (confirm("Do You Want to Archive this Project?")) condition = true;
        else condition = false;

        if (condition) {
            $.ajax({
                url: "<?= $this->Url->build('/Companies/deleteProject/') ?>" + id,
                method: "GET",
                success: function(res) {
                    if (res == 1) $(`#tr${id}`).removeAttr("style").hide();
                },
            });
        }
    }
</script>