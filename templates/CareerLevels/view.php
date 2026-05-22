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
            <?= $this->Html->link(__('Edit Career Level'), ['action' => 'edit', $careerLevel->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Career Level'), ['action' => 'delete', $careerLevel->id], ['confirm' => __('Are you sure you want to delete # {0}?', $careerLevel->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Career Levels'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Career Level'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="careerLevels view content">
            <h3><?= h($careerLevel->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($careerLevel->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($careerLevel->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= $this->Number->format($careerLevel->status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created At') ?></th>
                    <td><?= h($careerLevel->created_at) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified At') ?></th>
                    <td><?= h($careerLevel->modified_at) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
