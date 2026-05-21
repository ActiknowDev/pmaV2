<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Leave $leave
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Leave'), ['action' => 'edit', $leave->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Leave'), ['action' => 'delete', $leave->id], ['confirm' => __('Are you sure you want to delete # {0}?', $leave->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Leaves'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Leave'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="leaves view content">
            <h3><?= h($leave->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Leave Type') ?></th>
                    <td><?= h($leave->leave_type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($leave->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created By') ?></th>
                    <td><?= $this->Number->format($leave->created_by) ?></td>
                </tr>
                <tr>
                    <th><?= __('Approved By') ?></th>
                    <td><?= $this->Number->format($leave->approved_by) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= $this->Number->format($leave->status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Applied On') ?></th>
                    <td><?= h($leave->applied_on) ?></td>
                </tr>
                <tr>
                    <th><?= __('From Date') ?></th>
                    <td><?= h($leave->from_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('To Date') ?></th>
                    <td><?= h($leave->to_date) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Subject') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($leave->subject)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
