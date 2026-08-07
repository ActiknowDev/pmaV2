<style>
    .status-free{
        color:#28a745;
        font-weight:600;
    }

    .status-dead{
        color:#dc3545;
        font-weight:600;
    }

    .status-repair{
        color:#ff9800;
        font-weight:600;
    }
</style>

<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-project-diagram"></i></span>Asset List
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="actions-ctrl text-md-right">
                        <?= $this->Html->link('<span>Asset Assigned List</span>', '/asset-assigned-entries', ['class' => 'v-btn', 'escape' => false]); ?>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="actions-ctrl text-md-right">
                        <?= $this->Html->link('<span>Asset Categories</span>', '/asset-categories', ['class' => 'v-btn', 'escape' => false]); ?>
                    </div>
                </div>


                <div class="col-md-2">
                    <div class="actions-ctrl text-md-right ">
                        <a href="#" class="v-btn v-btn-primary btn-block" data-target="#add_asset" data-toggle="modal">
                            <i class="fa fa-plus"></i><span>Add Assets</span>
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

            <div class="row">
                <div class="col-md-12">
                    <table data-page-length='25' class="table table-light nowrap table-sm" id="example"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Asset Name</th>
                                <th>Category</th>
                                <th>Serial Number</th>
                                <th>Configuration</th>
                                <th>Amount</th>
                                <th>Expenses</th>
                                <th>Status</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody id="filterData1">
                            <?php
                                $i=1;
                                foreach($assets as $asset):
                                    $status=$asset->free_asset_status;
                                    $class='';
                                    if($status=="Dead")
                                        $class="status-dead";
                                    elseif(stripos($status,"Repair")!==false)
                                        $class="status-repair";
                                    else
                                        $class="status-free";
                            ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><a href="<?= $this->Url->build('/asset-assigned-entries/editAssetData/' . $asset->id) ?>"
                                        class="link"><?= $asset->product_name ?></a></td>
                                <td><?= h($asset->cat_name); ?></td>
                                <td><?= h($asset->serial_number); ?></td>
                                <td><?=$asset->configuration!='' ? h($asset->configuration) : '--'; ?></td>
                                <td>₹<?= number_format($asset->asset_price); ?></td>
                                <td>₹<?= number_format($asset->expense_amount); ?></td>
                                <td>
                                    <span class="<?= $class; ?>"><?= $status!='' ? h($status) : 'Available'; ?></span>
                                </td>
                                <td><?= date('Y-m-d',strtotime($asset->created_at)); ?></td>
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

<!-- Add Asset Data -->
<div class="modal fade" tabindex="-1" role="dialog" id="add_asset">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <?= $this->Form->create(null, [
                'url' => [
                    'controller' => 'AssetDatas',
                    'action' => 'add'
                ]
            ]) ?>

            <div class="modal-header">
                <h5 class="modal-title">Add Asset</h5>
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
                                <select name="asset_categorie_id" class="form-control" required>
                                    <option>Select Category</option>
                                    <?php
                                    foreach ($assetCategories as $catVal) {
                                    ?>
                                    <option value="<?= $catVal->id ?>">
                                        <?= $catVal->cat_name ?>
                                    </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Product Name</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-toolbox"></i></span>
                                <?= $this->form->input("product_name", [
                                    "class" => "form-control",
                                    "required" => true,
                                ]); ?>

                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Serial Number</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-key"></i></span>
                                <?= $this->form->text("serial_number", [
                                    "class" => "form-control",
                                    "required" => true,
                                    "autocomplete" => "off",
                                    "placeholder" => "Enter Serial Number",
                                ]); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Configuration</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="">#</i></span>
                                <?= $this->form->text("configuration", [
                                    "class" => "form-control",
                                    "required" => true,
                                    "autocomplete" => "off",
                                    "placeholder" => "Enter Asset Configuration",
                                ]); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Amount</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="">₹</i></span>
                                <?= $this->form->text("asset_price", [
                                    "class" => "form-control",
                                    "required" => true,
                                    "autocomplete" => "off",
                                    "placeholder" => "Enter Asset Amount(Price)",
                                ]); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Asset Status</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-toolbox"></i></span>
                                <select name="free_asset_status" id="free_asset_status" class="form-control" required>
                                    <option>Select Asset</option>
                                    <option value='Free & Available'>Free & Available</option>
                                    <option value='Free & Need to Repair'>Free & Need to Repair</option>
                                    <option value='Dead'>Dead</option>
                                </select>
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