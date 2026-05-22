<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\EmployeeReference[]|\Cake\Collection\CollectionInterface $employeeReferences
 */
?>
<div class="employeeReferences index content">
    <?= $this->Html->link(__('New Employee Reference'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Employee References') ?></h3>
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
                <?php foreach ($employeeReferences as $employeeReference): ?>
                <tr>
                    <td><?= $this->Number->format($employeeReference->id) ?></td>
                    <td><?= $employeeReference->has('user') ? $this->Html->link($employeeReference->user->name, ['controller' => 'Users', 'action' => 'view', $employeeReference->user->id]) : '' ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $employeeReference->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $employeeReference->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $employeeReference->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employeeReference->id)]) ?>
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
