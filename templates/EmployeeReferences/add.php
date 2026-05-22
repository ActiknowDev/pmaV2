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
            <?= $this->Html->link(__('List Employee References'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="employeeReferences form content">
            <?= $this->Form->create($employeeReference) ?>
            <fieldset>
                <legend><?= __('Add Employee Reference') ?></legend>
                <?php
                    // echo $this->Form->control('user_id', ['options' => $users,'empty'=>$user_id,'readonly'=>true]);
                ?>
                <input type="hidden" name="user_id" value="<?=$user_id?>">
                <?php
                    echo $this->Form->control('ref_name',['label'=>'Name']);
                    echo $this->Form->control('ref_org',['label'=>'Organization']);
                    echo $this->Form->control('ref_desigtion',['label'=>'Designation']);
                    echo $this->Form->control('ref_address',['label'=>'']);
                    echo $this->Form->control('ref_contact');
                    echo $this->Form->control('ref_flag');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
