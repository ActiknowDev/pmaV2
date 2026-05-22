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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $employeeAcademic->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $employeeAcademic->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Employee Academics'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="employeeAcademics form content">
            <?= $this->Form->create($employeeAcademic) ?>
            <fieldset>
                <legend><?= __('Edit Employee Academic') ?></legend>
                <?php
                    echo $this->Form->control('user_id', ['options' => $users]);
                    echo $this->Form->control('ac_type');
                    echo $this->Form->control('ac_org');
                    echo $this->Form->control('ac_education');
                    echo $this->Form->control('ac_passout');
                    echo $this->Form->control('acc_certificate');
                    echo $this->Form->control('acc_mark');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
