<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CareerLevel $careerLevel
 */
?>
<div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">

             <?=$this->Form->create($careerLevel,[
                'url' => [
                    'controller' => 'CareerLevels',
                    'action' => 'update'
                ]]) ?>

                <div class="modal-header">
                    <h5 class="modal-title">Edit Career Level</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="content">
                        <div class="form-group">
                            <label for="">Career Level* </label>
                            <div class="adon-group">
                                <?= $this->Form->number('level', ['class' => 'form-control','required'=>true,'maxlength'=>'4']);?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Career Level Name*</label>
                            <div class="adon-group">
                                <?= $this->Form->text('name', ['class' => 'form-control','required'=>true]);?>
                                <?= $this->Form->hidden('id', ['class' => 'form-control','required'=>true]);?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Career Level Description</label>
                            <div class="adon-group">
                                <?= $this->Form->textarea('description', ['class' => 'form-control','style'=>'height: 131px;','rows'=>'10']);?>
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
