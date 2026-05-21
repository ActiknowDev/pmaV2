<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AssetCategory $assetCategory
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Asset Category'), ['action' => 'edit', $assetCategory->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Asset Category'), ['action' => 'delete', $assetCategory->id], ['confirm' => __('Are you sure you want to delete # {0}?', $assetCategory->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Asset Categories'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Asset Category'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="assetCategories view content">
            <h3><?= h($assetCategory->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Cat Name') ?></th>
                    <td><?= h($assetCategory->cat_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($assetCategory->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created At') ?></th>
                    <td><?= h($assetCategory->created_at) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Assets') ?></h4>
                <?php if (!empty($assetCategory->assets)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Asset Categorie Id') ?></th>
                            <th><?= __('Product Name') ?></th>
                            <th><?= __('Created At') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($assetCategory->assets as $assets) : ?>
                        <tr>
                            <td><?= h($assets->id) ?></td>
                            <td><?= h($assets->asset_categorie_id) ?></td>
                            <td><?= h($assets->product_name) ?></td>
                            <td><?= h($assets->created_at) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Assets', 'action' => 'view', $assets->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Assets', 'action' => 'edit', $assets->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Assets', 'action' => 'delete', $assets->id], ['confirm' => __('Are you sure you want to delete # {0}?', $assets->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
