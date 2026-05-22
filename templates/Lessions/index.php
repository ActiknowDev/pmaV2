<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Lession[]|\Cake\Collection\CollectionInterface $lessions
 */
?>

<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-project-diagram"></i></span>Lessions List
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= $this->Url->build('/Users/login/'); ?>"><i class="fa fa-home"></i> Home</a></li>
                                <li class="breadcrumb-item"><a href="<?= $this->Url->build('/training/'); ?>">Training</a></li>
                                <li class="breadcrumb-item active">Lessions List</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-6">
                    <div class="actions-ctrl text-md-right mt-2">
                        <a href="#" class="v-btn v-btn-secondary" data-target="#add_lession" data-toggle="modal" data-backdrop="static" data-keyboard="false">
                            <i class="fa fa-plus"></i><span>Add Lession</span>
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
                                <th>Training Name</th>
                                <th>Course Name</th>
                                <th>Lession Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1;
                            foreach ($lessions as $lession):?>
                            <tr>
                                <td><?= $i; ?></td>
                                <td><?= $lession->course->training->name ?></td>
                                <td><?= $lession->course->name ?></td>
                                <td><?= $lession->name ?></td>
                                <td>
                                    <input class="tgl tgl-light change-status" id="<?= $lession->id;?>" type="checkbox" value="<?=$lession->status; ?>"  <?= $lession->status == 1?'checked':'' ?>/>
                                    <label class="tgl-btn" for="<?= $lession->id;?>"></label>
                                </td>
                                <td>
                                    <a href="#" class="" onClick="editLession(<?=$lession->id?>)" >
                                        <i class="fa fa-pencil-alt"></i><span></span>
                                    </a>

                                    <a href="#" data-toggle="modal" data-id="<?= $lession->id;?>" data-target="#confirm" data-type="entry" class="icon icon-sm delete" data-url="<?= $this->Url->build()?>">
                                      <i class="fa fa-archive"></i>
                                    </a> 
                                                   
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
<!-- add Lession -->
<div class="modal fade" tabindex="-1" role="dialog" id="add_lession">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <?=$this->Form->create(null,[
            'url' => [
                'controller' => 'Lessions',
                'action' => 'add'
            ],'enctype'=>'multipart/form-data']) ?>

                <div class="modal-header">
                    <h5 class="modal-title">Add Lession</h5>
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
                                    "required"=>true,
                                    "onchange"=>"refeshCourses(this)"
                                ]);?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Select Course</label>
                            <div class="adon-group">
                                <select class="form-control course_id" name="course_id">
                                <option value=''>Select Course</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Lession Name</label>
                            <div class="adon-group">
                                <?= $this->Form->text('name', ['class' => 'form-control','required'=>true]);?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Upload Document</label>
                            <div class="adon-group">
                                <?= $this->Form->file('files[]', ['class' => 'form-control lession_document','onChange'=>'validateFileExtension()','id'=>'lession_document','multiple'=>true]);?>
                            </div>
                                <span id="file_error" class="text-danger"></span>
                        </div>
                    </div>  
                </div>
                <div class="modal-footer">
                    <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                    <button  class="v-btn v-btn-primary saveBtn" type="submit">Save</button>
                </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<!-- edit lession -->
<div class="modal fade" tabindex="-1" role="dialog" id="edit_lession" data-backdrop="static" data-keyboard="false">

</div>

<!-- delete lession -->
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
                                    <h2>Do You Want to Archive this lession?<span class="fw-600 name"></span>?</h2>
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
            url:"<?= $this->Url->build('/Lessions/updateStatus/'); ?>"+id+'/'+status,                  
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

    function editLession(id){ 
        $.ajax({
            url:  "<?= $this->Url->build('/Lessions/edit/'); ?>"+id,
            type: 'GET',
            beforeSend: function() { 
                // $.LoadingOverlay("show");
            },
            success: function (response) {
                // $.LoadingOverlay("hide");
                $("#edit_lession").html(response);
                $("#edit_lession").modal("show");
            },
            error: function (response) {  
                alertBox("NOt working");              
            }
        });
    }
    function refeshCourses(el){
        let training_id = el.value;

        $.ajax({                  
            type:'GET',
            url:"<?= $this->Url->build('/Lessions/getCoursesByTrainingId/'); ?>"+training_id,                  
            beforeSend: function (){
                $.LoadingOverlay("show");
            },
            success:function(response){
                $.LoadingOverlay("hide");
                let obj = JSON.parse(response);
                var html = "<option value=''>Select Course</option>";
                if(obj.success){
                    for (const [key, value] of Object.entries(obj.data)) {
                        html += '<option value="'+ key +'">' + value + '</option>';
                    }                    }
                $('.course_id').html(html);
            },
            error: function (response) {
                $.LoadingOverlay("hide");
                console.log(response);
            }
        });
    }

    function validateFileExtension() {
        var totalfiles = document.getElementById('lession_document').files.length;
        var error_flag = 0; 
        var names= '';
        for (var index = 0; index < totalfiles; index++) {
            names = document.getElementById('lession_document').files[index].name;

            var ext = names.split(".");
            ext = ext[ext.length-1].toLowerCase(); 
            var arrayExtensions = ["jpeg","jpg","JPEG","JPG","pdf","doc","txt","docx"]; 

            if (arrayExtensions.lastIndexOf(ext) == -1) { 
            error_flag++;
            }
        }
        if (error_flag > 0) {
            $(".saveBtn").attr("disabled","disabled");
            $("#file_error").text("Allowed extensions are jpeg,jpg,png,PNG,JPEG,JPG,pdf,doc,txt,docx ");
        }else{
            $(".saveBtn").removeAttr("disabled");
            $("#file_error").text('');
        }
    }

    function deleteLessonDocument(doc_id) {
        let text = "Are you sure you want to delete this?";
        if (confirm(text) == true) {
            text = "You pressed OK!";
            $.ajax({
                url: "<?=$this->Url->build('/Lessions/deleteLessonDocument/'); ?>"+doc_id,     
                type: 'GET',
                success: function (response) {
                    if(response == 1){
                        document.getElementById('doc_'+doc_id).remove();
                    }
                }
            })
        }
    }
</script>