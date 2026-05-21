<select name="asset_id" id="assetDataId" data-live-search="true" class="form-control" required=true>
    <option value="0">Select Asset</option>
    <?php foreach ($assetData as $val) :  ?>
    <option value="<?= $val->id ?>" <?= $val->id == $asset_id ? 'selected' : null ?>><?= $val->product_name ?></option>
    <?php endforeach; ?>
</select>