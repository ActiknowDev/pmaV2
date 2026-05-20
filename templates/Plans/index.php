<?php $session = new \Cake\Http\Session();
$userSession = $session->read('data');
$role = $userSession['role'];
?>
<style>
    .pro-color {
        width: 20px;
        height: 20px;
    }
    .center-item {
        display: flex;
        justify-content: center;
    }
</style>
<section class="page page-dashboard">
    <!-- PAGE-CONTENT -->
    <div class="page-content">
        <div class="container">

            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-project-diagram"></i></span>Plans List
                    </div>
                </div>

                <div class="col-md-2">
                </div>
                <div class="col-md-2  m1-2">
                    <div class="actions-ctrl text-md-right">
                        <?= $this->Html->link('<span>Clients Plan List</span>', '/support-plans', ['class' => 'v-btn', 'escape' => false]); ?>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="actions-ctrl text-md-right ">
                        <a href="#" class="v-btn v-btn-primary btn-block" data-target="#add_plan" data-toggle="modal">
                            <i class="fa fa-plus"></i><span>Add New Plan</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- TABLE -->
            <div class="row">
                <div class="col-md-12">
                <?= $this->Flash->render() ?>
                    <table id="example" style="width:100%" class="table table-light table-sm  block">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th style="width:220px !important;">Plan Name</th>
                                <th style="width:220px !important;">Plan Duration</th>
                                <th>Plan Price</th>
                                <th>Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($plans as $row) : ?>
                            <tr id="tr<?= $row['id'] ?>">
                                <td><?= $i; ?></td>
                                <td><?= $row['plan_name']; ?></td>
                                <td>
                                <?php
                                $duration = ($row['plan_duration'] == 1 ? 'Monthly' : ($row['plan_duration'] == 3 ? 'Quarterly' : ($row['plan_duration'] == 6 ? 'Half yearly' : ($row['plan_duration'] == 12 ? 'Annual' : '---'))));
                                echo $duration;
                                ?>
                                </td>
                                <td>$ <?= $row['price']; ?></td>
                                
                                <!-- Action Start -->
                                <td>
                                <a href="#" data-target="#edit_plan" onclick="loadModelData(this)" data-toggle="modal" class="icon ft-primary icon-sm" title="Edit Category" data-id="<?= $row["id"] ?>" data-name="<?= $row["plan_name"] ?>" data-amount="<?= $row["price"] ?>" data-duration="<?= $row["plan_duration"] ?>"><i class="fa fa-pencil-alt"></i></a>
                                <a href="<?= $this->Url->build(["controller" => "Plans", "action" => "deletePlan", $row["id"]]) ?>" onclick="return confirm('Are you sure')" title="Delete Plan" class="icon icon-sm"><i class="fa fa-trash-alt"></i></a>
                                </td>
                                <!-- End -->
                            <?php $i++;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add Asset Data -->
<div class="modal fade" tabindex="-1" role="dialog" id="add_plan">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <?= $this->Form->create(null, [
                'url' => [
                    'controller' => 'Plans',
                    'action' => 'addplan'
                ]
            ]) ?>

            <div class="modal-header">
                <h5 class="modal-title">Add Plan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                    <input type="hidden" name="plan_duration" value="1">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Plan Name</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-user"></i></span>
                                <?= $this->form->text("plan_name", [
                                    "class" => "form-control",
                                    "required" => true,
                                    "autocomplete" => "off",
                                    "placeholder" => "Enter Plan Name",
                                ]); ?>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Duration</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-clock"></i></span>
                                <select name="plan_duration" id="plan_duration" class="form-control" required="">
                                <option value="">Select Duration</option>
                                <option value="1">Monthly</option>
                                <option value="3">Quarterly</option>
                                <option value="6">Half yearly</option>
                                <option value="12">Annual</option>
                                </select>
                            </div>
                        </div>
                    </div> -->

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Amount (Monthly)</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="">$</i></span>
                                <?= $this->form->text("price", [
                                    "class" => "form-control",
                                    "required" => true,
                                    "autocomplete" => "off",
                                    "id" => "amount_id",
                                    "oninput" => "validateNumericInput(this)",
                                    "placeholder" => "Enter Plan Amount($)",
                                ]); ?>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                <button class="v-btn v-btn-primary" type="submit">Save</button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="edit_plan">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <?= $this->Form->create(null, [
                'url' => [
                    'controller' => 'Plans',
                    'action' => 'editPlan'
                ]
            ]) ?>

            <?= $this->Form->input("id", [
                "type" => "hidden",
                "id" => "edit_plan_id"
            ]); ?>

            <div class="modal-header">
                <h5 class="modal-title">Edit Plan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                <input type="hidden" name="plan_duration" value="1">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Plan Name</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-user"></i></span>
                                <?= $this->form->input("plan_name", [
                                    "class" => "form-control",
                                    "placeholder" => "Enter Plan Name",
                                    "required" => true,
                                    "id" => "plan_name_id"
                                ]); ?>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Duration</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-clock"></i></span>
                                <select name="plan_duration" id="plan_duration_id" class="form-control" required="">
                                <option value="">Please Select</option>
                                <option value="1">Monthly</option>
                                <option value="3">Quarterly</option>
                                <option value="6">Half yearly</option>
                                <option value="12">Annual</option>
                                </select>
                            </div>
                        </div>
                    </div> -->

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Amount (Monthly)</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="">$</i></span>
                                <?= $this->form->text("price", [
                                    "class" => "form-control",
                                    "required" => true,
                                    "oninput" => "validateNumericInput(this)",
                                    "autocomplete" => "off",
                                    "placeholder" => "Enter Plan Amount($)",
                                    "id" => "plan_amount_id"
                                ]); ?>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                <button class="v-btn v-btn-primary" type="submit">Update</button>
            </div>
            <?= $this->Form->end() ?>

        </div>
    </div>
</div>

<script type="text/javascript">
    function loadModelData(ele) {
        var id = $(ele).attr("data-id");
        var name = $(ele).attr("data-name");
        // var duration = $(ele).attr("data-duration");
        var amount = $(ele).attr("data-amount");
        $("#plan_name_id").val(name);
        $("#plan_amount_id").val(amount);
        // $("#plan_duration_id").val(duration);
        $("#edit_plan_id").val(id);
    }

    function validateNumericInput(input) {
        input.value = input.value.replace(/[^0-9.]/g, '');
        let decimalCount = (input.value.match(/\./g) || []).length;
        if (decimalCount > 1) {
            input.value = input.value.replace(/\.(?=.*\.)/g, '');
        }
    }

</script>