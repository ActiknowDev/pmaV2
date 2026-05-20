<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Salary $salary
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Salary'), ['action' => 'edit', $salary->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Salary'), ['action' => 'delete', $salary->id], ['confirm' => __('Are you sure you want to delete # {0}?', $salary->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Salaries'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Salary'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="salaries view content">
            <h3><?= h($salary->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $salary->has('user') ? $this->Html->link($salary->user->name, ['controller' => 'Users', 'action' => 'view', $salary->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Amount Type') ?></th>
                    <td><?= h($salary->amount_type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($salary->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Amount') ?></th>
                    <td><?= $this->Number->format($salary->amount) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created By') ?></th>
                    <td><?= $this->Number->format($salary->created_by) ?></td>
                </tr>
                <tr>
                    <th><?= __('From Date') ?></th>
                    <td><?= h($salary->from_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created At') ?></th>
                    <td><?= h($salary->created_at) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Remark') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($salary->remark)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
