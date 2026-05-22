<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CareerTrack $careerTrack
 */
?>
<div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">

             <?=$this->Form->create($careerTrack,[
                'url' => [
                    'controller' => 'CareerTracks',
                    'action' => 'update'
                ]]) ?>

                <div class="modal-header">
                    <h5 class="modal-title">Edit Career Track</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="content">
                        <div class="form-group">
                            <label for="">Career Track Name</label>
                            <div class="adon-group">
                                <?= $this->Form->text('name', ['class' => 'form-control','required'=>true]);?>
                                <?= $this->Form->hidden('id', ['class' => 'form-control','required'=>true]);?>
                            </div>
                        </div>
                    </div>  
                </div>
                <div class="modal-footer">
                    <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                    <button  class="v-btn v-btn-primary" type="submit">Save</button>
                </div>
            <?= $this->Form->end() ?>
    </div>
</div>