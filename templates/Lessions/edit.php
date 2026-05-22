<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Lession $lession
 */
?>
<div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">

             <?=$this->Form->create($lession,[
                'url' => [
                    'controller' => 'Lessions',
                    'action' => 'update'
                ],'enctype'=>'multipart/form-data']) ?>

                <div class="modal-header">
                    <h5 class="modal-title">Edit Lession</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="content">
                        <div class="form-group">
                            <label for="">Select Training</label>
                            <div class="adon-group">
                                <?=$this->form->select("training_id",$trainings,[
                                    "class"=>"form-control",
                                    "empty"=>"Select Training",
                                    "default"=>$lession->course->training->id,
                                    "required"=>true,
                                    "onchange"=>"refeshCourses(this)"
                                ]);?>
                            </div>
                        </div>

                        <div class="form-group">
                                <label for="">Select Course</label>
                                <div class="adon-group">
                                    <?=$this->form->select("course_id",$courses,[
                                        "class"=>"form-control course_id",
                                        "empty"=>"Select Course",
                                        "default"=>$lession->course_id,
                                        "required"=>true
                                    ]);?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Lession Name</label>
                                <div class="adon-group">
                                    <?= $this->Form->text('name', ['class' => 'form-control','required'=>true]);?>
                                    <?= $this->Form->hidden('id', ['class' => 'form-control','required'=>true]);?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Upload Document</label>
                                <div class="adon-group">
                                    <?= $this->Form->file('files[]', ['class' => 'form-control lession_document','onChange'=>'validateFileExtension()','id'=>'lession_document','multiple'=>true]);?>
                                </div>
                                    <span id="file_error" class="text-danger"></span>
                            </div>

                            <div class="m-2" style="padding-left: 17px;">
                                <?php if(!empty($lession->lession_documents)){ ?> 
                                    <ul class="list-unstyled">
                                        <?php
                                        foreach($lession->lession_documents as $lession_document){
                                            $doc_url = $this->Url->image('Lessions/'.$lession_document->lession_id."/".$lession_document->name);
                                            $delete_url = $this->Url->build('/Lessions/deleteLessonDocument/').$lession_document->id; ?> 
                                            
                                                <li id="doc_<?=$lession_document->id?>">
                                                    <a  href="#" onclick="deleteLessonDocument(<?=$lession_document->id?>)"  title="Delete File" ><?=$lession_document->name?>
                                                    <span style="padding-left: 10px;"><i class="fa fa-times"></i></span>
                                                </li>                                      

                                            <?php
                                        } ?>
                                    </ul>
                                <?php } ?>
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