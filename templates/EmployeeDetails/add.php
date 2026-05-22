<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-user-tie"></i></span>Add Employee
                    </div>
                </div>
                <div class="col-6">
                    <div class="actions-ctrl text-md-right">
                        <a href="#" class="v-btn v-btn-secondary">
                            <i class="fa fa-list"></i><span>List Employee</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- PAGE-CONTENT -->
    <div class="page-content">
        <div class="container">
            <!-- PROJECT ADD -->
            <div class="row">

                <?= $this->Form->create(
                    null,
                    [
                        'url' => [
                            'Controller' => 'EmployeeDetails',
                            'action' => 'edit'
                        ],
                        "type" => "file"
                    ],
                )
                ?>

                <input type="hidden" value="<?= $user_data['id'] ?>" name="user_id">

                <input type="hidden" name="emp_detail_id" value="<?= @$user_data['emp_detail']['id'] ?>">

                <div class="col-md-12">


                    <?= $this->Flash->render() ?>

                    <div class="block">
                        <div class="header">
                            <h4 class="title">Add Personal Details</h4>
                        </div>
                        <div class="content ">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Employee ID</label>
                                        <div class="adon-group">
                                            <input type="text" readonly name="emp_id" class="form-control"
                                                placeholder="" value="<?= @$user_data['id'] ?>">
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Employee Name</label>
                                        <div class="adon-group">
                                            <input type="text" name="name" class="form-control" placeholder=""
                                                value="<?= @$user_data['name'] ?>" autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Father Name/ Husband's Name</label>
                                        <div class="adon-group">
                                            <input type="text" class="form-control " name="guardian_name"
                                                value="<?= @$user_data['emp_detail']['guardian_name'] ?>"
                                                autocomplete="off" required placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">DOB</label>
                                        <div class="adon-group"> <span class="icon ft-primary"><i
                                                    class="fa fa-calendar-alt"></i></span>

                                            <?= $this->Form->control('dob', ['autocomplete' => false, 'label' => false, 'class' => 'form-control', 'type' => 'date', 'required' => true, 'default' => @$user_data['emp_detail']['dob']]); ?>



                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">DOJ</label>
                                        <div class="adon-group"> <span class="icon ft-primary"><i
                                                    class="fa fa-calendar-alt"></i></span>


                                            <?= $this->Form->control('doj', ['autocomplete' => false, 'label' => false, 'class' => 'form-control', 'type' => 'date', 'required' => true, 'default' => @$user_data['emp_detail']['doj']]); ?>



                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Email ID</label>
                                        <div class="adon-group"> <span class="icon ft-primary"><i
                                                    class="fa fa-envelope"></i></span>
                                            <input required type="email" class="form-control "
                                                value="<?= @$user_data['email'] ?>" autocomplete="off" name="email"
                                                placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Mobile Number</label>
                                        <div class="adon-group"> <span class="icon ft-primary"><i
                                                    class="fa fa-mobile"></i></span>
                                            <input type="text" class="form-control " name="mobile_no"
                                                value="<?= @$user_data['emp_detail']['mobile_no'] ?>" autocomplete="off"
                                                placeholder="" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Emergency Number</label>
                                        <div class="adon-group"> <span class="icon ft-primary"><i
                                                    class="fa fa-phone"></i></span>
                                            <input type="text" name="phone_no" class="form-control "
                                                value="<?= @$user_data['emp_detail']['phone_no'] ?>" autocomplete="off"
                                                placeholder="" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Organisation</label>
                                        <div class="adon-group"> <span class="icon ft-primary"><i
                                                    class="fa fa-industry"></i></span>
                                            <input type="text" value="<?= @$user_data['company_name'] ?>"
                                                class="form-control " name="company_name" autocomplete="off"
                                                placeholder="" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">CTC</label>
                                        <div class="adon-group">
                                            <input type="text" name="ctc" class="form-control "
                                                value="<?= @$user_data['emp_detail']['ctc'] ?>" autocomplete="off"
                                                placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Location</label>
                                        <div class="adon-group"> <span class="icon ft-primary"><i
                                                    class="fa fa-map-marker-alt"></i></span>
                                            <input type="text" class="form-control " name="location"
                                                value="<?= @$user_data['emp_detail']['location'] ?>" autocomplete="off"
                                                placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">PAN Number</label>
                                        <div class="adon-group">
                                            <input type="text" class="form-control " name="pan_no"
                                                value="<?= @$user_data['emp_detail']['pan_no'] ?>" autocomplete="off"
                                                placeholder="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">UN/PF Number</label>
                                        <div class="adon-group">
                                            <input type="text" class="form-control " name="pf_no"
                                                value="<?= @$user_data['pf_no'] ?>" placeholder="" autocomplete="off">
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Notice Period</label>
                                        <div class="adon-group">
                                            <input type="text" class="form-control " name="ntc_perd"
                                                value="<?= @$user_data['emp_detail']['ntc_perd'] ?>" placeholder=""
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Bond</label>
                                        <div class="adon-group">
                                            <input type="text" class="form-control " name="bond"
                                                value="<?= @$user_data['emp_detail']['bond'] ?>" placeholder=""
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="title ft-dark">Permanent Address</h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">House No.</label>
                                                <input type="text"
                                                    value="<?= @$user_data['emp_detail']['house_no_prmnt'] ?>"
                                                    name="house_no_prmnt" autocomplete="off" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Locality</label>
                                                <input type="text"
                                                    value="<?= @$user_data['emp_detail']['locality_prmnt'] ?>"
                                                    name="locality_prmnt" autocomplete="off" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">City</label>
                                                <input type="text"
                                                    value="<?= @$user_data['emp_detail']['city_prmnt'] ?>"
                                                    name="city_prmnt" autocomplete="off" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">State</label>
                                                <input type="text"
                                                    value="<?= @$user_data['emp_detail']['state_prmnt'] ?>"
                                                    name="state_prmnt" autocomplete="off" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Zip</label>
                                                <input type="text" value="<?= @$user_data['emp_detail']['zip_prmnt'] ?>"
                                                    name="zip_prmnt" autocomplete="off" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Phone No.</label>
                                                <input type="text"
                                                    value="<?= @$user_data['emp_detail']['phone_prmnt'] ?>"
                                                    name="phone_prmnt" autocomplete="off" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>





                                <div class="col-md-6">


                                    <h4 class=" title ft-dark">Present Address</h4>

                                    <label for="same_address">Same as Permanent</label>
                                    <input type="checkbox" id="same_address">

                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">House No.</label>
                                                <input name="house_no_prsnt"
                                                    value="<?= @$user_data['emp_detail']['house_no_prsnt'] ?>"
                                                    type="text" class="form-control" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Locality</label>
                                                <input name="locality_prsnt"
                                                    value="<?= @$user_data['emp_detail']['locality_prsnt'] ?>"
                                                    type="text" class="form-control" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">City</label>
                                                <input type="text"
                                                    value="<?= @$user_data['emp_detail']['city_prsnt'] ?>"
                                                    name="city_prsnt" class="form-control" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">State</label>
                                                <input type="text"
                                                    value="<?= @$user_data['emp_detail']['state_prsnt'] ?>"
                                                    name="state_prsnt" autocomplete="off" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Zip</label>
                                                <input name="zip_prsnt"
                                                    value="<?= @$user_data['emp_detail']['zip_prsnt'] ?>" type="text"
                                                    class="form-control" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Phone No.</label>
                                                <input name="phone_prsnt"
                                                    value="<?= @$user_data['emp_detail']['phone_prsnt'] ?>" type="text"
                                                    class="form-control" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?= $this->Form->submit("Update Employee", ['class' => 'v-btn v-btn-secondary float-right']) ?>

                    <!-- REFERENCES -->
                    <div class="block">
                        <div class="header">
                            <h4 class="title">References </h4>
                        </div>
                        <div class="content table-responsive">
                            <h4 class="fw-600 ft-md mb-2">Reference 2 (One of Govt. employee)</h4>
                            <table class="table table-default table-sm mb-3">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Organization</th>
                                        <th>Designation</th>
                                        <th>Address</th>
                                        <th>Contact Number</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="goverment_add_more_tbody">
                                    <?php foreach ($user_data['emp_ref'] as $key) { ?>
                                    <?php if ($key['ref_flag'] == 'GOV') : ?>
                                    <tr>
                                        <td>
                                            <p><?= @$key['ref_name'] ?></p>

                                        </td>
                                        <td>
                                            <p><?= @$key['ref_org'] ?></p>

                                        </td>
                                        <td>
                                            <p><?= @$key['ref_desigtion'] ?></p>

                                        </td>
                                        <td>
                                            <p><?= @$key['ref_address'] ?></p>

                                        </td>
                                        <td>
                                            <p><?= @$key['ref_contact'] ?></p>

                                        </td>
                                        <td>
                                            <a href="#" onclick="load_modal_edit_ref_gov(this);" data-toggle="modal"
                                                data-id="<?= $key['id'] ?>" data-name="<?= $key['ref_name'] ?>"
                                                data-org="<?= $key['ref_org'] ?>"
                                                data-desgn="<?= $key['ref_desigtion'] ?>"
                                                data-address="<?= $key['ref_address'] ?>"
                                                data-contact="<?= $key['ref_contact'] ?>" data-target="#edit_gov_ref">
                                                <i class="fa fa-edit">
                                                </i>
                                            </a>

                                            <?= $this->Html->link(
                                                        '<i class="fa fa-archive"></i>',
                                                        ['action' => 'delete', $key['id'], 'controller' => 'EmployeeReferences'],
                                                        ['confirm' => __('Are you sure you want to delete'), 'escape' => false]
                                                    ) ?>

                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php } ?>

                                </tbody>
                            </table>

                            <input type="hidden" id="_user_id" value="<?= $user_data['id'] ?>">

                            <a href="#" data-toggle="modal" data-target="#add_gov_ref"
                                class="v-btn v-btn-primary float-right" id="add_ref_gov_prf_modal">Add</a>



                            <h4 class="fw-600 ft-md mb-2">Last Organisation Reference 2 (Colleague HR)</h4>
                            <table class="table table-default table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Organization</th>
                                        <th>Designation</th>
                                        <th>Address</th>
                                        <th>Contact Number</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="ref_last_add_body">

                                    <?php foreach ($user_data['emp_ref'] as $key) { ?>
                                    <?php if ($key['ref_flag'] == 'PRV') : ?>

                                    <tr>
                                        <td>
                                            <p><?= @$key['ref_name'] ?></p>

                                        </td>
                                        <td>
                                            <p><?= @$key['ref_org'] ?></p>

                                        </td>
                                        <td>
                                            <p><?= @$key['ref_desigtion'] ?></p>

                                        </td>
                                        <td>
                                            <p><?= @$key['ref_address'] ?></p>

                                        </td>
                                        <td>
                                            <p><?= @$key['ref_contact'] ?></p>

                                        </td>

                                        <td>

                                            <a href="#" onclick="load_modal_edit_prv_gov(this);" data-toggle="modal"
                                                data-id="<?= $key['id'] ?>" data-name="<?= $key['ref_name'] ?>"
                                                data-org="<?= $key['ref_org'] ?>"
                                                data-desgn="<?= $key['ref_desigtion'] ?>"
                                                data-address="<?= $key['ref_address'] ?>"
                                                data-contact="<?= $key['ref_contact'] ?>" data-target="#edit_prv_ref">
                                                <i class="fa fa-edit">
                                                </i>
                                            </a>


                                            <?= $this->Html->link(
                                                        '<i class="fa fa-archive"></i>',
                                                        ['action' => 'delete', $key['id'], 'controller' => 'EmployeeReferences'],
                                                        ['confirm' => __('Are you sure you want to delete'), 'escape' => false]
                                                    ) ?>

                                        </td>
                                    </tr>

                                    <?php endif; ?>
                                    <?php } ?>


                                </tbody>
                            </table>

                            <a href="#" data-toggle="modal" data-target="#add_prv_ref"
                                class="v-btn v-btn-primary float-right" id="add_ref_prv_prf_modal">Add</a>
                        </div>
                    </div>
                    <!-- REFERENCES -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="block">
                                <div class="header">
                                    <h4 class="title">Identity Proof (Any One) </h4>
                                    <p class="lead ft-xs">[Passport/Voter ID, Driving License]</p>
                                </div>
                                <div class="content table-responsive">
                                    <div class="container">
                                        <div class="upload-file adon-group">
                                            <!-- <input type="file" name="identity_proof[]" class="form-control" multiple> -->


                                            <?= $this->Form->file("identity_proof[]", ['class' => 'form-control', 'required' => false, 'multiple' => true]) ?>

                                            <!--  <a  class="v-btn v-btn-primary">Upload</a> -->
                                        </div>
                                        <ul class="uploaded-file-list">

                                            <?php foreach ($user_data['emp_proof'] as $key) { ?>
                                            <?php if ($key['prf_flag'] == 'IDP') : ?>

                                            <li>
                                                <a href="<?= $this->Url->webroot(@$key['prf_file']) ?>"
                                                    target="_blank"><?= @end(explode('/', $key['prf_file'])) ?></a>

                                                <span data-id="<?= $key['id'] ?>"
                                                    class="icon icon-xs ft-dark identity_proof_delete">
                                                    <i class="fa fa-trash"></i>
                                                </span>


                                            </li>

                                            <?php endif; ?>
                                            <?php } ?>
                                            <l>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="block">
                                <div class="header">
                                    <h4 class="title">Address Proof (Any One) </h4>
                                    <p class="lead ft-xs">[Passport/Voter ID, Driving License]</p>
                                </div>
                                <div class="content table-responsive">
                                    <div class="container">
                                        <div class="upload-file adon-group">
                                            <?= $this->Form->file("address_proof[]", ['class' => 'form-control', 'required' => false, 'multiple' => true]) ?>
                                            <!-- <a href="#" class="v-btn v-btn-primary">Upload</a> -->
                                        </div>
                                        <ul class="uploaded-file-list">

                                            <!-- Run Loop -->

                                            <?php foreach ($user_data['emp_proof'] as $key) { ?>
                                            <?php if ($key['prf_flag'] == 'ADP') : ?>

                                            <a
                                                href="<?= $this->Url->webroot(@$key['prf_file']) ?>"><?= @end(explode('/', $key['prf_file'])) ?></a>
                                            <span data-id="<?= $key['id'] ?>"
                                                class="icon icon-xs ft-dark identity_proof_delete">
                                                <i class="fa fa-trash"></i>
                                            </span>
                                            </li>

                                            <?php endif; ?>
                                            <?php } ?>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?= $this->Form->submit("Update Identity/Goverment Proof", ['class' => 'v-btn v-btn-secondary float-right', 'id' => 'imgUpload', 'onclick' => 'uploadImg(event)']) ?>


                    <div class="block">
                        <div class="header">
                            <h4 class="title">Academic Details
                            </h4>
                        </div>
                        <div class="content table-responsive table-sm">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>BOARD/University</th>
                                        <th>Education</th>
                                        <th>Passout Year</th>
                                        <th class="width:200px;">Certificate</th>
                                        <th>Action#</th>
                                    </tr>
                                </thead>
                                <tbody id="education_tbody">

                                    <?php foreach ($user_data['emp_acad'] as $key) { ?>

                                    <tr>
                                        <td>
                                            <p><?= $key['ac_type'] ?></p>

                                        </td>
                                        <td>
                                            <p><?= $key['ac_org'] ?></p>

                                        </td>
                                        <td>
                                            <p><?= $key['ac_education'] ?></p>

                                        </td>
                                        <td>
                                            <p><?= $key['ac_passout'] ?></p>

                                        </td>
                                        <td>
                                            <p>
                                                <a <?php if ($key['acc_mark']) : ?>
                                                    href="<?= $this->Url->webroot(@$key['acc_mark']) ?>" <?php endif; ?>
                                                    target="_blank"><?= @end(explode('/', $key['acc_mark'])) ?></a>

                                                <?php if ($key['acc_mark']) {

                                                    ?>
                                                <span class="icon icon-xs academic-delete" data-id="<?= $key['id'] ?>"
                                                    data-flag="mark">
                                                    <i class="fa fa-times"></i>
                                                </span>
                                                <?php } ?>
                                            </p>
                                            <p>
                                                <a <?php if ($key['acc_certificate']) : ?>
                                                    href="<?= $this->Url->webroot(@$key['acc_certificate']) ?>"
                                                    <?php endif; ?>
                                                    target="_blank"><?= @end(explode('/', $key['acc_certificate'])) ?></a>
                                                <?php if ($key['acc_certificate']) {

                                                    ?>
                                                <span class="icon icon-xs academic-delete" data-id="<?= $key['id'] ?>"
                                                    data-flag="cert">
                                                    <i class="fa fa-times"></i>
                                                </span>
                                                <?php } ?>
                                            </p>
                                        </td>
                                        <td>


                                            <a href="#" data-id="<?= @$key['id'] ?>" data-type="<?= @$key['ac_type'] ?>"
                                                data-org="<?= @$key['ac_org'] ?>"
                                                data-educ="<?= @$key['ac_education'] ?>"
                                                data-passout="<?= @$key['ac_passout'] ?>"
                                                onclick="load_modal_education(this);" class="icon icon-xs"
                                                data-toggle="modal" data-target="#edit_education">
                                                <i class="fa fa-edit"></i>
                                            </a>


                                            <?= $this->Html->link(
                                                    '<i class="fa fa-archive"></i>',
                                                    ['action' => 'delete', $key['id'], 'controller' => 'EmployeeAcademics'],
                                                    ['confirm' => __('Are you sure you want to delete'), 'escape' => false]
                                                ) ?>




                                            <!-- <label>Marksheet</label>
                                            <input type="file" class="form-control" name="Marksheet[]">
                                            <label>Certificate</label> -->
                                            <!-- <input type="file" class="form-control" name="certificate[]">
 -->
                                            <!-- 
                                            <a href="#" data-target="#upload-certificate" data-toggle="modal"
                                                class="icon icon-sm"><i class="fa fa-upload"></i></a> -->
                                        </td>
                                    </tr>


                                    <?php } ?>

                                </tbody>
                            </table>


                        </div>

                        <a href="#" data-toggle="modal" data-target="#add_education"
                            class="v-btn v-btn-primary float-right">Add</a>






                        <!-- <a href="#" data-target="#add_payment_received"
                                    data-toggle="modal" class="v-btn v-btn-primary float-right">
                                    <span>Add
                                        More
                                    </span>
                                </a> -->

                    </div>
                    <div class="block">
                        <div class="header">
                            <h4 class="title">Work History </h4>
                        </div>
                        <div class="content table-responsive table-sm">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Company Name</th>
                                        <th>Designation</th>
                                        <th>Location</th>
                                        <th>DOJ</th>
                                        <th>DOR</th>
                                        <th>Certificate</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="salary_tbody">
                                    <?php foreach ($user_data['emp_work_history'] as $key) { ?>
                                    <tr>
                                        <td>
                                            <p><?= $key['cmp_name'] ?></p>

                                        </td>
                                        <td>
                                            <p><?= $key['cmp_desgnation'] ?></p>

                                        </td>
                                        <td>
                                            <p><?= $key['cmp_location'] ?></p>

                                        </td>
                                        <td>

                                            <p><?= $key['cmp_doj'] ?></p>

                                        </td>
                                        <td>
                                            <p><?= $key['cmp_dor'] ?></p>

                                        </td>
                                        <td>

                                            <p><a <?php if ($key['cmp_splip']) : ?>
                                                    href="<?= $this->Url->webroot(@$key['cmp_splip']) ?>"
                                                    <?php endif; ?>
                                                    target="_blank"><?= @end(explode('/', $key['cmp_splip'])) ?></a><span
                                                    class="icon icon-xs">

                                                    <?php if ($key['cmp_splip']) {

                                                        ?>
                                                    <span class="icon icon-xs work-history-delete"
                                                        data-id='<?= $key['id'] ?>'>
                                                        <i class="fa fa-times"></i>
                                                    </span>
                                                    <?php } ?>


                                            </p>
                                        </td>
                                        <td>

                                            <a href="#" data-id="<?= @$key['id'] ?>"
                                                data-name="<?= @$key['cmp_name'] ?>"
                                                data-dsgn="<?= @$key['cmp_desgnation'] ?>"
                                                data-location="<?= @$key['cmp_location'] ?>"
                                                data-doj="<?= @$key['cmp_doj'] ?>" data-dor="<?= @$key['cmp_dor'] ?>"
                                                onclick="load_modal_work_history(this);" class="icon icon-xs"
                                                data-toggle="modal" data-target="#edit_work_history">
                                                <i class="fa fa-edit"></i>
                                            </a>


                                            <?= $this->Html->link(
                                                    '<i class="fa fa-trash"></i>',
                                                    ['action' => 'delete', $key['id'], 'controller' => 'EmployeeWorkhistorys'],
                                                    ['confirm' => __('Are you sure you want to delete'), 'escape' => false]
                                                ) ?>

                                        </td>
                                    </tr>
                                    <?php } ?>


                                </tbody>
                            </table>
                        </div>

                        <a href="#" data-toggle="modal" data-target="#add_work_history"
                            class="v-btn v-btn-primary float-right">Add</a>


                    </div>
                    <div class="block">
                        <div class="header">
                            <h4 class="title">Other Information</h4>
                        </div>
                        <div class="content table-responsive table-sm">
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label for="">Bank Account No.</label>
                                    <input name="bank_acc_no" type="text" class="form-control"
                                        value="<?= @$user_data['emp_othdetail']['bank_acc_no'] ?>">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label for="">PF Declaration form</label>
                                    <input type="file" name="pf_form[]" class="form-control" multiple>

                                    <label class="text-primary"><?= @end(explode('/', $user_data['emp_othdetail']['pf_form']))
                                                                ?></label>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Employee Certificate</label>
                                    <input type="file" name="emp_certificate[]" class="form-control" multiple>
                                    <label class="text-primary"><?= @end(explode('/', $user_data['emp_othdetail']['emp_certific']))
                                                                ?></label>
                                </div>
                                <div class="col-md-4">
                                    <label for="">NDA Form</label>
                                    <input type="file" name="nda_form[]" class="form-control" multiple>
                                    <label class="text-primary"><?= @end(explode('/', $user_data['emp_othdetail']['nda_form']))
                                                                ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  <input type="submit"> -->

                    <?= $this->Form->submit("Update Other Information", ['class' => 'v-btn v-btn-secondary float-right']) ?>
                    <!-- 
                    <a href="#" data-target="#confirmation_assign_project" data-toggle="modal"
                        class="v-btn v-btn-secondary float-right"><span>Save Project</span></a> -->
                </div>


                <?= $this->Form->end(); ?>

            </div>
        </div>
    </div>
