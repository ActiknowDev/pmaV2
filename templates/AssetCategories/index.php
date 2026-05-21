<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-project-diagram"></i></span>Asset Categories
                    </div>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-2">
                    <div class="actions-ctrl text-md-right">
                        <?= $this->Html->link("<i class=''></i><span>Back</span>", [
                            "controller" => "AssetAssignedEntries",
                            "action" => "index",
                        ], [
                            'class' => "v-btn v-btn",
                            "escape" => false
                        ]) ?>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="actions-ctrl text-md-right ">
                        <a href="#" class="v-btn v-btn-primary" data-target="#add_category" data-toggle="modal">
                            <i class="fa fa-plus"></i><span>Add Category</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?= $this->Flash->render() ?>

    <!-- PAGE-CONTENT -->
    <div class="page-content">
        <div class="container">

            <hr class="dark">

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-light nowrap table-sm" id="example" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($assetCategoryData as $data) : ?>

                                <tr>
                                    <td><?= $i; ?></td>
                                    <td><a href="#" class="ft-secondary"><?= $data["cat_name"] ?></a></td>

                                    <td>
                                        <a href="#" data-target="#edit_category" onclick="loadModelData(this)" data-toggle="modal" class="icon ft-primary icon-sm" title="Edit Category" data-id="<?= $data["id"] ?>" data-name="<?= $data["cat_name"] ?>"><i class="fa fa-pencil-alt"></i></a>
                                        <a href="<?= $this->Url->build(["controller" => "AssetCategories", "action" => "delete", $data["id"]]) ?>" onclick="return confirm('Are you sure')" title="Delete Project" class="icon icon-sm"><i class="fa fa-trash-alt"></i></a>
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
</section>

<!-- ADD COMPANY MODAL -->


<div class="modal fade" tabindex="-1" role="dialog" id="add_category">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <?= $this->Form->create(null, [
                'url' => [
                    'controller' => 'AssetCategories',
                    'action' => 'add'
                ]
            ]) ?>

            <div class="modal-header">
                <h5 class="modal-title">Add Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Category Name</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-toolbox"></i></span>
                                <?= $this->form->input("cat_name", [
                                    "class" => "form-control",
                                    "placeholder" => "Enter Category Name",
                                    "required" => true
                                ]); ?>
                                <!--  <input type="text" class="form-control" placeholder="" required> -->
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

<div class="modal fade" tabindex="-1" role="dialog" id="edit_category">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <?= $this->Form->create(null, [
                'url' => [
                    'controller' => 'AssetCategories',
                    'action' => 'edit'
                ]
            ]) ?>

            <?= $this->Form->input("id", [
                "type" => "hidden",
                "id" => "edit_category_id"
            ]); ?>

            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Category Name</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-toolbox"></i></span>
                                <?= $this->form->input("cat_name", [
                                    "class" => "form-control",
                                    "placeholder" => "Enter Category Name",
                                    "required" => true,
                                    "id" => "cat_name_id"
                                ]); ?>
                                <!--  <input type="text" class="form-control" placeholder="" required> -->
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

        $("#cat_name_id").val(name);
        $("#edit_category_id").val(id);

    }
</script>