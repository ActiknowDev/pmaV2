<style>
    .sidefix {
        position: sticky;
        top: 23%;
    }

    .text-white {
        color: #ffff;
    }

    .text-end {
        text-align: end;
    }

    .fw-600 {
        font-weight: 600;
    }

    .mr-2 {
        margin-right: 20px !important;
    }
    h2 {
        font-size:22px;
    }
</style>

<div class="container mt-md-5">
    <div class="row">
        <div class="col-md-12 text-end">
            <a class="btn btn-sm btn-success text-white mb-2" href="<?= $this->Url->build('/companies/upworkData') ?>"> <i class="fa fa-arrow-left mr-2"></i> Back to List</a>
        </div>
    </div>

    <div class="row">

        <div class="col-md-8">
            <?php 
            if (count($mileDetails) > 0) : ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <!-- <th scope="col">Fixed Price Upfront Payment :</th> -->
                        <th scope="col">Milestone</th>
                        <th scope="col">Deposit Amount</th>
                        <th scope="col">State</th>
                    </tr>
                </thead>
                <tbody>

                	 <?php
                    $x = 1;
                foreach ($mileDetails as $miles) :
            ?>

                    <tr>
                        <th scope="row"><?= $x++ ?></th>
                       <!--  <td><?= $miles->fixed_price_upfront_payment ?></td> -->
                        <td><?= $miles->description ?></td>
                        <td><?= $miles->deposit_amount ?></td>
                        <td><?= $miles->state ?></td>
                    </tr>

                    <?php
                endforeach;
            ?>
                    <!-- <tr>
                        <th scope="row">2</th>
                        <td>99999</td>
                        <td>Website enhancement</td>
                        <td>140</td>
                        <td>Paid</td>
                    </tr> -->
                    
                </tbody>
            </table>
          <?php  else : ?>
                <h2>Data Not Found...</h2>
            <?php
            endif;
            ?>
        </div>
        <?php 
            if (count($mileDetails) > 0) : ?>
        <div class="col-md-4">
            <div class="sidefix">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item fw-600"><?=$mileDetails[0]['engagement_title']?></li>
                    <li class="list-group-item fw-600">Job Type : <?=$mileDetails[0]['engagement_job_type']?></li>
                    <li class="list-group-item fw-600">Contract Status : <?=$mileDetails[0]['contract_status']?></li>
                    <li class="list-group-item fw-600">Fixed Charge Amount Agreed : <?=$mileDetails[0]['fixed_charge_amount_agreed']?></li>
                     <li class="list-group-item fw-600">Fixed Price Upfront Payment : <?=$mileDetails[0]['fixed_price_upfront_payment']?></li>
                    <li class="list-group-item fw-600">Charge Rate : <?=$mileDetails[0]['charge_rate']?></li>
                </ul>
            </div>
        </div>
        <?php
            endif;
            ?>
    </div>

</div>