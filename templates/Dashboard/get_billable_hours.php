<?php
ini_set('display_errors', 0);


/*
|--------------------------------------------------------------------------
| Group Project + Milestone wise hours
|--------------------------------------------------------------------------
*/

$billableProjects = [];
$nonBillableProjects = [];

$totalBillable = 0;
$totalNonBillable = 0;


foreach ($list as $row) {

    $projectId   = $row['project_id'];
    $projectName = trim($row['project_name']);
    $milestoneId = $row['milestone_id'];
    $milestone   = trim($row['title']);

    $timeUsed = (float)$row['time_used'];

    /*
     * Unique key:
     * Same Project + Same Milestone
     */
    $key = $projectId . '_' . $milestoneId;


    /*
    |--------------------------------------------------------------------------
    | Billable
    |--------------------------------------------------------------------------
    */

    if ($row['bill'] === 'Billable') {

        if (!isset($billableProjects[$key])) {

            $billableProjects[$key] = [
                'project_name' => $projectName,
                'milestone'    => $milestone,
                'total_hours'  => 0
            ];
        }

        $billableProjects[$key]['total_hours'] += $timeUsed;

        $totalBillable += $timeUsed;
    }


    /*
    |--------------------------------------------------------------------------
    | Non Billable
    |--------------------------------------------------------------------------
    */

    elseif ($row['bill'] === 'Non Billable') {

        if (!isset($nonBillableProjects[$key])) {

            $nonBillableProjects[$key] = [
                'project_name' => $projectName,
                'milestone'    => $milestone,
                'total_hours'  => 0
            ];
        }

        $nonBillableProjects[$key]['total_hours'] += $timeUsed;

        $totalNonBillable += $timeUsed;
    }
}

?>

<div class="modal-dialog modal-lg">

    <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">

            <h6 class="heading ft-secondary">
                Hours filled by Projects and Milestones -
                <?= !empty($list)
                    ? htmlspecialchars(trim($list[0]['username']))
                    : ''
                ?>
            </h6>

            <button
                type="button"
                class="close"
                data-dismiss="modal"
            >
                &times;
            </button>

        </div>


        <!-- Modal Body -->
        <div class="modal-body">

            <div class="table-responsive">

                <table
                    class="table table-bordered"
                    style="margin-bottom:0;"
                >

                    <!-- Main Header -->
                    <thead>

                        <tr>
                            <th
                                colspan="3"
                                style="
                                    font-weight:600;
                                    text-align:left;
                                    background:#ffffff;
                                "
                            >
                                Hours filled by Projects and Milestones
                            </th>
                        </tr>

                        <tr style="background:#d9d9d9;">

                            <th style="width:40%;">
                                Project name
                            </th>

                            <th style="width:40%;">
                                Milestone
                            </th>

                            <th
                                style="
                                    width:20%;
                                    text-align:right;
                                "
                            >
                                Total hours
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <!-- ================================================= -->
                        <!-- BILLABLE PROJECTS -->
                        <!-- ================================================= -->

                        <tr>
                            <td
                                colspan="3"
                                style="
                                    font-weight:700;
                                    background:#ffffff;
                                "
                            >
                                Billable Projects
                            </td>
                        </tr>


                        <?php if (!empty($billableProjects)) : ?>

                            <?php foreach ($billableProjects as $project) : ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars(
                                            $project['project_name']
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $project['milestone']
                                        ) ?>
                                    </td>

                                    <td style="text-align:right;">

                                        <b class="bold-data">
                                            <?= $project['total_hours']?>
                                        </b>

                                    </td>

                                </tr>

                            <?php endforeach; ?>


                            <!-- Billable Total -->

                            <tr>

                                <td
                                    colspan="2"
                                    style="
                                        text-align:right;
                                        font-weight:600;
                                    "
                                >
                                    Total Billable
                                </td>

                                <td
                                    style="
                                        text-align:right;
                                        font-weight:600;
                                    "
                                >
                                    <?= $totalBillable ?>
                                </td>

                            </tr>

                        <?php else : ?>

                            <tr>

                                <td
                                    colspan="3"
                                    class="text-center"
                                >
                                    No Billable Projects Found
                                </td>

                            </tr>

                        <?php endif; ?>



                        <!-- Empty separator row -->

                        <tr>

                            <td
                                colspan="3"
                                style="
                                    height:20px;
                                    background:#ffffff;
                                "
                            >
                            </td>

                        </tr>



                        <!-- ================================================= -->
                        <!-- NON BILLABLE PROJECTS -->
                        <!-- ================================================= -->

                        <tr>

                            <td
                                colspan="3"
                                style="
                                    font-weight:700;
                                    background:#ffffff;
                                "
                            >
                                Non Billable Projects
                            </td>

                        </tr>


                        <?php if (!empty($nonBillableProjects)) : ?>

                            <?php foreach ($nonBillableProjects as $project) : ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars(
                                            $project['project_name']
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $project['milestone']
                                        ) ?>
                                    </td>

                                    <td style="text-align:right;">

                                        <b class="bold-data">
                                            <?= $project['total_hours']; ?>
                                        </b>

                                    </td>

                                </tr>

                            <?php endforeach; ?>


                            <!-- Non Billable Total -->

                            <tr>

                                <td
                                    colspan="2"
                                    style="
                                        text-align:right;
                                        font-weight:600;
                                    "
                                >
                                    Total Non Billable
                                </td>

                                <td
                                    style="
                                        text-align:right;
                                        font-weight:600;
                                    "
                                >
                                    <?= $totalNonBillable ?>
                                </td>

                            </tr>

                        <?php else : ?>

                            <tr>

                                <td
                                    colspan="3"
                                    class="text-center"
                                >
                                    No Non Billable Projects Found
                                </td>

                            </tr>

                        <?php endif; ?>


                    </tbody>


                    <!-- ================================================= -->
                    <!-- GRAND TOTAL -->
                    <!-- ================================================= -->

                    <tfoot>

                        <tr style="background:#f2f2f2;">

                            <th
                                colspan="2"
                                style="text-align:right;"
                            >
                                Total Hours
                            </th>

                            <th style="text-align:right;">

                                <?= $totalBillable + $totalNonBillable ?>

                            </th>

                        </tr>

                    </tfoot>


                </table>

            </div>

        </div>

    </div>

</div>