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

    .status-assigned {
        display: inline-flex;
        flex-direction: column;
        line-height: 1.2;
    }

    .status-assigned strong {
        color: #2962ff;
        font-size: 12px;
        font-weight: 600;
    }

    .status-assigned small {
        color: #777;
        font-size: 10px;
        font-style: italic;
        margin-top: 2px;
    }
   .lable{
        font-weight:600!important;
   }
    .release-asset-btn{
        display: inline-flex;
        align-items: center;
        font-size: 12px;
    }
    .assign-asset-btn{
        display: inline-flex;
        align-items: center;
        font-size: 12px;
        line-height: 25px;
    }
    
    /* view assets modal */
    .asset-title{
        font-size:24px;
        font-weight:600;
        color:#2d3748;
        margin-bottom:25px;
        border-bottom:1px solid #ececec;
        padding-bottom:15px;
    }

    .asset-grid{
        display:grid;
        grid-template-columns:repeat(2,1fr);
        gap:18px 30px;
    }

    .asset-item label{
        display:block;
        font-size:12px;
        text-transform:uppercase;
        letter-spacing:.5px;
        color:#8b8b8b;
        margin-bottom:5px;
        font-weight:600;
    }

    .asset-item div{
        font-size:15px;
        color:#2d3748;
        font-weight:500;
        word-break:break-word;
    }

    .asset-description{
        margin-top:25px;
        padding-top:20px;
        border-top:1px solid #ececec;
    }

    .asset-description label{
        display:block;
        font-size:12px;
        text-transform:uppercase;
        color:#8b8b8b;
        margin-bottom:8px;
        font-weight:600;
    }

    .asset-description div{
        color:#444;
        line-height:1.7;
        white-space:pre-wrap;
    }

    @media(max-width:768px){

        .asset-grid{
            grid-template-columns:1fr;
        }

    }
    /* view assets modal end */
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
                <div class="col">
                    <label>Category</label>
                    <select id="categoryFilter" class="form-control" onchange="filterData(this.value,'category')">
                        <option value="">Select Category</option>
                        <?php foreach ($assetCategories as $catVal) { ?>
                            <option value="<?= $catVal->cat_name ?>">
                                <?= $catVal->cat_name ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col">
                    <label>Assigned To</label>
                    <select id="assignFilter" class="form-control" data-live-search="true" onchange="filterData(this.value,'assign')">
                        <option value="">Select Employee</option>                        
                        <?php foreach ($user_data as $id => $name): ?>
                            <option value="<?= h($name) ?>">
                                <?= h($name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col">
                    <label>Status</label>
                    <select id="statusFilter"class="form-control" onChange="filterData(this.value,'status')">
                        <option value=''>Select</option>
                        <option value='Free & Available'>Free & Available</option>
                        <option value='Free & Need to Repair'>Free & Need to Repair</option>
                        <option value='Dead'>Dead</option>
                    </select>
                </div>

                <div class="col">
                    <label>Date of Purchase From</label>
                    <input type="text" class="datepicker1 form-control" onchange="filterData(this.value,'date_of_purchase')"
                         id="date_from" autocomplete="off" placeholder="Date of Purchase From">
                </div>

                <div class="col">
                    <label>Date of Purchase To</label>
                    <input type="text" class="datepicker1 form-control" onchange="filterData(this.value,'date_of_purchase')"
                         id="date_to" autocomplete="off" placeholder="Date of Purchase To">
                </div>
            </div>

            <hr class="dark">


            <div class="row">
                <div class="col-md-12">
                    <table data-page-length='25' class="table table-light nowrap table-sm" id="example"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Asset Name</th>
                                <th>Category</th>
                                <!-- <th>Serial Number</th>
                                <th>Configuration</th> -->
                                <th>Amount</th>
                                <th>Expenses</th>
                                <th>Status</th>
                                <!-- <th>Assigned On</th> -->
                                <th>Date of Purchase</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody id="filterData">
                            <?php
                            // echo "<pre>"; print_r($assets);die(' dsds');
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
                                <td><?= $i; ?></td>
                                <td>
                                    <a href="javascript:void(0)" 
                                        class="view-asset link"                                        
                                        data-product="<?= h($asset->product_name) ?>"
                                        data-category="<?= h($asset->cat_name) ?>"
                                        data-serial="<?= h($asset->serial_number) ?>"
                                        data-configuration="<?= h($asset->configuration) ?>"
                                        data-amount="<?= number_format($asset->asset_price) ?>"
                                        data-expense="<?= number_format($asset->expense_amount) ?>"
                                        data-date="<?= date('d M Y',strtotime($asset->date_of_assign)) ?>"
                                        data-date-of-purchase="<?= date('d M Y',strtotime($asset->date_of_purchase)) ?>"
                                        data-status="<?= h($asset->free_asset_status ?: 'Available') ?>"
                                        data-description="<?= h($asset->description) ?>"
                                        >
                                        <?= h($asset->product_name) ?>
                                    </a>
                                </td>

                                <td><?= h($asset->cat_name); ?></td>
                                <td>₹<?= number_format($asset->asset_price); ?></td>
                                <td>₹<?= number_format($asset->expense_amount); ?></td>
                               <td>
                                    <?php if (!empty($asset->user_name)): ?>
                                        <span class="status-assigned"><strong>Assigned</strong><small>Owner: <?= h($asset->user_name) ?></small> </span>                                    
                                    <?php elseif (!empty($asset->free_asset_status)): ?>
                                        <span class="<?= $class; ?>"><?= h($asset->free_asset_status) ?> </span>
                                    <?php else: ?>
                                        <span class="<?= $class; ?>"> Free </span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('Y-m-d',strtotime($asset->date_of_purchase)); ?></td>
                                <td>
                                    <?php if (!empty($asset->user_name)): ?>
                                        <!-- Assigned -->
                                        <a href="javascript:void(0)"
                                            class="btn btn-sm btn-outline-danger release-asset-btn"
                                            data-asset-id="<?= h($asset->id) ?>"
                                            data-asset-assignment-id="<?= h($asset->assignment_id) ?>"
                                            data-asset-name="<?= h($asset->product_name) ?>"
                                            data-asset_release_remark="<?= h($asset->asset_release_remark ?? '') ?>"
                                            title="Release Asset">
                                                <i class="fa fa-sign-out-alt mr-1"></i><span>Release Asset</span>
                                        </a>
                                    <?php elseif ($asset->free_asset_status == 'Free & Available'): ?>
                                        <!-- Available -->
                                        <a href="javascript:void(0)"
                                            class="v-btn v-btn-primary assign-asset-btn"
                                            data-target="#assign_asset"
                                            data-toggle="modal"
                                            data-asset-id="<?= h($asset->id) ?>"
                                            data-product="<?= h($asset->product_name) ?>"
                                            data-category-id="<?= h($asset->category_id) ?>"
                                            data-category="<?= h($asset->cat_name) ?>">
                                                <i class="fa fa-plus"></i><span>Assign Asset</span>
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?= $this->Url->build('/asset-assigned-entries/editAssetData/' . $asset->id) ?>" title="Edit Asset" class="icon icon-sm"><i class="fa fa-pencil-alt"></i></a>
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
            <?= $this->Form->hidden('redirect_page', [
                'value' => 'list'
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
                                    <option value="">Select Category</option>
                                    <?php foreach ($assetCategories as $catVal) { ?>
                                        <option value="<?= $catVal->id ?>">
                                            <?= $catVal->cat_name ?>
                                        </option>
                                    <?php } ?>
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
                                <?= $this->form->number("asset_price", [
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
                                    <option value="">Select Asset Status</option>
                                    <option value='Free & Available'>Free & Available</option>
                                    <option value='Free & Need to Repair'>Free & Need to Repair</option>
                                    <option value='Dead'>Dead</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Date of Purchase</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fas fa-calendar-alt"></i></span>
                                <?= $this->form->date("date_of_purchase", [
                                    "class" => "datepicker1 form-control",
                                    "autocomplete" => "off",
                                    "placeholder" => "Enter Asset date of purchase",
                                ]); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Description</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fas fa-align-left"></i></span>
                                <?= $this->form->textarea("description", [
                                    "class" => "form-control",
                                    "autocomplete" => "off",
                                    "placeholder" => "Enter Asset Description",
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

<!-- Show Asset Details Modal -->
 <div class="modal fade" id="viewAssetModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-laptop mr-2"></i>Asset Details</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="asset-view">
                    <div class="asset-title" id="m_product"></div>
                    <div class="asset-grid">
                        <div class="asset-item">
                            <label>Category</label>
                            <div id="m_category"></div>
                        </div>
                        <div class="asset-item">
                            <label>Status</label>
                            <div id="m_status"></div>
                        </div>
                        <div class="asset-item">
                            <label>Serial Number</label>
                            <div id="m_serial"></div>
                        </div>
                        <div class="asset-item">
                            <label>Configuration</label>
                            <div id="m_configuration"></div>
                        </div>
                        <div class="asset-item">
                            <label>Amount</label>
                            <div id="m_amount"></div>
                        </div>
                        <div class="asset-item">
                            <label>Expenses</label>
                            <div id="m_expense"></div>
                        </div>
                        <div class="asset-item">
                            <label>Date of Purchase</label>
                            <div id="m_date_of_purchase"></div>
                        </div>
                    </div>
                    <div class="asset-description">
                        <label>Description</label>
                        <div id="m_description"></div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button class="v-btn v-btn-base" data-dismiss="modal"> Close </button>
            </div>
        </div>
    </div>
</div>

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
            <?= $this->Form->hidden('redirect_page', [
                'value' => 'list'
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
                                <select name="user_id" class="form-control assignEmp" data-live-search="true">
                                    <option value="">Select Employee</option>
                                    
                                    <?php foreach ($user_data as $id => $name): ?>
                                        <option value="<?= h($id) ?>">
                                            <?= h($name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Category</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-toolbox"></i></span>
                                <input type="text"
                                    id="a_category"
                                    class="form-control"
                                    placeholder="Category"
                                    readonly>

                                <!-- Category ID submitted to PHP -->
                                <input type="hidden"
                                    name="categories_id"
                                    id="a_category_id">

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Asset</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-toolbox"></i></span>
                                <!-- Display asset name -->
                                <input type="text"
                                    id="a_asset"
                                    class="form-control"
                                    placeholder="Asset"
                                    readonly>

                                <!-- Asset ID submitted to PHP -->
                                <input type="hidden"
                                    name="asset_id"
                                    id="a_asset_id">

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

<!-- Release Asset Modal -->
 <div class="modal fade" id="releaseAssetModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <?= $this->Form->create(null, [
                'url' => [
                    'controller' => 'AssetAssignedEntries',
                    'action' => 'releaseAsset'
                ]
            ]) ?>
            <input type="hidden" name="redirect_page" id="release_redirect_page" value="list">
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
                    <label for="asset_release_remark"> Release Description </label>
                    <textarea name="asset_release_remark" id="asset_release_remark" class="form-control" rows="4" placeholder="Enter reason or description for releasing this asset..." required></textarea>
                </div>
                <input type="hidden" name="asset_id" id="release_asset_id">
                <input type="hidden" name="assignment_id" id="release_asset_assignment_id">

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
<!-- Release Asset Modal end -->



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script>
    $(document).ready(() => {

        $(".datepicker1").datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            changeYear: true
        });

        $(document).on("click", ".datepicker1", function () {
            $(this).datepicker("show");
        });

    })
    $(document).on("click",".view-asset",function(e){

        e.preventDefault();

        var t=$(this);

        $("#m_product").text(t.data("product"));
        $("#m_category").text(t.data("category"));
        $("#m_serial").text(t.data("serial"));
        $("#m_configuration").text(t.data("configuration"));
        $("#m_amount").text("₹"+t.data("amount"));
        $("#m_expense").text("₹"+t.data("expense"));
        $("#m_date_of_purchase").text(t.data("date-of-purchase"));
        $("#m_status").text(t.data("status"));
        $("#m_description").text(t.data("description") || "No description available.");

        $("#viewAssetModal").modal("show");

    });
    $(document).on("click",".assign-asset-btn",function(e){
         e.preventDefault();

        var t = $(this);

        // Get values from clicked row
        var assetId = t.data("asset-id");
        var product = t.data("product");
        var categoryId = t.data("category-id");
        var category = t.data("category");

        console.log("Asset ID:", assetId);
        console.log("Asset:", product);
        console.log("Category ID:", categoryId);
        console.log("Category:", category);

        // Display values
        $("#a_asset").val(product);
        $("#a_category").val(category);

        // Set hidden IDs for form submission
        $("#a_asset_id").val(assetId);
        $("#a_category_id").val(categoryId);

        // Reset employee and date
        $(".assignEmp").val("");

        $("#assign_asset").modal("show");

    });

    $(document).on("click", ".release-asset-btn", function (e) {

        e.preventDefault();

        var t = $(this);

        var assetId = t.data("asset-id");
        var assetAssignmentId = t.data("asset-assignment-id");
        var assetName = t.data("asset-name");
        var description = t.attr("data-description") || "";

        // Set selected asset
        $("#release_asset_id").val(assetId);
        $("#release_asset_assignment_id").val(assetAssignmentId);
        $("#release_asset_name").val(assetName);
        $("#asset_release_remark").val(description);

        // Open modal
        $("#releaseAssetModal").modal("show");
    });
    function filterData(value, type) {
        var from = "";
        var to = "";

        if (type == 'category') {
            $("#assignFilter").val("");
            $("#statusFilter").val("");
            $("#date_from").val("");
            $("#date_to").val("");
        }

        // Assigned To filter
        else if (type == 'assign') {
            $("#categoryFilter").val("");
            $("#statusFilter").val("");
            $("#date_from").val("");
            $("#date_to").val("");
        }

        // Status filter
        else if (type == 'status') {
            $("#categoryFilter").val("");
            $("#assignFilter").val("");
            $("#date_from").val("");
            $("#date_to").val("");
        }

        // Date of Purchase filter
        else if (type == 'date_of_purchase') {

            // Reset OTHER filters
            $("#categoryFilter").val("");
            $("#assignFilter").val("");
            $("#statusFilter").val("");

            // Keep both date fields
            from = $("#date_from").val();
            to = $("#date_to").val();

            value = from;
        }

        // var to ;
        // if (type == 'date_of_purchase') {
        //     var from = $("#date_from").val();
        //     to = $("#date_to").val();
        //     value = from;
        // }

        $.ajax({
            url: "<?= $this->Url->build(['controller' => 'AssetAssignedEntries', 'action' => 'assetsDataFilter']) ?>",
            method: "GET",
            data: {
                type,
                value,
                to
            },
            beforeSend: function() {
                $.LoadingOverlay("show")
            },
            success: function(res) {
                $("#filterData").html(res);
                $.LoadingOverlay("hide")
            },
            error: function() {
                $.LoadingOverlay("hide");
            }
        })

    }
</script>