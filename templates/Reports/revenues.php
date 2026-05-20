<style>
   .table-responsive {
      display: inline-table !important;
      width: 100%;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
   }

   .canvas-chart{
    width: 100%;
    height: 300px;
    position:relative
   }
   .canvas-chart canvas {
  width: 100% !important;
  height: auto !important;
}
   @media (max-width: 767px){
    .table-responsive {
      display: block !important;
   }
   .canvas-chart{
    height: 300px;
   }
   }
</style>
<section class="page page-dashboard">
   <!-- PAGE-TITLE -->
   <div class="page-title skin-light">
      <div class="container">
         <div class="row">
            <div class="col-6">
               <div class="heading ft-secondary">
                  Revenue</a>
               </div>
            </div>
            <!-- <div class="col-4 offset-md-2">
               <div class="actions-ctrl text-md-right">
                   <div class="adon-group">
                       <span class="icon"><i class="fa fa-search"></i></span>
                       <input type="text" class="form-control" placeholder="Search Project here...">
                   </div>
               </div>
               </div> -->
         </div>
      </div>
   </div>
   <!-- PAGE-CONTENT -->
   <div class="page-content">
      <div class="container">
         <div class="row">
            <div class="col-md-3">
               <div class="block primary">
                  <div class="content text-center">
                     <h4 class="title">Current Year Revenue</h4>
                     <span>$<?php echo number_format($totalRevenue); ?></span>
                  </div>
               </div>
               <div class="block primary">
                  <div class="content text-center">
                     <h4 class="title">YTD Revenue</h4>
                     <span>$<?php echo number_format($ytdRevenue); ?></span>
                  </div>
               </div>
               <div class="block primary">
                  <div class="content text-center">
                     <h4 class="title">Total Paid</h4>
                     <span>$<?php echo number_format($totalPaid); ?></span>
                  </div>
               </div>
               <div class="block primary">
                  <div class="content text-center">
                     <h4 class="title">Total Billed Unpaid</h4>
                     <span>$<?php echo number_format($totalBilledUnPaid); ?></span>
                  </div>
               </div>
            </div>
            <div class="col-md-9">
               <?= $this->Form->create(null, array('id' => 'revenue')) ?>
               <div class="row">

                  <div class="col-md-3">
                     <div class="form-group">
                        <label for="">BDE</label>
                        <select name="bde[]" class="form-control" multiple id="langOpt">

                           <?php $bData = array();
                           if ($bd_id != 0) $bData = explode(',', $bd_id);
                           foreach ($projectBd as $key => $bd) { ?>
                              <option value="<?php echo $bd['id']; ?>"
                               <?php if (in_array($bd['id'], $bData)) {
                                 echo "Selected";
                              } ?>><?php echo  $bd['name']; ?></option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label for="">Manager</label>
                        <select name="managers[]" class="form-control" multiple id="langOpt1">
                           <?php $mData = array();
                           if ($manager_id != 0) $mData = explode(',', $manager_id);
                           foreach ($projectManagers as $key => $projectManager) { ?>
                              <option value="<?php echo $projectManager['id'] ?>"
                               <?php if (in_array($projectManager['id'], $mData)) {
                                 echo "Selected";
                              } ?>><?php echo $projectManager['name']; ?></option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label for="">Month</label>
                        <select name="months[]" class="form-control" multiple id="langOpt2">

                           <?php $mthData = array();
                           if ($month_id != 0) $mthData = explode(',', $month_id);
                           for ($m = 1; $m <= 12; $m++) {
                              $date = date("Y-{$m}-25");
                              $d = date('M', strtotime($date));
                              echo '  <option value="' . $m . '"';
                              if (in_array($m, $mthData)) echo "Selected";
                              echo '>' . $d  . '</option>' . PHP_EOL;
                           }
                           ?>

                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label for="">Type</label>
                        <select name="source" class="form-control">
                           <option value="">Select Type</option>
                           <option value="Regular" <?= ($source == 'Regular') ? 'selected' : ""; ?>>Regular</option>
                           <option value="External" <?= ($source == 'External') ? 'selected' : ""; ?>>External</option>
                        </select>
                     </div>
                  </div>

               </div>

               <div class="row">

                  <div class="col-md-2">
                     <div class="form-group">
                        <label for="">From Date</label>
                        <input type="text" name="from_date" id="from_date" class="form-control datepicker" value="<?= date('m/d/Y', strtotime($financial_year_from)); ?>">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <label for="">To Date</label>
                        <input type="text" name="to_date" id="to_date" class="form-control datepicker" value="<?= date('m/d/Y', strtotime($financial_year_to)); ?>">
                     </div>
                  </div>
                  <div class="col-md-2 align-self-end">
                     <div class="form-group">
                        <button type="submit" name="submit" class="v-btn v-btn-dark btn-block btn-md" id="savecompany">Search</button>
                     </div>
                  </div>

               </div>
               <?= $this->Form->end() ?>
               <hr class="dark">
               <div class="row">
                  <div class="col-md-12">
                     <h4 class="title fw-600 ft-dark mb-3">Month</h4>
                     <table class="table table-light task-table nowrap table-sm table-responsive" id="" style="width:100%">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>Month</th>
                              <th>Client</th>
                              <th>Total Revenue</th>
                              <th>Total Paid</th>
                              <th>Monthly Paid</th>
                              <th>Total Billed Unpaid</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php $i = 1;
                           foreach ($monthArray as $key => $month) { ?>
                        <tbody>
                           <tr class="active">
                              <td><?= $i; ?></td>
                              <td>
                                 <label class="labels collaps-icon" for="milestoneOne_<?= $month['month_name']; ?>"><i class="fa fa-chevron-up"></i></label>
                                 <input type="checkbox" name="milestoneOne" id="milestoneOne_<?= $month['month_name']; ?>" data-toggle="toggle">
                                 <?= $month['month_name']; ?>


                              </td>
                              <td></td>
                              <td>$<?php echo  number_format($month['revenue']); ?></td>
                              <td>$<?php echo  number_format($month['paid']); ?></td>
                              <td>$<?php echo  number_format($month['monthlypaid']); ?></td>
                              <td>$<?php echo  number_format($month['unpaid']); ?></td>
                           </tr>
                        </tbody>
                        <tbody class="hide" style="display: none;">
                           <?php if (count($month['projects']) > 0):
                                 foreach ($month['projects'] as $bp): ?>
                                 <tr>
                                    <td></td>

                                    <td style="padding-left: 1.7rem;"><?= $this->Html->link(substr($bp['project_name'], 0, 20), '/edit-project/' . $bp['project_id'], ['class' => 'link']); ?></td>
                                    <td><?= $bp['client_name'] ?></td>
                                    <td>$<?php echo  number_format($bp['revenue']); ?></td>
                                    <td>$<?php echo  number_format($bp['paid']); ?></td>
                                    <td>$<?php echo  number_format($bp['monthlypaid']); ?></td>
                                    <td>$<?php echo  number_format($bp['unpaid']); ?></td>
                                 </tr>
                           <?php endforeach;
                              endif; ?>
                        <?php $i++;
                           } ?>
                        </tbody>
                     </table>

                     <!-- Client wise data table start -->

                     <h4 class="title fw-600 ft-dark mb-3 pt-3">Client</h4>

                     <table class="table table-light task-table nowrap table-sm table-responsive" style="width:100%">
                        <thead>
                           <tr>
                                 <th>#</th>
                                 <th>Client / Project</th>
                                 <th>Month</th>
                                 <th>Total Revenue</th>
                                 <th>Total Paid</th>
                                 <th>Monthly Paid</th>
                                 <th>Total Billed Unpaid</th>
                           </tr>
                        </thead>

                        <tbody>
                           <?php $i = 1;
                           foreach ($clientWise as $client) {

                                 // ✅ client totals
                                 $totalRevenue = $totalPaid = $monthlyPaid = $totalUnpaid = 0;

                                 foreach ($client['projects'] as $proj) {
                                    foreach ($proj['months'] as $m) {
                                       $totalRevenue += $m['revenue'];
                                       $totalPaid += $m['paid'];
                                       $monthlyPaid += $m['monthlypaid'];
                                       $totalUnpaid += $m['unpaid'];
                                    }
                                 }
                           ?>

                                 <!-- ✅ CLIENT ROW -->
                                 <tr class="active">
                                    <td><?= $i; ?></td>
                                    <td>
                                       <label class="labels collaps-icon" for="client_<?= $i; ?>">
                                             <i class="fa fa-chevron-up"></i>
                                       </label>
                                       <input type="checkbox" id="client_<?= $i; ?>" data-toggle="toggle">

                                       <strong><?= $client['client_name']; ?></strong>
                                    </td>
                                    <td></td>
                                    <td>$<?= number_format($totalRevenue); ?></td>
                                    <td>$<?= number_format($totalPaid); ?></td>
                                    <td>$<?= number_format($monthlyPaid); ?></td>
                                    <td>$<?= number_format($totalUnpaid); ?></td>
                                 </tr>

                                 <!-- ✅ CHILD ROWS -->
                                 <tbody class="hide" style="display: none;">
                                    <?php foreach ($client['projects'] as $proj): ?>

                                       <?php $first = true; ?>

                                       <?php foreach ($proj['months'] as $m): ?>
                                             <tr>
                                                <td></td>

                                                <!-- ✅ Project name only once -->
                                                <td style="padding-left: 1.7rem;" title="<?= $proj['project_name']; ?>">
                                                   <?php if ($first): ?>
                                                         <?= $this->Html->link(
                                                            substr($proj['project_name'], 0, 20),
                                                            '/edit-project/' . $proj['project_id'],
                                                            ['class' => 'link']
                                                         ); ?>
                                                   <?php endif; ?>
                                                </td>

                                                <td><?= $m['month']; ?></td>
                                                <td>$<?= number_format($m['revenue']); ?></td>
                                                <td>$<?= number_format($m['paid']); ?></td>
                                                <td>$<?= number_format($m['monthlypaid']); ?></td>
                                                <td>$<?= number_format($m['unpaid']); ?></td>
                                             </tr>

                                             <?php $first = false; ?>
                                       <?php endforeach; ?>

                                    <?php endforeach; ?>
                                 </tbody>

                           <?php $i++; } ?>
                        </tbody>
                     </table>

                     <!-- client wise data table end  -->
                     <!-----BDE Table---->
                     <h4 class="title fw-600 ft-dark my-3">BDE</h4>
                     <!-- <table class="table table-light task-table nowrap table-sm table-responsive" id="" style="width:100%">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>BDE</th>
                              <th>Client</th>
                              <th>Total Revenue</th>
                              <th>Total Paid</th>
                              <th>Total Billed Unpaid</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php $i = 1;
                           foreach ($projectBdData as $key => $bd) { ?>
                        <tbody>
                           <tr class="active">
                              <td><?= $i; ?></td>
                              <td>
                                 <label class="labels collaps-icon" for="milestoneOne_<?= $bd['bd_id']; ?>"><i class="fa fa-chevron-up"></i></label>
                                 <input type="checkbox" name="milestoneOne" id="milestoneOne_<?= $bd['bd_id']; ?>" data-toggle="toggle">
                                 <?= $bd['bd_name']; ?>
                              </td>
                              <td></td>
                              <td>$<?php echo number_format($bd['revenue']); ?></td>
                              <td>$<?php echo number_format($bd['paid']); ?></td>
                              <td>$<?php echo number_format($bd['unpaid']); ?></td>
                           </tr>
                        </tbody>
                        <tbody class="hide" style="display: none;">
                           <?php if (count($bd['projects']) > 0):
                                 foreach ($bd['projects'] as $bp): ?>
                                 <tr>
                                    <td></td>

                                    <td style="padding-left: 1.7rem;"><?= substr($bp['project_name'], 0, 20); ?></td>

                                    <td><?= $bp['client_name'] ?></td>
                                    <td>$<?php echo number_format($bp['revenue']); ?></td>
                                    <td>$<?php echo number_format($bp['paid']); ?></td>
                                    <td>$<?php echo number_format($bp['unpaid']); ?></td>
                                 </tr>
                           <?php endforeach;
                              endif; ?>
                        <?php $i++;
                           } ?>
                        </tbody>
                     </table> -->
                     <table class="table table-light task-table nowrap table-sm table-responsive" style="width:100%">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>BDE</th>
                              <th>Client</th>
                              <th>Total Revenue</th>
                              <th>Total Paid</th>
                              <th>Total Billed Unpaid</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php 
                           $i = 1;
                           foreach ($projectBdData as $key => $bd) { 
                              // Skip BDEs with 0 revenue
                              if ($bd['revenue'] == 0) continue;
                           ?>
                           <tbody>
                              <tr class="active">
                                 <td><?= $i; ?></td>
                                 <td>
                                    <label class="labels collaps-icon" for="milestoneOne_<?= $bd['bd_id']; ?>"><i class="fa fa-chevron-up"></i></label>
                                    <input type="checkbox" name="milestoneOne" id="milestoneOne_<?= $bd['bd_id']; ?>" data-toggle="toggle">
                                    <?= $bd['bd_name']; ?>
                                 </td>
                                 <td></td>
                                 <td>$<?= number_format($bd['revenue']); ?></td>
                                 <td>$<?= number_format($bd['paid']); ?></td>
                                 <td>$<?= number_format($bd['unpaid']); ?></td>
                              </tr>
                           </tbody>

                           <tbody class="hide" style="display: none;">
                              <?php 
                              if (!empty($bd['projects'])):
                                 foreach ($bd['projects'] as $bp): 
                                    // Skip projects with 0 revenue
                                    if ($bp['revenue'] == 0) continue;
                              ?>
                                 <tr>
                                    <td></td>
                                    <td style="padding-left: 1.7rem;"><?= substr($bp['project_name'], 0, 20); ?></td>
                                    <td><?= $bp['client_name'] ?></td>
                                    <td>$<?= number_format($bp['revenue']); ?></td>
                                    <td>$<?= number_format($bp['paid']); ?></td>
                                    <td>$<?= number_format($bp['unpaid']); ?></td>
                                 </tr>
                              <?php 
                                 endforeach; 
                              endif; 
                              ?>
                           </tbody>
                           <?php 
                              $i++;
                           } 
                           ?>
                        </tbody>
                     </table>

                     <!---Project manager table---->
                     <h4 class="title fw-600 ft-dark my-3">Project Manager</h4>
                     <!-- <table class="table table-light task-table nowrap table-sm table-responsive" id="" style="width:100%">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>Project Manager</th>
                              <th>Client</th>
                              <th>Total Revenue</th>
                              <th>Total Paid</th>
                              <th>Total Billed Unpaid</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php $i = 1;
                           foreach ($projetManagersData as $key => $projectManager) { ?>
                           <?php
                           if ($projectManager['status'] == 0 && $projectManager['revenue'] <= 0) {
                              continue;
                           }
                           ?>
                        <tbody>
                           <tr class="active">
                              <td><?= $i; ?></td>
                              <td>
                                 <label class="labels collaps-icon" for="milestoneOne_<?= $i; ?>"><i class="fa fa-chevron-up"></i></label>
                                 <input type="checkbox" name="milestoneOne" id="milestoneOne_<?= $i; ?>" data-toggle="toggle">
                                 <?= $projectManager['manager_name']; ?>
                              </td>

                              <td></td>
                              <td>$<?php echo number_format($projectManager['revenue']); ?></td>
                              <td>$<?php echo number_format($projectManager['paid']); ?></td>
                              <td>$<?php echo number_format($projectManager['unpaid']); ?></td>
                           </tr>
                        </tbody>
                        <tbody class="hide" style="display: none;">
                           <?php if (count($projectManager['projects']) > 0):
                                 foreach ($projectManager['projects'] as $bp): ?>
                                 <tr>
                                    <td></td>

                                    <td style="padding-left: 1.7rem;"><?= substr($bp['project_name'], 0, 20); ?></td>

                                    <td><?= $bp['client_name'] ?></td>

                                    <td>$<?php echo number_format($bp['revenue']); ?></td>
                                    <td>$<?php echo number_format($bp['paid']); ?></td>
                                    <td>$<?php echo number_format($bp['unpaid']); ?></td>
                                 </tr>
                           <?php endforeach;
                              endif; ?>

                           <?php $i++; ?>
                        <?php } ?>
                        </tbody>
                     </table> -->
                     <table class="table table-light task-table nowrap table-sm table-responsive" style="width:100%">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>Project Manager</th>
                              <th>Client</th>
                              <th>Total Revenue</th>
                              <th>Total Paid</th>
                              <th>Total Billed Unpaid</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php 
                           $i = 1;
                           foreach ($projetManagersData as $key => $projectManager) {

                              // Skip Project Manager if inactive and has 0 or less revenue
                              if ($projectManager['status'] == 0 && $projectManager['revenue'] <= 0) {
                                 continue;
                              }

                              // Skip if total revenue is 0 or less (even if status is active)
                              if ($projectManager['revenue'] <= 0) {
                                 continue;
                              }
                           ?>
                           <tbody>
                              <tr class="active">
                                 <td><?= $i; ?></td>
                                 <td>
                                    <label class="labels collaps-icon" for="milestoneOne_<?= $i; ?>">
                                       <i class="fa fa-chevron-up"></i>
                                    </label>
                                    <input type="checkbox" name="milestoneOne" id="milestoneOne_<?= $i; ?>" data-toggle="toggle">
                                    <?= htmlspecialchars($projectManager['manager_name']); ?>
                                 </td>
                                 <td></td>
                                 <td>$<?= number_format($projectManager['revenue']); ?></td>
                                 <td>$<?= number_format($projectManager['paid']); ?></td>
                                 <td>$<?= number_format($projectManager['unpaid']); ?></td>
                              </tr>
                           </tbody>

                           <tbody class="hide" style="display: none;">
                              <?php 
                              if (!empty($projectManager['projects'])):
                                 foreach ($projectManager['projects'] as $bp):
                                    // Skip project if revenue is 0 or less
                                    if ($bp['revenue'] <= 0) continue;
                              ?>
                                 <tr>
                                    <td></td>
                                    <td style="padding-left: 1.7rem;">
                                       <?= htmlspecialchars(substr($bp['project_name'], 0, 20)); ?>
                                    </td>
                                    <td><?= htmlspecialchars($bp['client_name']); ?></td>
                                    <td>$<?= number_format($bp['revenue']); ?></td>
                                    <td>$<?= number_format($bp['paid']); ?></td>
                                    <td>$<?= number_format($bp['unpaid']); ?></td>
                                 </tr>
                              <?php 
                                 endforeach;
                              endif; 
                              ?>
                           </tbody>

                           <?php 
                              $i++;
                           } 
                           ?>
                        </tbody>
                     </table>


                     <h4 class="title fw-600 ft-dark my-3">Revenue Chart</h4>

                     <div class="row">
                        <div class="col-md-3">
                           <div class="mb-3 form-group">
                              <label for="revenueViewType">View Type:</label>
                              <select id="revenueViewType" class="form-control" style="max-width: 250px;">
                                 <option value="monthly" selected>Monthly Revenue</option>
                                 <option value="cumulative">Cumulative Revenue</option>
                              </select>
                           </div>
                        </div>

                        <div class="col-md-3">
                           <div class="form-group mb-3">
                              <label for="financialYear">Select Financial Year:</label>
                              <select name="financialYear" id="financialYear" class="form-control" style="max-width: 250px;">
                                 <?php
                                 $startYear = 2021; // or whatever base year you want
                                 $endYear = date('Y') + 1; // show future year too if needed
                                 for ($y = $startYear; $y <= $endYear; $y++):
                                 ?>
                                    <option value="<?= $y ?>–<?= $y + 1 ?>" <?= ($y == $selectedYear) ? 'selected' : '' ?>>
                                       <?= $y ?>–<?= $y + 1 ?>
                                    </option>
                                 <?php endfor; ?>
                              </select>
                           </div>
                        </div>

                        <div class="col-md-6">
                        </div>

                     </div>
                     <div class="canvas-chart">
                        <canvas id="revenueComparisonChart"></canvas>
                     </div>


                     <div class="my-3" style="margin-top: 60px;">
                        <div class="canvas-chart">
                            <canvas id="percentageChangeRevenueChart"></canvas>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="http://localhost/cakeproject/js/jquery-ui.js"></script>
<script type="text/javascript">
   $('#bde').on('change', function() {
      var bd_id = $(this).val();
      var manager_id = $('#managers').val();
      var month = $('#months').val();
      var val = bd_id + '-' + manager_id + '-' + month;
      var target_url = "<?= $this->Url->build('/reports/revenues/bd/') ?>" + val;
      if (bd_id != '') {
         window.location.href = target_url;
      } else {
         window.location.href = "<?= $this->Url->build('/reports/revenues/') ?>"
      }
   });

   $('#managers').on('change', function() {
      var manager_id = $(this).val();
      var month = $('#months').val();
      var bd_id = $('#bde').val();
      var val = manager_id + '-' + bd_id + '-' + month;
      var target_url = "<?= $this->Url->build('/reports/revenues/manager/') ?>" + val;
      if (manager_id != '') {
         window.location.href = target_url;
      } else {
         window.location.href = "<?= $this->Url->build('/reports/revenues/') ?>"
      }
   });

   $('#months').on('change', function() {
      var month = $(this).val();
      var manager_id = $('#managers').val();
      var bd_id = $('#bde').val();
      var val = month + '-' + bd_id + '-' + manager_id;
      var target_url = "<?= $this->Url->build('/reports/revenues/month/') ?>" + val;
      if (month != '') {
         window.location.href = target_url;
      } else {
         window.location.href = "<?= $this->Url->build('/reports/revenues/') ?>"
      }
   });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
   document.addEventListener("DOMContentLoaded", function() {
      let monthlyCurrent = <?= json_encode($currentRevenueList) ?>;
      let monthlyPrevious = <?= json_encode($previousRevenueList) ?>;
      let cumulativeCurrent = <?= json_encode($cumulativeCurrent) ?>;
      let cumulativePrevious = <?= json_encode($cumulativePrevious) ?>;
      let growthPercentages = <?= json_encode($growthPercentages) ?>;
      let cummulativeGrowthPercentages = <?= json_encode($cummulativeGrowthPercentages ?? []) ?>;
      let currentChartYear = <?= $toYear ?>;
      let previousChartYear = <?= $fromYear ?>;
      const labels = <?= json_encode($labels) ?>;

      const ctx1 = document.getElementById('revenueComparisonChart').getContext('2d');
      const ctx2 = document.getElementById('percentageChangeRevenueChart').getContext('2d');

      Chart.register(ChartDataLabels); // Register the datalabels plugin


      // === Revenue Chart ===
      let chart = new Chart(ctx1, {
         type: 'bar',
         data: {
            labels: labels,
            datasets: [{
               label: currentChartYear + ' Year Revenue',
               data: monthlyCurrent,
               backgroundColor: 'rgba(54, 162, 235, 0.5)',
               borderColor: 'rgba(54, 162, 235, 1)',
               borderWidth: 1
            }, {
               label: previousChartYear + ' Year Revenue',
               data: monthlyPrevious,
               backgroundColor: 'rgba(255, 99, 132, 0.5)',
               borderColor: 'rgba(255, 99, 132, 1)',
               borderWidth: 1
            }]
         },
         options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
               datalabels: {
                  display: false
               },
               tooltip: {
                  mode: 'index',
                  intersect: false,
                  callbacks: {
                     label: function(context) {
                        let year = context.datasetIndex === 0 ? currentChartYear : previousChartYear;
                        if (context.dataIndex >= 9) year++;
                        let labelSuffix = context.dataset.label.substring(4);
                        let value = context.formattedValue || 0;
                        return `${year}${labelSuffix}: ${value}`;
                     }
                  }
               },
                legend: {
                    labels: {
                        font: {
                            size: ctx => ctx.chart.width < 500 ? 10 : 12
                        }
                    }
                },
                title: {
                    display: true,
                    font: {
                        size: ctx => ctx.chart.width < 500 ? 12 : 16,
                        weight: 'bold'
                    }
                },
                  clamp: true,
                  clip: true
            },
            scales: {
               y: {
                  beginAtZero: true,
                  title: {
                     display: true,
                     text: 'Revenue Amount ($)',
                     font: {
                        size: ctx => ctx.chart.width < 500 ? 10 : 12
                    }
                  },
                  ticks: {
                    font: {
                        size: ctx => ctx.chart.width < 500 ? 9 : 11
                    }
                },
                  grid: {
                     display: false
                  }
               },
               x: {
                ticks: {
                    font: {
                        size: ctx => ctx.chart.width < 500 ? 9 : 11
                    }
                },
                  grid: {
                     display: false
                  }
               }
            }
         }
      });

      let growthChart = new Chart(ctx2, {
         type: 'bar',
         data: {
            labels: labels,
            datasets: [{
               label: 'Growth Percentage',
               data: growthPercentages,
               backgroundColor: growthPercentages.map(value => value < 0 ? 'rgba(218, 57, 57, 1)' : 'rgba(50, 199, 45, 0.5)'),
               borderColor: growthPercentages.map(value => value < 0 ? 'rgba(218, 57, 57, 1)' : 'rgba(50, 199, 45, 0.5)'),
               borderWidth: 1
            }]
         },
         options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
               padding: {
                  top: 30,
                  bottom: 40
               }
            },
            plugins: {
               title: {
                  display: true,
                  text: 'Growth Percentage',
                  align: 'center',
                  color: '#000',
                  font: {
                     size: ctx => ctx.chart.width < 500 ? 12 : 16,
                     weight: 'bold'
                  },
                  padding: {
                     bottom: 50
                  }
               },
               legend: {
                  display: false
               },
               tooltip: {
                  callbacks: {
                     label: function (context) {
                        return context.raw + '% Growth';
                     }
                  }
               },
               datalabels: {
                  color: '#000',
                  anchor: 'end',
                  align: 'top',
                  offset: 1,
                //   rotation: -45,
                rotation: function(context) {
                    return context.chart.width < 600 ? -90 : 0;
                },
                  formatter: function (value) {
                     return value + '%';
                  },
                //   font: {
                //     size: 10,
                //     weight: 'bold'
                //   },
                font: function(context) {
    // chart width available here
    let chartWidth = context.chart.width;

    // calculate size based on width
    if (chartWidth < 400) return { size: 6, weight: '600' };   // small screens
    if (chartWidth < 800) return { size: 8, weight: 'bold' };  // tablets
    return { size: 12, weight: 'bold' };                        // desktop
  },
                  clamp: false,
                  clip: false
               }
            },
            scales: {
               y: {
                  beginAtZero: true,
                  title: {
                     display: true,
                     text: 'Growth (%)',
                     font: {
                        size: ctx => ctx.chart.width < 500 ? 8 : 12
                    }
                  },
                  ticks: {
                     callback: value => value + '%',
                     font: {
                        size: ctx => ctx.chart.width < 500 ? 7 : 11
                    }
                  },
                  grid: {
                     display: false
                  },
                  suggestedMin: -100,
            suggestedMax: 100,
            grace: '5%'
               },
               x: {
                ticks: {
                    font: {
                        size: ctx => ctx.chart.width < 500 ? 8 : 11
                    }
                },
                  grid: {
                     display: false
                  }
               }
            }
         },
         plugins: [ChartDataLabels]
      });


      // === Update both charts ===
      function updateCharts(viewType) {
         if (viewType === 'monthly') {
            chart.data.datasets[0].data = monthlyCurrent;
            chart.data.datasets[1].data = monthlyPrevious;
            chart.data.datasets[0].label = currentChartYear + ' Year Revenue';
            chart.data.datasets[1].label = previousChartYear + ' Year Revenue';
         } else {
            chart.data.datasets[0].data = cumulativeCurrent;
            chart.data.datasets[1].data = cumulativePrevious;
            chart.data.datasets[0].label = currentChartYear + ' Year Cumulative Revenue';
            chart.data.datasets[1].label = previousChartYear + ' Year Cumulative Revenue';
         }
         chart.update();

         if (viewType === 'monthly') {
            growthChart.data.labels = labels;
            growthChart.data.datasets[0].data = growthPercentages;
            growthChart.data.datasets[0].backgroundColor = growthPercentages.map(value => value < 0 ? 'rgba(221, 27, 27, 1)' : 'rgba(50, 199, 45, 0.5)');
            growthChart.data.datasets[0].borderColor = growthPercentages.map(value => value < 0 ? 'rgba(221, 27, 27, 1)' : 'rgba(50, 199, 45, 0.5)');
         } else {
            growthChart.data.datasets[0].data = cummulativeGrowthPercentages;
            growthChart.data.datasets[0].backgroundColor = cummulativeGrowthPercentages.map(value => value < 0 ? 'rgba(221, 27, 27, 1)' : 'rgba(50, 199, 45, 0.5)');
            growthChart.data.datasets[0].borderColor = cummulativeGrowthPercentages.map(value => value < 0 ? 'rgba(221, 27, 27, 1)' : 'rgba(50, 199, 45, 0.5)');
         }
         growthChart.update();
      }

      // === View Type Change ===
      document.getElementById('revenueViewType').addEventListener('change', function(e) {
         updateCharts(e.target.value);
      });

      // === Financial Year Change ===
      $('#financialYear').on('change', function() {
         var year = $(this).val();
         var selectElement = $(this);
         var bde_ids = $('select[name="bde[]"]').val();
         var manager_ids = $('select[name="managers[]"]').val();
         var source_type = $('select[name="source"]').val();
         var target_url = "<?= $this->Url->build('/reports/get-chart-data/') ?>" + year;

         selectElement.prop('disabled', true);

         $.ajax({
            url: target_url,
            type: 'GET',
            dataType: 'json',
            data: {
               bde: bde_ids,
               managers: manager_ids,
               source: source_type
            },
            success: function(data) {
               monthlyCurrent = data.monthlyCurrent;
               monthlyPrevious = data.monthlyPrevious;
               cumulativeCurrent = data.cumulativeCurrent;
               cumulativePrevious = data.cumulativePrevious;
               growthPercentages = data.growthPercentages;
               cummulativeGrowthPercentages = data.cummulativeGrowthPercentages;
               currentChartYear = data.toYear;
               previousChartYear = data.fromYear;
               updateCharts($('#revenueViewType').val());
            },
            error: function() {
               alert('An error occurred while fetching chart data.');
            },
            complete: function() {
               selectElement.prop('disabled', false);
            }
         });
      });
   });
</script>