</section>

<!-- UPLOAD CERTIFICATE MODAL -->







<div class="modal fade" tabindex="-1" role="dialog" id="add_work_history">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= $this->Form->create($employeeWorkHistory, ['url' => ['controller' => 'EmployeeWorkHistorys', 'action' => 'add'], 'type' => 'file']) ?>


            <div class="modal-header">
                <h5 class="modal-title">Add Work History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">




                    <input type="hidden" name="user_id" value="<?= $user_data['id'] ?>">

                    <?php

                    echo $this->Form->control('cmp_name', ['autocomplete' => false, 'label' => 'Company Name', 'required' => true, 'type' => 'text', 'class' => 'form-control']);
                    echo $this->Form->control('cmp_desgnation', ['autocomplete' => false, 'label' => 'Company Designation', 'required' => true, 'type' => 'text', 'class' => 'form-control']);
                    echo $this->Form->control('cmp_location', ['autocomplete' => false, 'label' => 'Company Location', 'required' => true, 'type' => 'text', 'class' => 'form-control']);
                    echo $this->Form->control('cmp_doj', ['autocomplete' => false, 'class' => 'form-control', 'label' => 'DOJ', 'required' => true, 'type' => 'date']);
                    echo $this->Form->control('cmp_dor', ['autocomplete' => false, 'label' => 'DOR', 'class' => 'form-control', 'required' => true, 'type' => 'date']);
                    echo $this->Form->control('cmp_splip', ['autocomplete' => false, 'label' => 'SLIP', 'class' => 'form-control', 'required' => true, 'type' => 'file']);
                    ?>




                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                <?= $this->Form->button(__('Add'), ['class' => 'v-btn v-btn-base']) ?>

            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>



