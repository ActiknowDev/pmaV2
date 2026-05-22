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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $employeeReference->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $employeeReference->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Employee References'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>

            <?php echo $this->Html->link("Back", array('controller' => 'EmployeeDetails', 'class'=>'side-nav-item','action' => 'edit', $employeeReference->id));?>

        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="employeeReferences form content">
            <?= $this->Form->create($employeeReference) ?>
            <fieldset>
                <legend><?= __('Edit Employee Reference') ?></legend>
                <?php
                    // echo $this->Form->control('user_id', ['options' => $users]);
                    echo $this->Form->control('ref_name');
                    echo $this->Form->control('ref_org');
                    echo $this->Form->control('ref_desigtion');
                    echo $this->Form->control('ref_address');
                    echo $this->Form->control('ref_contact');
                    // echo $this->Form->control('ref_flag');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
