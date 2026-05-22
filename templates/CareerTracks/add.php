<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CareerTrack $careerTrack
 */
?>
<section class="page page-dashboard">
     <!-- PAGE-TITLE -->
     <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-project-diagram"></i></span>Add New Career Track
                    </div>
                </div>
                <div class="col-6">
                    <div class="actions-ctrl text-md-right">
                        <?= $this->Html->link('<i class="fa fa-list"></i><span>List Career Tracks</span>','/CareerTracks/index',['class' => 'v-btn v-btn-secondary','escape' => false]); ?>
                            
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
                            <h4 class="title">Add Career Track Details</h4>
                        </div>
                        <?= $this->Form->create($careerTrack) ?>

                            <div class="content ">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Career Track Name</label>
                                            <div class="adon-group cname">
                                                <?= $this->Form->text('name', ['class' => 'form-control','required'=>true]);?>
                                            </div>
                                            <label id="tags-error-empty" class="error" for="tags"></label>
                                        </div>
                                    </div>                            
                                </div>
                                <div class="row">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <div class="form-group" style="margin-top:22px;">
                                            <button type="submit" name="submit" class="v-btn v-btn-secondary float-right"><span>Save Career Track</span></button>
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