<div class="modal fade" tabindex="-1" role="dialog" id="edit_work_history">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= $this->Form->create(null, ['url' => ['controller' => 'EmployeeWorkHistorys', 'action' => 'edit'], 'type' => 'file']) ?>


            <div class="modal-header">
                <h5 class="modal-title">Edit Work History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">




                    <input type="hidden" name="user_id" value="<?= $user_data['id'] ?>">
                    <input type="hidden" name="_id" id="edit_work_history_id">

                    <?php

                    echo $this->Form->control('cmp_name', ['autocomplete' => false, 'label' => 'Company Name', 'required' => true, 'type' => 'text', 'class' => 'form-control', 'id' => 'cmp_name_edit']);
                    echo $this->Form->control('cmp_desgnation', ['autocomplete' => false, 'label' => 'Company Designation', 'required' => true, 'type' => 'text', 'class' => 'form-control', 'id' => 'cmp_desgnation_edit']);
                    echo $this->Form->control('cmp_location', ['autocomplete' => false, 'label' => 'Company Location', 'required' => true, 'type' => 'text', 'class' => 'form-control', 'id' => 'cmp_location_edit']);
                    echo $this->Form->control('cmp_doj', ['autocomplete' => false, 'class' => 'form-control', 'label' => 'DOJ', 'required' => true, 'type' => 'date', 'id' => 'cmp_doj_edit']);
                    echo $this->Form->control('cmp_dor', ['autocomplete' => false, 'label' => 'DOR', 'class' => 'form-control', 'required' => true, 'type' => 'date', 'id' => 'cmp_dor_edit']);
                    echo $this->Form->control('cmp_splip', ['label' => 'SLIP', 'class' => 'form-control', 'required' => false, 'type' => 'file', 'id' => 'cmp_splip_edit']);
                    ?>




                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                <?= $this->Form->button(__('Update'), ['class' => 'v-btn v-btn-base']) ?>

            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>



