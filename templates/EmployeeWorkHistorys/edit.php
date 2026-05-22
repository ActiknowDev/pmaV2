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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $employeeWorkHistory->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $employeeWorkHistory->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Employee Work Historys'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="employeeWorkHistorys form content">
            <?= $this->Form->create($employeeWorkHistory) ?>
            <fieldset>
                <legend><?= __('Edit Employee Work History') ?></legend>
                <?php
                    echo $this->Form->control('user_id', ['options' => $users]);
                    echo $this->Form->control('cmp_name');
                    echo $this->Form->control('cmp_desgnation');
                    echo $this->Form->control('cmp_location');
                    echo $this->Form->control('cmp_doj');
                    echo $this->Form->control('cmp_dor');
                    echo $this->Form->control('cmp_splip');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
