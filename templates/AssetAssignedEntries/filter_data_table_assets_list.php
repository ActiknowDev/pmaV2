<?php $i = 1;
foreach ($assets as $ase) : 
    // echo "<pre>";print_r($ase->asset_category->cat_name);//die();
$status=$ase->free_asset_status;
$class='';
if($status=="Dead")
    $class="status-dead";
elseif(stripos($status,"Repair")!==false)
    $class="status-repair";
else
    $class="status-free";?>

    <tr>
        <td><?= $i; ?></td>
        <td>
            <a href="javascript:void(0)" 
            class="view-asset link"
            
            data-product="<?= h($ase->product_name) ?>"
            data-category="<?= h($ase->asset_category->cat_name) ?>"
            data-serial="<?= h($ase->serial_number) ?>"
            data-configuration="<?= h($ase->configuration) ?>"
            data-amount="<?= number_format($ase->asset_price) ?>"
            data-expense="<?= number_format($ase->expense_amount) ?>"
            data-date="<?= date('d M Y',strtotime($ase->date_of_assign)) ?>"
            data-date-of-purchase="<?= date('d M Y',strtotime($ase->date_of_purchase)) ?>"
            data-status="<?= h($ase->free_asset_status ?: 'Available') ?>"
            data-description="<?= h($ase->description) ?>"
            >
                <?= h($ase->product_name) ?>
            </a>
        </td>

        <td><?= h($ase->asset_category->cat_name); ?></td>
        <!-- <td><?= h($ase->serial_number); ?></td>
        <td><?=$ase->configuration!='' ? h($ase->configuration) : '--'; ?></td> -->
        <td>₹<?= number_format($ase->asset_price); ?></td>
        <td>₹<?= number_format($ase->expense_amount); ?></td>
        <td>
            <?php if (!empty($ase->user_name)): ?>
                <span class="status-assigned"><strong>Assigned</strong><small>Owner: <?= h($ase->user_name) ?></small> </span>                                    
            <?php elseif (!empty($ase->free_asset_status)): ?>
                <span class="<?= $class; ?>"><?= h($ase->free_asset_status) ?> </span>
            <?php else: ?>
                <span class="<?= $class; ?>"> Free </span>
            <?php endif; ?>
        </td>
        <!-- <td><?= $ase->date_of_assign ? date('Y-m-d',strtotime($ase->date_of_assign)) : "--" ?></td> -->
        <td><?= date('Y-m-d',strtotime($ase->created_at)); ?></td>
        <td>
            <?php if (!empty($ase->user_name)): ?>

                <!-- Assigned -->
                <a href="javascript:void(0)"
                    class="btn btn-sm btn-outline-danger release-asset-btn"
                    data-asset-id="<?= h($ase->id) ?>"
                    data-asset-assignment-id="<?= h($ase->assignment_id) ?>"
                    data-asset-name="<?= h($ase->product_name) ?>"
                    data-description="<?= h($ase->description ?? '') ?>"
                    title="Release Asset">
                        <i class="fa fa-sign-out-alt mr-1"></i>
                        <span>Release Asset</span>
                </a>

            <?php elseif ($ase->free_asset_status == 'Free & Available'): ?>

                <!-- Available -->
                <a href="javascript:void(0)"
                class="v-btn v-btn-primary assign-asset-btn"
                data-target="#assign_asset"
                data-toggle="modal"
                data-asset-id="<?= h($ase->id) ?>"
                data-product="<?= h($ase->product_name) ?>"
                data-category-id="<?= h($ase->category_id) ?>"
                data-category="<?= h($ase->cat_name) ?>">

                    <i class="fa fa-plus"></i>
                    <span>Assign Asset</span>
                </a>

            <?php endif; ?>

            <a href="<?= $this->Url->build('/asset-assigned-entries/editAssetData/' . $ase->id) ?>" title="Edit Asset" class="icon icon-sm"><i class="fa fa-pencil-alt"></i></a>
        </td>
    </tr>

<?php $i++;
endforeach; ?>