<div class="modal fade" tabindex="-1" role="dialog" id="add_education">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= $this->Form->create($employeeAcademic, ['url' => ['controller' => 'EmployeeAcademics', 'action' => 'add'], 'type' => 'file']) ?>
            <div class="modal-header">
                <h5 class="modal-title">Add Education</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">


                    <fieldset>
                        <?php
                        // echo $this->Form->control('user_id', ['options' => $users,'empty'=>$user_id,'readonly'=>true]);
                        ?>
                        <input type="hidden" name="user_id" value="<?= $user_data['id'] ?>">
                        <?php
                        echo $this->Form->control('ac_type', ['label' => 'Type', 'type' => 'select', 'options' => ['10th' => '10th', '12th' => '12th', 'UG' => 'UG', 'PG' => 'PG'], 'default' => '10th', 'class' => 'form-control', 'required' => true]);
                        echo $this->Form->control('ac_org', ['autocomplete' => false, 'label' => 'BOARD/UNIVERSITY', 'class' => 'form-control', 'required' => true, 'type' => 'text']);
                        echo $this->Form->control('ac_education', ['autocomplete' => false, 'label' => 'Education', 'class' => 'form-control', 'required' => true, 'type' => 'text']);
                        echo $this->Form->control('ac_passout', ['autocomplete' => false, 'label' => 'Passout', 'class' => 'form-control', 'required' => true, 'type' => 'text']);
                        echo $this->Form->control('acc_certificate', ['type' => 'file', 'required' => false, 'label' => 'Certificate']);
                        echo $this->Form->control('acc_mark', ['type' => 'file', 'required' => false, 'label' => 'Marksheet']);
                        ?>

                    </fieldset>



                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                <?= $this->Form->button(__('Add'), ['class' => 'v-btn v-btn-base']) ?>

            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="edit_education">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= $this->Form->create(null, ['url' => ['controller' => 'EmployeeAcademics', 'action' => 'edit'], 'type' => 'file']) ?>
            <div class="modal-header">
                <h5 class="modal-title">Edit Education</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">


                    <fieldset>
                        <?php
                        // echo $this->Form->control('user_id', ['options' => $users,'empty'=>$user_id,'readonly'=>true]);
                        ?>
                        <input type="hidden" name="user_id" value="<?= $user_data['id'] ?>">
                        <input type="hidden" name="_id" id="edit_educ_id">

                        <?php
                        echo $this->Form->control('ac_type', ['label' => 'Type', 'type' => 'select', 'options' => ['10th' => '10th', '12th' => '12th', 'UG' => 'UG', 'PG' => 'PG'], 'default' => '10th', 'class' => 'form-control', 'required' => true, 'id' => 'ac_type_edit']);
                        echo $this->Form->control('ac_org', ['autocomplete' => false, 'label' => 'BOARD/UNIVERSITY', 'class' => 'form-control', 'required' => true, 'id' => 'ac_org_edit']);
                        echo $this->Form->control('ac_education', ['autocomplete' => false, 'label' => 'Education', 'class' => 'form-control', 'required' => true, 'id' => 'ac_educ_edit']);
                        echo $this->Form->control('ac_passout', ['autocomplete' => false, 'label' => 'Passout', 'class' => 'form-control', 'required' => true, 'id' => 'ac_passout_edit']);
                        echo $this->Form->control('acc_certificate', ['type' => 'file', 'required' => false, 'label' => 'Certificate', 'id' => 'ac_certificate_edit']);
                        echo $this->Form->control('acc_mark', ['type' => 'file', 'required' => false, 'label' => 'Marksheet', 'id' => 'ac_marksheet_edit']);
                        ?>

                    </fieldset>



                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                <?= $this->Form->button(__('Update'), ['class' => 'v-btn v-btn-base']) ?>

            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>



