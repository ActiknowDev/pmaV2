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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $assetAssignedEntry->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $assetAssignedEntry->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Asset Assigned Entries'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="assetAssignedEntries form content">
            <?= $this->Form->create($assetAssignedEntry) ?>
            <fieldset>
                <legend><?= __('Edit Asset Assigned Entry') ?></legend>
                <?php
                    echo $this->Form->control('user_id', ['options' => $users, 'empty' => true]);
                    echo $this->Form->control('asset_id', ['options' => $assetCategories, 'empty' => true]);
                    echo $this->Form->control('created_at');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
