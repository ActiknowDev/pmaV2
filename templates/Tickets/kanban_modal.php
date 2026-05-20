<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-13 fw-semibold" id="exampleModalLabel"><?= $ticketData[0]['title'] ?>
                        <div>
                            <span class="fs-10">Added by <?= ($ticketData[0]['role_id'] == 2 ? $ticketData[0]['client_name'] : $ticketData[0]['manager_name'])  ?></span>
                        </div>
                    </h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="location.reload()"><span aria-hidden="true">&times;</span></button>

                </div>
                <div class="modal-body">
                    <div class="row d-flex">
                        <div class="col-md-9">

                            <!-- Client Name  -->
                           <div class="row mb-2">
                                <div class="col-md-1">
                                    <div class="icon me-3 text-center bg-secondary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fa fa-user"></i>
                                    </div>
                                </div>
                                <div class="col-md-11">
                                    <div class="border-bottom pb-3">
                                        <p class="fs-13 fw-semibold mb-0">Client Name</p>
                                        <div class="mt-2">
                                        <?= $ticketData[0]['client_name'] ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           <!-- End -->

                           <!-- Project Type  -->
                           <div class="row mb-2">
                                <div class="col-md-1">
                                    <div
                                        class="icon me-3 text-center bg-secondary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fa fa-building"></i>
                                    </div>
                                </div>
                                <div class="col-md-11">
                                    <div class="border-bottom pb-3">
                                        <p class="fs-13 fw-semibold mb-0">Project Name</p>
                                        <div class="mt-2">
                                        <?= $ticketData[0]['project_name'] ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           <!-- End -->

                            <div class="row mb-2">
                                <div class="col-md-1">
                                    <div
                                        class="icon me-3 text-center bg-secondary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-tag-fill"></i>
                                    </div>
                                </div>
                                <div class="col-md-11">
                                    <div class="border-bottom pb-3">
                                        <p class="fs-13 fw-semibold mb-0">Ticket Type</p>
                                        <div class="mt-2">
                                        <?php
                                        $labelName = '';
                                            if ($ticketData[0]->ticket_type == 1) {
                                                $labelName = "Bug";
                                                $className = "danger";
                                            } else if ($ticketData[0]->ticket_type == 2) {
                                                $labelName = "Feature Enhancement";
                                                $className = "info";
                                            } else if ($ticketData[0]->ticket_type == 3) {
                                                $labelName = "Change";
                                                $className = "success";
                                            }
                                        ?>
                                        <ul class="list-unstyled m-0 d-flex align-items-center">
                                        <li class="bg-<?= $className ?>-subtle text-<?= $className ?> py-1 px-2 fs-12 fw-semibold rounded me-1"><?= $labelName ?></li>
                                        </ul>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-1">
                                    <div
                                        class="icon me-3 text-center bg-secondary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-body-text"></i>
                                    </div>
                                </div>
                                <div class="col-md-11">
                                    <div class="border-bottom pb-3">
                                        <p class="fs-13 fw-semibold mb-0">Notes</p>
                                        <div class="mt-2">
                                            <p class="fs-12 mb-0">
                                               <?= $ticketData[0]->description ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                           
                            <!-- Attachmen Div Section Start  -->
                            <?php if(!empty($ticketAttachment)) { ?>
                            <div class="row mb-2">
                                <div class="col-md-1">
                                    <div class="icon me-3 text-center bg-secondary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-paperclip" style="rotate: 45deg;"></i>
                                    </div>
                                </div>
                              
                                <!-- Attachment Data   -->
                                <div class="col-md-11">
                                    <div class="border-bottom pb-3">
                                        <div class="d-flex align-items-center">
                                            <p class="fs-13 fw-semibold mb-0">Attachments</p>
                                        </div>

                                        <!-- Loop Start Here  -->
                                        <?php  
                                            foreach ($ticketAttachment as $document) {
                                            if($document->added_by == 1)
                                            {
                                                $imagePath = WEBURL.'/img/tickets_file/';
                                            }
                                            else
                                            {
                                                $imagePath = BUG_REPORTING;
                                            }
                                        
                                        if ($document->doc_type == 1) { ?>
                                            <div class="my-2">
                                                <div class="d-flex attachment-img">
                                                <a href="<?=$imagePath ?>/<?= $document->document ?>" target="_blank">
                                                    <img src="https://cdn-icons-png.flaticon.com/128/2991/2991108.png" class="rounded" alt="Your Image Description">
                                                </a>
                                                    <div class="ms-2">
                                                        <a href="<?= $imagePath ?>/<?= $document->document ?>" target="_blank" class="fs-12 fw-semibold"><?= $document->document ?></a>
                                                        <div class="d-flex align-items-center"></div>
                                                        <span class="fs-10">Uploaded at <?= $ticketData[0]->created ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="my-2">
                                            <div class="d-flex attachment-img">
                                                <img src="<?= $imagePath ?>/<?= $document->document ?>" class="rounded">
                                                <div class="ms-2">
                                                    <a href="<?= $imagePath ?>/<?= $document->document ?>" class="fs-12 fw-semibold" target="_blank"><?= $document->document ?></a>
                                                    <div class="d-flex align-items-center">
                                                    </div>
                                                    <span class="fs-10">Uploaded at <?= $ticketData[0]->created ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php } ?>
                                        <!-- Loop End Here  -->

                                    </div>
                                </div>
                                <!-- End  -->
                     
                            </div>
                            <?php } ?>
                            <!-- End Attachment Div Section  -->
                           


                            <?php if($ticketData[0]->status != 2) { ?>
                            <div class="row mb-2">
                                <div class="col-md-1">
                                    <div
                                        class="icon me-3 text-center bg-secondary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-chat"></i>
                                    </div>
                                </div>

                               
                                <div class="col-md-11">
                                    <div class="border-bottom pb-3">
                                        <p class="fs-13 fw-semibold mb-0">Comments</p>
                                        <div class="row mt-2">
                                            <div class="col-md-1">
                                                <div class="avatar avatar-xs">
                                                    <img src="<?= WEBURL ?>img/Actiknow/user.png" class="rounded-circle">
                                                </div>
                                            </div>
                                            <div class="col-md-11">
                                                <?= $this->Form->create(null, ['class' => 'border rounded']) ?>
                                                    <textarea class="form-control w-100 border-0" rows="3" style="resize: none;"  id="commentMessage" ></textarea>
                                                    <div class="bg-light d-flex align-items-center p-2">
                                                    <button class="btn btn-primary rounded fs-12" type="submit" onclick="event.preventDefault(); commentAddShow(<?= $ticketData[0]->id ?>, <?= $userSession['id'] ?>, '<?= $userSession['name'] ?>')">Save</button>
                                                        <div class="ms-auto d-flex align-items-center">
                                                        <input type="file" id="fileInput" style="display: none;">
                                                        <a href="#" class="fw-semibold text-dark" onclick="document.getElementById('fileInput').click(); return false;">
                                                        <i class="bi bi-paperclip"></i>
                                                        </a>
                                                        </div>
                                                    </div>
                                                    <?= $this->Form->end() ?>
                                            </div>
                                        </div>

                                        <!-- Comments Start -->
                                        <div id="showComment">
                                        <?php
                                        if (count($commentData) > 0) {
                                            foreach ($commentData as $commVal) {
                                        ?>

                                        <div class="row d-flex align-items-center mt-2">
                                            <div class="col-md-1">
                                                <div class="avatar avatar-xs">
                                                 <img src="<?= WEBURL ?>/img/Actiknow/user.png" class="rounded-circle">
                                                </div>
                                            </div>
                                            <div class="col-md-11">
                                                <div class="bg-light d-flex align-items-center fs-12 p-2 rounded">
                                                    <p class="fw-bold"><?= (empty($commVal->client_name) ? ucfirst($userSession['name']): ucfirst($commVal->client_name)) ?>:</p>
                                                    <span>&nbsp; <?= $commVal->comment_notes ?> </span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="fs-12 text-secondary"><?= $commVal->cmt_time; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } } ?>
                                        </div>
                                        <!-- Comments End -->
                                    </div>
                                </div>
                               
                            </div>
                            <?php } ?>
                          
                        </div>
                        <div class="col-md-3">
                            <div class="mb-2">
                                <p class="fs-13 fw-semibold">Status</p>
                                <a
                                    class="mb-2 d-flex align-items-center bg-light text-secondary fs-12 fw-semibold p-2 rounded">
                                    <select id="statusId" class="form-control"
                                            aria-label=".form-select-sm example"
                                            onchange="changeStatus(<?= $ticketData[0]->id ?>)">
                                            <option value="3" <?= $ticketData[0]->status == 3 ? 'selected' : '' ?>>Created
                                            </option>
                                            <option value="1" <?= $ticketData[0]->status == 1 ? 'selected' : '' ?>>In-Progress
                                            </option>
                                            <option value="2" <?= $ticketData[0]->status == 2 ? 'selected' : '' ?>>Resolved
                                            </option>
                                         </select>
                                </a>
                            </div>
                            <div class="">
                                <p class="fs-13 fw-semibold">Ticket Type</p>
                                <a
                                    class="mb-2 d-flex align-items-center bg-light text-secondary fs-12 fw-semibold p-2 rounded">
                                    <select id="typeId" class="form-control"
                                            aria-label=".form-select-sm example"
                                            onchange="changeType(<?= $ticketData[0]->id ?>)">
                                            <option value="1" <?= $ticketData[0]->ticket_type == 1 ? 'selected' : '' ?>>Bug
                                            </option>
                                            <option value="3" <?= $ticketData[0]->ticket_type == 3 ? 'selected' : '' ?>>Change
                                            </option>
                                            <option value="2" <?= $ticketData[0]->ticket_type == 2 ? 'selected' : '' ?>>Feature Enhancement
                                            </option>
                                         </select>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
function commentAddShow(ticketId, userId, userName) {
    let commentMessage = $('#commentMessage').val();
    const token = $('input[name="_csrfToken"]').attr('value');
    $.ajax({
        url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'addShowComment']) ?>",
        method: 'POST',
        headers: {
            'X-CSRF-Token': token,
        },
        data: {
            ticketId: ticketId,
            userId: userId,
            commentMessage: commentMessage
        },
        success: function(res) {
            let commentData = JSON.parse(res);
            if (typeof commentData.client_name === 'undefined') {
                var name = userName;
            } else {
                var name = commentData.client_name;
            }

            // Create the HTML markup string
            var htmlString = '<div class="row d-flex align-items-center mt-2"><div class="col-md-1"><div class="avatar avatar-xs"><img src="<?= WEBURL ?>/img/Actiknow/user.png" class="rounded-circle"></div></div>';
            htmlString += '<div class="col-md-11">';
            htmlString += '<div class="bg-light d-flex align-items-center fs-12 p-2 rounded">';
            htmlString += '<p class="fw-bold">'+ name +':</p> <span>&nbsp;'+ commentData.comment_notes +'</span></div>'
            htmlString +=  '</div></div>';

            $('#showComment').append(htmlString);
            $('#commentMessage').val('');
        }
    })
}

function changeStatus(id) {
    let status = $('#statusId').val();
    if (status != 0) {
        $.ajax({
            url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'ticketStatus']) ?>",
            method: "GET",
            data: {
                ticketId: id,
                status: status
            },
            success: function(res) {
               location.reload();
            }
        });
    }
}

function changeType(id) {
    let type = $('#typeId').val();
    if (type != 0) {
        $.ajax({
            url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'ticketType']) ?>",
            method: "GET",
            data: {
                ticketId: id,
                type: type
            },
            success: function(res) {
               location.reload();
            }
        });
    }
}
</script>