<div class="modal fade" tabindex="-1" role="dialog" id="add_prv_ref">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= $this->Form->create($employeeReference, ['url' => ['controller' => 'EmployeeReferences', 'action' => 'add']]) ?>
            <div class="modal-header">
                <h5 class="modal-title">Add Last Organisation Reference</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">


                    <fieldset>
                        <?php
                        // echo $this->Form->control('user_id', ['options' => $users,'empty'=>$user_id,'readonly'=>true]);
                        ?>
                        <input type="hidden" name="user_id" value="<?= $user_data['id'] ?>">
                        <?php
                        echo $this->Form->control('ref_name', ['autocomplete' => false, 'label' => 'Name', 'class' => 'form-control', 'required' => true, 'type' => 'text']);
                        echo $this->Form->control('ref_org', ['autocomplete' => false, 'label' => 'Organization', 'class' => 'form-control', 'required' => true, 'type' => 'text']);
                        echo $this->Form->control('ref_desigtion', ['autocomplete' => false, 'label' => 'Designation', 'class' => 'form-control', 'required' => true, 'type' => 'text']);
                        echo $this->Form->control('ref_address', ['autocomplete' => false, 'label' => 'Address', 'class' => 'form-control', 'required' => true, 'type' => 'text']);
                        echo $this->Form->control('ref_contact', ['autocomplete' => false, 'label' => 'Contact', 'class' => 'form-control', 'required' => true, 'type' => 'text']);
                        ?>
                        <input type="hidden" name="ref_flag" value="PRV">
                    </fieldset>



                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                <?= $this->Form->button(__('Add'), ['class' => 'v-btn v-btn-base']) ?>

            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="add_gov_ref">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= $this->Form->create($employeeReference, ['url' => ['controller' => 'EmployeeReferences', 'action' => 'add']]) ?>
            <div class="modal-header">
                <h5 class="modal-title">Add Goverment Refernce</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">


                    <fieldset>
                        <?php
                        // echo $this->Form->control('user_id', ['options' => $users,'empty'=>$user_id,'readonly'=>true]);
                        ?>
                        <input type="hidden" name="user_id" value="<?= $user_data['id'] ?>">
                        <?php
                        echo $this->Form->control('ref_name', ['autocomplete' => false, 'label' => 'Name', 'class' => 'form-control', 'required' => true, 'type' => 'text']);
                        echo $this->Form->control('ref_org', ['autocomplete' => false, 'label' => 'Organization', 'class' => 'form-control', 'required' => true, 'type' => 'text']);
                        echo $this->Form->control('ref_desigtion', ['autocomplete' => false, 'label' => 'Designation', 'class' => 'form-control', 'required' => true, 'type' => 'text']);
                        echo $this->Form->control('ref_address', ['autocomplete' => false, 'label' => 'Address', 'class' => 'form-control', 'required' => true, 'type' => 'text']);
                        echo $this->Form->control('ref_contact', ['autocomplete' => false, 'label' => 'Contact', 'class' => 'form-control', 'required' => true, 'type' => 'text']);
                        ?>
                        <input type="hidden" name="ref_flag" value="GOV">
                    </fieldset>



                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                <?= $this->Form->button(__('Add'), ['class' => 'v-btn v-btn-base']) ?>

            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<!-- ADD MILESTONE MODAL -->


