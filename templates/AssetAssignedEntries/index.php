<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-project-diagram"></i></span>Asset Assigned List
                    </div>
                </div>

                <div class="col-md-2">
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
                <div class="col-md-3">
                    <input type="text" class="form-control" onkeyup="filterData(this.value,'category')"
                        autocomplete="off" placeholder="Category filter...">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" onkeyup="filterData(this.value,'assign')" autocomplete="off"
                        placeholder="Assigned filter...">
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
                                <th>Assigned To</th>
                                <th>Serial Number</th>
                                <th>Configuration</th>
                                <th>Amount</th>
                                <th>Expenses</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="filterData">
                            <?php $i = 1;
                            foreach ($asset_data as $ase) : ?>

                            <tr>
                                <td><?= $i; ?></td>
                                <td><a href="<?= $this->Url->build('/asset-assigned-entries/editAssetData/' . $ase->asset_id) ?>"
                                        class="link"><?= $ase->product_name ?></a></td>
                                <td style="white-space:pre-line;">
                                    <?= $ase->cat_name ?>
                                </td>
                                <td><?= $ase->name ? $ase->name : ($ase->free_asset_status ? $ase->free_asset_status : 'Free') ?></td>
                                <td><?= $ase->serial_number ? $ase->serial_number : "--" ?></td>
                                <td style="white-space:pre-line;">
                                    <?= $ase->configuration ? $ase->configuration : "--" ?>
                                </td>
                                <td>₹<?= $ase->asset_price ? $ase->asset_price : 0 ?>
                                </td>
                                
                                <td>₹<?= $ase->expenses_amount ? $ase->expenses_amount : 0 ?>
                                </td>
                                <td><?= date("Y-m-d", strtotime($ase->created_at)) ?></td>
                                <!-- <td><?= date("Y-m-d", strtotime($ase->created_at)) != "1970-01-01" ? date("Y-m-d", strtotime($ase->created_at)) : "--" ?>
                                </td> -->
                                <td>
                                    <a onclick="return confirm('Are you sure')"
                                        href="<?= $this->Url->build(["controller" => 'AssetAssignedEntries', "action" => "delete", $ase->asset_id]) ?>"
                                        title="Delete Project" class="icon icon-sm"><i class="fa fa-trash-alt"></i></a>
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
                                <?= $this->form->select("user_id", $user_data, [
                                    "class" => "form-control assignEmp",
                                    "empty" => "Assigned None",
                                    "data-live-search" => "true"
                                ]); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Category</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-toolbox"></i></span>
                                <select name="categories_id" id="assetCat" onchange="fetchAsset(this)"
                                    class="form-control" required=true>
                                    <option value='0'>Select Category</option>
                                    <?php
                                    foreach ($assetCategories as $value) :
                                    ?>
                                    <option value="<?= $value->id ?>"><?= $value->cat_name ?></option>
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
                                <select name="asset_id" id="assetData" data-live-search="true" class="form-control"
                                    required=true>
                                    <option value="0">Select Asset</option>
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


<div class="modal fade bd-example-modal-lg" id="entries_log" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">View Assign Asset Log</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Asset</th>
                            <th scope="col">User</th>
                            <th scope="col">DOA</th>
                            <th scope="col">DOR</th>
                        </tr>
                    </thead>
                    <tbody id="entries_log_id">
                        <!-- <tr>
      <th scope="row">1</th>
      <td>Mark</td>
      <td>Otto</td>
      <td>SK</td>
      <td>23889</td>
    </tr> -->

                    </tbody>
                </table>


            </div>


        </div>
    </div>
</div>



<input id="log_get_url" type="hidden" value="<?= $this->Url->build([
                                                    "controller" => "AssetAssignedEntries",
                                                    "action" => "getlogentries",
                                                ]); ?>" hidden>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<script type="text/javascript">
$(document).ready(() => {
    $('.assignEmp').selectpicker();
})

function loadData(id) {
    // console.log(id);\
    $.ajax({
        url: "<?= $this->Url->build(["controller" => "AssetAssignedEntries", "action" => "editAsset"]) ?>",
        method: "GET",
        data: {
            id
        },
        success: (res) => {
            $("#editAsset").html(res);
            $("#editAsset").modal("show");
            $("#editAssetId").selectpicker();
        }
    })
}

function loadDataModel(ele) {

    var id = $(ele).attr("data-id");

    var assign_to = $(ele).attr("data-assign");

    var asset = $(ele).attr("data-asset");

    var serial_number = $(ele).attr("data-serial");


    $("#edit_it").val(id);
    $("#assigned_to_edit").val(assign_to);
    $("#asset_id_edit").val(asset);
    $("#serial_no_edit").val(serial_number)

}


$(".log-entries").click(function() {

    var url = $("#log_get_url").val();

    var asset_id = $(this).attr("data-asset");

    var token = $("input[name='_csrfToken']").val();

    var url =
        $.ajax({
            type: "POST",
            url: url,
            data: {
                _csrfToken: token,
                asset_id: asset_id
            },
            beforeSend: function() {},
            success: function(data) {

                $("#entries_log_id").empty();
                var response = $.parseJSON(data);

                response.forEach((element, index) => {

                    $("#entries_log_id").append(
                        `<tr><td>${index+1}</td>
                            <td>${element["asset_data"]["product_name"]}</td>
                            <td>${element["user"]["name"]}</td>
                            <td>${element["created_at"]}</td>
                            <td>${element["dor"]}</td>
                            `
                    )

                    // console.log(element);

                })

            }
        });

});

function fetchAsset(value) {
    const catId = value.value;

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
                row += `<option value="${item.id}">${item.product_name}</option>`
            })

            $("#assetData").empty().append(row);
            $('#assetData').selectpicker('refresh');
        }
    });

}

function filterData(value, type) {
    $.ajax({
        url: "<?= $this->Url->build(['controller' => 'AssetAssignedEntries', 'action' => 'filterData']) ?>",
        method: "GET",
        data: {
            type,
            value
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