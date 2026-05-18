<?php $session = new \Cake\Http\Session();
$userSession = $session->read('data');

?>
<style>
    .bold-data {
        font-weight: 700;
    }
</style>
<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-building"></i></span>Employee Punch Time Report
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- PAGE-CONTENT -->
    <div class="page-content">
        <div class="container">
            <!-- FILTER -->
                
            <!-- TABLE -->
            <div class="row">
                <div class="col-md-2 pb-2">
                    <form id="filter_form">
                        <div class="filter_month">
                            <label><b class="bold-data">From</b></label>
                            <input type="date" class="form-control datepicker custom-dt-picker hasDatepicker" name="from_date" id="from_date" value="<?= $from ?>" onchange="FilterData()"/>
                        </div>
                    </form>
                </div>
                <div class="col-md-2 pb-2">
                    <form id="filter_form">
                        <div class="filter_year">
                            <label><b class="bold-data">To</b></label>
                            <input type="date" class="form-control datepicker custom-dt-picker hasDatepicker" name="to_date" id="to_date" value="<?= $to ?>" onchange="FilterData()"/>
                        </div>
                    </form>
                </div>
                <div class="col-md-2 pb-2">
                    <form id="filter_form">
                        <div class="filter_year">
                        <label><b class="bold-data">Employee</b></label>
                            <select name="employee" class="form-control" id="employee" onchange="FilterData()">
                            <?php if($employee==''){ ?>
                                <option value="Select Employee" hidden selected>Select Employee</option>
                           <?php } else { ?>
                                <option value="<?= $employee ?>" hidden selected><?= $employee ?></option>
                         <?php  } ?>
                         <?php foreach ($user_data as $user) : ?>
                            <option value="<?= $user['name'] ?>"><?= $user['name'] ?></option>
                            <?php endforeach; ?>
                            </select>
                            
                        </div>
                    </form>
                </div>
                <div class="col-md-2 pb-2">
                    <form id="filter_form">
                        <div class="filter_year">
                        <label><b class="bold-data">Status</b></label>
                            <select name="status" class="form-control" id="status" onchange="FilterData()">
                            <?php if($status==''){ ?>
                                <option value=" " hidden selected>Select Status</option>
                           <?php } else { ?>
                                <option value="<?= $status ?>" hidden selected><?= $status ?></option>
                         <?php  } ?>
                         
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                          
                            </select>
                            
                        </div>
                    </form>
                </div>
                <div class="col-md-1">
                            <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'employeePunchTimeReport']) ?>" class="btn btn-sm text-white" style="background-color: #3fd5db; margin-top: 22px;">Clear</a>
                            <?php if($userSession['email']=='sumit.jhunjhunwala@actiknowbi.com' || $userSession['email']=='himani.duhan@actiknow.com' || $userSession['email']=='arpit.batham@actiknow.com' || $userSession['email']=='pinkey.yadav@actiknowbi.com') { ?>
                            <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'exportPunchTimeReport']) ?>" class="btn btn-sm text-white" style="background-color: #3fd5db; margin-top: 22px;">Export</a>
                            <?php } ?>
                        </div>
                        <div class="col-md-3 pb-2">
                            <p style="margin-top: 22px; font-weight:600;">Today's Attendance : <b style="font-weight: 600;"><?= $totalemp ?></b></p>
                            <p onclick="forgetCardData();" style="font-weight:600; cursor: pointer;">Today's Forget Card : <b style="font-weight: 600;"><?= $totalForgetCard[0]['totalforgetcard'] ?></b></p>
                        </div>
                <div class="col-md-12">
                    <?= $this->Flash->render() ?>
                    <div class="content ">
                    
                    <table id="example1" style="width:100%" class="table table-default table-striped block table-bordered">
                            <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Manager Name</th>
                                    <th>Date</th>
                                    <th>In Time</th>
                                    <th>Out Time</th>
                                    <th>Total Time</th>
                                    <th>Late</th>
                                    <!-- <th>Status</th> -->
                                </tr>
                                
                            </thead>
                            <tbody>
                            <?php if (count($list) > 0) :
                                    foreach ($list as $emp) : ?>
                            <tr class="active">
                                <td class="bold-data"><?= $emp['emp'] ?></td>
                                <td><?= $emp['manager_name'] ?></td>
                                <td><?= $emp['date'] ?></td>
                                <td><?= $emp['intime'] ?></td>
                                <td><?= $emp['outtime'] ?></td>
                                <td>
                                    <?php 
                                    if($emp['total_time']==''){
                                      echo '';
                                    } else {
                                        echo date('H:i:s',strtotime($emp['total_time']));
                                    }
                                    ?>
                                </td>
                                <td><?php 
                                $timeParts = explode('.', $emp['Late_by']);
                                $timeWithoutFraction = $timeParts[0];
                                echo $timeWithoutFraction;
                                ?></td>
                                <!-- <td><?= $emp['status'] ?></td> -->
                                
                            </tr>
                            <?php endforeach;
                                endif; ?>
                    </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- CREATE CLIENT -->

<div class="modal fade" id="model_show" role="dialog">
    
  </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!-- <script>
    $("input[type=text]").on("focus", function() {
        if ($(this).val() == 0)
            $(this).val('');
    });
</script> -->

<script>
   $(document).ready(function() {
    $('#example1').dataTable({
        /* Disable initial sort */
        "aaSorting": [],
        "lengthMenu": [[100, "All", 50, 25], [100, "All", 50, 25]],
        // stateSave: true
    });

   
});


function FilterData() {
    var from= $("#from_date").val();
    var to= $("#to_date").val();
    var employee = $("#employee").val();
    var status = $("#status").val();
    console.log(employee);
    // alert( month );
    // console.log(month);
    $.ajax({
        
            url:"<?= $this->Url->build(['controller'=>'Users', 'action'=>'employeePunchTimeReport']) ?>/"+from+"/"+to+"/"+employee+"/"+status,
			method:"get",
			
         success : function(resp){
            // url:"<?= WEBURL ?>timesheet_report/"+month,
            //  $('#project_show').html(resp);
            //  $('#project_show').modal('show');
            // console.log(resp);
            // location.reload();
            window.location.href = "<?= WEBURL ?>punch_report/"+from+"/"+to+"/"+employee+"/"+status;		 	
            }
       });
}

function forgetCardData(){
		$.ajax({
            url:"<?= $this->Url->build(['controller'=>'Users', 'action'=>'forgetCardData']) ?>",
			method:"get",
         success : function(resp){
             $('#model_show').html(resp);
             $('#model_show').modal('show');
			 	
            }
       });
}
</script>

<script>
    $(document).ready(function ($) {
        $("#employee").select2({
            placeholder: "Select Option",
        });
    });
</script>