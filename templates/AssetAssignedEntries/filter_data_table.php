<?php $i = 1;
foreach ($assigned_entries as $ase) : ?>

    <tr>
        <td><?= $i; ?></td>
        <td><a href="<?= $this->Url->build('/asset-assigned-entries/editAssetData/' . $ase['asset_id']) ?>" class="link"><?= $ase["asset_data"]["product_name"] ?></a></td>
        <td><a href="#" class="ft-secondary"><?= $ase["asset_data"]["asset_category"]["cat_name"] ?></a>
        </td>
        <td><?= @$ase["user"]["name"] ?></td>
        <td><?= $ase["asset_data"]["serial_number"] ?></td>
        <td><?= $ase["asset_data"]["configuration"] ? $ase["asset_data"]["configuration"] : "--" ?>
        </td>
        <td>₹<?= $ase["asset_data"]["asset_price"] ? $ase["asset_data"]["asset_price"] : 0 ?>
        </td>
        <?php
        $totalExpenses = 0;
        foreach ($ase["asset_data"]["asset_expenses"] as $expVal) {
            $totalExpenses += $expVal->expenses_amount;
        }

        ?>
        <td>₹<?= $totalExpenses ?></td>
        <td><?= date("Y-m-d", strtotime($ase['created_at'])) ?></td>
        <!-- <td><?= date("Y-m-d", strtotime($ase['dor'])) != "1970-01-01" ? date("Y-m-d", strtotime($ase['dor'])) : "--" ?>
                                </td> -->
        <td>
            <a onclick="return confirm('Are you sure')" href="<?= $this->Url->build(["controller" => 'AssetAssignedEntries', "action" => "delete", $ase["id"]]) ?>" title="Delete Project" class="icon icon-sm"><i class="fa fa-trash-alt"></i></a>
        </td>
    </tr>

<?php $i++;
endforeach; ?>