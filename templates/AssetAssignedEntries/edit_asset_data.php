<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-project-diagram"></i></span>Edit Assigned Asset
                    </div>
                </div>
                <div class="col-6">
                    <div class="actions-ctrl text-md-right">
                        <?= $this->Html->link('<i class="fa fa-list"></i><span>Back</span>', '/asset-assigned-entries', ['class' => 'v-btn v-btn-secondary', 'escape' => false]); ?>
                    </div>
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
                    <?= $this->Form->create(null, ['url' => [ 'controller' => 'AssetAssignedEntries', 'action' => 'editAssignAssetData' ]]) ?>
                    <div class="block">
                        <div class="header">
                            <h4 class="title">Edit Asset</h4>
                        </div>
                        <div class="content ">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Assign To</label>
                                        <div class="adon-group">

                                            <select name="user_id" class="form-control assignEmployee" data-live-search="true" disabled>
                                                <option value="">Select Employee</option>
                                                <?php
                                                foreach ($user_data as $value) :
                                                ?>
                                                    <option value="<?= $value['id'] ?>" <?= ($value['id'] == $AssetAssignedEntries->user_id) ? 'selected' : null ?>>
                                                        <?= $value['name'] ?>
                                                    </option>
                                                <?php
                                                endforeach;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Category</label><?php //echo "<pre>";print_r($assetCategories);die('fkh');?>
                                        <div class="adon-group pname">
                                            <select name="categories_id" id="editAssetCat" class="form-control" required=true>
                                                
                                                <option value='0'>Select Category</option>
                                                <?php foreach ($assetCategories as $value) : ?>
                                                    <option value="<?= $value->id ?>" <?= (!empty($AssetCategory) && $value->id == $AssetCategory->id) ? 'selected' : '' ?>>  <?= $value->cat_name ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Asset Name</label>
                                        <div class="adon-group pname" id="assetData">

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Product Name</label>
                                        <div class="adon-group pname">
                                            <input type="text" class="form-control" name="product_name" value="<?= $AssetDatas->product_name ?>" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Serial No.</label>
                                        <div class="adon-group pname">
                                            <input type="text" class="form-control" name="serial_number" value="<?= $AssetDatas->serial_number ?>" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Configuration</label>
                                        <div class="adon-group pname">
                                            <input type="text" class="form-control" name="configuration" value="<?= $AssetDatas->configuration ?>" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Date of Purchase</label>
                                        <div class="adon-group pname">
                                            <input type="text" class="datepicker1 form-control" name="date_of_purchase"  id="date_of_purchase" value="<?= isset($AssetDatas->date_of_purchase) ? date('Y-m-d', strtotime($AssetDatas->date_of_purchase)) : '' ?>" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Date of Assign</label>
                                        <div class="adon-group pname">
                                            <input type="text" class="datepicker1 form-control" name="date_of_assign" id="date_of_assign" value="<?= isset($AssetAssignedEntries->date_of_assign) ? date('Y-m-d', strtotime($AssetAssignedEntries->date_of_assign)) : '' ?>" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Amount</label>
                                        <div class="adon-group pname">
                                            <input type="text" class="form-control" name="asset_price" value="<?= $AssetDatas->asset_price ?>" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Total Expenses</label>
                                        <div class="adon-group pname">
                                            <input type="text" class="form-control" id="totalExp" name="expenses_amount" value="<?= $totalAssetExpenses ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Description</label>
                                        <div class="adon-group pname">
                                            <input type="textarea" class="form-control" name="description" value="<?= $AssetDatas->description ?>" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <?php if(empty($AssetAssignedEntries)){ ?>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Asset Status</label>
                                            <div class="adon-group pname">
                                                <select name="free_asset_status" id="free_asset_status" class="form-control" required=true>
                                                    <?php if(!empty($AssetDatas->free_asset_status)){ ?>
                                                        <option value='<?= $AssetDatas->free_asset_status ?>' selected hidden><?= $AssetDatas->free_asset_status ?></option>
                                                    <?php  } else { ?>
                                                        <option value='' selected>Select</option>
                                                    <?php } ?>
                                                    <option value='Free & Available'>Free & Available</option>
                                                    <option value='Free & Need to Repair'>Free & Need to Repair</option>
                                                    <option value='Dead'>Dead</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                               <?php }else{?> 
                               
                                <input type="hidden" value="<?= $AssetAssignedEntries->id ?>" name="asset_datas_id">
                                <input type="hidden" value="<?= $AssetAssignedEntries->id ?>" name="asset_assign_id">
                            <?php } ?>
                            </div>
                            <div class="d-flex flex-row-reverse">
                                <div class="form-group float-right">
                                    <button type="submit" class="v-btn v-btn-secondary float-right" id="save_project"><span>Update Assign Asset</span></button>
                                </div>
                            </div>
                            <?= $this->Form->end() ?>
                        </div>
                    </div>
                    <div class="block">
                        <div class="header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="title">Assets Expenses</h4>
                                </div>
                                <div class="col-md-4">
                                </div>
                                <div class="col-lg-2 col-sm-4">
                                    <a href="javascript:void(0)" class="v-btn v-btn-primary" data-target="#add_expenses" data-toggle="modal">
                                        <i class="fa fa-plus"></i><span>Add Expenses</span>
                                    </a>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div class="content table-responsive" id="expenseTbl">

                        </div>
                    </div>

                    <div class="block">
                        <div class="header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="title">Assigned Asset</h4>
                                </div>
                                <div class="col-md-4">
                                </div>
                                <div class="col-lg-2 col-sm-4">
                                <?php if(empty($AssetAssignedEntries)){ ?>
                                    <a href="javascript:void(0)" class="v-btn v-btn-primary" data-target="#assign_asset" data-toggle="modal">
                                        <i class="fa fa-plus"></i><span>Assign Asset</span>
                                    </a>
                                    <?php } ?>
                                    
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div class="content table-responsive" id="expenseTbl">
                            <table class="table table-default" id="table_data">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Assigned To</th>
                                        <th>Assigned Date</th>
                                        <th>Release Date</th>
                                        <th>Release Remark</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $x = 1;
                                    foreach ($allAssignedEntries as $values) :
                                    ?>
                                        <tr>
                                            <td>
                                                <?= $x++ ?>
                                            </td>
                                            <td>
                                                <?= $values->user_name ? $values->user_name : '--' ?>
                                            </td>
                                            <td>
                                                <?= $values->date_of_assign ? date("Y-m-d", strtotime($values->date_of_assign)) : '--' ?>
                                            </td>
                                            <td>
                                                <?= $values->date_of_release ? date("Y-m-d", strtotime($values->date_of_release)) : '--' ?>
                                            </td>
                                            <td>
                                                <?= $values->asset_release_remark ? $values->asset_release_remark : '--' ?>
                                            </td>
                                            <td>
                                                <?php if ($values->active == 1) { ?>
                                                    <!-- <a href="<?= $this->Url->build('/asset-assigned-entries/releaseAsset/' . $values->id . '/' . $values->asset_id) ?>" class="link-warning" style="color: #ef5350 ;" title="Release Asset"> Release Asset
                                                        </a> -->
                                                    <!-- <a href="<?= $this->Url->build('/asset-assigned-entries/releaseAsset/' . $values->id . '/' . $values->asset_id) ?>" class="btn btn-sm btn-outline-danger" title="Release Asset"> <i class="fa fa-sign-out-alt mr-1"></i> Release Asset </a> -->
                                                    <a href="javascript:void(0)"
                                                        class="btn btn-sm btn-outline-danger release-asset-btn"
                                                        data-assignment-id="<?= h($values->id) ?>"
                                                        data-asset-id="<?= h($AssetDatas->id) ?>"
                                                        data-asset-name="<?= h($AssetDatas->product_name ?? '') ?>"
                                                        data-asset_release_remark="<?= h($values->asset_release_remark ?? '') ?>"
                                                        data-redirect-page="edit"
                                                        title="Release Asset">
                                                            <i class="fa fa-sign-out-alt mr-1"></i> Release Asset
                                                    </a>

                                                        <?php }  ?>

                                                    
                                            </td>
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

