<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <!-- FILTER -->
            <div class="row">



                <div class="col-md-2 align-bottom">

                    <select data-live-search="true" required name="project_filter" class="form-control selectpicker" id="project_id_search">

                        <?php if (count($projects_data) > 0) :
                            foreach ($projects_data as $p) : ?>

                                <?php if ($p["id"] == $project_id_search) : ?>

                                    <option value="<?= $p['id']; ?>"><?= substr($p['project_name'], 0, 20); ?> - <?= $p["client"]["client_name"] ?></option>

                                <?php else : ?>

                                    <option value="<?= $p['id']; ?>"><?= substr($p['project_name'], 0, 20); ?> - <?= $p["client"]["client_name"] ?></option>

                        <?php endif;
                            endforeach;
                        endif; ?>
                    </select>
                </div>
                <div class="col-md-2 align-bottom">
                    <select name="client_filter" class="form-control" id="client_filter_search">

                        <?php if (count($client_data) > 0) : ?>
                            <?php foreach ($client_data as $cd) : ?>


                                <?php if ($cd["id"] == $client_id_search) : ?>

                                    <option selected value="<?= $cd["id"] ?>"><?= $cd["client_name"] ?></option>

                                <?php else : ?>

                                    <option value="<?= $cd["id"] ?>"><?= $cd["client_name"] ?></option>


                        <?php endif;
                            endforeach;
                        endif; ?>
                    </select>
                </div>
                <div class="col-md-2 align-bottom">
                    <select name="date_filter" class="form-control" id="date_filter_task">
                        <!--                      <option value="default">All Date</option>
                        <option value="today">Today</option>
                        <option value="tomorrow">Tomorrow</option>
                        <option value="past">Past Dues</option>
                        <option value="custom">Custom</option> -->

                        <?php if (count($date_data) > 0) : ?>
                            <?php foreach ($date_data as $dd) : ?>

                                <?php if ($dd["id"] == $date_filter_search) : ?>

                                    <option selected value="<?= $dd["id"] ?>"><?= $dd["name"] ?></option>


                                <?php else : ?>

                                    <option value="<?= $dd["id"] ?>"><?= $dd["name"] ?></option>


                        <?php endif;
                            endforeach;
                        endif; ?>


                    </select>
                </div>

                <?php if ($date_filter_search == "custom") : ?>

                    <div class="col-md-2 unhide-class">

                    <?php else : ?>
                        <div class="col-md-2 unhide-class" style="display: none;">

                        <?php endif; ?>


                        <div class="adon-group">
                            <span class="icon icon-light ft-primary"><i class="fa fa-calendar"></i></span>
                            <input type="text" autocomplete="off" class="form-control datepicker custom-dt-picker" placeholder="From Date" id="from_date_search" <?php if ($from_date_search != null) : ?> value="<?= $from_date_search; ?>" <?php endif; ?>>
                        </div>
                        </div>

                        <?php if ($date_filter_search == "custom") : ?>

                            <div class="col-md-2 unhide-class">

                            <?php else : ?>
                                <div class="col-md-2 unhide-class" style="display: none;">

                                <?php endif; ?>
                                <div class="adon-group">
                                    <span class="icon icon-light ft-primary"><i class="fa fa-calendar"></i></span>
                                    <input type="text" autocomplete="off" class="form-control datepicker custom-dt-picker" placeholder="To Date" id="to_date_search" <?php if ($to_date_search != null) : ?> value="<?= $to_date_search; ?>" <?php endif; ?>>
                                </div>
                                </div>
                                <div class="col-md-2 align-center align-top">
                                    <a href="#" onclick="filtermytask()" class="v-btn v-btn-primary">Search</a>
                                    <a href="#" data-target="#add_task" data-toggle="modal" class="v-btn v-btn-secondary">
                                        <i class="fa fa-plus"></i><span>Task</span>
                                    </a>
                                </div>
                            </div>
                    </div>
            </div>
            <!-- PAGE-CONTENT -->
            <div class="page-content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs task-tabs" id="myTab" role="tablist">
                                <li class="" role="presentation">
                                    <a class="active" onclick="setSessionValue('mytask')" id="mytask-tab" data-toggle="tab" href="#mytask" role="tab" aria-controls="mytask" aria-selected="true">My Task <span>(<?= count($mytask); ?>)</span></a>
                                </li>
                                <li class="" role="presentation">
                                    <a class="" onclick="setSessionValue('review')" id="reviewed-tab" data-toggle="tab" href="#reviewed" role="tab" aria-controls="reviewed" aria-selected="false">To be Reviwed<span><?= count($assigned_task); ?></span></a>
                                </li>
                                <li class="" role="presentation">
                                    <a class="" onclick="setSessionValue('completed')" id="completed-tab" data-toggle="tab" href="#completed" role="tab" aria-controls="completed" aria-selected="false">Completed<span><?= count($completed_task); ?></span></a>
                                </li>
                                <li class="" role="presentation">
                                    <a class="" onclick="setSessionValue('approve')" id="approved-tab" data-toggle="tab" href="#approved" role="tab" aria-controls="approved" aria-selected="false">Approved<span><?= count($approved_task) ?></span></a>
                                </li>
                                <li class="" role="presentation">
                                    <a class="" onclick="setSessionValue('myteam')" id="myteam-tab" data-toggle="tab" href="#myteam" role="tab" aria-controls="myteam" aria-selected="false">My Team<span><?= $my_team_count; ?></span></a>
                                </li>

                            </ul>
                            <div class="tab-content task-tab-content" id="myTabContent">
                                <div class="tab-pane fade show active task-wapper" id="mytask" role="tabpanel" aria-labelledby="mytask-tab">
                                    <!-- FILTER ROW -->
                                    <!--    <div class="filter-row">
                                 <div class="filter-row-item">
                                    <label for=""> Filter</label>
                                </div>
                                <div class="filter-row-item">
                                    <div class="adon-group">
                                        <span class="icon icon-md icon-light"><i class="fa fa-search"></i></span>
                                        <input type="text" class="form-control" placeholder="Search">
                                    </div>
                                </div>
                                <div class="filter-row-item">
                                    <div class="adon-group">
                                        <span class="icon icon-md icon-light"><i class="fa fa-check"></i></span>
                                        <select name="" id="" class="form-control">
                                            <option value="">All Task</option>
                                            <option value="">To Be Review</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="filter-row-item">
                                    <div class="adon-group">
                                        <span class="icon icon-md icon-light"><i class="fa fa-users"></i></span>
                                        <select name="" id="" class="form-control">
                                            <option value="">All Users</option>
                                            <option value="">Varun Dev</option>
                                            <option value="">Karan Dev</option>
                                            <option value="">Suresh Kumar</option>
                                            <option value="">Shubham Gupta</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                         -->
                                    <!--     <h4 class="time-title">Today <span>(05)</span></h4> -->

                                    <!-- TASK ROW -->

                                    <!--                 
                            <div class="task-row">
                                <div class="dropdown action-dropdown">
                                    <a class="icon icon-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" data-target="#add_task" data-toggle="modal" href="#">Edit</a>
                                        <a class="dropdown-item" href="#" data-target="#view_notes" data-toggle="modal">Notes</a>
                                        <a class="dropdown-item" href="#">Delete</a>
                                    </div>
                                </div>
                                <div class="header">
                                    <div class="project-details">
                                        <h4><a href="#"><?= substr(@$p['project_name'], 0, 20); ?></a> | <?= @$p['client_name']; ?></h4>
                                    </div>
                                    <ul class="label-tags">
                                        <li><span class="tag user"><?= @$p["assigned_by"]; ?></span></li>
                                        <li><span class="tag due-date">Due 25 May</span> </li>
                                        <li><span class="tag extend-date">EXT 30 May | 3 T</span></li>
                                        <li>
                                            <div class="form-group form-check form-check-btn">
                                                <input type="checkbox" class="" id="exampleCheck1">
                                                <label class="form-check-label" for="exampleCheck1">Complete</label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="content">
                                    <h4>
                                        <span class="show-description-icon">
                                            <i class="fas fa-chevron-circle-down"></i>
                                        </span>
                                        Design a landing page webiste for Azabiz tool. 
                                        <span class="created-on">
                                            -23 May
                                        </span>
                                    </h4>
                                    <p class="description">
                                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorum blanditiis voluptas rerum. Quasi amet nisi ea non quia at, quos quam repellendus, nesciunt facilis aspernatur explicabo! Quidem velit repellat quo!
                                    </p>
                                </div>
                            </div>
 -->


                                    <?php if (count($mytask) > 0) :
                                        $i = 0;
                                        foreach ($mytask as $p) : ?>


                                            <div class="task-row">

                                                <div class="dropdown action-dropdown">
                                                    <a class="icon icon-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">

                                                        <?php if ($p["assigned_by_id"] == $user_id) : ?>

                                                            <a class="dropdown-item" data-target="#edit_task" data-toggle="modal" href="#" onclick="edit_modal_data('<?php echo $p['id'] ?>')">Edit</a>

                                                        <?php endif; ?>

                                                        <a class="dropdown-item" href="#" data-target="#view_notes" onclick="loadModalNotesData(this);" data-id="<?= $p["id"] ?>" data-toggle="modal">Notes</a>
                                                        <!--  <a class="dropdown-item" href="#">Delete</a> -->
                                                    </div>
                                                </div>

                                                <div class="header">
                                                    <div class="project-details">
                                                        <h4><a href="#"><?= substr($p['project_name'], 0, 20); ?></a> |
                                                            <?= $p["client_name"]; ?></h4>
                                                    </div>
                                                    <ul class="label-tags">
                                                        <li><span class="tag user">
                                                                <?php $ans;
                                                                preg_match("(\w+\s\w)", $p["assigned_by"], $ans); ?>
                                                                By <?= $ans[0]; ?>

                                                            </span></li>
                                                        <li><span class="tag due-date">Due <?= $p["due_date"]; ?></span> </li>


                                                        <?php if ($p["extend_days"]) : ?>

                                                            <li><span class="tag extend-date">EXT <?= $p["extend_days"] ?> | <?= $p["extend_count"] ?></span></li>
                                                        <?php endif; ?>

                                                        <li>
                                                            <div class="form-group form-check form-check-btn">
                                                                <input type="checkbox" class="" id="exampleCheck<?= $i ?>" onclick="CompletedMyTask('unchecked','<?= $p['id'] ?>',this)">
                                                                <label class="form-check-label" for="exampleCheck<?= $i ?>">Complete</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="content">
                                                    <h4>
                                                        <span class="show-description-icon" data-value="<?= $i ?>">
                                                            <i class="fas fa-chevron-circle-down"></i>
                                                        </span>

                                                        <?= $p["task_name"]; ?>
                                                        <span class="created-on">
                                                            <?= $p["created_at"]; ?>
                                                        </span>
                                                    </h4>
                                                    <p class="description-<?= $i ?>" style="display: none;">
                                                        <?= $p["description"]; ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <!-- 
                            <h4 class="time-title">Tomorrow <span>(10)</span></h4>
                            <div class="load-more text-center">
                                <a href="#" class="v-btn v-btn-base"><i class="fa fa-reply"></i><span>Load More</span></a>
                            </div> -->
                                    <?php $i++;
                                        endforeach;
                                    endif; ?>

                                </div>

                                <div class="tab-pane fade task-wapper" id="reviewed" role="tabpanel" aria-labelledby="reviewed-tab">


                                    <div class="filter-row">
                                        <div class="filter-row-item">
                                            <label for=""> Filter</label>
                                        </div>

                                        <div class="filter-row-item">
                                            <div class="adon-group">
                                                <span class="icon icon-md icon-light"><i class="fa fa-check"></i></span>
                                                <select name="review_filter_task" id="review_filter_task" class="form-control">
                                                    <option value="all_task">All Task</option>
                                                    <option value="to_be_review">To Be Review</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="filter-row-item">
                                            <div class="adon-group">
                                                <span class="icon icon-md icon-light"><i class="fa fa-users"></i></span>
                                                <select name="review_user_filter" id="review_user_filter" class="form-control">
                                                    <option value="all">All Users</option>
                                                    <?php foreach ($users as $u) : ?>

                                                        <option value="<?= $u["id"] ?>"><?= $u["name"] ?></option>

                                                    <?php endforeach; ?>

                                                </select>
                                            </div>
                                        </div>

                                        <div class="filter-row-item">
                                            <div class="adon-group">
                                                <span class="icon icon-md icon-light"><i class="fa fa-check"></i></span>

                                                <button class="form-control" onclick="ApprovedTask()">Review</button>

                                            </div>
                                        </div>
                                        <div class="filter-row-item">
                                            <div class="adon-group">
                                                <span class="icon icon-md icon-light"><i class="fa fa-check"></i></span>

                                                <button class="form-control" onclick="CheckCompletedTask()">Check completed Task</button>

                                            </div>
                                        </div>

                                    </div>

                                    <?php if (count($assigned_task) > 0) :
                                        $j = $i;
                                        foreach ($assigned_task as $p) : ?>


                                            <?php if ($p["completed"] == 1) : ?>
                                                <div class="task-row toBeReviewed" data-user="<?= $p["assigned_to_id"] ?>">

                                                <?php else : ?>

                                                    <div class="task-row not-completed-review" data-user="<?= $p["assigned_to_id"] ?>">


                                                    <?php endif; ?>
                                                    <div class="dropdown action-dropdown">
                                                        <a class="icon icon-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">

                                                            <a class="dropdown-item" data-target="#edit_task" data-toggle="modal" href="#" onclick="edit_modal_data('<?php echo $p['id'] ?>')">Edit</a>

                                                            <a class="dropdown-item" href="#" data-target="#view_notes" onclick="loadModalNotesData(this);" data-id="<?= $p["id"] ?>" data-toggle="modal">Notes</a>
                                                            <a class="dropdown-item" onclick="deletetask(<?= $p['id']; ?>)" href="#">Delete</a>
                                                        </div>
                                                    </div>
                                                    <div class="header">
                                                        <div class="project-details">
                                                            <h4><a href="#"><?= $p["project_name"] ?></a> | <?= $p["client_name"] ?></h4>
                                                        </div>
                                                        <ul class="label-tags">

                                                            <li><span class="tag user">
                                                                    <?php $ans;
                                                                    preg_match("(\w+\s\w)", $p["assigend_to"], $ans); ?>
                                                                    To <?= $ans[0]; ?>

                                                                </span></li>


                                                            <li><span class="tag due-date">Due <?= $p["due_date"] ?></span> </li>



                                                            <?php if ($p["extend_days"]) : ?>

                                                                <li><span class="tag extend-date">EXT <?= $p["extend_days"] ?> | <?= $p["extend_count"] ?></span></li>
                                                            <?php endif; ?>


                                                            <li>
                                                                <div class="form-group form-check form-check-btn">


                                                                    <?php if ($p["completed"] == 0) : ?>


                                                                        <input type="checkbox" class="" id="exampleCheck<?= $j ?>" onclick="alert('Task Not Completed Yet');return false;">



                                                                    <?php else : ?>


                                                                        <input type="checkbox" class="review-input-box completed-task" id="exampleCheck<?= $j ?>" data-review="<?= $p["id"] ?>">

                                                                    <?php endif; ?>


                                                                    <label class="form-check-label" for="exampleCheck<?= $j ?>">Approve</label>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="content">
                                                        <h4>
                                                            <span class="show-description-icon" data-value="<?= $j ?>">
                                                                <i class="fas fa-chevron-circle-down"></i>
                                                            </span>
                                                            <?= $p["task_name"] ?>
                                                            <span class="created-on">
                                                                <?= $p["created_at"] ?>
                                                            </span>
                                                        </h4>
                                                        <p class="description-<?= $j ?>" style="display: none;">
                                                            <?= $p["description"] ?>
                                                        </p>
                                                    </div>
                                                    </div>

                                            <?php $j++;
                                        endforeach;
                                    endif; ?>

                                            <!-- <div class="load-more text-center">
                                <a href="#" class="v-btn v-btn-base"><i class="fa fa-reply"></i><span>Load More</span></a>
                            </div> -->
                                                </div>

                                                <div class="tab-pane fade task-wapper" id="completed" role="tabpanel" aria-labelledby="completed-tab">


                                                    <?php if (count($completed_task) > 0) :
                                                        $k = $j;
                                                        foreach ($completed_task as $p) : ?>




                                                            <div class="task-row">

                                                                <div class="dropdown action-dropdown">
                                                                    <a class="icon icon-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        <i class="fa fa-ellipsis-v"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">

                                                                        <a class="dropdown-item" href="#" data-target="#view_notes_ap_com" onclick="loadModalNotesDataReview(this);" data-id="<?= $p["id"] ?>" data-toggle="modal">Notes</a>

                                                                    </div>
                                                                </div>

                                                                <div class="header">
                                                                    <div class="project-details">
                                                                        <h4><a href="#"><?= $p["project"]["project_name"] ?></a> | <?= $p["project"]["client"]["client_name"] ?></h4>
                                                                    </div>
                                                                    <ul class="label-tags">
                                                                        <!-- 
                                        <li><span class="tag user"><?= $p["assigned_by_data"]["name"] ?></span></li>
                                        
                                         -->
                                                                        <li><span class="tag user">
                                                                                <?php $ans;
                                                                                preg_match("(\w+\s\w)", $p["assigned_by_data"]["name"], $ans); ?>
                                                                                By <?= $ans[0]; ?>

                                                                            </span></li>

                                                                        <li><span class="tag due-date">Due <?= $p["due_date"] ?></span> </li>



                                                                        <?php if ($p["extend_days"]) : ?>

                                                                            <li><span class="tag extend-date">EXT <?= $p["extend_days"] ?> | <?= $p["extend_count"] ?></span></li>
                                                                        <?php endif; ?>



                                                                        <li>
                                                                            <div class="form-group form-check form-check-btn">
                                                                                <input type="checkbox" class="" id="exampleCheck<?= $k; ?>" checked onclick="CompletedMyTask('checked','<?= $p['id'] ?>',this)">
                                                                                <label class="form-check-label" for="exampleCheck<?= $k ?>">Completed</label>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                                <div class="content">
                                                                    <h4>
                                                                        <span class="show-description-icon" data-value="<?= $k ?>">
                                                                            <i class="fas fa-chevron-circle-down"></i>
                                                                        </span>
                                                                        <?= $p["task_name"] ?>
                                                                        <span class="created-on">
                                                                            <?= $p["created_at"] ?>
                                                                        </span>
                                                                    </h4>
                                                                    <p class="description-<?= $k ?>" style="display: none;">
                                                                        <?= $p["description"] ?>
                                                                    </p>
                                                                </div>
                                                            </div>

                                                    <?php $k++;
                                                        endforeach;
                                                    endif; ?>






                                                </div>

                                                <div class="tab-pane fade task-wapper" id="approved" role="tabpanel" aria-labelledby="approved-tab">



                                                    <div class="filter-row">
                                                        <div class="filter-row-item">
                                                            <label for=""> Filter</label>
                                                        </div>


                                                        <div class="filter-row-item">
                                                            <div class="adon-group">
                                                                <span class="icon icon-md icon-light"><i class="fa fa-users"></i></span>
                                                                <select name="review_user_filter" class="form-control filter-main">
                                                                    <option value="all">All Users</option>
                                                                    <?php foreach ($users as $u) : ?>

                                                                        <option value="<?= $u["id"] ?>"><?= $u["name"] ?></option>

                                                                    <?php endforeach; ?>

                                                                </select>
                                                            </div>
                                                        </div>



                                                    </div>



                                                    <?php if (count($approved_task) > 0) :
                                                        $l = $k;
                                                        foreach ($approved_task as $p) : ?>



                                                            <?php if ($p["assigned_by"] == $user_id) : ?>

                                                                <div class="task-row toBeReviewed filter-item-main-<?= $p['assigned_to_data']['id'] ?> filter-item-main

                                    ">

                                                                <?php else : ?>


                                                                    <div class="task-row filter-item-main-<?= $p['assigned_by_data']['id'] ?> filter-item-main">



                                                                    <?php endif; ?>


                                                                    <div class="dropdown action-dropdown">
                                                                        <a class="icon icon-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <i class="fa fa-ellipsis-v"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">

                                                                            <a class="dropdown-item" href="#" data-target="#view_notes_ap_com" onclick="loadModalNotesDataReview(this);" data-id="<?= $p["id"] ?>" data-toggle="modal">Notes</a>

                                                                        </div>
                                                                    </div>


                                                                    <div class="header">
                                                                        <div class="project-details">
                                                                            <h4><a href="#"><?= $p["project"]["project_name"] ?></a> | <?= $p["project"]["client"]["client_name"] ?></h4>
                                                                        </div>
                                                                        <ul class="label-tags">


                                                                            <li><span class="tag user">

                                                                                    <?php if ($p["assigned_by"] == $user_id) : ?>

                                                                                        <?php $ans;
                                                                                        preg_match("(\w+\s\w)", $p["assigned_to_data"]["name"], $ans); ?>
                                                                                        To <?= $ans[0]; ?>



                                                                                    <?php else : ?>


                                                                                        <?php $ans;
                                                                                        preg_match("(\w+\s\w)", $p["assigned_by_data"]["name"], $ans); ?>
                                                                                        By <?= $ans[0]; ?>

                                                                                    <?php endif; ?>


                                                                                </span></li>




                                                                            <li><span class="tag due-date">Due <?= $p["due_date"] ?></span> </li>



                                                                            <?php if ($p["extend_days"]) : ?>

                                                                                <li><span class="tag extend-date">EXT <?= $p["extend_days"] ?> | <?= $p["extend_count"] ?></span></li>
                                                                            <?php endif; ?>



                                                                            <!--     <li>
                                            <div class="form-group form-check form-check-btn">
                                                <input type="checkbox" class="" id="exampleCheck<?= $l; ?>" checked onclick="CompletedMyTask('checked','<?= $p['id'] ?>',this)">
                                                <label class="form-check-label" for="exampleCheck<?= $i ?>">Completed</label>
                                            </div>
                                        </li> -->
                                                                        </ul>
                                                                    </div>
                                                                    <div class="content">
                                                                        <h4>
                                                                            <span class="show-description-icon" data-value="<?= $l ?>">
                                                                                <i class="fas fa-chevron-circle-down"></i>
                                                                            </span>
                                                                            <?= $p["task_name"] ?>
                                                                            <span class="created-on">
                                                                                <?= $p["created_at"] ?>
                                                                            </span>
                                                                        </h4>
                                                                        <p class="description-<?= $l ?>" style="display: none;">
                                                                            <?= $p["description"] ?>
                                                                        </p>
                                                                    </div>
                                                                    </div>

                                                            <?php $l++;
                                                        endforeach;
                                                    endif; ?>









                                                                </div>




                                                                <div class="tab-pane fade task-wapper" id="myteam" role="tabpanel" aria-labelledby="myteam-tab">


                                                                    <div class="filter-row">
                                                                        <div class="filter-row-item">
                                                                            <label for=""> Filter</label>
                                                                        </div>


                                                                        <div class="filter-row-item">
                                                                            <div class="adon-group">
                                                                                <span class="icon icon-md icon-light"><i class="fa fa-users"></i></span>
                                                                                <select name="review_user_filter" class="form-control filter-main">
                                                                                    <option value="all">All Users</option>
                                                                                    <?php foreach ($users as $u) : ?>

                                                                                        <option value="<?= $u["id"] ?>"><?= $u["name"] ?></option>

                                                                                    <?php endforeach; ?>

                                                                                </select>
                                                                            </div>
                                                                        </div>


                                                                    </div>


                                                                    <?php if (count($myTeamData) > 0) :
                                                                        $q = $l;
                                                                        foreach ($myTeamData as $p) : ?>




                                                                            <div class="task-row filter-item-main-<?= $p['id'] ?> filter-item-main">

                                                                                <div class="content">
                                                                                    <div class="header">
                                                                                        <div class="project-details">

                                                                                            <div class="row">
                                                                                                <span class="show-description-icon mr-2" data-value="<?= $q ?>">
                                                                                                    <i class="fas fa-chevron-circle-down"></i>
                                                                                                </span>

                                                                                                <h4><a href="#" class="show-description-icon mr-2" data-value="<?= $q ?>"><?= $p["name"] ?></a></h4>



                                                                                            </div>

                                                                                        </div>

                                                                                    </div>


                                                                                    <div class="description-<?= $q ?>" style="display: none;">


                                                                                        <?php $t = (++$q);
                                                                                        foreach ($p["tasks"] as $tsk) : ?>






                                                                                            <?php if ($tsk["completed"] == 1) : ?>

                                                                                                <div class="task-row toBeReviewed">

                                                                                                <?php else : ?>


                                                                                                    <div class="task-row">



                                                                                                    <?php endif; ?>




                                                                                                    <div class="header">
                                                                                                        <div class="project-details">

                                                                                                            <div class="row">
                                                                                                                <h4 class="mr-2"><?= $tsk["task_name"] ?> | <?= $tsk["project_name"] ?> | <?= $tsk["client_name"] ?></h4>

                                                                                                                <span class="show-description-icon" data-value="<?= $t ?>">
                                                                                                                    <i class="fas fa-chevron-circle-down"></i>
                                                                                                                </span>


                                                                                                            </div>

                                                                                                        </div>
                                                                                                        <ul class="label-tags">


                                                                                                            <li><span class="tag user">



                                                                                                                    <?php $ans;
                                                                                                                    preg_match("(\w+\s\w)", $tsk["assigned_by_name"], $ans); ?>
                                                                                                                    By <?= $ans[0]; ?>











                                                                                                                </span></li>




                                                                                                            <li><span class="tag due-date">Due <?= $tsk["due_date"] ?></span> </li>



                                                                                                            <?php if ($tsk["extend_days"]) : ?>

                                                                                                                <li><span class="tag extend-date">EXT <?= $tsk["extend_days"] ?> | <?= $tsk["extend_count"] ?></span></li>
                                                                                                            <?php endif; ?>



                                                                                                            <?php if ($tsk["notesid"]) : ?>

                                                                                                                <li><span class="tag extend-date"><a href="#" data-target="#view_notes_ap_com" onclick="loadModalNotesDataReview(this);" data-id="<?= $tsk["id"] ?>" data-toggle="modal">Notes</a></span></li>
                                                                                                            <?php endif; ?>




                                                                                                        </ul>
                                                                                                    </div>
                                                                                                    <div class="content">

                                                                                                        <p class="description-<?= $t ?>" style="display: none;">
                                                                                                            <?= $tsk["description"] ?>
                                                                                                        </p>
                                                                                                    </div>
                                                                                                    </div>




                                                                                                <?php $t++;
                                                                                                $q++;
                                                                                            endforeach; ?>

                                                                                                </div>





                                                                                    </div>

                                                                                </div>

                                                                        <?php $q++;
                                                                        endforeach;
                                                                    endif; ?>







                                                                            </div>


                                                                </div>



                                                </div>
                                </div>
                            </div>
