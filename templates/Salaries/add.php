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
            <?= $this->Html->link(__('List Salaries'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="salaries form content">
            <?= $this->Form->create($salary) ?>
            <fieldset>
                <legend><?= __('Add Salary') ?></legend>
                <?php
                    echo $this->Form->control('user_id', ['options' => $users]);
                    echo $this->Form->control('amount');
                    echo $this->Form->control('from_date');
                    echo $this->Form->control('created_at');
                    echo $this->Form->control('created_by');
                    echo $this->Form->control('amount_type');
                    echo $this->Form->control('remark');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
