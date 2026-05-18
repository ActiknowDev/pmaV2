<?php $session = new \Cake\Http\Session();
$userSession = $session->read('data');

?>
<style>
    .bold-data {
        font-weight: 700;
    }
</style>
<style>
  .loader-container {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.7);
    z-index: 9999;
    text-align: center;
  }

  .loader {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 2s linear infinite;
    position: absolute;
    top: 50%;
    left: 50%;
    margin-top: -25px;
    margin-left: -25px;
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  tbody tr.odd:hover{
    background-color :#eae7f7;
  }
  tbody tr.even:hover{
    background-color :#eae7f7;
  }
  .emp-name {
    z-index: 0;
    width: 150px;
    min-width: fit-content;
    left: 0px;
    position: -webkit-sticky;
    position: sticky;
    background-color: white;
  }

</style>
<section class="page page-dashboard">
<div id="loader" class="loader-container">
  <div class="loader"></div>
  Loading...
</div>
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-building"></i></span>Attendance Report
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
            <div class="col-md-3 pb-2">
                    <form id="filter_form">
                        <div class="filter_month">
                            <!-- <label><strong>Filter By Month</strong></label> -->
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
                <div class="col-md-3 pb-2">
                    <form id="filter_form">
                        <div class="filter_year">
                            <!-- <label><strong>Filter By Month</strong></label> -->
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
                <div class="col-md-1 pb-2">
                            <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'attendancePunchTimeReport']) ?>" class="btn btn-sm text-white" style="background-color: #3fd5db; margin-top: 2px;">Clear</a>
                </div>
                        
                <div class="col-md-12">
                    <?= $this->Flash->render() ?>
                    <div class="content">
                        <?php
                        $headers = ['Emp Name'];
                        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                        for ($day = 1; $day <= $daysInMonth; $day++) { // Assuming a month with 30 days
                            $headers[] = sprintf('%02d-%02d-%04d', $day, $month, $year); // Format the date as needed (day-month-year)
                        }
                        // $headers[] = 'Status';
                        ?>

                        <table id="datatable" style="width:100%; height: 45vh;" class="table table-default table-striped block table-bordered table-responsive">
                        <thead>
                            <tr style="position: sticky; top: 0; z-index: 15;">
                                <?php foreach ($headers as $header) : ?>
                                    <th><?= $header ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>

                            <?php foreach ($users_list as $key => $row) : ?>
                                <?php 
                                $employeeClass = strtolower(str_replace(' ', '-', $row['name']));
                                echo "<style>tbody > .$employeeClass ~ .$employeeClass { display: none; }</style>";
                                ?>
                                <tr class="<?= $employeeClass ?>">
                                    <td class="emp-name"><?= $row['name'] ?></td>
                                    <?php for ($day = 1; $day <= $daysInMonth; $day++) : ?>
                                        <td>
                                            <?php
                                            $currentDate = sprintf('%02d-%02d-%04d', $day, $month, $year); // Format the current date
                                            // dd('current - '.$currentDate);
                                            $found = false;

                                            $leaveData = [];
                                                foreach ($leaves as $leave) {
                                                    // $leave_from=date('d-m-Y',strtotime($leave['from_date']));
                                                    // $leave_to=date('d-m-Y',strtotime($leave['to_date']));
                                                    // if ($currentDate >= $leave_from && $currentDate <= $leave_to && $leave['created_by'] == $row['id']) {
                                                    //     $leaveData[] = $leave;
                                                    // }

                                                    // Convert dates to DateTime objects
                                                    $leave_from = new DateTime($leave['from_date']);
                                                    $leave_to = new DateTime($leave['to_date']);
                                                    $currentDateObj = new DateTime($currentDate);

                                                    // Check if the current date falls within the leave range
                                                    if ($currentDateObj >= $leave_from && $currentDateObj <= $leave_to && $leave['created_by'] == $row['id']) {
                                                        $leaveData[] = $leave;
                                                    }
                                                    
                                                }

                                            foreach ($list as $emp) {
                                                $date=date('d-m-Y',strtotime($emp['DATE']));
                                                // dd('date - '. $date);
                                                if ($date == $currentDate && $row['id'] == $emp['emp_id']) {
                                                    $inTime = $emp['intime'];
                                                    $outTime = $emp['outtime'];

                                                    $inDisplay = $inTime;
                                                    $outDisplay = $outTime;
                                                    if ($inTime && strtotime($inTime) > strtotime("10:00:00")) {
                                                        $inDisplay = "<span style='color:red; font-weight:500;'>" . h($inTime) . "</span>";
                                                    }

                                                    // Highlight early out-time
                                                    if ($outTime && strtotime($outTime) < strtotime("18:45:00")) {
                                                        $outDisplay = "<span style='color:red; font-weight:500;'>" . h($outTime) . "</span>";
                                                    }
                                                    if (count($leaveData) > 0) {
                                                        foreach ($leaveData as $leave) {
                                                            if ($leave['leave_type'] === 'Forgot Card') {
                                                                $leaveType = "<span style='color:blue; font-weight:500;'>" . h($leave['leave_type']) . "</span>";
                                                            }else if ($leave['leave_type'] === 'Half Day' && $leave['wfh_flag'] !=''  && ($leave['wfh_flag'] == 1 || $leave['wfh_flag'] == 0)){
                                                                $leaveType = h($leave['leave_type']) . " (WFH)";
                                                            } else {
                                                                $leaveType = h($leave['leave_type']);
                                                            }
                                                            // echo h($leave['leave_type']);
                                                            // echo "&nbsp;&nbsp; In: " . h($emp['intime']) . "<br/>&nbsp;&nbsp; Out: " . h($emp['outtime'])."<br/> (".h($leave['leave_type']).") - ".h($leave['reason'])."";
                                                            echo "&nbsp;&nbsp; In: $inDisplay <br/>&nbsp;&nbsp; Out: $outDisplay <br/> ($leaveType) - " . h($leave['reason']);
                                                        }
                                                    } else {
                                                        echo "&nbsp;&nbsp; In: $inDisplay <br/>&nbsp;&nbsp; Out: $outDisplay";
                                                        // echo "&nbsp;&nbsp; In: " . h($emp['intime']) . "<br/>&nbsp;&nbsp; Out: " . h($emp['outtime']); 
                                                    }
                                                    $found = true;
                                                    break; // Stop searching once found
                                                }
                                            }

                                            if (!$found) {
                                                // Check for leaves
                                                // $leaveData = [];
                                                // foreach ($leaves as $leave) {
                                                //     $leave_from=date('d-m-Y',strtotime($leave['from_date']));
                                                //     $leave_to=date('d-m-Y',strtotime($leave['to_date']));
                                                //     if ($currentDate >= $leave_from && $currentDate <= $leave_to && $leave['created_by'] == $row['id']) {
                                                //         $leaveData[] = $leave;
                                                //     }
                                                    
                                                // }
                                                if (count($leaveData) > 0) {
                                                    foreach ($leaveData as $leave) {
                                                         if ($leave['leave_type'] === 'Forgot Card') {
                                                            echo "<span style='color: blue; font-weight: 500;'>" . h($leave['leave_type']) . "</span>";
                                                        }else if($leave['leave_type'] === 'Half Day' && $leave['wfh_flag'] !='' && ($leave['wfh_flag'] == 1 || $leave['wfh_flag'] == 0)){
                                                        echo h($leave['leave_type']) . " (WFH)</span>";
                                                        } else {
                                                            echo h($leave['leave_type']);
                                                        }
                                                        // echo h($leave['leave_type']);
                                                    }
                                                } elseif (date('N', strtotime($currentDate)) == 6 || date('N', strtotime($currentDate)) == 7) {
                                                    // Check for weekend (Saturday or Sunday)
                                                    echo "Weekend";
                                                } else {
                                                    echo "<span style='color: red; font-weight: 500;'>Absent</span>";
                                                    // echo "Absent";
                                                }
                                            }
                                            ?>
                                        </td>
                                    <?php endfor; ?>
                                </tr>
                            <?php endforeach; ?>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- <script>
    $("input[type=text]").on("focus", function() {
        if ($(this).val() == 0)
            $(this).val('');
    });
</script> -->

<script>
//    $(document).ready(function() {
//     $('#example1').dataTable({
//         /* Disable initial sort */
//         "aaSorting": [],
//         "lengthMenu": [[100, "All", 50, 25], [100, "All", 50, 25]],
//         // stateSave: true
//     });

   
// });


function FilterData() {
    $("#loader").show();
    var month= $("#month").val();
    var year= $("#year").val();
    $.ajax({
        
            url:"<?= $this->Url->build(['controller'=>'Users', 'action'=>'exportPunchTimeReport']) ?>/"+month+"/"+year,
			method:"get",
			
         success : function(resp){
            window.location.href = "<?= WEBURL ?>export_punch_time_report/"+month+"/"+year;	
            $("#loader").hide();	 	
            },
       });
}
</script>