<div class="modal fade" tabindex="-1" role="dialog" id="edit_gov_ref">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= $this->Form->create(null, ['url' => ['controller' => 'EmployeeReferences', 'action' => 'edit']]) ?>
            <div class="modal-header">
                <h5 class="modal-title">Edit Goverment Refernce</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">


                    <fieldset>
                        <?php
                        // echo $this->Form->control('user_id', ['options' => $users,'empty'=>$user_id,'readonly'=>true]);
                        ?>
                        <input type="hidden" name="_id" id="ref-id-edit">
                        <input type="hidden" name="user_id_sent" value="<?= $user_data['id'] ?>">
                        <?php
                        echo $this->Form->control('ref_name', ['autocomplete' => false, 'label' => 'Name', 'class' => 'form-control', 'required' => true, 'id' => 'ref-name-edit', 'type' => 'text']);
                        echo $this->Form->control('ref_org', ['autocomplete' => false, 'label' => 'Organization', 'class' => 'form-control', 'required' => true, 'id' => 'ref-org-edit', 'type' => 'text']);
                        echo $this->Form->control('ref_desigtion', ['autocomplete' => false, 'label' => 'Designation', 'class' => 'form-control', 'required' => true, 'id' => 'ref-desigtion-edit', 'type' => 'text']);
                        echo $this->Form->control('ref_address', ['autocomplete' => false, 'label' => 'Address', 'class' => 'form-control', 'required' => true, 'id' => 'ref-address-edit', 'type' => 'text']);
                        echo $this->Form->control('ref_contact', ['autocomplete' => false, 'label' => 'Contact', 'class' => 'form-control', 'required' => true, 'id' => 'ref-contact-edit', 'type' => 'text']);
                        ?>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                <?= $this->Form->button(__('Update'), ['class' => 'v-btn v-btn-base']) ?>

            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="edit_prv_ref">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= $this->Form->create(null, ['url' => ['controller' => 'EmployeeReferences', 'action' => 'edit']]) ?>
            <div class="modal-header">
                <h5 class="modal-title">Edit Last Organisation Reference</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">


                    <fieldset>
                        <?php
                        // echo $this->Form->control('user_id', ['options' => $users,'empty'=>$user_id,'readonly'=>true]);
                        ?>
                        <input type="hidden" name="_id" id="ref-id-edit-prv">
                        <input type="hidden" name="user_id_sent" value="<?= $user_data['id'] ?>">
                        <?php
                        echo $this->Form->control('ref_name', ['autocomplete' => false, 'label' => 'Name', 'class' => 'form-control', 'required' => true, 'id' => 'ref-name-edit-prv', 'type' => 'text']);
                        echo $this->Form->control('ref_org', ['autocomplete' => false, 'label' => 'Organization', 'class' => 'form-control', 'required' => true, 'id' => 'ref-org-edit-prv', 'type' => 'text']);
                        echo $this->Form->control('ref_desigtion', ['autocomplete' => false, 'label' => 'Designation', 'class' => 'form-control', 'required' => true, 'id' => 'ref-desigtion-edit-prv', 'type' => 'text']);
                        echo $this->Form->control('ref_address', ['autocomplete' => false, 'label' => 'Address', 'class' => 'form-control', 'type' => 'text', 'required' => true, 'id' => 'ref-address-edit-prv', 'type' => 'text']);
                        echo $this->Form->control('ref_contact', ['autocomplete' => false, 'label' => 'Contact', 'class' => 'form-control', 'required' => true, 'id' => 'ref-contact-edit-prv', 'type' => 'text']);
                        ?>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                <?= $this->Form->button(__('Update'), ['class' => 'v-btn v-btn-base']) ?>

            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>


