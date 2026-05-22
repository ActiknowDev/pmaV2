<style>
    b, strong {
    font-weight: bolder !important;
}
.card-text ul {
    list-style-type: disc;
    padding-left: 35px;
}

.card-text ol {
    list-style-type: decimal;
    padding-left: 35px;
}

.card-text li {
    margin-bottom: 5px;
}
.cke_dialog {
    z-index: 999999 !important;
}
.modal {
    z-index: 1050;
}

/* table css */
.card-text table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.card-text table,
.card-text th,
.card-text td {
    border: 1px solid #ccc;
}

.card-text th,
.card-text td {
    padding: 8px;
    text-align: left;
}

.card-text th {
    background-color: #f5f5f5;
    font-weight: bold;
}
</style>
<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-users" aria-hidden="true"></i></span>Notice
                    </div>
                </div>
                <?php if (in_array(12, $role_name)) : ?>
                    <div class="col-lg-6 col-sm-4 pdr-0">
                        <div class="actions-ctrl text-md-right mt-2">
                            <?= $this->Html->link('<i class="fa fa-envelope mx-2" aria-hidden="true"></i><span>Add Notice</span>', [], [
                                'class' => "btn btn-dark",
                                "data-toggle" => "modal",
                                "data-target" => "#addModal",
                                "escape" => false,
                            ]); ?>

                        </div>
                    </div>
                <?php endif;  ?>
            </div>
        </div>
    </div>

    <!-- PAGE-CONTENT -->

    <div class="container mt-5">
        <div class="row">
            <?php foreach ($notice_data as $value) :  ?>
                <div class="col-md-6 my-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title font-weight-bold"><?= $value['title'] ?></h5>
                            <div class="card-text">
                                <?= html_entity_decode($value['description']) ?>
                            </div>
                        </div>
                        <?php if (in_array(12, $role_name)) : ?>
                            <div class="d-flex flex-row-reverse">
                                <a onclick="deleteNotice(<?= $value['id'] ?>)" style="cursor: pointer;" class="icon icon-sm icon-danger mb-1 mx-1"><i class="fa fa-archive"></i></a>
                                <a class="icon icon-sm icon-secondary mb-1 mx-1" data-toggle="modal" data-target="#updateModal" style="cursor: pointer;" onclick="updateNotice(<?= $value['id'] ?>)"><i class="fa fa-pencil-alt"></i></a>
                            </div>
                        <?php endif;  ?>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    


    <!-- Add Notice button modal -->

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Notice</h5>
                    <button type="button" class="close closeBtn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= $this->Form->create(null, ['type' => 'post']) ?>
                    <p id="error" style="color: red;"></p>
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" style="font-weight: 400;" placeholder="Title">
                        <input type="hidden" name="user_id" id="uId" value="<?= $user_id ?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="desc" id="description"></textarea>
                    </div>
                    <button type="button" id="addBtn" class="btn btn-info">Add Notice</button>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>


    <!-- End Notice button modal -->

    <!-- Update Notice button modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Notice</h5>
                    <button type="button" class="close closeBtn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= $this->Form->create(null, ['type' => 'post']) ?>
                    <p id="updateError" style="color: red;"></p>
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" style="font-weight: 400;" id="updateTitle" placeholder="Title">
                        <input type="hidden" name="user_id" id="updateId" value="<?= $user_id ?>">
                        <input type="hidden" name="noticeId" id="noticeId">
                    </div>
                    <div class="form-group">
                        <label for="updateDescription">Description</label>
                        <textarea class="form-control" name="editDesc" id="updateDescription"></textarea>
                    </div>
                    <button type="button" id="updateBtn" class="btn btn-info">Update Notice</button>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>

    <!-- End Update Notice button modal -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- CKEditor CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.18.0/ckeditor.js" integrity="sha512-woYV6V3QV/oH8txWu19WqPPEtGu+dXM87N9YXP6ocsbCAH1Au9WDZ15cnk62n6/tVOmOo0rIYwx05raKdA4qyQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
     <!-- <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script> -->
