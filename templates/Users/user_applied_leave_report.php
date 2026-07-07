<?php $session = new \Cake\Http\Session();
$userSession = $session->read('data');

?>
<style>
    .bold-data {
        font-weight: 700;
    }
    .buttons-csv {
        display: none;
    }
    .buttons-pdf {
        display: none;
    }
    .buttons-print {
        display: none;
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
                        <span class="icon"><i class="fa fa-building"></i></span>Total Employee Leaves
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-tab">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="v-tab">

                        <li>
                            <!--  <a href="#">My Leaves</a>
                            -->
                            <?= $this->Html->link('Available Leaves', [
                                'controller' => 'Users',
                                'action' => 'userTotalLeaveReport'
                            ]); ?>
                        </li>

                        <li class="active">
                            <?= $this->Html->link('Applied Leaves', [
                                'controller' => 'Users',
                                'action' => 'userAppliedLeaveReport'
                            ]); ?>
                        </li>

                        <li>
                            <?= $this->Html->link('Comp-Off Leave', [
                                'controller' => 'Users',
                                'action' => 'userCompLeave'
                            ]); ?>
                        </li>
                        
                        <?php if (in_array(12, $userSession['role_name'])) { ?>
                            <li>
                                <?= $this->Html->link('Apply Leave', [
                                    'controller' => 'Leaves',
                                    'action' => 'hrLeaves'
                                ]); ?>
                            </li>
                        <?php } ?>

                    </ul>
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
                        <div class="filter_from">
                            <label><b class="bold-data">From</b></label>
                            <input type="date" class="form-control datepicker" value="<?= $from ?>" onchange="FilterData();" name="from" id="from">
                        </div>
                    </form>
                </div>
                <div class="col-md-3 pb-2">
                    <form id="filter_form">
                    <div class="filter_to">
                            <label><b class="bold-data">To</b></label>
                            <input type="date" class="form-control datepicker" value="<?= $to ?>" onchange="FilterData();" name="to" id="to">
                        </div>
                    </form>
                </div>
                <!-- <div class="col-md-12 pb-2">
                            <a href="javascript::void(0);" data-target="#leave_module" data-toggle="modal" class="btn btn-sm text-white" style="background-color: #3fd5db; margin-top: 2px; float: inline-end;">Manage Leave</a>
                </div> -->
                        
                <div class="col-md-12">
                    <?= $this->Flash->render() ?>
                    <div class="content">
                        <table id="datatable" style="width:100%;" class="table table-default table-striped block table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Emp Name</th>
                                                <th>Date</th>
                                                <th>Particular</th>
                                                <th>Type</th>
                                                <th>Qty</th>
                                            </tr>
                                            
                                        </thead>
                                    <tbody>
                                    <?php
                                    // Step 1: Group by leave_id, user_id, and employee_name
                                    $groupedByLeave = [];

                                    foreach ($leaves as $data) {
                                        $key = $data['leave_id'] . "_" . $data['user_id'] . "_" . $data['employee_name']; // Unique key
                                        if (!isset($groupedByLeave[$key])) {
                                            $groupedByLeave[$key] = [
                                                'leave_id' => $data['leave_id'],
                                                'user_id' => $data['user_id'],
                                                'employee_name' => $data['employee_name'],
                                                'leave_type' => $data['leave_type'],
                                                'wfh_flag' => $data['wfh_flag'],
                                                'leavedates' => [], // Collect all leave dates here
                                                'cl' => $data['cl'] ?? 0,
                                                'sl' => $data['sl'] ?? 0,
                                                'el' => $data['el'] ?? 0,
                                                'lwp' => $data['lwp'] ?? 0,
                                                'comp_off' => $data['comp_off'] ?? 0,
                                            ];
                                        }
                                        // Add leave date to the leavedates array
                                        $groupedByLeave[$key]['leavedates'][] = $data['leavedate'];
                                    }

                                    // Step 2: Generate records
                                    $finalResult = [];

                                    foreach ($groupedByLeave as $group) {
                                        $leaveQuantities = [
                                            'CL' => &$group['cl'],
                                            'SL' => &$group['sl'],
                                            'EL' => &$group['el'],
                                            'LWP' => &$group['lwp'],
                                            'comp_off' => &$group['comp_off']
                                        ];
                                    
                                        $dateIndex = 0; // Initialize the date index
                                    
                                        // Continue while there are any leave quantities remaining
                                        while (array_sum($leaveQuantities) > 0) {
                                            if ($dateIndex >= count($group['leavedates'])) {
                                                $dateIndex = 0; // Reset to the first date if no more dates are available
                                            }
                                    
                                            $leaveDate = $group['leavedates'][$dateIndex];
                                    
                                            foreach ($leaveQuantities as $type => &$qty) {
                                                if ($qty > 0) {
                                                    $finalResult[] = [
                                                        'leave_id' => $group['leave_id'],
                                                        'user_id' => $group['user_id'],
                                                        'employee_name' => $group['employee_name'],
                                                        'wfh_flag' => $group['wfh_flag'],
                                                        'leave_date' => $leaveDate,
                                                        'leave_type' => $group['leave_type'],
                                                        'type' => $type,
                                                        'qty' => in_array($group['leave_type'], ['Paid Leave', 'Casual Leave', 'Sick Leave', 'comp_off'])
                                                            ? 1
                                                            : (($qty >= 0.5) ? 0.5 : $qty),
                                                    ];
                                    
                                                    if (in_array($group['leave_type'], ['Paid Leave', 'Casual Leave', 'Sick Leave', 'comp_off'])) {
                                                        $qty -= 1; // Deduct 1 (or full leave) for these types
                                                    } else {
                                                        $qty -= 0.5; // Deduct 0.5 for other leave types
                                                    }
                                    
                                                    break; // Process only one leave type per iteration
                                                }
                                            }
                                    
                                            $dateIndex++;
                                        }
                                    }
                                    foreach ($finalResult as $data) : 
                                        ?>
                                        <tr data-id="<?= $data['user_id'] ?>">
                                            <td><?= $data['employee_name'] ?></td>
                                            <td><?= $data['leave_date'] ?></td>
                                            <td>
                                                <?php 
                                                if($data['leave_type']=='Casual Leave' || $data['leave_type']=='Sick Leave' || $data['leave_type']=='Paid Leave') {
                                                    echo "Leave";
                                                } 
                                                elseif($data['leave_type']=='Half Day' && $data['wfh_flag']=='0') {
                                                    echo "Half Day (WFH)";
                                                } 
                                                elseif($data['leave_type']=='Half Day' && $data['wfh_flag']=='1') {
                                                    echo "Half Day (WFH)";
                                                } 
                                                elseif($data['leave_type']=='WFH' && $data['wfh_flag']=='0') {
                                                    echo "Half Day (WFH)";
                                                } 
                                                elseif($data['leave_type']=='WFH' && $data['wfh_flag']=='1') {
                                                    echo "WFH";
                                                } 
                                                elseif($data['leave_type']=='Half Day' && $data['wfh_flag']=='') {
                                                    echo "Half Day";
                                                } else {
                                                    echo $data['leave_type'];
                                                } 
                                                 ?>
                                            </td>
                                            <td>
                                            <?= $data['type']; ?>
                                            </td>
                                            <td>
                                            <?= $data['qty']; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- CREATE CLIENT -->

<!-- <div class="modal fade" id="project_show" role="dialog">
    
  </div> -->

  <div class="modal fade" tabindex="-1" role="dialog" id="leave_module">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= $this->Form->create(null, ['url' => [
                'Controller' => 'Leaves',
                'action' => 'leaveModule'
            ]]) ?>

           
            <div class="modal-header">
                <h5 class="modal-title">Leave Management</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Employee</label>
                            <div class="adon-group res">
                                <select name="emp[]" class="form-control" id="emp">
                                    <?php foreach ($leave_data as $empdata) : ?>

                                    <option value="<?= $empdata['uid'] ?>"><?= $empdata['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Leave Type</label>
                            <?= $this->Form->control('leave_type', [
                                'required' => true,
                                'label' => false,
                                'class' => 'form-control',
                                'onchange' => 'leaveType()',
                                'id' => 'leave',
                                'options' => [
                                    'Casual Leave' => 'Casual Leave',
                                    'Sick Leave' => 'Sick Leave',
                                    'Paid Leave' => 'Paid Leave',
                                    'comp_off' => 'Comp_Off',
                                ]
                            ]); ?>

                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Enter Leaves</label>
                            <?= $this->Form->control('subject', [
                                'required' => true,
                                'label' => false,
                                'class' => 'form-control',
                                'type' => 'text',
                                'autocomplete' => 'off'
                            ]); ?>

                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12 mt-2" id="wfhtyperadio">
                            <input type="radio" name="wfhtype" value="0" id="type-0" class="rd-input" checked /><label for="type-0">&nbsp;Addon</label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="wfhtype" value="1" id="type-1" class="rd-input" /><label for="type-1">&nbsp;Delete</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="v-btn v-btn-base" data-dismiss="modal" aria-label="Close">Close</button>
                <button class="v-btn v-btn-primary" type="submit" id="submit">Apply Leave</a>
            </div>

            <?= $this->Form->end() ?>

        </div>
    </div>
</div>

<style>
    .select2-container--default .select2-selection--single {
        border: unset !important;
        border-radius: unset !important;
        height: 33px !important;
        
    }
    .select2-container {
        width: 100% !important;
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- <script>
    $("input[type=text]").on("focus", function() {
        if ($(this).val() == 0)
            $(this).val('');
    });
</script> -->
<script>
    $(document).ready(function ($) {
        $("#emp").select2({
            placeholder: "Select Option",
        });
    });
</script>

<script>
//    $(document).ready(function() {
//     $('#example1').dataTable({
//         /* Disable initial sort */
//         "aaSorting": [],
//         "lengthMenu": [[100, "All", 50, 25], [100, "All", 50, 25]],
//         // stateSave: true
//     });

   
// });

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': '<?= $this->request->getAttribute('csrfToken') ?>'
        }
    });
});

$(document).ready(function() {
    $('td[contenteditable=true]').blur(function() {
        var value = $(this).text();
        var colName = $(this).attr('col-name');
        var userId = $(this).closest('tr').data('id');

        // console.log('value',value);
        // console.log('colName',colName);
        // console.log('userId',userId);

        // return false;

        // AJAX POST request
        $.ajax({
            url: '<?= $this->Url->build(['controller' => 'Users', 'action' => 'updateLeaveData']) ?>',
            type: 'POST',
            data: {
                userId: userId,
                colName: colName,
                value: value
            },
            success: function(response) {
                // Handle success response
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }
        });
    });
});


function FilterData() {
    $("#loader").show();
    var from= $("#from").val();
    var to= $("#to").val();
    $.ajax({
        
            url:"<?= $this->Url->build(['controller'=>'Users', 'action'=>'userAppliedLeaveReport']) ?>/"+from+"/"+to,
			method:"get",
			
         success : function(resp){
            window.location.href = "<?= WEBURL ?>users/user-applied-leave-report/"+from+"/"+to;	
            $("#loader").hide();	 	
            },
       });
}
</script>