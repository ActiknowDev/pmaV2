<?php
ini_set('display_errors', 0);

// Group data by work_date
$totalBillable = 0;
$totalNonBillable = 0;
$groupedData = [];

foreach ($list as $row) {

    $date = $row['work_date'];

    // First time date aaye to initialize
    if (!isset($groupedData[$date])) {
        $groupedData[$date] = [
            'work_date' => $date,
            'billable' => 0,
            'non_billable' => 0
        ];
    }

    $timeUsed = (float) $row['time_used'];

    if ($row['bill'] === 'Billable') {

        $groupedData[$date]['billable'] += $timeUsed;
        $totalBillable += $timeUsed;

    } elseif ($row['bill'] === 'Non Billable') {

        $groupedData[$date]['non_billable'] += $timeUsed;
        $totalNonBillable += $timeUsed;
    }
}
?>

<div class="modal-dialog modal-lg">

    <div class="modal-content">

        <div class="modal-header">

            <h6 class="heading ft-secondary">
                Total Hours -
                <?= !empty($list) ? htmlspecialchars($list[0]['username']) : '' ?>
            </h6>

            <button type="button" class="close" data-dismiss="modal">
                &times;
            </button>

        </div>

        <div class="modal-body">

            <table class="table table-default table-striped block table-bordered">

                <thead>
                    <tr>
                        <th>Date</th>

                        <th style="text-align:center;">
                            Billable
                        </th>

                        <th style="text-align:center;">
                            Non Billable
                        </th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($groupedData)) : ?>

                        <?php foreach ($groupedData as $p) : ?>

                            <tr>

                                <td>
                                    <?= date('d-M-Y', strtotime($p['work_date'])) ?>
                                </td>

                                <td style="text-align:center;">

                                    <?php if ($p['billable'] > 0) : ?>

                                        <b class="bold-data">
                                            <?= $p['billable'] ?>
                                        </b>

                                    <?php else : ?>

                                        -

                                    <?php endif; ?>

                                </td>

                                <td style="text-align:center;">

                                    <?php if ($p['non_billable'] > 0) : ?>

                                        <b class="bold-data">
                                            <?= $p['non_billable'] ?>
                                        </b>

                                    <?php else : ?>

                                        -

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else : ?>

                        <tr>
                            <td colspan="3" class="text-center">
                                No records found
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>
                <tfoot>
                  <tr>
                      <th style="text-align:left; font-weight:600;">
                          Total
                      </th>

                      <th style="text-align:center; font-weight:600;">
                          <?= $totalBillable ?>
                      </th>

                      <th style="text-align:center; font-weight:600;">
                          <?= $totalNonBillable ?>
                      </th>
                  </tr>
              </tfoot>

            </table>

        </div>

    </div>

</div>