</section>



<div class="modal fade" tabindex="-1" role="dialog" id="add_task">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?= $this->Form->create(null, array('id' => 'addTask', 'url' => '/addTask/', 'type' => 'post')) ?>

            <div class="modal-body">

                <div class="content">

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Project Name</label>

                            <select data-live-search="true" name="project_id" class="form-control selectpicker" id="project_id" Required>
                                <?php if (count($add_task_project) > 0) :
                                    foreach ($add_task_project as $p) : ?>

                                        <!-- <option value="<?= $p['id']; ?>"><?= substr($p['project_name'], 0, 20); ?> - <?= $p["client"]["client_name"] ?></option> -->

                                        <option value="<?= $p['id']; ?>">
                                            <?= substr($p['project_name'], 0, 20); ?> -
                                            <?= !empty($p['client']) ? $p['client']['client_name'] : 'No Client'; ?>
                                        </option>

                                <?php endforeach;
                                endif; ?>

                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Task Name</label>
                            <?= $this->Form->input('task_name', array('class' => 'form-control', 'type' => 'text', 'autocomplete' => 'off', 'required' => true)) ?>
                            <!--  <input id="tags" name="task_name" type="text" class="form-control" placeholder=""> -->
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Task Description</label>
                            <textarea id="description" name="description" class="form-control" Required></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Due on</label>
                            <?= $this->Form->input('due_date', array('class' => 'form-control datepicker', 'type' => 'text', 'autocomplete' => 'off', 'required' => true)) ?>
                            <!--   <input type="text" name="due_date" class="form-control datepicker" placeholder=""> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Assign To</label>

                            <select required name="assigned_to" id="" class="form-control">
                                <option selected value="" disabled="disabled">Select User</option>
                                <?php if (count($users) > 0) :
                                    foreach ($users as $p) : ?>
                                        <option value="<?= $p['id']; ?>"><?= $p['name']; ?></option>
                                <?php endforeach;
                                endif; ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-md-12">

                            <input type="checkbox" name="send_mail_task" id="send_mail_task">
                            <label for="send_mail_task">Send Mail</label>
                        </div>
                    </div>





                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                <button class="v-btn v-btn-primary" type="submit" id="add_task_button">
                    <!--  data-dismiss="modal"> -->Add Task
                </button>

            </div>

            <?= $this->Form->end() ?>

        </div>
    </div>