<!-- Assign MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="assign_asset">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <?= $this->Form->create(null, [
                'url' => [
                    'controller' => 'AssetAssignedEntries',
                    'action' => 'add'
                ]
            ]) ?>
            <div class="modal-header">
                <h5 class="modal-title">Assign Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Assigned To</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-toolbox"></i></span>
                                <select name="user_id" class="form-control assignEmp" data-live-search="true" required>
                                    <option value="">Select Employee</option>
                                    <?php foreach ($user_data as $emp) : ?>
                                        <option value="<?= $emp->id ?>"><?= $emp->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Category</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-toolbox"></i></span>
                                <select name="categories_id" id="modalAssetCat" class="form-control" required=true>
                                    <option value='0'>Select Category</option>
                                    <?php
                                    foreach ($assetCategories as $value) :
                                    ?>
                                        <option value="<?= $value->id ?>" <?= $value->id == $AssetCategory->id ? 'selected' : null ?>>
                                            <?= $value->cat_name ?></option>
                                    <?php
                                    endforeach;
                                    ?>
                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Asset</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-toolbox"></i></span>
                                <select name="asset_id" id="assetData1" data-live-search="true" class="form-control" required=true>

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Assign Date</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-toolbox"></i></span>
                                <?= $this->form->text("assign_date", [
                                    "class" => "datepicker form-control",
                                    "autocomplete" => "off",
                                    "required" => true,
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


<div class="modal fade" tabindex="-1" role="dialog" id="edit_expenses">
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="add_expenses">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <?= $this->Form->create(null, [
                'url' => [
                    'controller' => 'AssetAssignedEntries',
                    'action' => 'addExpenses'
                ]
            ]) ?>
            <div class="modal-header">
                <h5 class="modal-title">Add Expenses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                    <input type="hidden" name="asset_id" value="<?= $asset_id ?>">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Expense Type</label>
                            <div class="adon-group">
                                <input type="text" class="form-control" name="expense_type" autocomplete="off" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Expenses Amount</label>
                            <div class="adon-group">
                                <input type="number" class="form-control" name="expenses_amount" autocomplete="off" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Asset Serial Number</label>
                            <div class="adon-group">
                                <input type="text" class="form-control" name="serial_number" value="<?= $AssetDatas->serial_number ?>" autocomplete="off" required readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Expense Date</label>
                            <div class="adon-group">
                                <?= $this->form->text("expense_date", [
                                    "class" => "datepicker form-control",
                                    "autocomplete" => "off",
                                    "required" => true,
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

<!-- release asset modal -->
 <div class="modal fade" id="releaseAssetModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <?= $this->Form->create(null, [
                'url' => [
                    'controller' => 'AssetAssignedEntries',
                    'action' => 'releaseAsset'
                ]
            ]) ?>
            <input type="hidden" name="assignment_id" id="release_assignment_id">
            <input type="hidden" name="asset_id" id="release_asset_id">
            <input type="hidden" name="redirect_page" id="release_redirect_page">

            <div class="modal-header">
                <h5 class="modal-title">Release Asset</h5>

                <button type="button" class="close" data-dismiss="modal"> <span>&times;</span> </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Asset</label>
                    <input type="text" id="release_asset_name" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="release_free_asset_status"> Asset Status </label>
                    <select name="free_asset_status" id="release_free_asset_status" class="form-control" required>
                        <option value="">Select Asset Status</option>
                        <option value='Free & Available'>Free & Available</option>
                        <option value='Free & Need to Repair'>Free & Need to Repair</option>
                        <option value='Dead'>Dead</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="release_asset_remark"> Release Description </label>
                    <textarea name="asset_release_remark" id="release_asset_remark" class="form-control" rows="4" placeholder="Enter reason or description for releasing this asset..." required></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal"> Cancel </button>
                <button type="submit" class="btn btn-danger">
                    <i class="fa fa-sign-out-alt mr-1"></i> Release Asset
                </button>
            </div>
            <?= $this->Form->end() ?>

        </div>
    </div>
</div>
<!-- release asset modal end-->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<script type="text/javascript">
    $(document).ready(() => {
        $('.assignEmployee').selectpicker();
        $('.assignEmp').selectpicker();

        $('#editAssetCat').selectpicker();
        $('#modalAssetCat').selectpicker();

        $(".datepicker1").datepicker({
            dateFormat: 'yy-mm-dd'
        });

        let assetCat = $("#editAssetCat").val();
        let asset_id = Number("<?= $asset_id ?>");
        let productName = "<?= $AssetDatas->product_name ?>";

        assetNameSelect(assetCat, asset_id);
        expenseTblData(asset_id, productName);

        fetchAsset(<?= !empty($AssetCategory) ? (int)$AssetCategory->id : 0 ?>);
    });

    $(document).on('change', '#editAssetCat', function () {
        assetNameSelect(this.value);
    });

    $(document).on('change', '#modalAssetCat', function () {
        fetchAsset(this.value);
    });

    function assetNameSelect(id, asset_id = null) {
        if (!id) {
            return false;
        }

        $.ajax({
            url: "<?= $this->Url->build(["controller" => "AssetAssignedEntries", "action" => "fetchAssetData"]) ?>",
            method: "GET",
            data: {
                catId: id,
                asset_id
            },
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            success: (res) => {

                $("#assetData").html(res);
                $('#assetDataId').selectpicker('refresh');
                $.LoadingOverlay("hide");
            },
            error: function(res) {
                $.LoadingOverlay("hide");
            }
        });

    }

    function expenseTblData(asset_id, productName = null) {
        if (!asset_id) {
            return false;
        }
        // console.log('asset_id',asset_id);
        // console.log('productName',productName);
        $.ajax({
            url: "<?= $this->Url->build(["controller" => "AssetAssignedEntries", "action" => "expenseTblData"]) ?>",
            method: "get",
            data: {
                productName,
                asset_id
            },
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            success: (res) => {
                $("#expenseTbl").html(res);
                $("#totalExp").val($("#totalExpense").val());
                $.LoadingOverlay("hide");
                console.log(res);
            },
            error: function(res) {
                $.LoadingOverlay("hide");
            }
        });

    }

    function deleteExpense(id) {
        if (confirm("Are you sure want to delete this expense.")) {
            let asset_id = Number("<?= $asset_id ?>");
            let productName = "<?= $AssetDatas->product_name ?>";
            $.ajax({
                url: "<?= $this->Url->build(["controller" => "AssetAssignedEntries", "action" => "deleteExpense"]) ?>",
                method: "GET",
                data: {
                    id
                },
                beforeSend: function() {
                    $.LoadingOverlay("show");
                },
                success: (res) => {
                    if (res == 1) {
                        expenseTblData(asset_id, productName)
                    }
                    $.LoadingOverlay("hide")
                },
                error: function(res) {
                    $.LoadingOverlay("hide")
                }
            })
        }
    }

    function editExpense(id) {
        $.ajax({
            url: "<?= $this->Url->build(["controller" => "AssetAssignedEntries", "action" => "editExpense"]) ?>",
            method: "GET",
            data: {
                id
            },
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            success: (res) => {
                $("#edit_expenses").html(res);
                $("#edit_expenses").modal("show");
                $(".datepicker").datepicker({
                    dateFormat: 'yy-mm-dd'
                });
                $.LoadingOverlay("hide")
            },
            error: function(res) {
                $.LoadingOverlay("hide")
            }
        })
    }

    function fetchAsset(value) {
        const catId = value;
        let asset_id = Number("<?= $asset_id ?>");

        if (catId == 0) {
            return false;
        }
        // console.log(catId);

        $.ajax({
            url: "<?= $this->Url->build(["controller" => "AssetAssignedEntries", "action" => "fetchAsset"]) ?>",
            method: "GET",
            data: {
                catId
            },
            success: (res) => {
                $data = JSON.parse(res);
                // console.log(JSON.parse(res));

                let row = `<option value="0">Select Asset</option>`;

                $data.forEach(item => {
                    row +=
                        `<option value="${item.id}" ${asset_id ==item.id ? 'selected' : null} >${item.product_name}</option>`
                })

                $("#assetData1").empty().append(row);
                $('#assetData1').selectpicker('refresh');
            }
        });

    }

    $(document).on("click", ".release-asset-btn", function (e) {
// alert()
        e.preventDefault();

        var t = $(this);

        $("#release_asset_id").val(t.data("asset-id"));
        $("#release_assignment_id").val(t.data("assignment-id"));
        $("#release_asset_name").val(t.data("asset-name"));
        $("#release_asset_remark").val(t.attr("data-asset_release_remark") || "" );

        // From edit page
        $("#release_redirect_page").val(
            t.data("redirect-page") || "edit"
        );

        $("#releaseAssetModal").modal("show");
    });
</script>