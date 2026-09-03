<?php
ini_set('display_errors', 0);

$lastAttendanceDay = 0;

if (!empty($emp_attendence_list)) {

    foreach ($emp_attendence_list as $attendance) {

        if (!empty($attendance['date'])) {

            $attendanceDay = (int)date(
                'd',
                strtotime($attendance['date'])
            );

            if ($attendanceDay > $lastAttendanceDay) {
                $lastAttendanceDay = $attendanceDay;
            }
        }
    }
}

$lastAttendanceDay = (int)date('d');

?>

<div class="modal-dialog modal-lg">

    <div class="modal-content">


        <!-- =========================
             Modal Header
        ========================== -->

        <div class="modal-header">

            <h6 class="heading ft-secondary mb-0">

                Office Hours

                <?php if (!empty($userName)) : ?>

                    - <?= h($userName) ?>

                <?php endif; ?>

            </h6>


            <button
                type="button"
                class="close"
                data-dismiss="modal"
            >
                &times;
            </button>

        </div>



        <!-- =========================
             Modal Body
        ========================== -->

        <div class="modal-body p-0">


            <?php if (!empty($errorMessage)) : ?>


                <div class="alert alert-danger m-3">

                    <?= h($errorMessage) ?>

                </div>


            <?php else : ?>



                <!-- =========================
                     Attendance Table
                ========================== -->

                <div
                    class="table-responsive"
                    style="
                        max-height:500px;
                        overflow-y:auto;
                    "
                >


                    <table class="table table-clean table-bordered mb-0">


                        <thead>

                            <tr>

                                <th>
                                    Date
                                </th>

                                <th>
                                    In / Out
                                </th>

                                <th class="text-center">
                                    Hrs
                                </th>

                                <th class="text-center">
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <?php
                            for (
                                $d = 1;
                                $d <= $lastAttendanceDay;
                                $d++
                            ) :
                            ?>


                                <?php

                                $currentDate = sprintf(
                                    '%04d-%02d-%02d',
                                    $year,
                                    $month,
                                    $d
                                );


                                $dayOfWeek = date(
                                    'N',
                                    strtotime($currentDate)
                                );


                                /*
                                 * Find attendance
                                 */

                                $record = null;


                                foreach (
                                    $emp_attendence_list as $emp
                                ) {

                                    if (
                                        $emp['date'] == $currentDate
                                    ) {

                                        $record = $emp;

                                        break;
                                    }
                                }

                                ?>


                                <tr>


                                    <!-- Date -->

                                    <td>

                                        <?= date(
                                            'M d',
                                            strtotime($currentDate)
                                        ) ?>

                                    </td>



                                    <?php if ($record) : ?>


                                        <!-- =================================
                                             Present
                                        ================================== -->

                                        <td class="text-muted small">


                                            <?php if (
                                                !empty($record['intime'])
                                            ) : ?>

                                                <?= date(
                                                    'g:ia',
                                                    strtotime(
                                                        $record['intime']
                                                    )
                                                ) ?>

                                            <?php else : ?>

                                                -

                                            <?php endif; ?>


                                            &nbsp;-&nbsp;


                                            <?php if (
                                                !empty($record['outtime'])
                                            ) : ?>

                                                <?= date(
                                                    'g:ia',
                                                    strtotime(
                                                        $record['outtime']
                                                    )
                                                ) ?>

                                            <?php else : ?>

                                                -

                                            <?php endif; ?>


                                        </td>



                                        <td
                                            class="
                                                text-center
                                                font-weight-bold
                                            "
                                        >


                                            <?php if (
                                                !empty(
                                                    $record['total_time']
                                                )
                                            ) : ?>


                                                <?= date(
                                                    'H:i',
                                                    strtotime(
                                                        $record['total_time']
                                                    )
                                                ) ?>


                                            <?php else : ?>

                                                -

                                            <?php endif; ?>


                                        </td>



                                        <td class="text-center">

                                            <span
                                                class="
                                                    badge-status
                                                    status-present
                                                "
                                            >
                                                Present
                                            </span>

                                        </td>



                                    <?php else : ?>


                                        <?php

                                        /*
                                         * Find Leave / WFH
                                         */

                                        $leaveData = null;


                                        foreach ($leaves as $lv) {

                                            $fromDate = date(
                                                'Y-m-d',
                                                strtotime(
                                                    $lv['from_date']
                                                )
                                            );

                                            $toDate = date(
                                                'Y-m-d',
                                                strtotime(
                                                    $lv['to_date']
                                                )
                                            );


                                            if (
                                                $currentDate >= $fromDate
                                                &&
                                                $currentDate <= $toDate
                                            ) {

                                                $leaveData = $lv;

                                                break;
                                            }
                                        }



                                        /*
                                         * Find Holiday
                                         */

                                        $holidayData = null;


                                        foreach ($holidays as $h) {

                                            if (
                                                $currentDate ==
                                                date(
                                                    'Y-m-d',
                                                    strtotime(
                                                        $h['start']
                                                    )
                                                )
                                            ) {

                                                $holidayData = $h;

                                                break;
                                            }
                                        }

                                        ?>



                                        <?php if ($leaveData) : ?>


                                            <!-- =============================
                                                 Leave / WFH
                                            ============================== -->


                                            <td
                                                colspan="2"
                                                class="
                                                    text-center
                                                    text-muted
                                                    small
                                                "
                                            >

                                                <?php if (
                                                    $leaveData['leave_type']
                                                    === 'WFH'
                                                ) : ?>

                                                    Work From Home

                                                <?php else : ?>

                                                    Leave Record

                                                <?php endif; ?>

                                            </td>



                                            <td class="text-center">


                                                <?php if (
                                                    $leaveData['leave_type']
                                                    === 'WFH'
                                                ) : ?>


                                                    <span
                                                        class="
                                                            badge-status
                                                            status-wfh
                                                        "
                                                    >
                                                        WFH
                                                    </span>


                                                <?php else : ?>


                                                    <span
                                                        class="
                                                            badge-status
                                                            status-leave
                                                        "
                                                    >

                                                        <?= h(
                                                            $leaveData[
                                                                'leave_type'
                                                            ]
                                                        ) ?>

                                                    </span>


                                                <?php endif; ?>


                                            </td>



                                        <?php elseif ($holidayData) : ?>


                                            <!-- =============================
                                                 Holiday
                                            ============================== -->


                                            <td
                                                colspan="2"
                                                class="
                                                    text-center
                                                    small
                                                    font-weight-bold
                                                    text-info
                                                "
                                            >

                                                <?= h(
                                                    $holidayData['title']
                                                ) ?>

                                            </td>


                                            <td class="text-center">

                                                <span
                                                    class="
                                                        badge-status
                                                        status-holiday
                                                    "
                                                >
                                                    Holiday
                                                </span>

                                            </td>



                                        <?php elseif (
                                            $dayOfWeek >= 6
                                        ) : ?>


                                            <!-- =============================
                                                 Weekend
                                            ============================== -->


                                            <td
                                                colspan="2"
                                                class="
                                                    text-center
                                                    text-muted
                                                    small
                                                "
                                            >

                                                <?= $dayOfWeek == 6
                                                    ? 'Saturday'
                                                    : 'Sunday'
                                                ?>

                                            </td>


                                            <td class="text-center">

                                                <span
                                                    class="
                                                        badge-status
                                                        status-holiday
                                                    "
                                                >
                                                    Weekend
                                                </span>

                                            </td>



                                        <?php else : ?>


                                            <!-- =============================
                                                 Absent
                                            ============================== -->


                                            <td
                                                colspan="2"
                                                class="text-center"
                                            >
                                                -
                                            </td>


                                            <td class="text-center">

                                                <span
                                                    class="
                                                        badge-status
                                                        status-absent
                                                    "
                                                >
                                                    Absent
                                                </span>

                                            </td>


                                        <?php endif; ?>


                                    <?php endif; ?>


                                </tr>


                            <?php endfor; ?>


                        </tbody>


                    </table>


                </div>


            <?php endif; ?>


        </div>



        <!-- =========================
             Modal Footer
        ========================== -->

        <div class="modal-footer">

            <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
            >
                Close
            </button>

        </div>


    </div>

</div>