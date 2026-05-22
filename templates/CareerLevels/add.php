<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CareerLevel $careerLevel
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Career Levels'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="careerLevels form content">
            <?= $this->Form->create($careerLevel) ?>
            <fieldset>
                <legend><?= __('Add Career Level') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('status');
                    echo $this->Form->control('created_at');
                    echo $this->Form->control('modified_at');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
