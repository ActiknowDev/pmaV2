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
                        <a href="employeeList.php" class="v-btn v-btn-secondary">
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
                            'action' => 'add'
                        ],
                        "type" => "file"
                    ]
                )
                ?>

                <div class="col-md-12">

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
                                            <input type="text" name="emp_id" class="form-control" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Employee Name</label>
                                        <div class="adon-group">
                                            <input type="text" name="name" class="form-control" placeholder="" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Father Name/ Husband's Name</label>
                                        <div class="adon-group">
                                            <input type="text" class="form-control " name="guardian_name" required placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">DOB</label>
                                        <div class="adon-group"> <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span>
                                            <input required type="text" readonly name="dob" class="form-control datepicker" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">DOJ</label>
                                        <div class="adon-group"> <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span>
                                            <input required readonly type="text" name="doj" class="form-control datepicker" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Email ID</label>
                                        <div class="adon-group"> <span class="icon ft-primary"><i class="fa fa-envelope"></i></span>
                                            <input required type="email" class="form-control " name="email" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Mobile Number</label>
                                        <div class="adon-group"> <span class="icon ft-primary"><i class="fa fa-mobile"></i></span>
                                            <input type="text" class="form-control " name="mobile_no" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Phone Number</label>
                                        <div class="adon-group"> <span class="icon ft-primary"><i class="fa fa-phone"></i></span>
                                            <input type="text" name="phone_no" class="form-control " placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Organisation</label>
                                        <div class="adon-group"> <span class="icon ft-primary"><i class="fa fa-industry"></i></span>
                                            <input type="text" class="form-control " name="company_name" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">CTC</label>
                                        <div class="adon-group">
                                            <input type="text" name="ctc" class="form-control " placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Location</label>
                                        <div class="adon-group"> <span class="icon ft-primary"><i class="fa fa-map-marker-alt"></i></span>
                                            <input type="text" class="form-control " name="location" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">PAN Number</label>
                                        <div class="adon-group">
                                            <input type="text" class="form-control " name="pan_no" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Notice Period</label>
                                        <div class="adon-group">
                                            <input type="text" class="form-control " name="ntc_perd" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Bond</label>
                                        <div class="adon-group">
                                            <input type="text" class="form-control " name="bond" placeholder="">
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
                                                <input type="text" name="house_no_prmnt" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Locality</label>
                                                <input type="text" name="locality_prmnt" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">City</label>
                                                <input type="text" name="city_prmnt" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">State</label>
                                                <input type="text" name="state_prmnt" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Zip</label>
                                                <input type="text" name="zip_prmnt" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Phone No.</label>
                                                <input type="text" name="phone_prmnt" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4 class=" title ft-dark">Present Address</h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">House No.</label>
                                                <input name="house_no_prsnt" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Locality</label>
                                                <input name="locality_prsnt" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">City</label>
                                                <input type="text" name="city_prsnt" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">State</label>
                                                <input type="text" name="state_prsnt" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Zip</label>
                                                <input name="zip_prsnt" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Phone No.</label>
                                                <input name="phone_prsnt" type="text" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                    </tr>
                                </thead>
                                <tbody id="goverment_add_more_tbody">
                                    <tr>
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
                                    </tr>
                                    <!-- <tr>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Name">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Organization">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Designation">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Address">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Contact Number">
                                        </td>
                                    </tr> -->
                                </tbody>
                            </table>

                            <button type="button" class="v-btn v-btn-primary float-right" id="goverment_add_more">Add</button>

                            <h4 class="fw-600 ft-md mb-2">Last Organisation Reference 2 (Colleague HR)</h4>
                            <table class="table table-default table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Organization</th>
                                        <th>Designation</th>
                                        <th>Address</th>
                                        <th>Contact Number</th>
                                    </tr>
                                </thead>
                                <tbody id="ref_last_add_body">
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

                                </tbody>
                            </table>
                            <button type="button" class="v-btn v-btn-primary float-right" id="ref_last_add">Add</button>
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

                                            <?= $this->Form->file("identity_proof[]", ['class' => 'form-control']) ?>

                                            <a href="#" class="v-btn v-btn-primary">Upload</a>
                                        </div>
                                        <ul class="uploaded-file-list">

                                            <!--  Run Loop -->

                                            <li>
                                                <a href="#">Voter id card varun dev</a>
                                                <span class="icon icon-xs ft-dark">
                                                    <i class="fa fa-trash"></i>
                                                </span>
                                            </li>
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
                                            <input type="file" class="form-control" name="address_proof[]" multiple>
                                            <a href="#" class="v-btn v-btn-primary">Upload</a>
                                        </div>
                                        <ul class="uploaded-file-list">

                                            <!-- Run Loop -->

                                            <li><a href="#">Voter id card varun dev</a> <span><i class="fa fa-trash"></i></span>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="block">
                        <div class="header">
                            <h4 class="title">Acadmic Details
                            </h4>
                        </div>
                        <div class="content table-responsive table-sm">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Boad/University</th>
                                        <th>Education</th>
                                        <th>Passout Year</th>
                                        <th class="width:200px;">Certificate</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody id="education_tbody">
                                    <tr>
                                        <td>
                                            <select name="education_type[]" class="form-control input-sm">
                                                <option value="" default>Please Select</option>
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
                                            <p>
                                                <a href="#">Marksheet</a>
                                                <span class="icon icon-xs">
                                                    <i class="fa fa-times"></i>
                                                </span>
                                            </p>
                                            <p>
                                                <a href="#">Certificate</a>
                                                <span class="icon icon-xs">
                                                    <i class="fa fa-times"></i>
                                                </span>
                                            </p>
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

                                </tbody>
                            </table>


                        </div>
                        <button type="button" class="v-btn v-btn-primary float-right" id="education_add_button"><i class="fa fa-plus"></i>Add More</button>

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

                                            <p><a href="#">Salary Slip</a><span class="icon icon-xs"><i class="fa fa-times"></i></span>
                                            </p>
                                        </td>
                                        <td>
                                            <!-- <a href="#" class="icon icon-xs"> -->
                                            <!-- <i class="fa fa-upload" -->

                                            <input type="file" name="salary_slip[]">
                                            <!--   <label for="salary_up1" class="fa fa-upload"></label> -->
                                            <a href="#" class="icon icon-xs"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="v-btn v-btn-primary float-right" id="salary_add_button"><i class="fa fa-plus"></i>Add More</button>

                        <!--  <a href="#" data-target="#add_payment_received"
                                    data-toggle="modal" class="v-btn v-btn-primary float-right">
                                    <i class="fa fa-plus"></i><span>Add
                                        More
                                    </span>
                                </a> -->
                    </div>
                    <div class="block">
                        <div class="header">
                            <h4 class="title">Other Information</h4>
                        </div>
                        <div class="content table-responsive table-sm">
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label for="">Bank Account No.</label>
                                    <input name="bank_acc_no" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label for="">PF Declaration form</label>
                                    <input type="file" name="pf_form" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="">Employee Certificate</label>
                                    <input type="file" name="emp_certificate" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="">NDA Form</label>
                                    <input type="file" name="nda_form" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  <input type="submit"> -->

                    <?= $this->Form->submit("Save Project", ['class' => 'v-btn v-btn-secondary float-right']) ?>
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
<div class="modal fade" tabindex="-1" role="dialog" id="upload-certificate">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Certificate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Upload Certificate</label>
                            <div class="adon-group">
                                <input type="file" class="form-control" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                <a href="company_list.php" class="v-btn v-btn-primary" data-dismiss="modal">Upload</a>
            </div>
        </div>
    </div>
</div>

<!-- ADD MILESTONE MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="add_milestone">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Milestone</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Milestone Title</label>
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
                                            <p>
                                                <a href="#">Marksheet</a>
                                                <span class="icon icon-xs">
                                                    <i class="fa fa-times"></i>
                                                </span>
                                            </p>
                                            <p>
                                                <a href="#">Certificate</a>
                                                <span class="icon icon-xs">
                                                    <i class="fa fa-times"></i>
                                                </span>
                                            </p>
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