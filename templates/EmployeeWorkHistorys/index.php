<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\EmployeeWorkHistory[]|\Cake\Collection\CollectionInterface $employeeWorkHistorys
 */
?>
<div class="employeeWorkHistorys index content">
    <?= $this->Html->link(__('New Employee Work History'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Employee Work Historys') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employeeWorkHistorys as $employeeWorkHistory): ?>
                <tr>
                    <td><?= $this->Number->format($employeeWorkHistory->id) ?></td>
                    <td><?= $employeeWorkHistory->has('user') ? $this->Html->link($employeeWorkHistory->user->name, ['controller' => 'Users', 'action' => 'view', $employeeWorkHistory->user->id]) : '' ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $employeeWorkHistory->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $employeeWorkHistory->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $employeeWorkHistory->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employeeWorkHistory->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
