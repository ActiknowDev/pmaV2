<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-user-tie"></i></span>Leave Management
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
                            <?= $this->Html->link('My Leaves', [
                'controller' => 'Leaves',
                'action' => 'index'
              ]); ?>
                        </li>

                        <li>
                            <?= $this->Html->link('Requested Leaves', [
                'controller' => 'Leaves',
                "action" => 'requestleave'
              ]); ?>
                        </li>

                        <li class="active">
                            <?= $this->Html->link('All Leaves', [
                'controller' => 'Leaves',
                'action' => 'allLeaves'
              ]) ?>
                        </li>

                        <li>
                            <?= $this->Html->link('Comp-Off', [
                'controller' => 'Leaves',
                'action' => 'addCompOff'
              ]); ?>
                        </li>

                        <li>
                            <?= $this->Html->link('Requested Comp-Off', [
                'controller' => 'Leaves',
                'action' => 'requestCompOff'
              ]); ?>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>


    <!-- PAGE-CONTENT -->
    <div class="page-content">
        <div class="container">
            <?= $this->Flash->render() ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="block">
                        <div class="header">
                            <h4 class="title">Leave Management</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <select name="" id="" class="form-control" onchange="location = this.value">
                                    <option
                                        value="<?php echo $this->Url->build(['controller' => 'leaves', 'action' => 'allLeaves']) ?>"
                                        <?= $selectStatus == '' ? 'selected' : '' ?>>
                                        All Leaves Details
                                    </option>
                                    <option
                                        value="<?php echo $this->Url->build(['controller' => 'leaves', 'action' => 'allLeaves', '?' => ['status' => 'Approved']]) ?>"
                                        <?= $selectStatus == 'Approved' ? 'selected' : '' ?>>
                                        Approved
                                    </option>
                                    <option
                                        value="<?php echo $this->Url->build(['controller' => 'leaves', 'action' => 'allLeaves', '?' => ['status' => 'Cancelled']]) ?>"
                                        <?= $selectStatus == 'Cancelled' ? 'selected' : '' ?>>
                                        Cancelled
                                    </option>
                                    <option
                                        value="<?php echo $this->Url->build(['controller' => 'leaves', 'action' => 'allLeaves', '?' => ['status' => 'Rejected']]) ?>"
                                        <?= $selectStatus == 'Rejected' ? 'selected' : '' ?>>
                                        Rejected
                                    </option>
                                    <option
                                        value="<?php echo $this->Url->build(['controller' => 'leaves', 'action' => 'allLeaves', '?' => ['status' => 'Pending']]) ?>"
                                        <?= $selectStatus == 'Pending' ? 'selected' : '' ?>>
                                        Pending
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="content ">
                            <table id="example" style="width:100%" class="table table-default table-striped block">
                                <thead>
                                    <tr>
                                        <th>Employee Name</th>
                                        <th>Reporting Manager</th>
                                        <th>Leave Type</th>
                                        <th>Subject</th>
                                        <th>Applied on</th>
                                        <th>Leave Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="filterData">
                                    <?php foreach ($leave as $value) : ?>
                                    <tr>
                                        <td><?= $value['CreatedBy']['name'] ?></td>

                                        <td>
                                            <?php
                                            if (!empty($value['user']['name'])) {
                                                echo $value['user']['name'];
                                            }
                                            ?>
                                        </td>

                                        <td><?= $value['leave_type'] ?></td>
                                        <td><?= $value['subject'] ?></td>

                                        <td>
                                            <span style="display:none;">
                                                <?= date('Ymd', strtotime($value['applied_on'])) ?>
                                            </span>
                                            <?= $value['applied_on'] ?>
                                        </td>

                                        <td>
                                            <span style="display:none;">
                                                <?= date('Ymd', strtotime($value['from_date'])) ?>
                                            </span>
                                            <?= $value['from_date'] . " to " . $value['to_date'] ?>
                                        </td>

                                        <td>
                                            <?php if ($value['status'] == 'Approved') : ?>
                                                <span class="badge badge-success">
                                                    <?= $value['status'] ?>
                                                </span>
                                            <?php else : ?>
                                                <span class="badge badge-danger">
                                                    <?= $value['status'] ?>
                                                </span>
                                            <?php endif; ?>
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
    </div>
</section>


<script>
// function filterLeaveData(data) {
//     let value = data.value;
//     // console.log(value);

//     $.ajax({
//         url: "<?= $this->Url->build("/Leaves/filterAllLeave") ?>",
//         method: "GET",
//         data: {
//             value
//         },
//         success: (res) => {
//             let data = JSON.parse(res);
//             console.log(data);

//             $("#filterData").html("");
//             let row = "";

//             data.forEach(element => {
//                 let appliedOn = new Date(element.applied_on).toLocaleDateString("en-us", {
//                     year: "2-digit",
//                     month: "2-digit",
//                     day: "2-digit",
//                     hour: "2-digit",
//                     minute: "2-digit"
//                 });
//                 let fromDate = new Date(element.from_date).toLocaleDateString("en-us", {
//                     year: "2-digit",
//                     month: "2-digit",
//                     day: "2-digit"
//                 });
//                 let toDate = new Date(element.to_date).toLocaleDateString("en-us", {
//                     year: "2-digit",
//                     month: "2-digit",
//                     day: "2-digit"
//                 });
//                 row += `<tr>
//             <td>
//               ${element.name}
//             </td>       
//             <td>
//               ${element.r_name}
//             </td>       
//             <td>
//               ${element.leave_type}
//             </td>       
//             <td>  
//               ${element.subject}
//             </td>       
//             <td>
//               ${appliedOn}
//             </td>       
//             <td>
//               ${fromDate} to ${toDate}
//             </td>       
//             <td>
//             ${element.leave_status === "Pending" 
//                   ? `<span class="badge badge-info">${element.leave_status}</span>` 
//                   : element.leave_status === "cancelled" || element.leave_status === "Rejected" 
//                   ? `<span class="badge badge-danger">${element.leave_status}</span>` 
//                   : `<span class="badge badge-success">${element.leave_status}</span>`}
//             </td>       
//           </tr>`
//             });

//             $("#filterData").html(row);

//         }

//     });



// }
</script>
<!-- : `<span class="badge badge-success">${element.leave_status}</span>`}
</td>
</tr>`
});

$("#filterData").html(row);

}

});



} -->
</script>