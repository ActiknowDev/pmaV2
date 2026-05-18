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
                        <span class="icon"><i class="fa fa-project-diagram"></i></span>Stage List
                    </div>
                </div>

                <div class="col-md-2">
                </div>
                <div class="col-md-2  m1-2">
                    <div class="actions-ctrl text-md-right">
                        <?= $this->Html->link('<span>List Opportunity</span>', '/list-opportunity', ['class' => 'v-btn v-btn-secondary', 'escape' => false]); ?>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="actions-ctrl text-md-right ">
                        <a href="#" class="v-btn v-btn-primary btn-block" data-target="#add_new" data-toggle="modal">
                            <i class="fa fa-plus"></i><span>Add New Stage</span>
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
                                <th style="width:220px !important;">Name</th>
                                <th>Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($list as $row) : ?>
                            <tr id="tr<?= $row['id'] ?>">
                                <td><?= $i; ?></td>
                                <td><?= $row['name']; ?></td>
                                
                                <!-- Action Start -->
                                <td>
                                <a href="#" data-target="#edit_plan" onclick="loadModelData(this)" data-toggle="modal" class="icon ft-primary icon-sm" title="Edit Category" data-id="<?= $row["id"] ?>" data-name="<?= $row["name"] ?>" ><i class="fa fa-pencil-alt"></i></a>
                                <a href="<?= $this->Url->build(["controller" => "Companies", "action" => "deleteStage", $row["id"]]) ?>" onclick="return confirm('Are you sure')" title="Delete Stage" class="icon icon-sm"><i class="fa fa-trash-alt"></i></a>
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
<div class="modal fade" tabindex="-1" role="dialog" id="add_new">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <?= $this->Form->create(null, [
                'url' => [
                    'controller' => 'Companies',
                    'action' => 'addstage'
                ]
            ]) ?>

            <div class="modal-header">
                <h5 class="modal-title">Add Stage</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Name</label>
                            <div class="adon-group">
                                <!-- <span class="icon ft-primary"><i class="fa fa-user"></i></span> -->
                                <?= $this->form->text("name", [
                                    "class" => "form-control",
                                    "required" => true,
                                    "autocomplete" => "off",
                                    "placeholder" => "Enter Name",
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
                    'controller' => 'Companies',
                    'action' => 'editStage'
                ]
            ]) ?>

            <?= $this->Form->input("id", [
                "type" => "hidden",
                "id" => "edit_stage_id"
            ]); ?>

            <div class="modal-header">
                <h5 class="modal-title">Edit Plan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Name</label>
                            <div class="adon-group">
                                <!-- <span class="icon ft-primary"><i class="fa fa-user"></i></span> -->
                                <?= $this->form->input("name", [
                                    "class" => "form-control",
                                    "placeholder" => "Enter Name",
                                    "required" => true,
                                    "id" => "stage_name_id"
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
        $("#stage_name_id").val(name);
        $("#edit_stage_id").val(id);
    }

    function validateNumericInput(input) {
        input.value = input.value.replace(/[^0-9.]/g, '');
        let decimalCount = (input.value.match(/\./g) || []).length;
        if (decimalCount > 1) {
            input.value = input.value.replace(/\.(?=.*\.)/g, '');
        }
    }

</script>