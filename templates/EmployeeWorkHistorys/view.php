<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\EmployeeWorkHistory $employeeWorkHistory
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Employee Work History'), ['action' => 'edit', $employeeWorkHistory->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Employee Work History'), ['action' => 'delete', $employeeWorkHistory->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employeeWorkHistory->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Employee Work Historys'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Employee Work History'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="employeeWorkHistorys view content">
            <h3><?= h($employeeWorkHistory->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $employeeWorkHistory->has('user') ? $this->Html->link($employeeWorkHistory->user->name, ['controller' => 'Users', 'action' => 'view', $employeeWorkHistory->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($employeeWorkHistory->id) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Cmp Name') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($employeeWorkHistory->cmp_name)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Cmp Desgnation') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($employeeWorkHistory->cmp_desgnation)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Cmp Location') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($employeeWorkHistory->cmp_location)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Cmp Doj') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($employeeWorkHistory->cmp_doj)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Cmp Dor') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($employeeWorkHistory->cmp_dor)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Cmp Splip') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($employeeWorkHistory->cmp_splip)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
