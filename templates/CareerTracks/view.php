<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CareerTrack $careerTrack
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Career Track'), ['action' => 'edit', $careerTrack->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Career Track'), ['action' => 'delete', $careerTrack->id], ['confirm' => __('Are you sure you want to delete # {0}?', $careerTrack->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Career Tracks'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Career Track'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="careerTracks view content">
            <h3><?= h($careerTrack->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($careerTrack->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($careerTrack->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= $this->Number->format($careerTrack->status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created At') ?></th>
                    <td><?= h($careerTrack->created_at) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified At') ?></th>
                    <td><?= h($careerTrack->modified_at) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
