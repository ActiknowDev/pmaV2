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
                        <span class="icon"><i class="fa fa-building"></i></span>Employee Timesheet Filled Report
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
                <!-- <div class="col-md-2 pb-2">
                    <form id="filter_form">
                        <div class="filter_month">
                            <label><b class="bold-data">From</b></label>
                            <input type="date" class="form-control datepicker custom-dt-picker hasDatepicker" name="from_date" id="from_date" value="<?= $from ?>" onchange="FilterData()"/>
                        </div>
                    </form>
                </div> -->
                <!-- <div class="col-md-2 pb-2">
                    <form id="filter_form">
                        <div class="filter_year">
                            <label><b class="bold-data">To</b></label>
                            <input type="date" class="form-control datepicker custom-dt-picker hasDatepicker" name="to_date" id="to_date" value="<?= $to ?>" onchange="FilterData()"/>
                        </div>
                    </form>
                </div> -->
                <div class="col-md-2 pb-2">
                    <form id="filter_form">
                        <div class="filter_year">
                        <label><b class="bold-data">Manager</b></label>
                            <select name="manager" class="form-control" id="manager" onchange="FilterData()">
                            <?php if($manager==''){ ?>
                                <option value="Select Manager" hidden selected>Select Manager</option>
                           <?php } else { ?>
                                <option value="<?= $manager ?>" hidden selected><?= $manager ?></option>
                         <?php  } ?>
                         <?php foreach ($manager_data as $user) : ?>
                            <option value="<?= $user['name'] ?>"><?= $user['name'] ?></option>
                            <?php endforeach; ?>
                            </select>
                            
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
                        <label><b class="bold-data">Select Month</b></label>
                        <select name="month" class="form-control" id="month" onchange="FilterData()">
                            <option value="<?php echo $month; ?>" hidden selected>
                                <?php $month_name = date("F", mktime(0, 0, 0, $month, 10)); 
                                echo $month_name;
                                ?>
                            </option>
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                            
                        </div>
                    </form>
                </div>
                <div class="col-md-2 pb-2">
                    <form id="filter_form">
                        <div class="filter_year">
                        <label><b class="bold-data">Select Year</b></label>
                            <select name="year" class="form-control" id="year" onchange="FilterData()">
                                <option value="<?= $year ?>" hidden selected>
                                    <?= $year; ?>
                                </option>
                                <?php
                                $currentYear = date('Y'); // Get the current year
                                for ($i = $currentYear; $i >= 2012; $i--) { // Loop from current year to 2012
                                    echo "<option value=\"$i\">$i</option>";
                                }
                                ?>
                            </select>
                            
                        </div>
                    </form>
                </div>
                        <div class="col-md-2">
                                    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'employeeTimesheetFilledReport']) ?>" class="btn btn-sm text-white" style="background-color: #3fd5db; margin-top: 22px;">Clear</a>
                        </div>
                        <div class="col-md-2">
                                    <button type="button" class="btn btn-sm text-white" style="background-color: #ee3434ff; margin-top: 22px;" data-toggle="modal" data-target="#notFilledModal">
                                        Not Filled Timesheet
                                    </button>
                        </div>
                        <!-- <div class="col-md-4 pb-2">
                            <p style="margin-top: 22px;">Today's Attendance : <b style="font-weight: 600;"><?= $totalemp ?></b></p>
                        </div> -->
                <div class="col-md-12">
                    <?= $this->Flash->render() ?>
                    <div class="content ">
                    
                    <table id="example1" style="width:100%" class="table table-default table-striped block table-bordered">
                            <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Manager Name</th>
                                    <th>Month Year</th>
                                    <th>Hours</th>
                                    <th>% Filled</th>
                                </tr>
                                
                            </thead>
                            <tbody>
                            <?php if (count($list) > 0) :
                                    foreach ($list as $data) : ?>
                            <tr class="active">
                                <td class="bold-data"><?= $data['name'] ?></td>
                                <td><?= $data['manager'] ?></td>
                                <td><?= $data['month_year'] ?></td>
                                <td><?= $data['hours'] ?></td>
                                <td><?= $data['filled'] ?></td>
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

<div class="modal fade" id="project_show" role="dialog">
    
  </div>

<div class="modal fade" id="notFilledModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    Timesheet Not Filled Users of – <?= h($month . ', ' . $year) ?>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <!-- Search box -->
                <input type="text"
                       id="searchNotFilled"
                       class="form-control mb-3"
                       placeholder="Search by employee or manager">

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="notFilledTable">
                        <thead class="thead-dark">
                            <tr>
                                 <th>Employee Name</th>
                                <th>Manager</th>
                                <th>Month</th>
                                <th>Hours</th>
                                <th>Filled</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($notfilledlist)) : ?>
                                <?php foreach ($notfilledlist as $row) : ?>
                                    <tr>
                                        <td><?= h($row['name']) ?></td>
                                        <td><?= h($row['manager'] ?? '-') ?></td>
                                        <td><?= h($row['month_year']) ?></td>
                                        <td><?= h($row['hours']) ?></td>
                                        <td>
                                            <span class="badge badge-danger">
                                                <?= h($row['filled']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        No users found
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
$(document).ready(function () {
    $('#searchNotFilled').on('keyup', function () {
        var value = $(this).val().toLowerCase();
        $('#notFilledTable tbody tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
</script>
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
    var manager= $("#manager").val();
    var employee = $("#employee").val();
    var month= $("#month").val();
    var year= $("#year").val();
    
    $.ajax({
        url:"<?= $this->Url->build(['controller'=>'Users', 'action'=>'employeeTimesheetFilledReport']) ?>/"+manager+"/"+employee+"/"+month+"/"+year,
		method:"get",
        success : function(resp){
            window.location.href = "<?= WEBURL ?>timesheet_filled_report/"+manager+"/"+employee+"/"+month+"/"+year;		 	
        }
    });
}
</script>