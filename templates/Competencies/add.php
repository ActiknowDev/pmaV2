<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Competency $competency
 */
?>

<section class="page page-dashboard">
     <!-- PAGE-TITLE -->
     <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-project-diagram"></i></span>Add New Competency
                    </div>
                </div>
                <div class="col-6">
                    <div class="actions-ctrl text-md-right">
                        <?= $this->Html->link('<i class="fa fa-list"></i><span>List Competencies</span>','/Competencies/index',['class' => 'v-btn v-btn-secondary','escape' => false]); ?>
                            
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- PAGE-CONTENT -->
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                        <?= $this->Flash->render() ?>
                    <div class="block">
                        <div class="header">
                            <h4 class="title">Add Competency Details</h4>
                        </div>
                        <?= $this->Form->create($competency) ?>

                            <div class="content ">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Competency Name</label>
                                            <div class="adon-group">
                                                <?= $this->Form->text('name', ['class' => 'form-control','required'=>true]);?>
                                            </div>
                                        </div>
                                    </div>                            
                                </div>
                                <div class="row">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <div class="form-group" style="margin-top:22px;">
                                            <button type="submit" name="submit" class="v-btn v-btn-secondary float-right"><span>Save Competency</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?= $this->Form->end() ?>
                    </div> 

                </div>
            </div>
        </div>
    </div>
</section>
