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
                    <div class="content ">
                    
                    <table id="example1" style="width:100%" class="table table-default table-striped block table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Day</th>
                                    <!-- <th>Date</th> -->
                                    <th>In Time</th>
                                    <th>Out Time</th>
                                    <th>Total Time</th>
                                    <th>Late</th>
                                    <th>Status</th>
                                </tr>
                                
                            </thead>
                            <tbody>
                            <?php 
                                if (count($list) > 0) :
                                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year); 

                                    for ($d = 1; $d <= $daysInMonth; $d++) {
                                        $time = mktime(12, 0, 0, $month, $d, $year);
                                        $currentDate = date('Y-m-d', $time);
                                        $currentDayOfWeek = date('N', $time);
                                ?>
                                        <tr class="active">
                                            <td><?= $currentDate ?></td>
                                            <td><?= date('l', $time) ?></td>
                                            <?php 
                                            $found = false;
                                            foreach ($list as $emp) {
                                                if ($emp['date'] == $currentDate) {
                                                    $found = true;
                                            ?>
                                                   
                                                    <td><?= $emp['intime'] ?></td>
                                                    <td><?= $emp['outtime'] ?></td>
                                                    <td>
                                                        <?php 
                                                        if ($emp['total_time'] == '') {
                                                            echo '';
                                                        } else {
                                                            echo date('H:i:s', strtotime($emp['total_time']));
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                        $timeParts = explode('.', $emp['Late_by']);
                                                        $timeWithoutFraction = $timeParts[0];
                                                        echo $timeWithoutFraction;
                                                        ?>
                                                    </td>
                                                    <td><?= $emp['status'] ?></td>
                                            <?php 
                                                }
                                            }
                                            if (!$found) {
                                                $leaveData = [];
                                                foreach ($leaves as $leave) {
                                                    if ($currentDate >= $leave['from_date'] && $currentDate <= $leave['to_date']) {
                                                        $leaveData[] = $leave;
                                                    }
                                                }
                                                if (count($leaveData) > 0) {
                                                    foreach ($leaveData as $leave) {
                                            ?>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><?= $leave['leave_type'] ?></td>
                                            <?php 
                                                    }
                                                } elseif ($currentDayOfWeek == 6 || $currentDayOfWeek == 7) { ?>
                                                
                                                
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td style="color: #f10000;">Holiday</td>

                                                <?php }
                                                else {
                                            ?>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="color: #ff6600;">Absent</td>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </tr>
                                <?php 
                                    }
                                endif; 
                            ?>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
    var month= $("#month").val();
    var year= $("#year").val();
    $.ajax({
        
            url:"<?= $this->Url->build(['controller'=>'Users', 'action'=>'attendancePunchTimeReport']) ?>/"+month+"/"+year,
			method:"get",
			
         success : function(resp){
            window.location.href = "<?= WEBURL ?>attendance/"+month+"/"+year;		 	
            }
       });
}
</script>