<!-- <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script> -->
    <script>
        CKEDITOR.replace('description', {
            height: 200,
            extraPlugins: 'uploadimage,image2',
            removePlugins: 'image',
            uploadUrl: '<?= $this->Url->build(["controller" => "PublishNotice", "action" => "uploadImage"]) ?>',
            filebrowserUploadUrl: '<?= $this->Url->build(["controller" => "PublishNotice", "action" => "uploadImage"]) ?>',
            // filebrowserUploadMethod: 'form',
            clipboard_handleImages: true,
            on: {
                fileUploadRequest: function(evt) {
                    var xhr = evt.data.fileLoader.xhr;
                    const token = $('input[name="_csrfToken"]').attr('value');

                    xhr.setRequestHeader('X-CSRF-Token', token);
                }
            }
        });
        CKEDITOR.replace('updateDescription', {
            height: 200,
            extraPlugins: 'uploadimage,image2',
            removePlugins: 'image',
            uploadUrl: '<?= $this->Url->build(["controller" => "PublishNotice", "action" => "uploadImage"]) ?>',
            filebrowserUploadUrl: '<?= $this->Url->build(["controller" => "PublishNotice", "action" => "uploadImage"]) ?>',
            // filebrowserUploadMethod: 'form',
            clipboard_handleImages: true,
            on: {
                fileUploadRequest: function(evt) {
                    var xhr = evt.data.fileLoader.xhr;
                    const token = $('input[name="_csrfToken"]').attr('value');

                    xhr.setRequestHeader('X-CSRF-Token', token);
                }
            }
        });
        $(document).ready(function() {
            $('.closeBtn').click(function() {
                // location.reload();
            })
            // Add New Notice Method
            $('#addBtn').click(function() {

                let title = document.getElementById("title").value;
                let desc = CKEDITOR.instances['description'].getData();
                // let desc = document.getElementById("description").value;
                let uId = document.getElementById("uId").value;
                let data;
                if (title.length < 5 || desc.length < 5) {
                    document.getElementById('error').innerText =
                        "* Title and Description must be at least 5 character.";
                    data = false
                } else {
                    document.getElementById('error').innerText = "";
                    data = true;
                }
                if (data) {
                    const token = $('input[name="_csrfToken"]').attr('value');
                    $.ajax({
                        url: '<?= $this->Url->build(['controller' => 'PublishNotice', 'action' => 'addNotice']) ?>',
                        method: 'POST',
                        headers: {
                            'X-CSRF-Token': token
                        },
                        data: {
                            user_id: uId,
                            title: title,
                            description: desc
                        },
                        success: function(res) {
                            if (res == 1) {
                                location.reload();
                            }
                        }
                    })
                }

            });
            // End Add new notice method

            // Update notice method
            $('#updateBtn').click(function() {

                let title = document.getElementById("updateTitle").value;
                let desc = CKEDITOR.instances['updateDescription'].getData();
                // let desc = document.getElementById("updateDescription").value;
                let uId = document.getElementById("updateId").value;
                let noticeId = document.getElementById("noticeId").value;
                let data;
                if (title.length < 5 || desc.length < 5) {
                    document.getElementById('updateError').innerText =
                        "* Title and Description must be at least 5 character.";
                    data = false
                } else {
                    document.getElementById('updateError').innerText = "";
                    data = true;
                }
                if (data) {
                    const token = $('input[name="_csrfToken"]').attr('value');
                    $.ajax({
                        url: '<?= $this->Url->build(['controller' => 'PublishNotice', 'action' => 'updateNotice']) ?>',
                        method: 'PUT',
                        headers: {
                            'X-CSRF-Token': token,
                        },
                        data: {
                            user_id: uId,
                            title: title,
                            description: desc,
                            noticeId: noticeId
                        },
                        success: function(res) {
                            if (res == 1) {
                                location.reload();
                            }
                        }
                    });
                }

            });
            // End update notice method
        });

        // Delete notice method

        function deleteNotice(id) {
            if (confirm('Are you sure want to delete this notice.')) {
                $.ajax({
                    url: '<?= $this->Url->build(['controller' => 'PublishNotice', 'action' => 'deleteNotice']) ?>',
                    method: 'GET',
                    data: {
                        id: id
                    },
                    success: function(res) {
                        if (res == 1) {
                            location.reload();
                        }
                    }
                });
            }
        }
        // End delete notice method

        // Update notice method

        function updateNotice(id) {
            // console.log(id);
            $.ajax({
                method: 'GET',
                url: '<?= $this->Url->build(['controller' => 'PublishNotice', 'action' => 'updateNotice']) ?>',
                data: {
                    id: id,
                },
                success: function(res) {
                    jsonData = JSON.parse(res);
                    jsonData.forEach(element => {
                        $('#updateTitle').val(element.title);
                        CKEDITOR.instances['updateDescription'].setData(element.description)
                        // $('#updateDescription').val(element.description);
                        $('#noticeId').val(element.id);
                    });
                }
            });
        }
        // End update notice method
    </script>