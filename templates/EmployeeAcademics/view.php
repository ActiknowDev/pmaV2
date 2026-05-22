<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\EmployeeAcademic $employeeAcademic
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Employee Academic'), ['action' => 'edit', $employeeAcademic->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Employee Academic'), ['action' => 'delete', $employeeAcademic->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employeeAcademic->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Employee Academics'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Employee Academic'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="employeeAcademics view content">
            <h3><?= h($employeeAcademic->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $employeeAcademic->has('user') ? $this->Html->link($employeeAcademic->user->name, ['controller' => 'Users', 'action' => 'view', $employeeAcademic->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($employeeAcademic->id) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Ac Type') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($employeeAcademic->ac_type)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Ac Org') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($employeeAcademic->ac_org)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Ac Education') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($employeeAcademic->ac_education)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Ac Passout') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($employeeAcademic->ac_passout)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Acc Certificate') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($employeeAcademic->acc_certificate)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Acc Mark') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($employeeAcademic->acc_mark)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
