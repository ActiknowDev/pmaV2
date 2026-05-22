<?php die('coming soon!');
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Training $training
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Training'), ['action' => 'edit', $training->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Training'), ['action' => 'delete', $training->id], ['confirm' => __('Are you sure you want to delete # {0}?', $training->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Training'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Training'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="training view content">
            <h3><?= h($training->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($training->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($training->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= $this->Number->format($training->status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created At') ?></th>
                    <td><?= h($training->created_at) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified At') ?></th>
                    <td><?= h($training->modified_at) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