<!-- ADD MILESTONE MODAL -->
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
                <div class="content">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Task</label>
                            <div class="adon-group">
                                <input type="text" class="form-control" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Due Date</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span>
                                <input type="text" class="form-control datepicker" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="">Amount</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-dollar-sign"></i></span>
                                <input type="number" class="form-control" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                <a href="company_list.php" class="v-btn v-btn-primary" data-dismiss="modal">Add Milestone</a>
            </div>
        </div>
    </div>
</div>

<!-- ADD PAYMENT RECEIVED MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="add_payment_received">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Description</label>
                            <div class="adon-group">
                                <input type="text" class="form-control" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Date</label>
                            <div class="adon-group">
                                <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span>
                                <input type="Email Id" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="">Received Payment</label>
                            <div class="adon-group">
                                <span class="icon"><i class="fa fa-dollar-sign"></i></span>
                                <input type="number" class="form-control" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                <a href="company_list.php" class="v-btn v-btn-primary" data-dismiss="modal">Add To Payment History</a>
            </div>
        </div>
    </div>
</div>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>
// function addGovMore(ele){

//     var par = document.getElementById("goverment_add_more_tbody");
//     console.log(par);
// }


$("#same_address").change(function() {
    if (this.checked) {
        $("input[name='house_no_prsnt']").val($("input[name='house_no_prmnt']").val());
        $("input[name='locality_prsnt']").val($("input[name='locality_prmnt']").val());
        $("input[name='city_prsnt']").val($("input[name='city_prmnt']").val());
        $("input[name='state_prsnt']").val($("input[name='state_prmnt']").val());
        $("input[name='zip_prsnt']").val($("input[name='zip_prmnt']").val());
        $("input[name='phone_prsnt']").val($("input[name='phone_prmnt']").val());
    } else {

        $("input[name='house_no_prsnt']").val('');
        $("input[name='locality_prsnt']").val('');
        $("input[name='city_prsnt']").val('');
        $("input[name='state_prsnt']").val('');
        $("input[name='zip_prsnt']").val('');
        $("input[name='phone_prsnt']").val('');

    }
});

$(".identity_proof_delete").click(function() {

    var csrf = $("input[name='_csrfToken']").val();
    var url = "<?= $this->Url->build([
                        "controller" => "EmployeeDetails",
                        "action" => "deleteimgproof"
                    ]) ?>";

    let res = confirm("Are You Sure");
    if (res) {

        var id = $(this).attr("data-id");

        $.post(url, {
            '_csrfToken': csrf,
            'id': id
        }, function(result) {

            if (result == "Yes") {
                location.reload();
            }


        });


    }

    return false;


});


$(".work-history-delete").click(function() {

    var csrf = $("input[name='_csrfToken']").val();
    var url = "<?= $this->Url->build([
                        "controller" => "EmployeeDetails",
                        "action" => "deleteimgworkhistory"
                    ]) ?>";

    let res = confirm("Are You Sure");
    if (res) {

        var id = $(this).attr("data-id");

        $.post(url, {
            '_csrfToken': csrf,
            'id': id
        }, function(result) {

            if (result == "Yes") {
                location.reload();
            }


        });


    }

    return false;


});


$(".academic-delete").click(function() {

    var csrf = $("input[name='_csrfToken']").val();
    var url = "<?= $this->Url->build([
                        "controller" => "EmployeeDetails",
                        "action" => "deleteacademic"
                    ]) ?>";

    let res = confirm("Are You Sure");
    if (res) {

        var id = $(this).attr("data-id");
        var flag = $(this).attr("data-flag");

        $.post(url, {
            '_csrfToken': csrf,
            'id': id,
            'flag': flag
        }, function(result) {

            if (result == "Yes") {
                location.reload();
            }


        });


    }

    return false;


});