</div>


<!-- ADD Task -->

<!-- 
<div class="modal fade" tabindex="-1" role="dialog" id="add_task">
  <div class="modal-dialog" role="document">
    
      
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Task</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label for="">Select Project</label>
            <select name="" class="form-control" id="">
                <option value="">Select Project</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">Task Name</label>
            <input type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="">Description</label>
            <textarea name="" class="form-control" id="" cols="30" rows="2"></textarea>
        </div>
        <div class="form-group">
            <label for="">Due Date</label>
            <input type="date" class="form-control">
        </div>
        <div class="form-group">
            <label for="">Assign To</label>
            <select name="" class="form-control" id="">
                <option value="">Varun Dev</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">Extend Days</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                <label class="form-check-label" for="inlineRadio1">1</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                <label class="form-check-label" for="inlineRadio2">2</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3" disabled>
                <label class="form-check-label" for="inlineRadio3">3 (disabled)</label>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
        <a href="#" class="v-btn v-btn-primary" data-dismiss="modal">Add</a>
      </div>
    </div>



  </div>
</div>

 -->


<div class="modal fade hide" tabindex="-1" role="dialog" id="edit_task">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?= $this->Form->create(null, array('id' => 'edit_task_form')) ?>

            <div class="modal-body">

                <div class="content">

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Project Name</label>

                            <select name="project_id" id="edit_task_project" class="form-control selectpicker" required data-live-search="true">

                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Task Name</label>
                            <?= $this->Form->input('task_name', array('class' => 'form-control', 'type' => 'text', 'id' => 'edit_task_name', 'required' => true)) ?>
                            <input id="edit_task_id" name="task_id" type="text" hidden>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Due on</label>
                            <?= $this->Form->input('due_date', array('class' => 'form-control datepicker', 'type' => 'text', 'autocomplete' => 'off', 'id' => 'edit_due_date', 'disabled' => true)) ?>

                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Assign To</label>

                            <select name="assigned_to" required id="edit_task_assign_to" class="form-control">

                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Task Description</label>
                            <textarea required id="edit_task_description" name="description" class="form-control"></textarea>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="">Extend Days</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="extend_days" id="inlineRadio1" value="1">
                            <label class="form-check-label" for="inlineRadio1">1</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="extend_days" id="inlineRadio2" value="2">
                            <label class="form-check-label" for="inlineRadio2">2</label>
                        </div>

                        <div class="form-check form-check-inline ">
                            <input class="form-check-input" type="radio" name="extend_days" id="inlineRadio3custom" value="3">
                            <label class="form-check-label" for="inlineRadio3custom">Custom Date</label>
                        </div>


                    </div>

                    <div class="form-group" style="display: none;" id="extend_days_id_div">

                        <label for="inlineRadio4">Custom Date</label>

                        <?= $this->Form->input('extend_days_custom', array('class' => 'form-control datepicker', 'type' => 'text', 'autocomplete' => 'off', 'id' => 'inlineRadio4')) ?>



                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">

                            <input type="checkbox" name="send_mail_task" id="send_mail_task">
                            <label for="send_mail_task">Send Mail</label>
                        </div>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                <button class="v-btn v-btn-primary" type="submit" id="edit_task_button">
                    <!--  data-dismiss="modal"> -->Update Task
                </button>

            </div>

            </form>

        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="view_notes_ap_com">
    <div class="modal-dialog" role="document">


        <!--     <input type="hidden" name="taskid" id="task_hidden_id">
 -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Notes Page</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="notes-list-task notes-list">

                    <!--  <li>
                <p><span class="notes-by">Deepika M: </span> Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid, perspiciatis! <span class="date">- 24 Mar</span></p>
            </li>
            <li>
                <p><span class="notes-by">Varun D: </span>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid, perspiciatis! <span class="date">- 24 Mar</span></p>
            </li>
            <li>
                <p><span class="notes-by">Me: </span>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid, perspiciatis! <span class="date">- 24 Mar</span></p>
            </li> -->
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                <!--         <button class="v-btn v-btn-primary" type="submit">Add</button>
 -->
                <!--  <a href="#" class="v-btn v-btn-primary" data-dismiss="modal">Add</a> -->
            </div>
        </div>


    </div>
