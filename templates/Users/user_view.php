<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="heading ft-secondary">
                        <?= $user->name;?> Profile <!-- <span class="icon icon-sm ft-primary"><i class="fa fa-pencil-alt"></i> </span> -->
                    </div>
                </div>
                <div class="col-6">
                    <div class="actions-ctrl text-md-right">
                        <?= $this->Html->link('<i class="fa fa-reply"></i><span>Back </span>','/users',['class' => 'v-btn v-btn-secondary','escape' => false]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- PAGE-CONTENT -->
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="block project-info">
                        <div class="content">
                            <h4 class="sub-title ft-primary"><?= $user->name;?></h4>
                            <ul class="">
                                <li>Joined <span class="lead"><?= date('d F Y', strtotime($user->created));?></span></li>
                                <li>Email <span class="lead"><?= $user->email; ?></span></li>
                                <li>Contact <span class="lead">+91-<?= $user->contact_no; ?></span></li>
                                <li>Manager <span class="lead"><?= ($manager != '')?$manager->name:''; ?></span></li>
                                <li>Tech Lead <span class="lead"><?= $user->team; ?></span></li>
                            </ul>
                        </div>
                    </div>
                </div> 
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="block primary">
                                <div class="content">
                                    <h4 class="title">Project Completed</h4>
                                    <span><?= $complete; ?></span>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="block primary">
                                <div class="content">
                                    <h4 class="title">On Going</h4>
                                    <span><?= $going; ?></span>
                                </div>
                            </div>
                        </div> 
                        <!-- <div class="col-md-4">
                            <div class="block primary">
                                <div class="content">
                                    <h4 class="title">Awards</h4>
                                    <span>12</span>
                                </div>
                            </div>
                        </div>  -->
                    </div>
                    <hr>
                    <!-- FILTER -->
                    <!-- <div class="row">
                        <div class="col-md-4">
                            <div class="adon-group form-group">
                                <span class="icon icon-light ft-primary"><i class="fa fa-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search for user here..">
                            </div>
                        </div>
                        <div class="col-md-2 offset-md-6 text-md-right">
                            <div class="adon-group form-group">
                                <span class="icon icon-light ft-primary"><i class="fa fa-filter"></i></span>
                                <select name="" id="" class="form-control">
                                    <option value="">Sort By</option>
                                    <option value="">Active</option>
                                    <option value="">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-md-12">
                            <table  class="table table-light mb-3 table-sm" id="example" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Project Name</th>
                                        <th>Client</th>
                                        <th>Awarded_On</th>
                                        <th>Due_Date</th>
                                        <th>Manager</th>
                                        <th>Tech Lead</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($projects): ?>
                                        <?php $i=1; foreach($projects as $p): ?>
                                    <tr>
                                        <td><?= $i; ?></td>
                                        <td><?= $p['project_name']; ?></td>
                                        <td><?= $p['client_name']; ?></td>
                                         <td><?= date('d F Y',strtotime($p['awarded_on'])); ?></td>
                                        <td><?= date('d F Y',strtotime($p['due_date'])); ?></td>
                                        <td><?= $this->Html->link($p['manager'],'/user-detail/'. $p['project_manager_id'],['class' => '']); ?></td>
                                        <td><?= $this->Html->link($p['lead'],'/user-detail/'. $p['tech_lead_id'],['class' => '']); ?></td>
                                        <td>
                                            <select name="" id="<?= $p['id'];?>" data-type="project" class="form-control status" data-url="<?= WEBURL;?>">
                                        <option value="Completed" <?php if($p['status']=="Completed") echo 'selected';?>>Completed</option>
                                        <option value="Pending" <?php if($p['status']=='Pending') echo 'selected';?>>Pending</option>
                                    </select>
                                        </td>
                                    </tr>
                                    <?php $i++; endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <!-- <ul class="pagination  justify-content-end">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item active">
                                    <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul> -->
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</section>




<div class="modal fade" tabindex="-1" role="dialog" id="add_user">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="content">
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="">User Name</label>
                    <input type="text" class="form-control" placeholder="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Reporting Manager</label>
                    <input type="text" class="form-control" placeholder="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Designation</label>
                    <input type="text" class="form-control" placeholder="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Role</label>
                    <div class="adon-group">
                        <select name="langOpt[]" class="form-control" multiple id="langOpt">
                            <option value="C++">Manager</option>
                            <option value="C#">Tech Lead</option>
                            <option value="Java">BD</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Team</label>
                    <select name="" id="" class="form-control">
                        <option value="">PHP Team</option>
                        <option value="">Andriod Team</option>
                        <option value="">IOS Team</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="">Technology</label>
                    <input type="contact" class="form-control" placeholder="">
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
        <a href="company_list.php" class="v-btn v-btn-primary" data-dismiss="modal">Add User</a>
      </div>
    </div>
  </div>
</div>