$(function() {
    $(".datepicker2").datepicker({
        dateFormat: 'yy-dd-mm'
    });
});

function load_modal_work_history(ele) {

    var id = $(ele).attr("data-id");
    var name = $(ele).attr("data-name");
    var dsgn = $(ele).attr("data-dsgn");
    var loc = $(ele).attr("data-location");
    var doj = $(ele).attr("data-doj");
    var dor = $(ele).attr("data-dor");


    $("#cmp_name_edit").val(name);
    $("#edit_work_history_id").val(id);
    $("#cmp_dor_edit").val(dor);
    $("#cmp_doj_edit").val(doj);
    $("#cmp_location_edit").val(loc);
    $("#cmp_desgnation_edit").val(dsgn)

}




function load_modal_education(ele) {
    var id = $(ele).attr("data-id");
    var type = $(ele).attr("data-type");
    var org = $(ele).attr("data-org");
    var educ = $(ele).attr("data-educ");
    var passout = $(ele).attr("data-passout");

    $("#edit_educ_id").val(id);
    $("#ac_type_edit").val(type);
    $("#ac_passout_edit").val(passout);
    $("#ac_org_edit").val(org);
    $("#ac_educ_edit").val(educ);
}

function get_data_from_ref_modal(ele) {

    var id = $(ele).attr("data-id");
    var name = $(ele).attr("data-name");
    var org = $(ele).attr("data-org");
    var dsg = $(ele).attr("data-desgn");
    var address = $(ele).attr("data-address");
    var contact = $(ele).attr("data-contact");

    return [id, name, org, dsg, address, contact];
}



function load_modal_edit_prv_gov(ele) {

    var data = get_data_from_ref_modal(ele);

    $("#ref-id-edit-prv").val(data[0]);
    $("#ref-name-edit-prv").val(data[1]);
    $("#ref-org-edit-prv").val(data[2]);
    $("#ref-desigtion-edit-prv").val(data[3]);
    $("#ref-address-edit-prv").val(data[4]);
    $("#ref-contact-edit-prv").val(data[5]);


}


function load_modal_edit_ref_gov(ele) {

    var data = get_data_from_ref_modal(ele);


    $("#ref-id-edit").val(data[0]);
    $("#ref-name-edit").val(data[1]);
    $("#ref-org-edit").val(data[2]);
    $("#ref-desigtion-edit").val(data[3]);
    $("#ref-address-edit").val(data[4]);
    $("#ref-contact-edit").val(data[5]);



}

$("#goverment_add_more").click(function() {
    $("#goverment_add_more_tbody").append(
        `<tr>
                                        <td>
                                            <input type="text" class="form-control" name="ref_gov_name[]" placeholder="Name">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="ref_gov_org[]" placeholder="Organization">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="ref_gov_desgn[]" placeholder="Designation">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="ref_gov_address[]" placeholder="Address">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="ref_gov_contact[]" placeholder="Contact Number">
                                        </td>
                                    </tr>`
    );
});


$("#ref_last_add").click(function() {

    $("#ref_last_add_body").append(
        `
      <tr>
                                        <td>
                                            <input type="text" class="form-control" name="ref_last_name[]" placeholder="Name">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="ref_last_org[]" placeholder="Organization">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="ref_last_desgn[]" placeholder="Designation">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="ref_last_addrss[]" placeholder="Address">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="ref_last_contact[]" placeholder="Contact Number">
                                        </td>
                                    </tr>
                                   

    `);
})

$("#education_add_button").click(function() {
    $("#education_tbody").append(`
            <tr>
                                        <td>
                                            <select name="education_type[]" class="form-control input-sm" id="">
                                                <option default>Please Select</option>
                                                <option value="10th">10th</option>
                                                <option value="12th">12th</option>
                                                <option value="UG">Graduation</option>
                                                <option value="PG">Post Graduation</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="education_org[]" class="form-control input-sm">
                                        </td>
                                        <td>
                                            <input type="text" name="education_Education[]" class="form-control input-sm">
                                        </td>
                                        <td>
                                            <input type="text" name="education_passout[]" class="form-control input-sm">
                                        </td>
                                        <td>
                                          
                                        </td>
                                        <td>

                                            <label>Marksheet</label>
                                            <input type="file" class="form-control" name="Marksheet[]">
                                            <label>Certificate</label>
                                            <input type="file" class="form-control" name="certificate[]">

                                            <!-- 
                                            <a href="#" data-target="#upload-certificate" data-toggle="modal"
                                                class="icon icon-sm"><i class="fa fa-upload"></i></a> -->
                                        </td>
                                    </tr>

        `);
});



$("#salary_add_button").click(function() {
    $("#salary_tbody").append(`

        <tr>
                                        <td>
                                            <input type="text" name="comp_name[]" class="form-control input-sm">
                                        </td>
                                        <td>
                                            <input name="cmp_design[]" type="text" class="form-control input-sm">
                                        </td>
                                        <td>
                                            <input name="cmp_location[]" type="text" class="form-control input-sm">
                                        </td>
                                        <td>
                                            <input type="text" name="cmp_doj[]" class="form-control input-sm datepicker">
                                        </td>
                                        <td>
                                            <input name="cmp_dor[]" type="text" class="form-control input-sm datepicker">
                                        </td>
                                        <td>

                                            <p><a href="#">Salary Slip</a><span class="icon icon-xs"><i
                                                        class="fa fa-times"></i></span>
                                            </p>
                                        </td>
                                        <td>
                                            <!-- <a href="#" class="icon icon-xs"> --><!-- <i class="fa fa-upload" -->

                                                <input type="file" name="salary_slip[]">
                                               <!--   <label for="salary_up1" class="fa fa-upload"></label> -->
                                            <a href="#" class="icon icon-xs"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>

        `);
    $(".datepicker").datepicker({

        dateFormat: 'yy-dd-mm'

    });
});
</script>

<script>
function uploadImg(e) {
    e.preventDefault();
    console.log(123);
}
</script>