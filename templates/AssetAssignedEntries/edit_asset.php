<div class="modal-dialog" role="document">
    <div class="modal-content">

        <?= $this->Form->create(null, [
            'url' => [
                'controller' => 'AssetAssignedEntries',
                'action' => 'editAsset'
            ]
        ]) ?>

        <input type="hidden" name="id" value="<?= $assetAssignId ?>">

        <div class="modal-header">
            <h5 class="modal-title">Edit Assigned Asset</h5>
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
                            <select name="user_id" id="edit" class="form-control" disabled>
                                <option value='0'>Select Assign User</option>
                                <?php
                                foreach ($user_data as $user) :
                                ?>
                                    <option value="<?= $user->id ?>" <?= $userId == $user->id ? "selected" : null ?>>
                                        <?= $user->name ?></option>
                                <?php
                                endforeach;
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
                            <select name="categories_id" id="editAssetCat" onchange="fetchAsset(this)" class="form-control" required=true>
                                <option value='0'>Select Category</option>
                                <?php
                                foreach ($assetCategories as $value) :
                                ?>
                                    <option value="<?= $value->id ?>" <?= $categoriesId == $value->id ? "selected" : null ?>>
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

                            <select name="asset_id" id="editAssetId" class="form-control" data-live-search="true" required=true>
                                <option value='0'>Select Asset</option>
                                <?php
                                foreach ($asset_data as $asset) :
                                ?>
                                    <option value="<?= $asset->id ?>" <?= $assetId == $asset->id ? "selected" : null ?>>
                                        <?= $asset->product_name ?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
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