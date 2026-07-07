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
                        <span class="icon"><i class="fa fa-building"></i></span>Timesheet and Allocation Report
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
                            <select name="month" class="form-control" id="month" onchange="FilterByMonth()">
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
                            <select name="year" class="form-control" id="year" onchange="FilterByMonth()">
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
                <div class="col-md-12">
                    <?= $this->Flash->render() ?>
                    <div class="content ">
                    
                    <table id="example1" style="width:100%" class="table table-default table-striped block table-bordered">
                            <thead>
                                <tr>
                                <th rowspan="1" colspan="1"></th>
                                <th style="text-align:center;" colspan="2">Billable</th>
                                <th style="text-align:center;" colspan="2">Non Billable</th>
                                </tr>
                                <tr>
                                    <th style="width:250px">user Name</th>
                                    <!-- <th>Project Name</th> -->
                                    <th title="Week Total | Assigned Hours ">Allocated Hours</th>
                                    <th title="Week Total | Work Hours ">Timesheet Hours</th>
                                    <th title="Week Total | Assigned Hours ">Allocated Hours</th>
                                    <th title="Week Total | Work Hours ">Timesheet Hours</th>
                                </tr>
                                <input type="hidden" id="url" value="<?= WEBURL; ?>">
                            </thead>
                            <tbody>
                                <?php if (count($projects) > 0) :
                                    foreach ($projects as $p) : ?>
                            <!-- <tbody> -->
                                <tr class="active">
                                    <td style="text-align: left;">
                                            <a href="javascript:void(0)" onclick="timesheetReportData(<?= $p['userid'] ?>,<?= $month ?>,<?= $year ?>)" style="color:#1391bb;"> <p><strong><?= $p['username'] ?>
                                            </strong></p></a>

                                    </td>
                                    <?php 
                                    $time_slot=0;
                                    foreach ($p['time_slot'] as $pa) : ?>
                                                    
                                                    <?php 
                                                    $time_slot=$time_slot+(int)$pa['time_slot'];
                                                    ?>
                                        <?php endforeach; ?>
                                        <td style="text-align: left;">
                                        <p class="bold-data"> <strong> <?php if ($time_slot > 0) : ?> <?= round($time_slot); ?> <?php else : ?> <?= "-" ?> <?php endif; ?><strong></p>
                                        <!-- <input type="text" class="form-control aloc-input" placeholder="hrs" disabled <?php if ($alt > 0) : ?> <?= "value=" . $alt; ?> <?php else : ?> <?= "value=-" ?> <?php endif; ?> > -->
                                        </td>
                                        <td style="text-align: left;">
                                        <p class="bold-data"> <strong> <?php if ($p['billable_time_used'] > 0) : ?> <?= round($p['billable_time_used'],2); ?> <?php else : ?> <?= "-" ?> <?php endif; ?><strong></p>
                                        
                                        </td>
                                <!-- Non billable data -->
                                    <td style="text-align: left;">
                                  <p class="bold-data"> <strong> <?php if ($p['non_billable_time_slot'] > 0) : ?> <?= round($p['non_billable_time_slot']); ?> <?php else : ?> <?= "-" ?> <?php endif; ?><strong></p>
                                    <!-- <input type="text" class="form-control aloc-input" placeholder="hrs" disabled <?php if ($alt > 0) : ?> <?= "value=" . $alt; ?> <?php else : ?> <?= "value=-" ?> <?php endif; ?> > -->
                                    </td>
                                    <td style="text-align: left;">
                                    <p class="bold-data"> <strong> <?php if ($p['non_billable_time_used'] > 0) : ?> <?= round($p['non_billable_time_used'],2); ?> <?php else : ?> <?= "-" ?> <?php endif; ?><strong></p>
                                   </td>
                             
                                </tr>

                            <!-- </tbody> -->
                            
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

function timesheetReportData(id,month,year){
		// alert(id);
		$.ajax({
			// url:"<?= WEBURL ?>timesheet_report_data/"+id,
            url:"<?= $this->Url->build(['controller'=>'Users', 'action'=>'timesheetReportData']) ?>/"+id+"/"+month+"/"+year,
			method:"get",
			// 	data:{
			// 	client_id: clientid,
			// 	type: type,
			// },
			// headers: {
			// 	'X-CSRF-Token': $('[name="_csrfToken"]').val()
			// },
         success : function(resp){
             $('#project_show').html(resp);
             $('#project_show').modal('show');
			 	
            }
       });
}

function FilterByMonth() {
    var month= $("#month").val();
    var year= $("#year").val();
    // alert( month );
    // console.log(month);
    $.ajax({
        
            url:"<?= $this->Url->build(['controller'=>'Users', 'action'=>'timesheetReport']) ?>/"+month+"/"+year,
			method:"get",
			
         success : function(resp){
            // url:"<?= WEBURL ?>timesheet_report/"+month,
            //  $('#project_show').html(resp);
            //  $('#project_show').modal('show');
            // console.log(resp);
            // location.href();
            window.location.href = "<?= WEBURL ?>timesheet_report/"+month+"/"+year;		 	
            }
       });
}
</script>