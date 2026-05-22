<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Competency[]|\Cake\Collection\CollectionInterface $competencies
 */
?>
<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-project-diagram"></i></span>Competencies List
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= $this->Url->build('/Users/login/'); ?>"><i class="fa fa-home"></i> Home</a></li>
                                <li class="breadcrumb-item"><a href="<?= $this->Url->build('/roles_responsibilities/'); ?>"> Roles & Responsibilities</a></li>
                                <li class="breadcrumb-item active">Competencies List</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-6">
                    <div class="actions-ctrl text-md-right mt-2">

                        <a href="#" class="v-btn v-btn-secondary" data-target="#add_competency" data-toggle="modal" data-backdrop="static" data-keyboard="false">
                            <i class="fa fa-plus"></i><span>Add Competency</span>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content">
        <div class="container">
        <div class="row">
                <div class="col-md-12">
                    <?= $this->Flash->render() ?>
                    <table  class="table table-light nowrap table-sm"  id="example" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1; 
                            foreach ($competencies as $competency): ?>
                            <tr>
                                <td><?= $i; ?></td>
                                <td><?= $competency->name ?></td>
                                <td>
                                    <input class="tgl tgl-light change-status" id="<?= $competency->id;?>" type="checkbox" value="<?=$competency->status; ?>"  <?= $competency->status == 1?'checked':'' ?>/>
                                    <label class="tgl-btn" for="<?= $competency->id;?>"></label>
                                </td>
                                <td>
                                    <!-- <?= $this->Html->link('<i class="fa fa-pencil-alt"></i>','/Competencies/edit/'.$competency->id,['class' => 'icon ft-primary icon-sm','escape' => false]); ?> -->
                                    <a href="#" class="" onClick="editCompetency(<?=$competency->id?>)" >
                                        <i class="fa fa-pencil-alt"></i><span></span>
                                    </a>

                                    <a href="" data-toggle="modal" data-id="<?= $competency->id;?>" data-target="#confirm" data-type="entry" class="icon icon-sm delete" data-url="<?= $this->Url->build()?>">
                                      <i class="fa fa-archive"></i>
                                    </a> 
                                                                         
                                    <!-- <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $competency->id], ['confirm' => __('Are you sure you want to delete # {0}?', $careerTrack->id)]) ?> -->
                                </td>
                            </tr>
                            <?php $i++; endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- add competency -->
<div class="modal fade" tabindex="-1" role="dialog" id="add_competency">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <?=$this->Form->create(null,[
            'url' => [
                'controller' => 'Competencies',
                'action' => 'add'
            ]]) ?>

                <div class="modal-header">
                    <h5 class="modal-title">Add Competency</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="content">
                        <div class="form-group">
                            <label for="">Competency Name</label>
                            <div class="adon-group">
                                <?= $this->Form->text('name', ['class' => 'form-control','required'=>true]);?>
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
</div>

<!-- edit competency -->
<div class="modal fade" tabindex="-1" role="dialog" id="edit_competency" data-backdrop="static" data-keyboard="false">

</div>

<!-- delete competency -->
<div class="modal" id="confirm">
    <div class="modal-dialog">
        <div class="modal-content">
      
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        <input type="hidden" name="p_url" id="p_url" value="">
            <!-- Modal body -->
            <form id='delete-data'>
                <input type="hidden" name="p_id" id="p_id" value="">
                
            </form>
            <div class="modal-body no-padding">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="widget">
                                <div class="widget-content">
                                    <h2>Do You Want to Archive this Competency?<span class="fw-600 name"></span>?</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Cancel</button>
                <button type="button" id="deleteConfirmBtn" class="v-btn v-btn-primary" data-dismiss="modal">Yes</button>             
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script type="text/javascript">
   //Status Change
       $('.change-status').click(function() {
         var id = $(this).attr('id');
         var status = $(this).val();               
               if(status == 1) {
                   status = 0; 
               } else {
                   status = 1; 
               }   
               $.ajax({                  
                   type:'GET',
                   url:"<?= $this->Url->build('/Competencies/updateStatus/'); ?>"+id+'/'+status,                  
                   beforeSend: function (){
                    $.LoadingOverlay("show");
                   },
                    success:function(data){
                        $.LoadingOverlay("hide");
                   },
                    error: function (response) {
                        $.LoadingOverlay("hide");
                    }
               });
       });
      
        $('#deleteConfirmBtn').click(function () {
            var id = $('#p_id').val();
            var url = $('#p_url').val();
            url = url + '/delete/';
            $.ajax({
                url: url+id,
                method: 'GET',
                success: function (returnData) {
                    location.reload();
                }
            })
        });

        function editCompetency(id)
        {
            $.ajax({
                url:  "<?= $this->Url->build('/Competencies/edit/'); ?>"+id,
                type: 'GET',
                beforeSend: function() { 
                    // $.LoadingOverlay("show");
                },
                success: function (response) {
                    // $.LoadingOverlay("hide");
                    $("#edit_competency").html(response);
                    $("#edit_competency").modal("show");
                },
                error: function (response) {  
                    alertBox("NOt working");              
                }
            });
        }

</script>
