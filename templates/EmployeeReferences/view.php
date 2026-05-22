<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\EmployeeReference $employeeReference
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Employee Reference'), ['action' => 'edit', $employeeReference->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Employee Reference'), ['action' => 'delete', $employeeReference->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employeeReference->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Employee References'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Employee Reference'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="employeeReferences view content">
            <h3><?= h($employeeReference->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $employeeReference->has('user') ? $this->Html->link($employeeReference->user->name, ['controller' => 'Users', 'action' => 'view', $employeeReference->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($employeeReference->id) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Ref Name') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($employeeReference->ref_name)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Ref Org') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($employeeReference->ref_org)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Ref Desigtion') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($employeeReference->ref_desigtion)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Ref Address') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($employeeReference->ref_address)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Ref Contact') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($employeeReference->ref_contact)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Ref Flag') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($employeeReference->ref_flag)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
