<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" />
<!-- for Datatables  -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" />


<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<?php echo $this->Html->css('dashboard.css'); ?>
<style>
    body {
        font-family: "poppins";
        background-color: #f7f7f7;
    }

    .fs-14 {
        font-size: 14px;
    }

    .fw-bolder {
        color: #000;
        font-size: 1.4rem;
        font-weight: 700;
    }

    .headline {
        font-size: 1rem;
        font-weight: 600 !important;
        margin-bottom: 1rem !important;
    }

    .fs-13 {
        font-size: 13px;
    }

    .fs-12 {
        font-size: 12px;
    }

    .fs-11 {
        font-size: 11px;
    }

    .fs-10 {
        font-size: 10px;
    }

    .fs_color {
        color: #3fd5db;
    }

    a {
        text-decoration: none;
    }

    .ss_card:hover {
        box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
        background-color: #3fd5db;
        color: #fff;
    }

    .ss_card:hover .card-icon {
        background-color: #333333;
    }

    .fw_500 {
        font-weight: 500 !important;
    }

    .bdrright {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    .ss-title {
        color: #000;
        width: 100%;
        height: 50px;
        font-size: 12px;
        font-weight: 500;
        margin-bottom: 15px;
    }

    .border_radius {
        border-radius: 10px;
    }

    .card_shadow {
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
    }

    .card_icon {
        /* width: 40px; */
        /* height: 40px; */
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        /* justify-content: center; */
        border-radius: 50%;
    }

    .bg_faebd769 {
        background-color: #eefcfd;
        /* background-color: #faebd769; */
    }

    .bg_f1f5ff {
        background-color: #fcfcd4;
        /* background-color: #f1f5ff; */
    }

    .bg_f5f6fa {
        background-color: #e6f4f1;
        /* background-color: #f5f6fa; */
    }

    .bg_fef8fc {
        background-color: #e9fdfe;
        /* background-color: #fef8fc; */
    }

    .bg_cddbfa9e {
        background-color: #cddbfa9e;
    }

    .bg_f9e2ca8a {
        background-color: #f9e2ca8a;
    }

    .bg_fddff8 {
        background-color: #fddff8;
    }

    .bg_c2f4e68f {
        background-color: #c2f4e68f;
    }

    .fs_success {
        color: #25cf8b;
    }

    .fs_danger {
        color: #fb5c5c;
    }

    .scroll {
        max-height: 445px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #aab7cf transparent;
    }

    .scroll::-webkit-scrollbar {
        width: 7px;
    }

    .scroll::-webkit-scrollbar-track {
        background-color: #fff;
    }

    .scroll::-webkit-scrollbar-thumb {
        background-color: #5cdbe0;
        border-radius: 10px;
        border: 1px solid transparent;
        background-clip: content-box;
    }

    .table th,
    .table td,
    table th,
    table td {
        font-size: 0.59rem !important;
        /* color:#000; */
    }

    tbody {
        font-weight: 600;
    }
    .justify-center {
        justify-content: center;
    }
</style>
<?php $session = new \Cake\Http\Session();
$userSession = $session->read('data');
$menu = $session->read('menu');
?>
<?php

// Initialize total seconds
$total_seconds = 0;
$total_records = count($emp_attendence_list);
$average_time = 0;

if (count($emp_attendence_list) > 0) {
    foreach ($emp_attendence_list as $att) {
        // Convert time to seconds using strtotime
        $time_seconds = strtotime($att['total_time']) - strtotime('TODAY');
        // Add to total seconds
        $total_seconds += $time_seconds;
    }
    $average_seconds = $total_seconds / $total_records;
    $average_time = gmdate('H:i', $average_seconds);
    // Convert total seconds to HH:MM:SS format
    // $total_time = gmdate('H:i', $total_seconds);
    // Output total time
    // dd($average_time);
}
?>

<div class="container" id="resdata">
    <div class="my-3">
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-3">
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="row d-flex align-items-center">
                                <!-- <div class="col-md-4 col-12 text-center mb-2">
                                    <img src="./5.png" class="img-fluid rounded" />
                                </div> -->
                                <div class="col-md-4 col-12 text-center mb-2">
                                    <?php if (!empty($emp_details->user_image)): ?>
                                        <img src="<?= $this->Url->webroot('/img/user_images/' . $emp_details->user_image) ?>"
                                            class="img-fluid rounded"
                                            alt="User Image"
                                            style="max-height: 200px;" />
                                    <?php else: ?>
                                        <!-- Optional fallback image -->
                                        <img src="<?= $this->Url->webroot('/img/default-user.png') ?>"
                                            class="img-fluid rounded"
                                            alt="Default Image"
                                            style="max-height: 200px;" />
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-8 col-12 mb-2">
                                    <div class="mb-2">
                                        <p class="fs-13 fw-semibold mb-0"><?= $emp_details->name; ?></p>
                                        <span class="fs-11 text-secondary fw_500"><?= $emp_details->designation; ?></span>
                                    </div>
                                    <div class="row d-flex align-items-center">
                                        <div class="col-12 mb-1">
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 48 48">
                                                        <path
                                                            fill="#c481b0"
                                                            d="M17.536 4.384C19.597 4.1 21.33 5.361 22.15 7l2.017 4.033c1.119 2.238.476 4.864-1.22 6.516c-1.002.975-2.009 2.089-2.593 3.072a.33.33 0 0 0-.034.23c.54 2.926 2.523 5.817 4.292 7.89a.68.68 0 0 0 .724.187l3.975-1.242a5.25 5.25 0 0 1 5.892 2.036l2.877 4.185c.78 1.135 1.175 2.675.577 4.156c-.534 1.323-1.567 3.231-3.446 4.5c-1.95 1.316-4.621 1.816-8.116.618c-3.905-1.34-7.594-4.7-10.653-8.997c-3.077-4.324-5.61-9.725-7.146-15.357C7.844 13.51 8.72 9.93 10.782 7.625c1.989-2.224 4.839-2.979 6.754-3.242m2.377 3.735c-.457-.913-1.272-1.364-2.037-1.259c-1.701.234-3.831.867-5.23 2.432c-1.326 1.483-2.244 4.095-.94 8.878c1.465 5.368 3.877 10.498 6.772 14.565c2.913 4.093 6.223 6.983 9.427 8.082c2.855.979 4.69.495 5.906-.325c1.286-.869 2.077-2.25 2.527-3.364c.206-.51.117-1.17-.319-1.804l-2.877-4.184a2.75 2.75 0 0 0-3.086-1.067l-3.975 1.242c-1.188.371-2.526.04-3.371-.95c-1.841-2.157-4.187-5.465-4.85-9.06a2.83 2.83 0 0 1 .344-1.961c.758-1.274 1.958-2.573 2.997-3.585c1.015-.988 1.303-2.458.729-3.607z" />
                                                    </svg>
                                                </div>
                                                <span class="fs-12 fw_500"><?= $emp_details_table->mobile_no ?></span>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-1">
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24">
                                                        <path
                                                            fill="#c481b0"
                                                            fill-rule="evenodd"
                                                            d="M2.804 8.353c-.28 2.603-.268 5.605.122 8.198a3.138 3.138 0 0 0 2.831 2.66l1.51.13c3.15.274 6.316.274 9.466 0l1.51-.13a3.138 3.138 0 0 0 2.831-2.66c.39-2.593.402-5.595.122-8.198a30.348 30.348 0 0 0-.122-.904a3.138 3.138 0 0 0-2.831-2.66l-1.51-.13a54.647 54.647 0 0 0-9.465 0l-1.51.13a3.138 3.138 0 0 0-2.832 2.66a31.1 31.1 0 0 0-.122.904m4.593-2.2a53.145 53.145 0 0 1 9.205 0l1.51.131a1.64 1.64 0 0 1 1.479 1.389l.034.233l-5.561 3.09a4.25 4.25 0 0 1-4.128 0l-5.561-3.09l.034-.233a1.638 1.638 0 0 1 1.478-1.389zM19.808 9.52a29.099 29.099 0 0 1-.217 6.807a1.638 1.638 0 0 1-1.478 1.389l-1.51.131a53.152 53.152 0 0 1-9.206 0l-1.51-.131a1.638 1.638 0 0 1-1.478-1.389a29.1 29.1 0 0 1-.218-6.807l5.016 2.787a5.75 5.75 0 0 0 5.585 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <span class="fs-12 fw_500"><?= $emp_details->email; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="fs-11 text-secondary fw_500">Employer Id</span>
                                        <p class="ms-2 fs-12 fw-semibold mb-0">&nbsp;<?= $emp_details->id; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-0">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3 headline">Working Statistic</h6>
                        <div class="row">
                            <div class="col-md-4 col-6 mb-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#projects_assigned_modal">
                                    <div class="card border-0 border_radius card_shadow bg_faebd769">
                                        <div class="card-body">
                                            <div class="card_icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 20 20">
                                                    <path
                                                        fill="#c481b0"
                                                        d="M9.354 7.104a.5.5 0 0 0-.708-.708L7.234 7.808l-.397-.362a.5.5 0 0 0-.674.738l.75.685a.5.5 0 0 0 .69-.016zm0 4.292a.5.5 0 0 1 0 .708l-1.75 1.75a.5.5 0 0 1-.691.015l-.75-.685a.5.5 0 0 1 .674-.738l.397.363l1.412-1.413a.5.5 0 0 1 .708 0M11 12a.5.5 0 0 0 0 1h1.67c-.11-.313-.17-.65-.17-1zm-5 4h5.05q-.05.243-.05.5q0 .25.038.5H6a3 3 0 0 1-3-3V6a3 3 0 0 1 3-3h8a3 3 0 0 1 3 3v3.401a3 3 0 0 0-1-.36V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2m4.5-8.5A.5.5 0 0 1 11 7h2.5a.5.5 0 0 1 0 1H11a.5.5 0 0 1-.5-.5m7 4.5a2 2 0 1 1-4 0a2 2 0 0 1 4 0m1.5 4.5c0 1.245-1 2.5-3.5 2.5S12 17.75 12 16.5a1.5 1.5 0 0 1 1.5-1.5h4a1.5 1.5 0 0 1 1.5 1.5" />
                                                </svg>
                                            </div>
                                            <p class="ss-title">Projects Assigned</p>
                                            <h4 class="fw-bolder mb-2"><?= $total_billable_project_assign ?></h4>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#milestonesModal">
                                    <div class="card border-0 border_radius card_shadow bg_f1f5ff">
                                        <div class="card-body">
                                            <div class="card_icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 2048 2048">
                                                    <path
                                                        fill="#c481b0"
                                                        d="M1792 256v1792H256V256h512q0-53 20-99t55-82t81-55t100-20q53 0 99 20t82 55t55 81t20 100zM640 512h768V384h-256V256q0-27-10-50t-27-40t-41-28t-50-10q-27 0-50 10t-40 27t-28 41t-10 50v128H640zm1024-128h-128v256H512V384H384v1536h1280zM512 896h512v128H512zm0 384h512v128H512zm0 384h512v128H512zm915-941l90 90l-237 237l-173-173l90-90l83 83zm0 384l90 90l-237 237l-173-173l90-90l83 83zm0 384l90 90l-237 237l-173-173l90-90l83 83z" />
                                                </svg>
                                            </div>
                                            <p class="ss-title">Milestones Assigned</p>
                                            <h4 class="fw-bolder mb-2"><?= $total_miles; ?></h4>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#internal_project_modal">
                                    <div class="card border-0 border_radius card_shadow bg_f5f6fa">
                                        <div class="card-body">
                                            <div class="card_icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="2.5em" height="2em" viewBox="0 0 640 512">
                                                    <path
                                                        fill="#c481b0"
                                                        d="M384 320H256c-17.67 0-32 14.33-32 32v128c0 17.67 14.33 32 32 32h128c17.67 0 32-14.33 32-32V352c0-17.67-14.33-32-32-32M192 32c0-17.67-14.33-32-32-32H32C14.33 0 0 14.33 0 32v128c0 17.67 14.33 32 32 32h95.72l73.16 128.04C211.98 300.98 232.4 288 256 288h.28L192 175.51V128h224V64H192zM608 0H480c-17.67 0-32 14.33-32 32v128c0 17.67 14.33 32 32 32h128c17.67 0 32-14.33 32-32V32c0-17.67-14.33-32-32-32" />
                                                </svg>
                                            </div>
                                            <p class="ss-title">Internal Projects Assigned</p>
                                            <h4 class="fw-bolder mb-2"><?= $total_non_billable_project_assign ?></h4>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#allocatedhoursModal">
                                    <div class="card border-0 border_radius card_shadow bg_fef8fc">
                                        <div class="card-body">
                                            <div class="card_icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 256 256">
                                                    <path
                                                        fill="#c481b0"
                                                        d="M136 72v43.05l36.42-18.21a8 8 0 0 1 7.16 14.31l-48 24A8 8 0 0 1 120 128V72a8 8 0 0 1 16 0m-8 144a88 88 0 1 1 88-88a8 8 0 0 0 16 0a104 104 0 1 0-104 104a8 8 0 0 0 0-16m103.73 5.94a8 8 0 1 1-15.46 4.11C213.44 215.42 203.46 208 192 208s-21.44 7.42-24.27 18.05A8 8 0 0 1 160 232a8.15 8.15 0 0 1-2.06-.27a8 8 0 0 1-5.67-9.79a40 40 0 0 1 17.11-23.32a32 32 0 1 1 45.23 0a40 40 0 0 1 17.12 23.32M176 176a16 16 0 1 0 16-16a16 16 0 0 0-16 16" />
                                                </svg>
                                            </div>
                                            <p class="ss-title">Hours Allocated</p>
                                            <h4 class="fw-bolder mb-2"><?= $total_time_slot; ?></h4>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#hoursfilledModal">
                                    <div class="card border-0 border_radius card_shadow bg_cddbfa9e">
                                        <div class="card-body">
                                            <div class="card_icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 2048 2048">
                                                    <path
                                                        fill="#c481b0"
                                                        d="M1536 1408h192v128h-320v-384h128zm-256-896H256V384h1024zm192 384q119 0 224 45t183 124t123 183t46 224q0 119-45 224t-124 183t-183 123t-224 46q-119 0-224-45t-183-124t-123-183t-46-224q0-119 45-224t124-183t183-123t224-46m0 1024q93 0 174-35t142-96t96-142t36-175q0-93-35-174t-96-142t-142-96t-175-36q-93 0-174 35t-142 96t-96 142t-36 175q0 93 35 174t96 142t142 96t175 36M1166 768q-109 48-200 128H256V768zm-391 384q-14 31-25 63t-21 65H256v-128zm-519 384h451q3 32 8 64t14 64H256zm594 384q50 71 116 128H0V0h1536v707l-32-2q-16-1-32-1t-32 1t-32 2V128H128v1792z" />
                                                </svg>
                                            </div>
                                            <p class="ss-title">Hours Filled</p>
                                            <h4 class="fw-bolder mb-2"><?= $user_time_use; ?></h4>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#billablehoursModal">
                                    <div class="card border-0 border_radius card_shadow bg_f9e2ca8a">
                                        <div class="card-body">
                                            <div class="card_icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 32 32">
                                                    <path
                                                        fill="#c481b0"
                                                        d="M6 3v26h16v-2H8V5h10v6h6v2h2V9.6l-.3-.3l-6-6l-.3-.3zm14 3.4L22.6 9H20zM10 13v2h12v-2zm17 2v2c-1.7.3-3 1.7-3 3.5c0 2 1.5 3.5 3.5 3.5h1c.8 0 1.5.7 1.5 1.5s-.7 1.5-1.5 1.5H25v2h2v2h2v-2c1.7-.3 3-1.7 3-3.5c0-2-1.5-3.5-3.5-3.5h-1c-.8 0-1.5-.7-1.5-1.5s.7-1.5 1.5-1.5H31v-2h-2v-2zm-17 3v2h7v-2zm9 0v2h3v-2zm-9 4v2h7v-2zm9 0v2h3v-2z" />
                                                </svg>
                                            </div>
                                            <p class="ss-title">Hours Filled on Billable Projects</p>
                                            <h4 class="fw-bolder mb-2"><?= $billable_user_time_use; ?></h4>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- <p class="fs-12 fw_500 mb-2">Working Statistic</p> -->
                        <div id="working_statistics"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="card border-0">
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <h6 class="fw-semibold mb-3 headline">Attendence Log</h6>
                            </div>
                            <!-- <div class="col-lg-5 mb-3">
                                <select name="emp_filter" onchange="FilterData()" class="form-control" id="emp_filter" required>
                                    <?php foreach ($emp_list as $row): ?>
                                    <option value="<?= $row['id']; ?>" <?php if ($emp_details->id == $row['id']) {
                                                                            echo 'selected';
                                                                        } ?> ><?= $row['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div> -->
                            <?php
                            $canSelectEmployee = ($userSession['role'] != 3) ||
                                ($userSession['role'] == 3 && array_intersect($userSession['role_name'], [10, 4, 13]));

                            if ($canSelectEmployee): ?>
                                <div class="col-lg-5 mb-3">
                                    <select name="emp_filter " onchange="FilterData()" class="form-control fs-13" id="emp_filter" required>
                                        <?php foreach ($emp_list as $row): ?>
                                            <option value="<?= $row['id']; ?>" <?= ($emp_details->id == $row['id']) ? 'selected' : '' ?>>
                                                <?= h($row['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php else: ?>
                                <div class="col-lg-5 mb-3">
                                    <input type="text" class="form-control" value="<?= h($emp_details->name); ?>" readonly>
                                    <input type="hidden" name="emp_filter" value="<?= h($emp_details->id); ?>">
                                </div>
                            <?php endif; ?>
                            <div class="col-lg-3 mb-3">
                                <form id="filter_form">
                                    <div class="filter_month">
                                        <!-- <label><strong>Filter By Month</strong></label> -->
                                        <select name="month" class="form-control" id="month" onchange="FilterData()">
                                            <option value="01" <?php if ($month == '01') {
                                                                    echo 'selected';
                                                                } ?>>January</option>
                                            <option value="02" <?php if ($month == '02') {
                                                                    echo 'selected';
                                                                } ?>>February</option>
                                            <option value="03" <?php if ($month == '03') {
                                                                    echo 'selected';
                                                                } ?>>March</option>
                                            <option value="04" <?php if ($month == '04') {
                                                                    echo 'selected';
                                                                } ?>>April</option>
                                            <option value="05" <?php if ($month == '05') {
                                                                    echo 'selected';
                                                                } ?>>May</option>
                                            <option value="06" <?php if ($month == '06') {
                                                                    echo 'selected';
                                                                } ?>>June</option>
                                            <option value="07" <?php if ($month == '07') {
                                                                    echo 'selected';
                                                                } ?>>July</option>
                                            <option value="08" <?php if ($month == '08') {
                                                                    echo 'selected';
                                                                } ?>>August</option>
                                            <option value="09" <?php if ($month == '09') {
                                                                    echo 'selected';
                                                                } ?>>September</option>
                                            <option value="10" <?php if ($month == '10') {
                                                                    echo 'selected';
                                                                } ?>>October</option>
                                            <option value="11" <?php if ($month == '11') {
                                                                    echo 'selected';
                                                                } ?>>November</option>
                                            <option value="12" <?php if ($month == '12') {
                                                                    echo 'selected';
                                                                } ?>>December</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-6 mb-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#average_hours_modal">
                                    <div class="card border-0 border_radius card_shadow bg_faebd769">
                                        <div class="card-body">
                                            <div class="card_icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24">
                                                    <g fill="none" stroke="#c481b0" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.077L14 14" />
                                                        <path d="m16.778 5.5l-.082-.368c-.334-1.501-.5-2.252-1.049-2.692C15.1 2 14.33 2 12.791 2H11.21c-1.54 0-2.31 0-2.857.44c-.549.44-.715 1.19-1.05 2.692l-.08.368" />
                                                        <path
                                                            d="m16.778 5.5l-.082-.368c-.334-1.501-.5-2.252-1.049-2.692C15.1 2 14.33 2 12.791 2H11.21c-1.54 0-2.31 0-2.857.44c-.549.44-.715 1.19-1.05 2.692l-.08.368m9.555 13l-.082.368c-.334 1.501-.5 2.252-1.049 2.692c-.548.44-1.318.44-2.856.44H11.21c-1.539 0-2.308 0-2.856-.44c-.549-.44-.715-1.19-1.05-2.692l-.08-.368" />
                                                        <path
                                                            stroke-linecap="round"
                                                            d="M18.961 9.2c-.076-1.535-.304-2.493-.986-3.175C16.95 5 15.3 5 12 5S7.05 5 6.025 6.025C5 7.05 5 8.7 5 12s0 4.95 1.025 5.975C7.05 19 8.7 19 12 19s4.95 0 5.975-1.025c.793-.793.972-1.96 1.013-3.975" />
                                                    </g>
                                                </svg>
                                            </div>
                                            <p class="ss-title">Average Hours in Office</p>
                                            <h4 class="fw-bolder mb-2"><?= $average_time; ?> Hr</h4>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#leave_modal">
                                    <div class="card border-0 border_radius card_shadow bg_f1f5ff">
                                        <div class="card-body">
                                            <div class="card_icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24">
                                                    <path
                                                        fill="none"
                                                        stroke="#c481b0"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 4h3a2 2 0 0 1 2 2v1m-5 13h3a2 2 0 0 0 2-2v-1M4.425 19.428l6 1.8A2 2 0 0 0 13 19.312V4.688a2 2 0 0 0-2.575-1.916l-6 1.8A2 2 0 0 0 3 6.488v11.024a2 2 0 0 0 1.425 1.916M21.001 12h-5m0 0l2-2m-2 2l2 2" />
                                                </svg>
                                            </div>
                                            <p class="ss-title">Leaves</p>
                                            <h4 class="fw-bolder mb-2"><?= $total_lv; ?></h4>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#wfh_modal">
                                    <div class="card border-0 border_radius card_shadow bg_f5f6fa">
                                        <div class="card-body">
                                            <div class="card_icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 48 48">
                                                    <g fill="#c481b0" fill-rule="evenodd" clip-rule="evenodd">
                                                        <path
                                                            d="M24 16a2 2 0 1 0 0 4a2 2 0 0 0 0-4m-4 2a4 4 0 1 1 8 0a4 4 0 0 1-8 0m-.932 7.95A7.156 7.156 0 0 1 24 24c1.841 0 3.616.696 4.932 1.95C30.25 27.205 31 28.917 31 30.714v3.429a1 1 0 0 1-1 1h-1.56l-1.055 6.03A1 1 0 0 1 26.4 42h-4.8a1 1 0 0 1-.985-.828l-1.055-6.03H18a1 1 0 0 1-1-1v-3.428c0-1.797.75-3.51 2.068-4.764M24 26a5.156 5.156 0 0 0-3.553 1.398A4.577 4.577 0 0 0 19 30.714v2.429h1.4a1 1 0 0 1 .985.828L22.44 40h3.12l1.055-6.03a1 1 0 0 1 .985-.827H29v-2.429a4.577 4.577 0 0 0-1.447-3.316A5.156 5.156 0 0 0 24 26" />
                                                        <path
                                                            d="M23.398 6.202a1 1 0 0 1 1.204 0l16.43 12.39c.028.021.054.043.079.066A2.792 2.792 0 0 1 42 20.6v18.925A2.482 2.482 0 0 1 39.525 42H8.475A2.482 2.482 0 0 1 6 39.525V20.6c0-.02 0-.04.002-.059c.042-.719.36-1.393.887-1.883a.993.993 0 0 1 .079-.066zM8.22 20.15a.792.792 0 0 0-.221.483V39.52a.482.482 0 0 0 .48.48h31.04c-.001 0 0 0 0 0a.484.484 0 0 0 .48-.48V20.635a.792.792 0 0 0-.22-.483L24 8.253z" />
                                                    </g>
                                                </svg>
                                            </div>
                                            <p class="ss-title">WFH</p>
                                            <h4 class="fw-bolder mb-2"><?= $total_wfh; ?></h4>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#avg_leave_plan_modal">
                                    <div class="card border-0 border_radius card_shadow bg_fef8fc">
                                        <div class="card-body">
                                            <div class="card_icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 16 16">
                                                    <path
                                                        fill="#c481b0"
                                                        d="M11.5 8.2c-.3-.1-.6-.2-.8-.2H8V7h3c0-.6-.4-1-1-1H6c0 .6.4 1 1 1v1c-.5 0-1-.2-1.2-.6L4.7 5.6C4.4 5.2 4 5 3.6 5H3v-.7c0-.3-.1-.5-.2-.8l-.3-.7C2.2 2.3 1.6 2 1 2H0l5 7c.4.6 1.1 1 1.8 1H8v1H7v2h-.6c-.9 0-1.8.4-2.4 1H3v1h11v-1h-1c-.6-.6-1.5-1-2.4-1H10v-2H9v-1h1.6c.2 0 .5.1.7.2l1.7.9c.9.5 2 .5 2.9 0h.1z" />
                                                </svg>
                                            </div>
                                            <p class="ss-title">Average Leave Plan time</p>
                                            <h4 class="fw-bolder mb-2"><?= number_format($total_average_leave_plan) ?></h4>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#late_entry">
                                    <div class="card border-0 border_radius card_shadow bg_cddbfa9e">
                                        <div class="card-body">
                                            <div class="card_icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 16 16">
                                                    <path
                                                        fill="#c481b0"
                                                        fill-rule="evenodd"
                                                        d="M6.229.199a8 8 0 0 1 9.727 6.964a.75.75 0 0 1-1.492.157a6.5 6.5 0 1 0-7.132 7.146a.75.75 0 1 1-.154 1.492a8 8 0 0 1-.95-15.76ZM8 3a.75.75 0 0 1 .75.75V9h-4a.75.75 0 0 1 0-1.5h2.5V3.75A.75.75 0 0 1 8 3m2.22 7.22a.75.75 0 0 1 1.06 0L13 11.94l1.72-1.72a.75.75 0 1 1 1.06 1.06L14.06 13l1.72 1.72a.75.75 0 1 1-1.06 1.06L13 14.06l-1.72 1.72a.75.75 0 1 1-1.06-1.06L11.94 13l-1.72-1.72a.75.75 0 0 1 0-1.06"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <p class="ss-title">Late Entries</p>
                                            <h4 class="fw-bolder mb-2"><?= $late_entries; ?></h4>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#early_exits">
                                    <div class="card border-0 border_radius card_shadow bg_f9e2ca8a">
                                        <div class="card-body">
                                            <div class="card_icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 14 14">
                                                    <g fill="none" stroke="#c481b0" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M5.214 1.643c0-.493.4-.893.893-.893h6.25c.493 0 .893.4.893.893v10.714c0 .493-.4.893-.893.893H9.232" />
                                                        <path d="M6.553 5.438a1.563 1.563 0 1 0 3.126 0a1.563 1.563 0 1 0-3.126 0m-4.017.669h1.818a1 1 0 0 1 .707.293L7.6 8.94a1 1 0 0 0 .707.292h1.371" />
                                                        <path d="M6.107 7.446L3.721 9.832a1 1 0 0 1-.707.293H.75" />
                                                        <path d="m4.321 9.232l1.493 1.493a1 1 0 0 1 .293.707v1.818" />
                                                    </g>
                                                </svg>
                                            </div>
                                            <p class="ss-title">Early Exits</p>
                                            <h4 class="fw-bolder mb-2"><?= $early_exits; ?></h4>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive scroll">
                            <table class="table fs-11">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Check In</th>
                                        <th>Check Out</th>
                                        <th>Working Hours</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <!-- <tbody>
                                    <?php
                                    if (count($emp_attendence_list) > 0) :
                                        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

                                        for ($d = 1; $d <= $daysInMonth; $d++) {
                                            $time = mktime(12, 0, 0, $month, $d, $year);
                                            $currentDate = date('Y-m-d', $time);
                                            $currentDayOfWeek = date('N', $time);
                                    ?>
                                            <tr class="active">
                                                <td><?= date('M d, Y', strtotime($currentDate)) ?></td>
                                                <?php
                                                $found = false;
                                                foreach ($emp_attendence_list as $emp) {
                                                    if ($emp['date'] == $currentDate) {
                                                        $found = true;
                                                ?>

                                                        <td><?= date('g:i A', strtotime($emp['intime'])) ?></td>
                                                        <td><?= date('g:i A', strtotime($emp['outtime'])) ?></td>
                                                        <td>
                                                            <?php
                                                            if ($emp['total_time'] == '') {
                                                                echo '';
                                                            } else {
                                                                echo date('H:i', strtotime($emp['total_time']));
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if ($emp['status'] == 'Present') {
                                                                echo '<div class="fw-semibold d-flex align-items-center"><span class="p-1 bg-success rounded-circle"></span><span class="ms-1 ml-1 text-success">Present</span></div>';
                                                            } else {
                                                                echo '<div class="fw-semibold d-flex align-items-center"><span class="p-1 bg-danger rounded-circle"></span><span class="ms-1 ml-1 text-danger">' . $emp["status"] . '</span></div>';
                                                            }
                                                            ?>
                                                        </td>
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

                                                            <td>Not Recorded</td>
                                                            <td>Not Recorded</td>
                                                            <td>Not Recorded</td>
                                                            <td>
                                                                <div class="fw-semibold d-flex align-items-center"><span class="p-1 bg-warning rounded-circle"></span><span class="ms-1 ml-1 text-warning"><?= $leave['leave_type'] ?></span></div>
                                                            </td>
                                                        <?php
                                                        }
                                                    } elseif ($currentDayOfWeek == 6 || $currentDayOfWeek == 7) { ?>



                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td style="color: #ff6600;">
                                                            <div class="fw-semibold d-flex align-items-center"><span class="p-1 bg-secondary rounded-circle"></span><span class="ms-1 ml-1 text-secondary">Holiday</span></div>
                                                        </td>

                                                    <?php } else {
                                                    ?>

                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td style="color: #ff6600;">
                                                            <div class="fw-semibold d-flex align-items-center"><span class="p-1 bg-danger rounded-circle"></span><span class="ms-1 ml-1 text-danger">Absent</span></div>
                                                        </td>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </tr>
                                    <?php
                                        }
                                    endif;
                                    ?>
                                </tbody> -->
                                 <tbody>
                                    <?php
                                    if (count($emp_attendence_list) > 0) :
                                        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

                                        for ($d = 1; $d <= $daysInMonth; $d++) {
                                            $time = mktime(12, 0, 0, $month, $d, $year);
                                            $currentDate = date('Y-m-d', $time);
                                            $currentDayOfWeek = date('N', $time);
                                    ?>
                                            <tr class="active">
                                                <td><?= date('M d, Y', strtotime($currentDate)) ?></td>
                                                <?php
                                                $found = false;
                                                foreach ($emp_attendence_list as $emp) {
                                                    if ($emp['date'] == $currentDate) {
                                                        $found = true;
                                                ?>
                                                        <td><?= date('g:i A', strtotime($emp['intime'])) ?></td>
                                                        <td><?= date('g:i A', strtotime($emp['outtime'])) ?></td>
                                                        <td>
                                                            <?php
                                                            if ($emp['total_time'] == '') {
                                                                echo '';
                                                            } else {
                                                                echo date('H:i', strtotime($emp['total_time']));
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if ($emp['status'] == 'Present') {
                                                                echo '<div class="fw-semibold d-flex align-items-center"><span class="p-1 bg-success rounded-circle"></span><span class="ms-1 ml-1 text-success">Present</span></div>';
                                                            } else {
                                                                echo '<div class="fw-semibold d-flex align-items-center"><span class="p-1 bg-danger rounded-circle"></span><span class="ms-1 ml-1 text-danger">' . $emp["status"] . '</span></div>';
                                                            }
                                                            ?>
                                                        </td>
                                                    <?php
                                                    }
                                                }

                                                // If no attendance found
                                                if (!$found) {
                                                    $leaveData = [];
                                                    foreach ($leaves as $leave) {
                                                        if ($currentDate >= $leave['from_date'] && $currentDate <= $leave['to_date']) {
                                                            $leaveData[] = $leave;
                                                        }
                                                    }

                                                    if (count($leaveData) > 0) {
                                                        foreach ($leaveData as $leave) { ?>
                                                            <td>Not Recorded</td>
                                                            <td>Not Recorded</td>
                                                            <td>Not Recorded</td>
                                                            <td>
                                                                <div class="fw-semibold d-flex align-items-center">
                                                                    <span class="p-1 bg-warning rounded-circle"></span>
                                                                    <span class="ms-1 ml-1 text-warning"><?= $leave['leave_type'] ?></span>
                                                                </div>
                                                            </td>
                                                        <?php }
                                                    } else {
                                                        // Holiday check from holidays table
                                                        $holidayFound = false;
                                                        foreach ($holidays as $holiday) {
                                                            if ($currentDate == $holiday['start']) {
                                                                $holidayFound = true; ?>
                                                                <!-- <td></td>
                                                                <td></td>
                                                                <td></td> -->
                                                                <td colspan="4">
                                                                    <div class="fw-semibold d-flex align-items-center justify-center ">
                                                                        <span class="p-1 bg-info rounded-circle"></span>
                                                                        <span class="ms-1 ml-1 text-info"><?= $holiday['title'] ?></span>
                                                                    </div>
                                                                </td>
                                                            <?php }
                                                        }

                                                        if (!$holidayFound) {
                                                            if ($currentDayOfWeek == 6 || $currentDayOfWeek == 7) {
                                                                 $dayName = ($currentDayOfWeek == 6) ? 'Saturday' : 'Sunday';
                                                                 ?>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>
                                                                    <div class="fw-semibold d-flex align-items-center">
                                                                        <span class="p-1 bg-secondary rounded-circle"></span>
                                                                        <span class="ms-1 ml-1 text-secondary"><?= $dayName ?></span>
                                                                        <!-- <span class="ms-1 ml-1 text-secondary">Holiday</span> -->
                                                                    </div>
                                                                </td>
                                                            <?php } else { ?>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>
                                                                    <div class="fw-semibold d-flex align-items-center">
                                                                        <span class="p-1 bg-danger rounded-circle"></span>
                                                                        <span class="ms-1 ml-1 text-danger">Absent</span>
                                                                    </div>
                                                                </td>
                                                            <?php }
                                                        }
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
    </div>
</div>

<!-- Working Hours modal start -->
<div class="modal fade" id="average_hours_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Average Working Hours</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="Working_hours_table">
                        <thead class="table-dark">
                            <tr>
                                <th class="fw_500">#</th>
                                <th class="fw_500">Date</th>
                                <th class="fw_500">Working Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($emp_attendence_list as $att) {
                                if (!empty($att['total_time'])) {
                                    echo '<tr>';
                                    echo '<td>' . $i++ . '</td>';
                                    echo '<td>' . date('M d, Y', strtotime($att['date'])) . '</td>';
                                    echo '<td>' . date('H:i', strtotime($att['total_time'])) . '</td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Working Hours modal end -->

<!-- Leaves modal start -->
<div class="modal fade" id="leave_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Leaves</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="leave_table">
                        <thead class="table-dark">
                            <tr>
                                <th class="fw_500">#</th>
                                <th class="fw_500">Date</th>
                                <th class="fw_500">Leave Type</th>
                            </tr>
                        </thead>
                        <!-- <tbody>
                            <?php if (!empty($leave_details_list)): ?>
                                <?php foreach ($leave_details_list as $lv): ?>
                                    <tr>
                                        <td><?= $lv['count'] ?></td>
                                        <td><?= date('M d, Y', strtotime($lv['date'])) ?></td>
                                        <td><?= $lv['leave_type'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">No Leaves Found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody> -->

                          <tbody>
                            <?php if (!empty($total_leave)): ?>
                                <?php
                                $count = 1;
                                foreach ($total_leave as $lv):
                                    $from = new DateTime($lv['from_date']->format('Y-m-d'));
                                    $to = new DateTime($lv['to_date']->format('Y-m-d'));

                                    // loop through each day
                                    for ($date = $from; $date <= $to; $date->modify('+1 day')):
                                ?>
                                        <tr>
                                            <td><?= $count++ ?></td>
                                            <td><?= $date->format('M d, Y') ?></td>
                                            <td><?= h($lv['leave_type']) ?></td>
                                        </tr>
                                    <?php endfor; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">No Leaves Found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Leaves modal end -->


<!-- Average leave plan modal start -->
<div class="modal fade" id="avg_leave_plan_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Average Leave Plan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="avg_leave_plan_table">
                        <thead class="table-dark">
                            <tr>
                                <th class="fw_500">#</th>
                                <th class="fw_500">Applied On</th>
                                <th class="fw_500">Leave From</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($average_leave_plan)): ?>
                                 <?php $count = 1; ?>
                                <?php 
                                    foreach ($average_leave_plan as $leave): ?>
                                    <tr>
                                         <td><?= $count++ ?></td>
                                        <td><?= date('M d, Y', strtotime($leave['applied_on'])) ?></td>
                                        <td><?= date('M d, Y', strtotime($leave['from_date'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">No leave plan data found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Average leave plan modal end -->


<!-- WFH modal start -->
<div class="modal fade" id="wfh_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">WFH Status</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="wfh_table">
                        <thead class="table-dark">
                            <tr>
                                <th class="fw_500">#</th>
                                <th class="fw_500">Date</th>
                                <th class="fw_500">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($wfh_dates)) : ?>
                                <?php foreach ($wfh_dates as $index => $date) : ?>
                                    <tr>
                                        <td><?= $index + 1; ?></td>
                                        <td><?= date('l, M d, Y', strtotime($date)); ?></td>
                                        <td>
                                            <span class="ms-1 ml-1 text-warning">WFH</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="3" class="text-center">No WFH records found</td>
                                </tr>
                            <?php endif; ?>

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- WFH modal end -->

<!-- early exists modal start -->
<div class="modal fade" id="early_exits" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-14 fw-semibold">Early Exits</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="early_exits_table">
                        <thead class="table-dark">
                            <tr>
                                <th class="fw_500">#</th>
                                <th class="fw_500">Date</th>
                                <th class="fw_500">In Time</th>
                                <th class="fw_500">Out Time</th>
                                <th class="fw_500">Early Exit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($early_exits_list)): ?>
                                <?php foreach ($early_exits_list as $exit): ?>
                                    <tr>
                                        <td><?= $exit['count'] ?></td>
                                        <td><?= date('M d, Y', strtotime($exit['date'])) ?></td>
                                        <td><?= date('g:i A', strtotime($exit['intime'])) ?></td>
                                        <td><?= date('g:i A', strtotime($exit['outtime'])) ?></td>
                                        <td><span class="badge bg-danger text-dark"><?= $exit['difference'] ?> early</span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No Early Exits</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- early exists modal end -->


<!-- late entry modal start -->
<div class="modal fade" id="late_entry" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Late Entry</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="late_entry_table">
                        <thead class="table-dark">
                            <tr>
                                <th class="fw_500">#</th>
                                <th class="fw_500">Date</th>
                                <th class="fw_500">In Time</th>
                                <th class="fw_500">Out Time</th>
                                <th class="fw_500">Late Entry</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($late_entries_list)): ?>
                                <?php foreach ($late_entries_list as $entry): ?>
                                    <tr>
                                        <td><?= $entry['count'] ?></td>
                                        <td><?= $entry['date'] ?></td>
                                        <td><?= date('g:i A', strtotime($entry['intime'])) ?></td>
                                        <td><?= date('g:i A', strtotime($entry['outtime'])) ?></td>
                                        <td><span class="badge bg-danger text-light"><?= $entry['difference'] ?> late</span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No Late Entries</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- late entry modal end -->


<!-- Projects assigned modal start -->
<div class="modal fade" id="projects_assigned_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Assigned Projects</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="projects_assigned_table">
                        <thead class="table-dark">
                            <tr>
                                <th class="fw_500">#</th>
                                <th class="fw_500">Project Name</th>
                                <th class="fw_500">Client</th>
                                <th class="fw_500">Project Manager</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($total_project_assigned)): ?>
                                <?php $index = 1; ?>
                                <?php foreach ($total_project_assigned as $project): ?>
                                    <?php if ($project['bill'] == 'Billable'): ?>
                                        <tr>
                                            <td><?= $index++ ?></td>
                                            <td><?= h($project['project_name']) ?></td>
                                            <td><?= h($project['client_name']) ?></td>
                                            <td><?= h($project['project_manager']) ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                                <?php if ($index == 1): ?>
                                    <tr>
                                        <td colspan="6">No billable projects found.</td>
                                    </tr>
                                <?php endif; ?>

                            <?php else: ?>
                                <tr>
                                    <td colspan="6">No projects assigned.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Projects assigned modal end -->


<!-- Internal Projects not billable assigned modal start -->
<div class="modal fade" id="internal_project_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Internal Assigned Projects</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="internal_projects_table">
                        <thead class="table-dark">
                            <tr>
                                <th class="fw_500">#</th>
                                <th class="fw_500">Project Name</th>
                                <th class="fw_500">Client</th>
                                <th class="fw_500">Project Manager</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($total_project_assigned)): ?>
                                <?php $index = 1; ?>
                                <?php foreach ($total_project_assigned as $project): ?>
                                    <?php if ($project['bill'] == 'Non Billable'): ?>
                                        <tr>
                                            <td><?= $index++ ?></td>
                                            <td><?= h($project['project_name']) ?></td>
                                            <td><?= h($project['client_name']) ?></td>
                                            <td><?= h($project['project_manager']) ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                                <?php if ($index == 1): ?>
                                    <tr>
                                        <td colspan="6">No billable projects found.</td>
                                    </tr>
                                <?php endif; ?>

                            <?php else: ?>
                                <tr>
                                    <td colspan="6">No projects assigned.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Projects assigned modal end -->

<!-- Timesheet Modal start -->
<div class="modal fade" id="timesheetModal" tabindex="-1" aria-labelledby="timesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Timesheet Records</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="time_sheet_table">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Project</th>
                                <th>Work Date</th>
                                <th>Time Used (hrs)</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($time_used)): ?>
                                <?php $i = 1;
                                foreach ($time_used as $t): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= h($t['project_name']) ?></td>
                                        <td><?= date('M d, Y', strtotime($t['work_date'])) ?></td>
                                        <td><?= h($t['time_used']) ?></td>
                                        <td><?= h($t['bill']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">No timesheet records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Timesheet Modal end -->


<!-- Allocated Hours Modal start -->
<div class="modal fade" id="allocatedhoursModal" tabindex="-1" aria-labelledby="timesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Allocated Hours</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="allocated_hours_table">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Project Name</th>
                                <th>Allocated Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($projects as $project): ?>
                                <?php if ($project['bill'] === 'Billable' && $project['time_slot'] > 0): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= h($project['project_name']) ?></td>
                                        <td><?= h($project['time_slot']) ?> hrs</td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Allocated Hours Modal end -->

<!-- Hours filled Modal start -->
<div class="modal fade" id="hoursfilledModal" tabindex="-1" aria-labelledby="timesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Hours Filled</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="hours_filled_table">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Project Name</th>
                                <th>Hours Filled</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($projects as $project): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= h($project['project_name']) ?></td>
                                    <td><?= h($project['time_used']) ?> hrs</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Hours filled Modal end -->


<!--Billable Hours Modal start -->
<div class="modal fade" id="billablehoursModal" tabindex="-1" aria-labelledby="timesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Billable Hours</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="billable_hours_table">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Project Name</th>
                                <th>Hours Filled on Billable Project</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($projects as $project): ?>
                                <?php if ($project['bill'] === 'Billable' && $project['time_used'] > 0): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= h($project['project_name']) ?></td>
                                        <td><?= h($project['time_used']) ?> hrs</td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Billable Hours Modal end -->


<!--Milestones Modal start -->
<div class="modal fade" id="milestonesModal" tabindex="-1" aria-labelledby="timesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Milestones Assigned</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped fs-11" id="milestones_assigned_table">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Project Name</th>
                                <th>Milestone</th>
                                <th>Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php if (!empty($melist1)): ?>
                            <?php foreach ($melist1 as $mlist): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= h($mlist['project_name']) ?></td>
                                    <td><?= h($mlist['title']) ?></td>
                                    <td><?= h($mlist['due_date']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No Milestones Exits</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Milestones Modal end -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- tooltip -->
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map((tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl));
</script>
<!-- tooltip -->

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<!-- <script>
    var options = {
        series: [{
                // name: 'Website Blog',
                type: "column",
                data: [440, 505, 414, 671, 227, 413],
            },
            {
                // name: 'Social Media',
                type: "line",
                data: [440, 510, 410, 677, 230, 410],
            },
        ],
        chart: {
            height: 220,
            type: "line",
            parentHeightOffset: 0,
            toolbar: {
                show: false,
            },
        },
        colors: ["#3fd5db", "#817FCD"],
        plotOptions: {
            bar: {
                colors: {
                    ranges: [{
                            from: 0,
                            to: 450,
                            color: "#5cdbe0",
                        },
                        {
                            from: 451,
                            to: 500,
                            color: "#F9F871",
                        },
                        {
                            from: 501,
                            to: 800,
                            color: "#333333",
                        },
                    ],
                },
                borderRadius: 0,
                columnWidth: "30%",
                horizontal: false,
                barHeight: "90%",
                distributed: false,
                rangeBarOverlap: false,
            },
        },
        stroke: {
            width: [0, 4],
            curve: "smooth",
            lineCap: "butt",
            dashArray: 0,
        },
        // title: {
        //     text: 'Traffic Sources'
        // },
        dataLabels: {
            enabled: true,
            enabledOnSeries: [1],
        },
        grid: {
            show: false,
        },
        labels: ["01 Jan 2001", "02 Jan 2001", "03 Jan 2001", "04 Jan 2001", "05 Jan 2001", "06 Jan 2001"],
        xaxis: {
            type: "datetime",
        },
        yaxis: [{
                title: {
                    // text: 'Website Blog'
                },
            },
            {
                opposite: true,
                title: {
                    // text: 'Social Media'
                },
            },
        ],
    };

    var chart = new ApexCharts(document.querySelector("#working_statistics"), options);
    chart.render();
</script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $(document).ready(function($) {
        $("#emp_filter").select2({
            // placeholder: "Select Option",
        });
        $("#month").select2({
            // placeholder: "Select Option",
        });
    });

    function FilterData() {
        $("#loader").show();
        var emp = $("#emp_filter").val();
        var month = $("#month").val();
        $.ajax({

            url: "<?= $this->Url->build(['controller' => 'ScoreCard', 'action' => 'index']) ?>/" + emp + "/" + month,
            method: "get",

            success: function(resp) {
                window.location.href = "<?= WEBURL ?>score-card/" + emp + "/" + month;
                // $("#loader").hide();
                // $('.app-content').removeClass('app-content');
                // $("#resdata").html(resp);
                // console.log(resp);	 	
            },
        });
    }
</script>

<!-- for Datatables  -->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<!-- for datatables -->
<!-- datatable -->
<script>
    $(document).ready(function() {
        $('#Working_hours_table').DataTable();
        $('#allocated_hours_table').DataTable();
        $('#hours_filled_table').DataTable();
        $('#billable_hours_table').DataTable();
        $('#avg_leave_plan_table').DataTable();
        $('#projects_assigned_table').DataTable();
        $('#time_sheet_table').DataTable();
        $('#internal_projects_table').DataTable();
        $('#milestones_assigned_table').DataTable();
        $('#early_exits_table').DataTable();
        $('#late_entry_table').DataTable();
        $('#wfh_table').DataTable();
        $('#leave_table').DataTable();
        // End 
    });
</script>