</div>








<!-- View Notes -->
<div class="modal fade" tabindex="-1" role="dialog" id="view_notes">
    <div class="modal-dialog" role="document">

        <?= $this->Form->create(null, [
            'url' => [
                'controller' => 'Users',
                'action' => 'addnotestotask'
            ]
        ]) ?>

        <input type="hidden" name="taskid" id="task_hidden_id">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Notes Page</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-0">
                    <label for="">Add Notes</label>
                    <input type="text" class="form-control" required name="notes">
                </div>
                <ul class="notes-list-task notes-list">

                    <!--  <li>
                <p><span class="notes-by">Deepika M: </span> Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid, perspiciatis! <span class="date">- 24 Mar</span></p>
            </li>
            <li>
                <p><span class="notes-by">Varun D: </span>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid, perspiciatis! <span class="date">- 24 Mar</span></p>
            </li>
            <li>
                <p><span class="notes-by">Me: </span>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid, perspiciatis! <span class="date">- 24 Mar</span></p>
            </li> -->
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                <button class="v-btn v-btn-primary" type="submit">Add</button>
                <!--  <a href="#" class="v-btn v-btn-primary" data-dismiss="modal">Add</a> -->
            </div>
        </div>

        <?= $this->Form->end() ?>

    </div>
</div>


