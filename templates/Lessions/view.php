<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Lession $lession
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Lession'), ['action' => 'edit', $lession->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Lession'), ['action' => 'delete', $lession->id], ['confirm' => __('Are you sure you want to delete # {0}?', $lession->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Lessions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Lession'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="lessions view content">
            <h3><?= h($lession->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($lession->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($lession->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Course Id') ?></th>
                    <td><?= $this->Number->format($lession->course_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= $this->Number->format($lession->status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created At') ?></th>
                    <td><?= h($lession->created_at) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified At') ?></th>
                    <td><?= h($lession->modified_at) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
