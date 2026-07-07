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
            <?= $this->Html->link(__('List Leaves'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="leaves form content">
            <?= $this->Form->create($leave) ?>
            <fieldset>
                <legend><?= __('Add Leave') ?></legend>
                <?php
                    echo $this->Form->control('leave_type');
                    echo $this->Form->control('subject');
                    echo $this->Form->control('applied_on', ['empty' => true]);
                    echo $this->Form->control('from_date', ['empty' => true]);
                    echo $this->Form->control('to_date', ['empty' => true]);
                    echo $this->Form->control('created_by');
                    echo $this->Form->control('approved_by');
                    echo $this->Form->control('status');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
