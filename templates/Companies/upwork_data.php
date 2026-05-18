<div class="container my-5">
    <div class="row mb-3">
        <div class="col-md-3">
            <!-- <input type="text" class="form-control" onkeyup="filterData(this.value,'job')" placeholder="Filter Job Type..."> -->
            <select class="form-control" onchange="filterData(this.value,'job')">
            <option value="">All Job Type</option>
            <option value="hourly">Hourly</option>
            <option value="fixed-price">Fixed Price</option>
            </select>
        </div>
        <div class="col-md-3">
            <!-- <input type="text" class="form-control" onkeyup="filterData(this.value,'status')" placeholder="Filter Contract Status..."> -->
            <select class="form-control" onchange="filterData(this.value,'status')">
            <option value="">CONTRACT STATUS</option>
            <option value="active">Active</option>
            <option value="closed">Closed</option>
            </select>
        </div>
        <div class="col-md-3">
            
        </div>
        <div class="col-md-3">
            <!-- <a href="#" class="v-btn v-btn-secondary"> Refresh Upwork Data</a> -->
        </div>
    </div>
    <table id="" class="table table-bordered">
        <thead>
            <tr>
                <th>S No.</th>
                <th>Ref No.</th>
                <th>Job Title</th>
                <th>Job Type</th>
                <th>Contract Status</th>
                <th>Charge Rate</th>
                <!-- <th>Milestone</th>
                <th>Deposit Amount</th>
                <th>State</th>
                <th>Due Date</th> -->
            </tr>
        </thead>
        <tbody id="uwTbl">
            <?php
            $sno = 1;
            foreach ($uwData as $value) :
            ?>
                <tr>
                    <td><?= $sno++ ?></td>
                    <td><a href="<?= $this->Url->build('/companies/milestoneDetails/' . $value->reference) ?>"><?= $value->reference ? $value->reference : "--" ?></a>
                    </td>
                    <td><?= $value->engagement_title ?></td>
                    <td><?= $value->engagement_job_type ?></td>
                    <td><?= $value->contract_status ?></td>
                    <td><?= $value->charge_rate ? $value->charge_rate : "--" ?></td>
                </tr>
            <?php
            endforeach;
            ?>
        </tbody>
    </table>

    <div class="mt-md-3">
        <ul class="pagination flex-row-reverse">

            <?php
            $pre = true;
            $next = true;
            if ($currentPage == 1)
                $pre = false;
            if (count($uwData) < $resultPerPage)
                $next = false;
            ?>

<?php
$nextpage =$currentPage+1;
$previouspage =$currentPage-1;
?>
            

            <?php if ($next) { ?>
                <li class="page-item"><a class="page-link" href="<?= $this->Url->build(['controller' => 'companies', 'action' => 'upworkData','?' => ['page' => $nextpage]]) ?>">Next</a></li>
            <?php } ?>

            <li class="page-item"><a class="page-link" href="<?= $this->Url->build(['controller' => 'companies', 'action' => 'upworkData',"?page=".$currentPage]) ?>"><?= $currentPage ?></a></li>

            <?php if ($pre) { ?>
                <li class="page-item"><a class="page-link" href="<?= $this->Url->build(['controller' => 'companies', 'action' => 'upworkData','?' => ['page' => $previouspage]]) ?>">Previous</a></li>
            <?php } ?>
        </ul>  
    </div>

</div>

<!-- //$currentPage - 1 -->

<script>
    // Filter upwork data
    function filterData(value, type) {
        // console.log(value, type);

        let url = "<?= $this->Url->build('/companies/milestoneDetails/') ?>";

        $.ajax({
            url: "<?= $this->Url->build(['controller' => 'Companies', 'action' => 'upworkDataFilter']) ?>",
            method: "GET",
            data: {
                value,
                type
            },
            success: (res) => {
                $("#uwTbl").html("");
                let row = "";

                let data = JSON.parse(res);
                // console.log(data);
                var sno = 1;

                data.forEach(item => {
                    // console.log(item.due_date);          

                    row += `<tr>
                        <td> ${sno++}</td>
                        <td><a href="${url}${item.reference}">${item.reference ? item.reference : "--"}</a></td>
                        <td>${item.engagement_title ? item.engagement_title : "--"}</td>
                        <td>${item.engagement_job_type ? item.engagement_job_type : "--"}</td>
                        <td>${item.contract_status ? item.contract_status : "--"}</td>
                        <td>${item.charge_rate ? item.charge_rate : "--"}</td>
                        
                    </tr>
                    `;
                });

                $("#uwTbl").html(row);

            }
        })

    }
</script>