<input type="hidden" id="my_task_url" value="<?= $this->Url->build([
                                                    "controller" => "Users",
                                                    "action" => "mytask",
                                                ]); ?>">

<input id="complete_task_url" type="hidden" value="<?= $this->Url->build([
                                                        "controller" => "Users",
                                                        "action" => "completedmytask",
                                                    ]); ?>" hidden>


<input id="approved_task_url" type="hidden" value="<?= $this->Url->build([
                                                        "controller" => "Users",
                                                        "action" => "approvedtask",
                                                    ]); ?>" hidden>

<input id="user_id" type="text" value="<?= $user_id; ?>" hidden>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>


<script type="text/javascript">
    function setSessionValue(name) {

        sessionStorage.name = name;
    }



    $(".filter-main").change(function() {



        var val = $(this).val();


        if (val == "all") {


            $(".filter-item-main").show();

        } else {
            console.log("no")
            $(".filter-item-main").hide();
            $(`.filter-item-main-${val}`).show();


        }

    });



    function filter_data_func(ele) {

        var val = $(ele).val();


        sessionStorage.user_filter = val;



        if (val == "all") {

            $(".task-row[data-user]").show();


        } else {

            $(".task-row[data-user]").hide();

            $(".task-row[data-user='" + val + "']").show();

            // $(":not(.task-row[data-user='"+val+"'])").hide();
        }

    }


    $(document).ready(function() {
        $('#add_task_button').click(function() {
            $('#addTask').submit();
            $('#add_task_button').prop('disabled', true);
            console.log('ss');
        });


        var value = sessionStorage.getItem("name");

        if (value != null) {

            if (value == "mytask") {

                $("#mytask-tab").click();


            } else if (value == "review") {

                $("#reviewed-tab").click();

                var select_user = sessionStorage.getItem("user_filter")



                if (select_user != null) {

                    // console.log("I ran")

                    // console.log(select_user);


                    $("#review_user_filter").val(select_user);

                    filter_data_func($("#review_user_filter"));


                }


            } else if (value == "completed") {

                $("#completed-tab").click();
            } else if (value == "approve") {


                $("#approved-tab").click();


            } else if (value == "myteam") {

                $("#myteam-tab").click();

            }





        }


    });



    $("#review_filter_task").change(function() {

        var val = $(this).val();



        if (val == "to_be_review") {

            $(".not-completed-review").hide();


        } else {

            $(".not-completed-review").show();

        }




    });




    $("#review_user_filter").change(function() {

        filter_data_func(this);


    });


    function filtermytask() {

        var project_id = $("#project_id_search").val();
        var client_id = $("#client_filter_search").val();
        var date_filter = $("#date_filter_task").val();
        var from_date = null;
        var to_date = null;
        if (date_filter == "custom") {





            from_date = $("#from_date_search").val();
            to_date = $("#to_date_search").val();

            console.log(from_date, to_date);

            if (from_date == "" && to_date == "") {


                alert("Please Select valid date");
                return false;

            }

            var temp_date = new Date(from_date);
            from_date = temp_date.getFullYear() + "-" + (temp_date.getMonth() + 1) + "-" + temp_date.getDate();

            var temp_date_2 = new Date(to_date);
            to_date = temp_date_2.getFullYear() + "-" + (temp_date_2.getMonth() + 1) + "-" + temp_date_2.getDate();


        }

        var url = $("#my_task_url").val();
        search_url = url + "/" + project_id + "/" + client_id + "/" + date_filter + "/" + from_date + "/" + to_date;
        location.replace(search_url);

        // console.log(project_id,client_id,date_filter,from_date,to_date);

    }

    function loadModalNotesDataReview(ele) {

        var id = $(ele).attr("data-id");

        var url =
            $.ajax({
                type: 'GET',
                url: "<?= $this->Url->build('/users/sendalltasknotes/'); ?>" + id,
                beforeSend: function() {},
                success: function(data) {

                    var response = $.parseJSON(data);


                    var parent = $(".notes-list-task");
                    parent.empty()

                    for (var i = 0; i < response.length; i++) {

                        var temp_date = new Date(response[i]["created_at"]);
                        var new_date = temp_date.getFullYear() + "-" + (temp_date.getMonth() + 1) + "-" + temp_date.getDate();
                        var html = '<li><p><span class="notes-by">' + response[i]["user"]["name"] + ': </span>' + response[i]["notes"] + '<span class="date">- ' + new_date + '</span></p></li>';
                        parent.append(html);

                    }



                }
            });


    }


    function loadModalNotesData(ele) {

        var id = $(ele).attr("data-id");
        console.log(id);
        $("#task_hidden_id").val(id);


        var url =
            $.ajax({
                type: 'GET',
                url: "<?= $this->Url->build('/users/sendalltasknotes/'); ?>" + id,
                beforeSend: function() {},
                success: function(data) {

                    var response = $.parseJSON(data);


                    var parent = $(".notes-list-task");
                    parent.empty()

                    for (var i = 0; i < response.length; i++) {

                        var temp_date = new Date(response[i]["created_at"]);
                        var new_date = temp_date.getFullYear() + "-" + (temp_date.getMonth() + 1) + "-" + temp_date.getDate();

                        var html = '<li><p><span class="notes-by">' + response[i]["user"]["name"] + ': </span>' + response[i]["notes"] + '<span class="date">- ' + new_date + '</span></p></li>';
                        parent.append(html);

                    }



                }
            });



    }



    $("#date_filter_task").change(function() {

        var val = $(this).val();

        if (val == "custom") {

            $(".unhide-class").show();
            $(".custom-dt-picker").val("");


        } else {
            $(".unhide-class").hide();
        }

    });


    function CompletedMyTask(condition, id, ele) {

        var r = confirm("Are you Sure");
        if (r) {
            var url = $("#complete_task_url").val();
            url = url + "/" + condition + "/" + id + "/";

            $.ajax({
                type: "GET",
                url: url,
                success: function(data) {
                    //    console.log(data);

                    location.reload();
                },
                error: function() {
                    alert("false");
                }
            });
            $(ele).prop("checked", true);

        } else {
            $(ele).prop("checked", false);
        }


    }





    function ApprovedTask() {


        ids = [];
        $(".review-input-box:checked").each(function() {

            ids.push($(this).attr("data-review"));
        });


        var token = $("input[name='_csrfToken']").val();

        if (ids.length <= 0) {

            alert("Please select task to review")

            return false;
        }


        var r = confirm("Are you Sure");
        if (r) {
            var url = $("#approved_task_url").val();
            // url = url+"/"+condition+"/"+id+"/";
            console.log(url);
            $.ajax({
                type: "POST",
                data: {
                    task_ids: ids,
                    _csrfToken: token
                },
                url: url,
                success: function(data) {
                    location.reload();

                },
                error: function() {
                    alert("false");
                }
            });
            // $(ele).prop("checked", true);

        } else {
            // $(ele).prop("checked", false);
        }





        console.log(ids);


    }


    function mouseDown(ele) {
        console.log("mouseDown work");
        $(ele).find('.hover-text').show();
    }

    function mouseUp(ele) {
        console.log("mouseUp work");
        $(ele).find('.hover-text').hide();
    }


    function edit_modal_data(task_id) {
        var url =
            $.ajax({
                type: 'GET',
                url: "<?= $this->Url->build('/users/editTask/'); ?>" + task_id,
                beforeSend: function() {},
                success: function(data) {

                    var response = $.parseJSON(data);


                    $("#edit_task_id").val(response.id);
                    $("#edit_task_name").val(response.task_name);
                    $("#edit_due_date").val(response.due_date);
                    $("#edit_task_assign_to").html(response.assigned_options);
                    $("#edit_task_description").val(response.task_description);
                    $("#edit_task_project").html(response.assigned_options_project);


                    $('.selectpicker').selectpicker('refresh')
                    $(".filter-option-inner-inner").last().text(response.assigned_options_project_selected)




                }
            });


    }


    function deletetask(task_id) {

        res = confirm("Are you Sure");
        if (res) {

            // console.log("<?= $this->Url->build('/users/deleteTask/'); ?>");
            $.ajax({
                type: "GET",
                url: "<?= $this->Url->build('/users/deleteTask/'); ?>" + task_id,
                success: function(data) {
                    location.reload();
                },
                error: function() {
                    // alert("false");
                }
            });

        }
    }

    function CheckCompletedTask() {
        $('.completed-task').prop('checked', 'true');
    }
</script>

<script>
    //edit form
    var uservalid = $("#edit_task_form").validate({
        rules: {
            task_name: {
                required: true,
            },
        },
        messages: {
            task_name: {
                required: "Please enter task",
            },

        },
        submitHandler: function(form) {
            $('#edit_task_button').html('sending..');
            $('#edit_task_button').prop('disabled', true);
            var task_id = $("#edit_task_id").val();
            $.ajax({
                url: "<?= $this->Url->build('/users/updateTask/') ?>" + task_id,
                type: "POST",
                data: $('#edit_task_form').serialize(),
                dataType: "json",
                success: function(response) {

                    location.reload();
                }
            });
        }
    })

    $(".cancel").click(function() {
        location.reload();
    });
</script>