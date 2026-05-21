<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AssetAssignedEntry $assetAssignedEntry
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Asset Assigned Entry'), ['action' => 'edit', $assetAssignedEntry->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Asset Assigned Entry'), ['action' => 'delete', $assetAssignedEntry->id], ['confirm' => __('Are you sure you want to delete # {0}?', $assetAssignedEntry->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Asset Assigned Entries'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Asset Assigned Entry'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="assetAssignedEntries view content">
            <h3><?= h($assetAssignedEntry->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $assetAssignedEntry->has('user') ? $this->Html->link($assetAssignedEntry->user->name, ['controller' => 'Users', 'action' => 'view', $assetAssignedEntry->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Asset Category') ?></th>
                    <td><?= $assetAssignedEntry->has('asset_category') ? $this->Html->link($assetAssignedEntry->asset_category->id, ['controller' => 'AssetCategories', 'action' => 'view', $assetAssignedEntry->asset_category->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($assetAssignedEntry->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created At') ?></th>
                    <td><?= h($assetAssignedEntry->created_at) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
