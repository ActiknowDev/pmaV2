<?php $session = new \Cake\Http\Session();
$userSession = $session->read('data');
$role = $userSession['role'];
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<?php echo $this->Html->css('dashboard.css'); ?>
<!-- for Datatables  -->

<section class="page page-dashboard">
    <!-- PAGE-CONTENT -->
    <div class="page-content">
        <div class="container">
        <div class="my-3">
            <div class="card border-0">
                <div class="card-body pb-0">
                    <h6 class="fw-semibold mb-3">Revenue</h6>
                    <div class="row d-flex align-items-center">
                        <div class="col-lg-2 col-md-3 col-6 mb-3">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#current_month_revenue">
                                <div class="card border-0 border_radius card_shadow bg_faebd769">
                                    <div class="card-body">
                                        <div class="card_icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                viewBox="0 0 256 256">
                                                <path fill="#c48ab0"
                                                    d="M224.56 103.81c-11.13-6.06-26.09-10.42-42.56-12.47V84c0-12.12-9.58-23.1-27-30.93C139.16 45.93 118.2 42 96 42s-43.16 3.93-59 11.07C19.58 60.9 10 71.88 10 84v40c0 12.12 9.58 23.1 27 30.93c10.49 4.72 23.21 8 37 9.73V172c0 12.12 9.58 23.1 27 30.93c15.84 7.14 36.8 11.07 59 11.07s43.16-3.93 59-11.07c17.39-7.83 27-18.81 27-30.93v-40c0-10.65-7.61-20.66-21.44-28.19m-5.74 10.54C228.61 119.68 234 126 234 132c0 14.19-30.39 30-74 30a166.9 166.9 0 0 1-21.21-1.34a110.79 110.79 0 0 0 16.21-5.73c17.39-7.83 27-18.81 27-30.93v-20.57c14.4 1.93 27.3 5.73 36.82 10.92m-110.66 39.23c-3.92.27-8 .42-12.16.42c-5.3 0-10.4-.24-15.28-.67a2.22 2.22 0 0 0-.37 0c-3.58-.33-7-.77-10.35-1.3v-27.91A178 178 0 0 0 96 126a178 178 0 0 0 26-1.88V152c-4.34.69-8.91 1.22-13.69 1.56ZM170 105.89V124c0 9.54-13.75 19.8-36 25.51v-27.66a115 115 0 0 0 21-6.92a66.2 66.2 0 0 0 15-9.04M96 54c43.61 0 74 15.81 74 30s-30.39 30-74 30s-74-15.81-74-30s30.39-30 74-30m-74 70v-18.11a66.2 66.2 0 0 0 15 9a115 115 0 0 0 21 6.92v27.66C35.75 143.8 22 133.54 22 124m64 48v-6.28c3.3.18 6.63.28 10 .28q5.91 0 11.66-.37a123.17 123.17 0 0 0 14.34 4.21v27.67C99.75 191.8 86 181.54 86 172m48 28v-27.9a177.84 177.84 0 0 0 26 1.9a178 178 0 0 0 26-1.88V200a170 170 0 0 1-52 0m64-2.49v-27.66a115 115 0 0 0 21-6.92a66.2 66.2 0 0 0 15-9V172c0 9.54-13.75 19.8-36 25.51" />
                                            </svg>
                                        </div>

                                        <p class="ss-title">Current Month Revenue (vs Last Month)</p>
                                        <h4 class="fw-bolder mb-2">$<?= number_format($current_month_revenue->amount) ?></h4>
                                        <p class="fs-11 mb-1">Last Month <span class="fw-semibold">$<?= number_format($last_month_revenue->amount) ?></span>
                                        </p>

                                        <?php 
                                            if($current_month_revenue > $last_month_revenue)
                                            {
                                                $icon = '+';
                                                $class = 'fs_success';
                                                $tag = 'Increase';
                                            }
                                            else
                                            {
                                                $icon = '';
                                                $class = 'fs_danger';
                                                $tag = 'Decrease';
                                            }
                                            $percent = (!empty($last_month_revenue->amount))
                                                ? (($current_month_revenue->amount - $last_month_revenue->amount) / $last_month_revenue->amount) * 100
                                                : 0;
                                            // $percent = (($current_month_revenue->amount - $last_month_revenue->amount) / $last_month_revenue->amount) * 100;
                                        ?>   

                                        <span class="<?= $class ?> fs-10 fw_500">
                                        <?= ($icon.round($percent, 2). '% ' .$tag) ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-3 col-6 mb-3">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#ytd_revenue_list">
                                <div class="card border-0 border_radius card_shadow bg_f1f5ff">
                                    <div class="card-body">
                                        <div class="card_icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                viewBox="0 0 24 24">
                                                <path fill="none" stroke="#c48ab0" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M13.6 16.733c.234.269.548.456.895.534a1.4 1.4 0 0 0 1.75-.762c.172-.615-.446-1.287-1.242-1.481c-.796-.194-1.41-.861-1.241-1.481a1.4 1.4 0 0 1 1.75-.762c.343.077.654.26.888.524m-1.358 4.017v.617m0-5.939v.725M4 15v4m3-6v6M6 8.5L10.5 5L14 7.5L18 4m0 0h-3.5M18 4v3m2 8a5 5 0 1 1-10 0a5 5 0 0 1 10 0" />
                                            </svg>
                                        </div>
                                        <p class="ss-title">YTD Revenue (vs Last Year)</p>
                                        <h4 class="fw-bolder mb-2">$<?= number_format($ytd_revenue->amount) ?></h4>
                                        <p class="fs-11 mb-1">Last Year <span class="fw-semibold">$<?= number_format($last_ytd_revenue->amount) ?></span>
                                        </p>
                                        <?php 
                                            if($ytd_revenue > $last_ytd_revenue)
                                            {
                                                $icon = '+';
                                                $class = 'fs_success';
                                                $tag = 'Increase';
                                            }
                                            else
                                            {
                                                $icon = '';
                                                $class = 'fs_danger';
                                                $tag = 'Decrease';
                                            }
                                            $percent = (($ytd_revenue->amount - $last_ytd_revenue->amount) / $last_ytd_revenue->amount) * 100;
                                        ?>   

                                        <span class="<?= $class ?> fs-10 fw_500">
                                        <?= ($icon.round($percent, 2). '% ' .$tag) ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-3 col-6 mb-3">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#active_opps_updated">
                                <div class="card border-0 border_radius card_shadow bg_cddbfa9e">
                                    <div class="card-body">
                                        <div class="card_icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                viewBox="0 0 32 32">
                                                <path fill="#c481b0"
                                                    d="M10.516 18a6.428 6.428 0 0 0-1.817.266l-.02.007l-5.671 2.176l1.984 5.57l4.93-1.89l7.137 3.93l12.324-5.106l-.766-1.844l-11.437 4.735l-7.102-3.91l-3.89 1.488l-.641-1.807l3.797-1.457A4.023 4.023 0 0 1 10.516 20c.703 0 1.522.156 2.222.79l.014.007l.004.004c1.03.895 1.808 1.52 2.89 1.86c1.082.34 2.31.378 4.36.37l-.01-2c-2.012.008-3.063-.063-3.75-.281c-.688-.211-1.176-.59-2.168-1.45l-.012-.007c-1.157-1.039-2.531-1.297-3.55-1.293" />
                                            </svg>
                                        </div>
                                        <p class="ss-title">Active opps updated > & days Ago</p>
                                        <h4 class="fw-bolder mb-2 d-flex"><?= $opportunity->count ?>
                                        <p>  > <?= ($daysAgo >= 0 ? $daysAgo . " Days ago" : abs($daysAgo) . " Days"); ?></p></h4>
                                        <p class="fs-11 mb-1">Last Year <span class="fw-semibold"><?= $last_opportunity->count ?></span></p>
                                        <?php 
                                            if($opportunity->count > $last_opportunity->count)
                                            {
                                                $icon = '+';
                                                $class = 'fs_success';
                                                $tag = 'Increase';
                                            }
                                            else
                                            {
                                                $icon = '';
                                                $class = 'fs_danger';
                                                $tag = 'Decrease';
                                            }
                                            $percent = (($opportunity->count -$last_opportunity->count) / $last_opportunity->count) * 100;
                                        ?>   

                                        <span class="<?= $class ?> fs-10 fw_500">
                                        <?= ($icon.round($percent, 2). '% ' .$tag) ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-2 col-md-3 col-6 mb-3">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#new_project_list">
                                <div class="card border-0 border_radius card_shadow bg_c2f4e68f">
                                    <div class="card-body">
                                        <div class="card_icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                viewBox="0 0 16 16">
                                                <path fill="#c481b0"
                                                    d="M12 15H2V1h6v4h4v1h1V4L9 0H1v16h12v-2h-1zM9 1l3 3H9z" />
                                                <path fill="#c481b0" d="M13 7h-2v2H9v2h2v2h2v-2h2V9h-2z" />
                                            </svg>
                                        </div>
                                        <p class="ss-title">New Projects</p>
                                        <h4 class="fw-bolder mb-2"><?= count($current_year_project) ?></h4>
                                        <p class="fs-11 mb-1">Last Year <span class="fw-semibold"><?= ($last_year_project) ?></span></p>
                                        <?php 
                                            if(count($current_year_project) > $last_year_project)
                                            {
                                                $icon = '+';
                                                $class = 'fs_success';
                                                $tag = 'Increase';
                                            }
                                            else
                                            {
                                                $icon = '';
                                                $class = 'fs_danger';
                                                $tag = 'Decrease';
                                            }
                                            $percent = ((count($current_year_project) - $last_year_project) / $last_year_project) * 100;
                                        ?>   

                                        <span class="<?= $class ?> fs-10 fw_500">
                                        <?= ($icon.round($percent, 2). '% ' .$tag) ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                       
                        <div class="col-lg-2 col-md-3 col-6 mb-3">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#client_list">
                                <div class="card border-0 border_radius card_shadow bg_f5f6fa">
                                    <div class="card-body">
                                        <div class="card_icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                viewBox="0 0 24 24">
                                                <path fill="#c48ab0"
                                                    d="M16.5 2a5.5 5.5 0 0 0-5.348 6.789L2.841 17.1a2.871 2.871 0 1 0 4.06 4.06l4.115-4.113a6.47 6.47 0 0 1 1.172-3.293L5.84 20.1a1.371 1.371 0 0 1-1.94-1.94l8.624-8.622a.75.75 0 0 0 .18-.768a4 4 0 0 1 4.213-5.248l-1.844 1.844a1.25 1.25 0 0 0 0 1.768l1.793 1.793a1.25 1.25 0 0 0 1.767 0l1.845-1.845q.021.207.021.418a4 4 0 0 1-2.162 3.554a6.5 6.5 0 0 1 1.85.526A5.5 5.5 0 0 0 22 7.5c0-.767-.157-1.498-.442-2.163a.75.75 0 0 0-1.22-.236L17.75 7.69l-1.439-1.44L18.9 3.662a.75.75 0 0 0-.235-1.22A5.5 5.5 0 0 0 16.5 2m-2.223 11.976a2 2 0 0 1-1.441 2.496l-.584.144a5.7 5.7 0 0 0 .006 1.808l.54.13a2 2 0 0 1 1.45 2.51l-.187.631c.44.386.94.699 1.485.922l.493-.519a2 2 0 0 1 2.899 0l.499.525a5.3 5.3 0 0 0 1.482-.913l-.198-.686a2 2 0 0 1 1.442-2.496l.583-.144a5.7 5.7 0 0 0-.006-1.808l-.54-.13a2 2 0 0 1-1.449-2.51l.186-.63a5.3 5.3 0 0 0-1.484-.922l-.493.518a2 2 0 0 1-2.9 0l-.498-.525c-.544.22-1.044.53-1.483.912zM17.5 19c-.8 0-1.45-.672-1.45-1.5S16.7 16 17.5 16s1.45.672 1.45 1.5S18.3 19 17.5 19" />
                                            </svg>
                                        </div>
                                        <p class="ss-title">Maintenance ARR</p>
                                        <h4 class="fw-bolder mb-2">$<?= number_format($current_maintainace_plan->amount) ?></h4>
                                        <p class="fs-11 mb-1">Last Year <span class="fw-semibold">$<?= number_format($last_maintainace_plan->amount) ?></span>
                                        </p>
                                        <?php 
                                            if($current_maintainace_plan->amount > $last_maintainace_plan->amount)
                                            {
                                                $icon = '+';
                                                $class = 'fs_success';
                                                $tag = 'Increase';
                                            }
                                            else
                                            {
                                                $icon = '';
                                                $class = 'fs_danger';
                                                $tag = 'Decrease';
                                            }
                                            $percent = (($current_maintainace_plan->amount - $last_maintainace_plan->amount) / $last_maintainace_plan->amount) * 100;
                                        ?>   

                                        <span class="<?= $class ?> fs-10 fw_500">
                                        <?= ($icon.round($percent, 2). '% ' .$tag) ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-3 col-6 mb-3">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#client_list">
                                <div class="card border-0 border_radius card_shadow bg_fef8fc">
                                    <div class="card-body">
                                        <div class="card_icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                viewBox="0 0 32 32">
                                                <path fill="#c48ab0"
                                                    d="M16 5c-3.9 0-7 3.1-7 7a6.96 6.96 0 0 0 3.07 5.813C8.51 19.346 6 22.892 6 27h2c0-4.4 3.6-8 8-8c3.9 0 7-3.1 7-7s-3.1-7-7-7m0 2c2.8 0 5 2.2 5 5s-2.2 5-5 5s-5-2.2-5-5s2.2-5 5-5m8.1 11v2.1c-.6.1-1.2.4-1.7.7l-1.5-1.5l-1.4 1.4l1.5 1.5c-.4.5-.6 1.1-.7 1.8H18v2h2.1c.1.6.4 1.2.7 1.8l-1.5 1.5l1.4 1.4l1.5-1.5c.5.3 1.1.6 1.7.7V32h2v-2.1c.6-.1 1.2-.4 1.7-.7l1.5 1.5l1.4-1.4l-1.5-1.5c.4-.5.6-1.1.7-1.8H32v-2h-2.1c-.1-.6-.4-1.2-.7-1.8l1.5-1.5l-1.4-1.4l-1.5 1.5c-.5-.3-1.1-.6-1.7-.7V18zm.9 4c1.7 0 3 1.3 3 3s-1.3 3-3 3s-3-1.3-3-3s1.3-3 3-3m0 2a.872.872 0 0 0-.367.086a1.138 1.138 0 0 0-.32.227a1.138 1.138 0 0 0-.227.32A.872.872 0 0 0 24 25c0 .375.281.75.633.914A.872.872 0 0 0 25 26c.5 0 1-.5 1-1s-.5-1-1-1" />
                                            </svg>
                                        </div>
                                        <p class="ss-title">Clients on Maintenance</p>
                                        <h4 class="fw-bolder mb-2"><?= $current_maintainace_plan->id ?></h4>
                                        <p class="fs-11 mb-1">Last Year <span class="fw-semibold"><?= $last_maintainace_plan->id ?></span></p>
                                        <?php 
                                            if($current_maintainace_plan->id > $last_maintainace_plan->id)
                                            {
                                                $icon = '+';
                                                $class = 'fs_success';
                                                $tag = 'Increase';
                                            }
                                            else
                                            {
                                                $icon = '';
                                                $class = 'fs_danger';
                                                $tag = 'Decrease';
                                            }
                                            $percent = (($current_maintainace_plan->id - $last_maintainace_plan->id) / $last_maintainace_plan->id) * 100;
                                        ?>   

                                        <span class="<?= $class ?> fs-10 fw_500">
                                        <?= ($icon.round($percent, 2). '% ' .$tag) ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-lg-5 col-md-6 col-12">
                            <div class="mb-3">
                                <div class="card border-0 card_shadow">
                                    <div class="card-body">
                                        <p class="fs-12 fw_500 mb-2">Current Year Revenue (vs Previous Year)</p>
                                        <div id="curr_month_revenue_last"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="mb-3">
                                <div class="card border-0 card_shadow">
                                    <div class="card-body">
                                        <p class="fs-12 fw_500 mb-2">Active Opps by Status</p>
                                        <div id="active_opps"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                     
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="mb-3">
                                <div class="card border-0 card_shadow">
                                    <div class="card-body">
                                        <p class="fs-12 fw_500 mb-2">Expected Revenue VS Total Revenue (Active Opps)</p>
                                        <div class="position-relative mx-auto text-center">
                                            <canvas id="revenue_opps"></canvas>
                                        </div>
                                        <div class="text-center mt-25 fs-12 fw-semibold">$<span id="preview-textfield"></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="my-3">
            <div class="row d-flex">
                <div class="col-lg-12">
                    <div class="card border-0">
                        <div class="card-body pb-0">
                            <h6 class="fw-semibold mb-3">Delivery</h6>
                            <div class="row d-flex align-items-center">
                                <div class="col-lg col-md-3 col-6 mb-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#active_projects">
                                        <div class="card border-0 border_radius card_shadow bg_faebd769">
                                            <div class="card-body">
                                                <div class="card_icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                        viewBox="0 0 32 32">
                                                        <path fill="#c481b0"
                                                            d="M12.707 16.293a1 1 0 0 0-1.414 1.414zM15 20l-.707.707a1 1 0 0 0 1.414 0zm6.207-4.793a1 1 0 0 0-1.414-1.414zm-9.914 2.5l3 3l1.414-1.414l-3-3zm4.414 3l5.5-5.5l-1.414-1.414l-5.5 5.5zM29 24V11h-2v13zM26.003 8H17v2h9.003zM17 8c-.208 0-.36-.063-.552-.228c-.245-.21-.442-.477-.805-.912c-.327-.393-.755-.872-1.35-1.24C13.678 5.236 12.933 5 12 5v2c.568 0 .947.138 1.238.318c.311.194.571.465.869.822c.262.315.627.797 1.04 1.15c.463.398 1.06.71 1.853.71zm-5-3H6v2h6zM3 8v16h2V8zm3 19h20v-2H6zM6 5a3 3 0 0 0-3 3h2a1 1 0 0 1 1-1zm23 6c0-1.655-1.338-3-2.997-3v2c.55 0 .997.446.997 1zM3 24a3 3 0 0 0 3 3v-2a1 1 0 0 1-1-1zm24 0a1 1 0 0 1-1 1v2a3 3 0 0 0 3-3z" />
                                                    </svg>
                                                </div>
                                                <p class="ss-title">Active Projects</p>
                                                <h4 class="fw-bolder mb-2"><?= count($active_project) ?></h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg col-md-3 col-6 mb-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#active_projects_beyond">
                                        <div class="card border-0 border_radius card_shadow bg_f1f5ff">
                                            <div class="card-body">
                                                <div class="card_icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                        viewBox="0 0 24 24">
                                                        <path fill="none" stroke="#c481b0" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="1.5"
                                                            d="M16.5 5V3m-9 2V3M3.25 8h17.5M10.5 12.5l3 3m0-3l-3 3M3 10.044c0-2.115 0-3.173.436-3.981a3.896 3.896 0 0 1 1.748-1.651C6.04 4 7.16 4 9.4 4h5.2c2.24 0 3.36 0 4.216.412c.753.362 1.364.94 1.748 1.65c.436.81.436 1.868.436 3.983v4.912c0 2.115 0 3.173-.436 3.981a3.896 3.896 0 0 1-1.748 1.651C17.96 21 16.84 21 14.6 21H9.4c-2.24 0-3.36 0-4.216-.412a3.896 3.896 0 0 1-1.748-1.65C3 18.128 3 17.07 3 14.955z" />
                                                    </svg>
                                                </div>
                                                <p class="ss-title">Active Projects beyond closed date</p>
                                                <h4 class="fw-bolder mb-2"><?= count($active_date_beyond) ?></h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg col-md-3 col-6 mb-3">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#active_milestones">
                                        <div class="card border-0 border_radius card_shadow bg_f5f6fa">
                                            <div class="card-body">
                                                <div class="card_icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                        viewBox="0 0 100 100">
                                                        <path fill="#c481b0"
                                                            d="M21 32C9.459 32 0 41.43 0 52.94c0 4.46 1.424 8.605 3.835 12.012l14.603 25.244c2.045 2.672 3.405 2.165 5.106-.14l16.106-27.41c.325-.59.58-1.216.803-1.856A20.668 20.668 0 0 0 42 52.94C42 41.43 32.544 32 21 32m0 9.812c6.216 0 11.16 4.931 11.16 11.129c0 6.198-4.944 11.127-11.16 11.127c-6.215 0-11.16-4.93-11.16-11.127c0-6.198 4.945-11.129 11.16-11.129M87.75 0C81.018 0 75.5 5.501 75.5 12.216c0 2.601.83 5.019 2.237 7.006l8.519 14.726c1.193 1.558 1.986 1.262 2.978-.082l9.395-15.99c.19-.343.339-.708.468-1.082a12.05 12.05 0 0 0 .903-4.578C100 5.5 94.484 0 87.75 0m0 5.724c3.626 0 6.51 2.876 6.51 6.492c0 3.615-2.884 6.49-6.51 6.49c-3.625 0-6.51-2.875-6.51-6.49c0-3.616 2.885-6.492 6.51-6.492" />
                                                        <path fill="#c481b0" fill-rule="evenodd"
                                                            d="M88.209 37.412c-2.247.05-4.5.145-6.757.312l.348 5.532a126.32 126.32 0 0 1 6.513-.303zm-11.975.82c-3.47.431-6.97 1.045-10.43 2.032l1.303 5.361c3.144-.896 6.402-1.475 9.711-1.886zM60.623 42.12a24.52 24.52 0 0 0-3.004 1.583l-.004.005l-.006.002c-1.375.866-2.824 1.965-4.007 3.562c-.857 1.157-1.558 2.62-1.722 4.35l5.095.565c.038-.406.246-.942.62-1.446h.002v-.002c.603-.816 1.507-1.557 2.582-2.235l.004-.002a19.64 19.64 0 0 1 2.388-1.256zM58 54.655l-3.303 4.235c.783.716 1.604 1.266 2.397 1.726l.01.005l.01.006c2.632 1.497 5.346 2.342 7.862 3.144l1.446-5.318c-2.515-.802-4.886-1.576-6.918-2.73c-.582-.338-1.092-.691-1.504-1.068m13.335 5.294l-1.412 5.327l.668.208l.82.262c2.714.883 5.314 1.826 7.638 3.131l2.358-4.92c-2.81-1.579-5.727-2.611-8.538-3.525l-.008-.002l-.842-.269zm14.867 7.7l-3.623 3.92c.856.927 1.497 2.042 1.809 3.194l.002.006l.002.009c.372 1.345.373 2.927.082 4.525l5.024 1.072c.41-2.256.476-4.733-.198-7.178c-.587-2.162-1.707-4.04-3.098-5.548M82.72 82.643a11.84 11.84 0 0 1-1.826 1.572h-.002c-1.8 1.266-3.888 2.22-6.106 3.04l1.654 5.244c2.426-.897 4.917-1.997 7.245-3.635l.006-.005l.003-.002a16.95 16.95 0 0 0 2.639-2.287zm-12.64 6.089c-3.213.864-6.497 1.522-9.821 2.08l.784 5.479c3.421-.575 6.856-1.262 10.27-2.18zm-14.822 2.836c-3.346.457-6.71.83-10.084 1.148l.442 5.522c3.426-.322 6.858-.701 10.285-1.17zm-15.155 1.583c-3.381.268-6.77.486-10.162.67l.256 5.536c3.425-.185 6.853-.406 10.28-.678zm-15.259.92c-2.033.095-4.071.173-6.114.245l.168 5.541a560.1 560.1 0 0 0 6.166-.246z"
                                                            color="#c481b0" />
                                                    </svg>
                                                </div>
                                                <p class="ss-title">Active Milestones</p>
                                                <h4 class="fw-bolder mb-2"><?= count($active_milestone) ?></h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg col-md-3 col-6 mb-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#active_milestones_beyond">
                                        <div class="card border-0 border_radius card_shadow bg_fef8fc">
                                            <div class="card-body">
                                                <div class="card_icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                        viewBox="0 0 24 24">
                                                        <path fill="#c481b0"
                                                            d="M16.5 13H15v3.69l3.19 1.84l.75-1.3l-2.44-1.41zM16 9c-1.96 0-3.73.82-5 2.12V7h6l2-2l-2-2h-6V2l-1-1l-1 1v4H3L1 8l2 2h6v10c-1.1 0-2 .9-2 2h5.41c1.05.63 2.28 1 3.59 1c3.87 0 7-3.13 7-7s-3.13-7-7-7m0 11.85c-2.68 0-4.85-2.17-4.85-4.85s2.17-4.85 4.85-4.85s4.85 2.17 4.85 4.85s-2.17 4.85-4.85 4.85" />
                                                    </svg>
                                                </div>
                                                <p class="ss-title">Active Milestones beyond closed date</p>
                                                <h4 class="fw-bolder mb-2"><?= count($active_beyond_milstone) ?></h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-lg col-md-3 col-6 mb-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#active_projects_allocation">
                                        <div class="card border-0 border_radius card_shadow bg_faebd769">
                                            <div class="card-body">
                                                <div class="card_icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                        viewBox="0 0 24 24">
                                                        <path fill="#c481b0"
                                                            d="M13 19c0 .34.04.67.09 1H4a2 2 0 0 1-2-2V6c0-1.11.89-2 2-2h6l2 2h8a2 2 0 0 1 2 2v5.81c-.61-.35-1.28-.59-2-.72V8H4v10h9.09c-.05.33-.09.66-.09 1m9.54-2.12l-1.42-1.41L19 17.59l-2.12-2.12l-1.41 1.41L17.59 19l-2.12 2.12l1.41 1.42L19 20.41l2.12 2.13l1.42-1.42L20.41 19z" />
                                                    </svg>
                                                </div>
                                                <p class="ss-title">Active Projects without proper allocation</p>
                                                <h4 class="fw-bolder mb-2"><?= count($project_allocation) ?></h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class="row d-flex align-items-center">
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="mb-3">
                                    <div class="card border-0 card_shadow">
                                        <div class="card-body">
                                        <h6 class="fw-semibold mb-3 text-center">Milestones by Status</h6>
                                            <div id="chartdiv"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="my-3">
            <div class="row d-flex">
                <div class="col-lg-12">
                    <div class="card border-0">
                        <div class="card-body pb-0">
                            <h6 class="fw-semibold mb-3">Delivery</h6>
                            <div class="row align-items-start">
                                <div class="col-lg-6 col-md-12 col-12">
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 col-md-4 col-12 mb-3">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#active_projects">
                                                <div class="card border-0 border_radius card_shadow bg_faebd769">
                                                    <div class="card-body">
                                                        <div class="card_icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                                viewBox="0 0 32 32">
                                                                <path fill="#c481b0"
                                                                    d="M12.707 16.293a1 1 0 0 0-1.414 1.414zM15 20l-.707.707a1 1 0 0 0 1.414 0zm6.207-4.793a1 1 0 0 0-1.414-1.414zm-9.914 2.5l3 3l1.414-1.414l-3-3zm4.414 3l5.5-5.5l-1.414-1.414l-5.5 5.5zM29 24V11h-2v13zM26.003 8H17v2h9.003zM17 8c-.208 0-.36-.063-.552-.228c-.245-.21-.442-.477-.805-.912c-.327-.393-.755-.872-1.35-1.24C13.678 5.236 12.933 5 12 5v2c.568 0 .947.138 1.238.318c.311.194.571.465.869.822c.262.315.627.797 1.04 1.15c.463.398 1.06.71 1.853.71zm-5-3H6v2h6zM3 8v16h2V8zm3 19h20v-2H6zM6 5a3 3 0 0 0-3 3h2a1 1 0 0 1 1-1zm23 6c0-1.655-1.338-3-2.997-3v2c.55 0 .997.446.997 1zM3 24a3 3 0 0 0 3 3v-2a1 1 0 0 1-1-1zm24 0a1 1 0 0 1-1 1v2a3 3 0 0 0 3-3z" />
                                                            </svg>
                                                        </div>
                                                        <p class="ss-title">Active Projects</p>
                                                        <h4 class="fw-bolder mb-2"><?= count($active_project) ?></h4>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-12 col-6 mb-3">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#active_projects_beyond">
                                                <div class="card border-0 border_radius card_shadow bg_f1f5ff">
                                                    <div class="card-body">
                                                        <div class="card_icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                                viewBox="0 0 24 24">
                                                                <path fill="none" stroke="#c481b0" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="1.5"
                                                                    d="M16.5 5V3m-9 2V3M3.25 8h17.5M10.5 12.5l3 3m0-3l-3 3M3 10.044c0-2.115 0-3.173.436-3.981a3.896 3.896 0 0 1 1.748-1.651C6.04 4 7.16 4 9.4 4h5.2c2.24 0 3.36 0 4.216.412c.753.362 1.364.94 1.748 1.65c.436.81.436 1.868.436 3.983v4.912c0 2.115 0 3.173-.436 3.981a3.896 3.896 0 0 1-1.748 1.651C17.96 21 16.84 21 14.6 21H9.4c-2.24 0-3.36 0-4.216-.412a3.896 3.896 0 0 1-1.748-1.65C3 18.128 3 17.07 3 14.955z" />
                                                            </svg>
                                                        </div>
                                                        <p class="ss-title">Active Projects beyond closed date</p>
                                                        <h4 class="fw-bolder mb-2"><?= count($active_date_beyond) ?></h4>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-12 col-6 mb-3">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#active_milestones">
                                                <div class="card border-0 border_radius card_shadow bg_f5f6fa">
                                                    <div class="card-body">
                                                        <div class="card_icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                                viewBox="0 0 100 100">
                                                                <path fill="#c481b0"
                                                                    d="M21 32C9.459 32 0 41.43 0 52.94c0 4.46 1.424 8.605 3.835 12.012l14.603 25.244c2.045 2.672 3.405 2.165 5.106-.14l16.106-27.41c.325-.59.58-1.216.803-1.856A20.668 20.668 0 0 0 42 52.94C42 41.43 32.544 32 21 32m0 9.812c6.216 0 11.16 4.931 11.16 11.129c0 6.198-4.944 11.127-11.16 11.127c-6.215 0-11.16-4.93-11.16-11.127c0-6.198 4.945-11.129 11.16-11.129M87.75 0C81.018 0 75.5 5.501 75.5 12.216c0 2.601.83 5.019 2.237 7.006l8.519 14.726c1.193 1.558 1.986 1.262 2.978-.082l9.395-15.99c.19-.343.339-.708.468-1.082a12.05 12.05 0 0 0 .903-4.578C100 5.5 94.484 0 87.75 0m0 5.724c3.626 0 6.51 2.876 6.51 6.492c0 3.615-2.884 6.49-6.51 6.49c-3.625 0-6.51-2.875-6.51-6.49c0-3.616 2.885-6.492 6.51-6.492" />
                                                                <path fill="#c481b0" fill-rule="evenodd"
                                                                    d="M88.209 37.412c-2.247.05-4.5.145-6.757.312l.348 5.532a126.32 126.32 0 0 1 6.513-.303zm-11.975.82c-3.47.431-6.97 1.045-10.43 2.032l1.303 5.361c3.144-.896 6.402-1.475 9.711-1.886zM60.623 42.12a24.52 24.52 0 0 0-3.004 1.583l-.004.005l-.006.002c-1.375.866-2.824 1.965-4.007 3.562c-.857 1.157-1.558 2.62-1.722 4.35l5.095.565c.038-.406.246-.942.62-1.446h.002v-.002c.603-.816 1.507-1.557 2.582-2.235l.004-.002a19.64 19.64 0 0 1 2.388-1.256zM58 54.655l-3.303 4.235c.783.716 1.604 1.266 2.397 1.726l.01.005l.01.006c2.632 1.497 5.346 2.342 7.862 3.144l1.446-5.318c-2.515-.802-4.886-1.576-6.918-2.73c-.582-.338-1.092-.691-1.504-1.068m13.335 5.294l-1.412 5.327l.668.208l.82.262c2.714.883 5.314 1.826 7.638 3.131l2.358-4.92c-2.81-1.579-5.727-2.611-8.538-3.525l-.008-.002l-.842-.269zm14.867 7.7l-3.623 3.92c.856.927 1.497 2.042 1.809 3.194l.002.006l.002.009c.372 1.345.373 2.927.082 4.525l5.024 1.072c.41-2.256.476-4.733-.198-7.178c-.587-2.162-1.707-4.04-3.098-5.548M82.72 82.643a11.84 11.84 0 0 1-1.826 1.572h-.002c-1.8 1.266-3.888 2.22-6.106 3.04l1.654 5.244c2.426-.897 4.917-1.997 7.245-3.635l.006-.005l.003-.002a16.95 16.95 0 0 0 2.639-2.287zm-12.64 6.089c-3.213.864-6.497 1.522-9.821 2.08l.784 5.479c3.421-.575 6.856-1.262 10.27-2.18zm-14.822 2.836c-3.346.457-6.71.83-10.084 1.148l.442 5.522c3.426-.322 6.858-.701 10.285-1.17zm-15.155 1.583c-3.381.268-6.77.486-10.162.67l.256 5.536c3.425-.185 6.853-.406 10.28-.678zm-15.259.92c-2.033.095-4.071.173-6.114.245l.168 5.541a560.1 560.1 0 0 0 6.166-.246z"
                                                                    color="#c481b0" />
                                                            </svg>
                                                        </div>
                                                        <p class="ss-title">Active Milestones</p>
                                                        <h4 class="fw-bolder mb-2"><?= count($active_milestone) ?></h4>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-12 mb-3">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#active_milestones_beyond">
                                                <div class="card border-0 border_radius card_shadow bg_fef8fc">
                                                    <div class="card-body">
                                                        <div class="card_icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                                viewBox="0 0 24 24">
                                                                <path fill="#c481b0"
                                                                    d="M16.5 13H15v3.69l3.19 1.84l.75-1.3l-2.44-1.41zM16 9c-1.96 0-3.73.82-5 2.12V7h6l2-2l-2-2h-6V2l-1-1l-1 1v4H3L1 8l2 2h6v10c-1.1 0-2 .9-2 2h5.41c1.05.63 2.28 1 3.59 1c3.87 0 7-3.13 7-7s-3.13-7-7-7m0 11.85c-2.68 0-4.85-2.17-4.85-4.85s2.17-4.85 4.85-4.85s4.85 2.17 4.85 4.85s-2.17 4.85-4.85 4.85" />
                                                            </svg>
                                                        </div>
                                                        <p class="ss-title">Active Milestones beyond closed date</p>
                                                        <h4 class="fw-bolder mb-2"><?= count($active_beyond_milstone) ?></h4>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-12 col-6 mb-3">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#active_projects_allocation">
                                                <div class="card border-0 border_radius card_shadow bg_faebd769">
                                                    <div class="card-body">
                                                        <div class="card_icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                                viewBox="0 0 24 24">
                                                                <path fill="#c481b0"
                                                                    d="M13 19c0 .34.04.67.09 1H4a2 2 0 0 1-2-2V6c0-1.11.89-2 2-2h6l2 2h8a2 2 0 0 1 2 2v5.81c-.61-.35-1.28-.59-2-.72V8H4v10h9.09c-.05.33-.09.66-.09 1m9.54-2.12l-1.42-1.41L19 17.59l-2.12-2.12l-1.41 1.41L17.59 19l-2.12 2.12l1.41 1.42L19 20.41l2.12 2.13l1.42-1.42L20.41 19z" />
                                                            </svg>
                                                        </div>
                                                        <p class="ss-title">Active Projects without proper allocation</p>
                                                        <h4 class="fw-bolder mb-2"><?= count($project_allocation) ?></h4>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="mb-3">
                                        <div class="card border-0 card_shadow">
                                            <div class="card-body">
                                            <h6 class="fw-semibold mb-3 text-center">Milestones by Status</h6>
                                                <div id="chartdiv"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="row d-flex align-items-center">
                            
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-3">
            <div class="row d-flex">
                <div class="col-lg-12">
                    <div class="card border-0">
                        <div class="card-body pb-0">
                            <h6 class="fw-semibold mb-3">Operations</h6>
                            <div class="row d-flex align-items-center">
                             
                                <div class="col-lg-3 col-md-3 col-6 mb-3">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#weekly_timesheet_report">
                                        <div class="card border-0 border_radius card_shadow bg_f1f5ff">
                                            <div class="card-body">
                                                <div class="card_icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                        viewBox="0 0 2048 2048">
                                                        <path fill="#c481b0"
                                                            d="M1536 1408h192v128h-320v-384h128zm-256-896H256V384h1024zm192 384q119 0 224 45t183 124t123 183t46 224q0 119-45 224t-124 183t-183 123t-224 46q-119 0-224-45t-183-124t-123-183t-46-224q0-119 45-224t124-183t183-123t224-46m0 1024q93 0 174-35t142-96t96-142t36-175q0-93-35-174t-96-142t-142-96t-175-36q-93 0-174 35t-142 96t-96 142t-36 175q0 93 35 174t96 142t142 96t175 36M1166 768q-109 48-200 128H256V768zm-391 384q-14 31-25 63t-21 65H256v-128zm-519 384h451q3 32 8 64t14 64H256zm594 384q50 71 116 128H0V0h1536v707l-32-2q-16-1-32-1t-32 1t-32 2V128H128v1792z" />
                                                    </svg>
                                                </div>
                                                <p class="ss-title">Last week Timesheets <90% </p>
                                                        <h4 class="fw-bolder mb-2"><?= count($weekly_timesheet_report) ?></h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-3 col-6 mb-3">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#timesheet_month_list">
                                        <div class="card border-0 border_radius card_shadow bg_f5f6fa">
                                            <div class="card-body">
                                                <div class="card_icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                        viewBox="0 0 16 16">
                                                        <path fill="#c481b0"
                                                            d="M9.5 14h-8C.67 14 0 13.33 0 12.5V2.38C0 1.55.67.88 1.5.88h11c.83 0 1.5.67 1.5 1.5v7.25c0 .28-.22.5-.5.5s-.5-.22-.5-.5V2.38c0-.28-.22-.5-.5-.5h-11c-.28 0-.5.22-.5.5V12.5c0 .28.22.5.5.5h8c.28 0 .5.22.5.5s-.22.5-.5.5" />
                                                        <path fill="#c481b0"
                                                            d="M4 3.62c-.28 0-.5-.22-.5-.5V.5c0-.28.22-.5.5-.5s.5.22.5.5v2.62c0 .28-.22.5-.5.5m6.12 0c-.28 0-.5-.22-.5-.5V.5c0-.28.22-.5.5-.5s.5.22.5.5v2.62c0 .28-.22.5-.5.5M13.5 6H.5C.22 6 0 5.78 0 5.5S.22 5 .5 5h13c.28 0 .5.22.5.5s-.22.5-.5.5m-1 10C10.57 16 9 14.43 9 12.5S10.57 9 12.5 9s3.5 1.57 3.5 3.5s-1.57 3.5-3.5 3.5m0-6a2.5 2.5 0 0 0 0 5a2.5 2.5 0 0 0 0-5" />
                                                        <path fill="#c481b0"
                                                            d="M13.5 14a.47.47 0 0 1-.35-.15l-1-1a.5.5 0 0 1-.15-.35V11c0-.28.22-.5.5-.5s.5.22.5.5v1.29l.85.85c.2.2.2.51 0 .71c-.1.1-.23.15-.35.15" />
                                                    </svg>
                                                </div>
                                                <p class="ss-title">Current Month Timesheets <90% </p>
                                                        <h4 class="fw-bolder mb-2"><?= count($timesheet_month_list) ?></h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-3 col-6 mb-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#people_on_leave">
                                        <div class="card border-0 border_radius card_shadow bg_fef8fc">
                                            <div class="card-body">
                                                <div class="card_icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                        viewBox="0 0 24 24">
                                                        <g fill="none" stroke="#c481b0" stroke-width="1.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M2.5 9.5L12 4l9.5 5.5" />
                                                            <path d="M7 21v-1a5 5 0 0 1 10 0v1" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M12 15a3 3 0 1 0 0-6a3 3 0 0 0 0 6" />
                                                        </g>
                                                    </svg>
                                                </div>
                                                <p class="ss-title">People on Leave/ WFH Today</p>
                                                <h4 class="fw-bolder mb-2"><?= count($today_leave_wfh) ?></h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-3 col-6 mb-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#late_entries_yesterday">
                                        <div class="card border-0 border_radius card_shadow bg_cddbfa9e">
                                            <div class="card-body">
                                                <div class="card_icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                        viewBox="0 0 24 24">
                                                        <path fill="#c481b0"
                                                            d="M9 8h2v6H9zm4-7H7v2h6zm4.03 6.39A8.96 8.96 0 0 1 19 13c0 4.97-4 9-9 9a9 9 0 0 1 0-18c2.12 0 4.07.74 5.62 2l1.42-1.44c.51.44.96.9 1.41 1.41zM17 13c0-3.87-3.13-7-7-7s-7 3.13-7 7s3.13 7 7 7s7-3.13 7-7m4-6v6h2V7zm0 10h2v-2h-2z" />
                                                    </svg>
                                                </div>
                                                <p class="ss-title">Late Entries Yesterday</p>
                                                <h4 class="fw-bolder mb-2"><?= count($late_entry_yestarday) ?></h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-3 col-6 mb-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#early_exits_yesterday">
                                        <div class="card border-0 border_radius card_shadow bg_f9e2ca8a">
                                            <div class="card-body">
                                                <div class="card_icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                        viewBox="0 0 16 16">
                                                        <path fill="#c481b0" d="M8 4H7v5h4V8H8z" />
                                                        <path fill="#c481b0"
                                                            d="M8 0C5 0 2.4 1.6 1.1 4.1L0 3v4h4L2.5 5.5C3.5 3.5 5.6 2 8 2c3.3 0 6 2.7 6 6s-2.7 6-6 6c-1.8 0-3.4-.8-4.5-2.1L2 13.2C3.4 14.9 5.6 16 8 16c4.4 0 8-3.6 8-8s-3.6-8-8-8" />
                                                    </svg>
                                                </div>
                                                <p class="ss-title">Early Exits Yesterday</p>
                                                <h4 class="fw-bolder mb-2"><?= count($early_exit) ?></h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-3 col-6 mb-3">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#late_entries_today">
                                        <div class="card border-0 border_radius card_shadow bg_c2f4e68f">
                                            <div class="card-body">
                                                <div class="card_icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                        viewBox="0 0 512 512">
                                                        <path fill="#c481b0"
                                                            d="M119.7 22.8c-23.36 3.1-44.58 30.5-44.58 66c0 19.5 6.78 36.8 16.69 48.8l11.79 14.2l-18.2 3.4c-12.86 2.5-22.31 9.3-30.39 20.4c-8.09 11.1-14.27 26.5-18.6 44.4c-7.84 32.2-9.58 71.6-9.84 106.4h42.86L81.2 484.2c29.9 6.8 61.8 6.5 90.6 0l10.4-157.8H223c0-35.2-.5-75.1-7.6-107.7c-3.9-17.9-9.8-33.3-18-44.3s-18.1-17.7-32.6-20l-18.6-2.9l11.8-14.7c9.5-11.9 15.9-29 15.9-48c0-37.9-23.7-65.9-49.4-65.9zm141.7 30.62v18h224v-18zm16 39c0 47.98 48 159.98 96 159.98s96-112 96-159.98zm96 179.98c-48 0-96 112-96 160h192c0-48-48-160-96-160m-112 181v18h224v-18z" />
                                                    </svg>
                                                </div>
                                                <p class="ss-title">Late Entries Today</p>
                                                <h4 class="fw-bolder mb-2"><?= count($late_entry_today) ?></h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>


    <!-- Start Current Month Revenue (vs Last Month) -->
    <div class="modal fade" id="current_month_revenue" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Current Month Revenue (vs Last
                        month)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped fs-11" id="current_revenue_table">
                            <thead class="table-dark">
                                <tr>
                                    <th class="fw_500">#</th>
                                    <th class="fw_500">Company</th>
                                    <th class="fw_500">Client</th>
                                    <th class="fw_500">Email</th>
                                    <th class="fw_500">Billing</th>
                                    <th class="fw_500">Project</th>
                                    <th class="fw_500">Status</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i= 1; foreach($current_month_revenue_list as $val){ ?>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label" for="row1">
                                                <?= $i ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td><?= (empty($val->company_name) ? "---" : $val->company_name) ?></td>
                                    <td><?= (empty($val->client_name) ? "---" : $val->client_name) ?></td>
                                    <td><?= (empty($val->email) ? "---" : $val->email) ?></td>
                                    <td>$<?= number_format($val->billing) ?></td>
                                    <td><?=  (empty($val->project_name) ? "---" : $val->project_name) ?></td>
                                    <td>
                                        <div class="fw-semibold d-flex align-items-center">
                                            <?php if($val->status == 'Completed')
                                            { ?>
                                             <span class="p-1 bg-success rounded-circle"></span><span
                                                class="ms-1 text-success"><?= $val->status ?></span>
                                            <?php }else { ?>
                                                <span class="p-1 bg-warning rounded-circle"></span><span
                                                class="ms-1 text-warning"><?= $val->status ?></span>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php  $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End  Current Month Revenue (vs Last Month)-->

    <!-- Start YTD Revenue (vs Last Year) -->
    <div class="modal fade" id="ytd_revenue_list" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">YTD Revenue (vs Last Year)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped fs-11" id="ytd_revenue_table">
                            <thead class="table-dark">
                                <tr>
                                    <th class="fw_500">#</th>
                                    <th class="fw_500">Company</th>
                                    <th class="fw_500">Client</th>
                                    <th class="fw_500">Email</th>
                                    <th class="fw_500">Billing</th>
                                    <th class="fw_500">Project</th>
                                    <th class="fw_500">Status</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i= 1; foreach($ytd_revenue_list as $val){ ?>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label" for="row1">
                                                <?= $i ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td><?= (empty($val->company_name) ? "---" : $val->company_name) ?></td>
                                    <td><?= (empty($val->client_name) ? "---" : $val->client_name) ?></td>
                                    <td><?= (empty($val->email) ? "---" : $val->email) ?></td>
                                    <td>$<?= number_format($val->billing) ?></td>
                                    <td><?=  (empty($val->project_name) ? "---" : $val->project_name) ?></td>
                                    <td>
                                        <div class="fw-semibold d-flex align-items-center">
                                            <?php if($val->status == 'Completed')
                                            { ?>
                                             <span class="p-1 bg-success rounded-circle"></span><span
                                                class="ms-1 text-success"><?= $val->status ?></span>
                                            <?php }else { ?>
                                                <span class="p-1 bg-warning rounded-circle"></span><span
                                                class="ms-1 text-warning"><?= $val->status ?></span>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php  $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End YTD Revenue (vs Last Year) -->

    <!-- Start Active opps updated > & days Ago  -->
    <div class="modal fade" id="ytd_revenue_list" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">YTD Revenue (vs Last Year)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped fs-11" id="ytd_revenue_table">
                            <thead class="table-dark">
                                <tr>
                                    <th class="fw_500">#</th>
                                    <th class="fw_500">Company</th>
                                    <th class="fw_500">Client</th>
                                    <th class="fw_500">Email</th>
                                    <th class="fw_500">Billing</th>
                                    <th class="fw_500">Project</th>
                                    <th class="fw_500">Status</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i= 1; foreach($ytd_revenue_list as $val){ ?>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label" for="row1">
                                                <?= $i ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td><?= (empty($val->company_name) ? "---" : $val->company_name) ?></td>
                                    <td><?= (empty($val->client_name) ? "---" : $val->client_name) ?></td>
                                    <td><?= (empty($val->email) ? "---" : $val->email) ?></td>
                                    <td>$<?= number_format($val->billing) ?></td>
                                    <td><?=  (empty($val->project_name) ? "---" : $val->project_name) ?></td>
                                    <td>
                                        <div class="fw-semibold d-flex align-items-center">
                                            <?php if($val->status == 'Completed')
                                            { ?>
                                             <span class="p-1 bg-success rounded-circle"></span><span
                                                class="ms-1 text-success"><?= $val->status ?></span>
                                            <?php }else { ?>
                                                <span class="p-1 bg-warning rounded-circle"></span><span
                                                class="ms-1 text-warning"><?= $val->status ?></span>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php  $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>                                            
    <!-- End Active opps updated > & days Ago -->

    <!-- Start Active opps updated > & days Ago -->
    <div class="modal fade" id="active_opps_updated" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Active opps updated > & days Ago</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped fs-11" id="active_opps_updated_table">
                            <thead class="table-dark">
                                <tr>
                                    <th class="fw_500">#</th>
                                    <th class="fw_500">Opportunity</th>
                                    <th class="fw_500">Client</th>
                                    <th class="fw_500">Assign To</th>
                                    <th class="fw_500">Stage</th>
                                    <th class="fw_500">Amount</th>
                                    <th class="fw_500">Probability</th>
                                    <th class="fw_500">Next Step</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i= 1; foreach($opportunity_list as $row){ ?>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label" for="row1">
                                                <?= $i ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td title="<?= $row->opportunity_name ?>"><a href="#"><?= (empty($row->opportunity_name) ? "---" : substr($row['opportunity_name'],0,20)) ?></a></td>
                                    <td><?= (empty($row->client_name) ? "---" : substr($row['client_name'],0,30)) ?></td>
                                    <td><?= (empty($row->assigne_name) ? "---" : $row->assigne_name) ?></td>
                                    <td><?= $row['stage'] ?></td>
                                    <td> <?= ($row['expected_amount'] == '0' ? "--" : "$".$row['expected_amount']); ?></td>
                                    <td class="center-item">
                                    <?php
                                    $probabilities = [
                                        '1' => 'Low',
                                        '2' => 'Average',
                                        '3' => 'High',
                                        '4' => 'Very High',
                                        '5' => 'Sure'
                                    ];

                                    echo isset($row['probability']) && isset($probabilities[$row['probability']]) ? $probabilities[$row['probability']] : '';
                                    ?>
                                    </td>
                                    <td title="<?= $row['next_step']; ?>"><a href="#"><?= substr($row['next_step'],0,40); ?></a></td> 
                                </tr>
                            <?php  $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <!-- End Active opps updated > & days Ago -->

    <!-- Start New Projects -->
    <div class="modal fade" id="new_project_list" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">New Projects List</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped fs-11" id="new_project_table">
                            <thead class="table-dark">
                                <tr>
                                    <th class="fw_500">#</th>
                                    <th>Project Name</th>
                                    <th>Client</th>
                                    <th>Project Manager</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i= 1; foreach($current_year_project as $p){ ?>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label" for="row1">
                                                <?= $i ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td title="<?= $p['project_name'] ?>"><a href="#"><?= (empty($p['project_name']) ? "---" : substr($p['project_name'],0,30)) ?></a></td>
                                    <td><?= (empty($p['client']) ? "---" : substr($p['client'],0,30)) ?></td>
                                    <td><?= (empty($p['project_manager']) ? "---" : $p['project_manager']) ?></td>
                                    <td><?=$p['due_date'] ?></td>
                                    <td><?=$p['status'] ?></td>
                                </tr>
                            <?php  $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>                                
    <!-- End New Projects -->

    <!-- Start  Client List  -->
    <div class="modal fade" id="client_list" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">New Projects List</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped fs-11" id="new_project_table">
                            <thead class="table-dark">
                                <tr>
                                <th>#</th>
                                <th>Client</th>
                                <th>Project</th>
                                <th>Plan</th>
                                <th>Manager</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Amount Billed</th>
                                <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i= 1; foreach($maintainace_plan_list as $p){ ?>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label" for="row1">
                                                <?= $i ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td title="<?= $p['client_name'] ?>"><a href="#"><?= (empty($p['client_name']) ? "---" : substr($p['client_name'],0,30)) ?></a></td>
                                    <td title="<?= $p['project_name'] ?>"><a href="#"><?= (empty($p['project_name']) ? "---" : substr($p['project_name'],0,30)) ?></a></td>
                                    <td><?= (empty($p['name']) ? "---" : $p['name']) ?></td>
                                    <td><?= (empty($p['assigne_name']) ? "---" : $p['assigne_name']) ?></td>
                                    <td><?= date_format(date_create($p['start_date']), "d-m-Y") ?></td>
                                    <td><?= date_format(date_create($p['end_date']), "d-m-Y") ?></td>
                                    <td>$ <?= $p['amount']; ?></td>
                                    <td><?= $p['status'] == 1 ? 'Active' : 'Expired' ?></td>
                                </tr>
                            <?php  $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <!-- End Client List  -->

    <!-- Start Active Projects -->
    <div class="modal fade" id="active_projects" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Active Projects</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped fs-11" id="active_projects_table">
                            <thead class="table-dark">
                                <tr>
                                    <th class="fw_500">#</th>
                                    <th>Project Name</th>
                                    <th>Client</th>
                                    <th>Project Manager</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i= 1; foreach($active_project as $p){ ?>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label" for="row1">
                                                <?= $i ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td title="<?= $p['project_name'] ?>"><a href="#"><?= (empty($p['project_name']) ? "---" : substr($p['project_name'],0,30)) ?></a></td>
                                    <td><?= (empty($p['client']) ? "---" : substr($p['client'],0,30)) ?></td>
                                    <td><?= (empty($p['project_manager']) ? "---" : $p['project_manager']) ?></td>
                                    <td><?=$p['due_date'] ?></td>
                                    <td><?=$p['status'] ?></td>
                                </tr>
                            <?php  $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>                           
    <!-- End Active Projects  -->

    <!-- Start Active Projects beyond closed date -->
    <div class="modal fade" id="active_projects_beyond" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Active Projects beyond closed date</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped fs-11" id="active_projects_beyond_table">
                            <thead class="table-dark">
                                <tr>
                                    <th class="fw_500">#</th>
                                    <th>Project Name</th>
                                    <th>Client</th>
                                    <th>Project Manager</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i= 1; foreach($active_date_beyond as $p) { ?>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label" for="row1">
                                                <?= $i ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td title="<?= $p['project_name'] ?>"><a href="#"><?= (empty($p['project_name']) ? "---" : substr($p['project_name'],0,30)) ?></a></td>
                                    <td><?= (empty($p['client']) ? "---" : substr($p['client'],0,30)) ?></td>
                                    <td><?= (empty($p['project_manager']) ? "---" : $p['project_manager']) ?></td>
                                    <td><?=$p['due_date'] ?></td>
                                    <td><?=$p['status'] ?></td>
                                </tr>
                            <?php $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>                                            
    <!-- End Active Projects beyond closed date -->

    <!-- Start Active Milestones-->
    <div class="modal fade" id="active_milestones" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Active Milestones</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped fs-11" id="active_milestones_table">
                            <thead class="table-dark">
                                <tr>
                                    <th class="fw_500">#</th>
                                    <th class="fw_500">Milestone Title</th>
                                    <th class="fw_500">Project</th>
                                    <th class="fw_500">Manager</th>
                                    <th class="fw_500">Deadline</th>
                                    <th class="fw_500">Amount</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i= 1; foreach($active_milestone as $val){ ?>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label" for="row1">
                                                <?= $i ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td><?= (empty($val->title) ? "---" : $val->title) ?></td>
                                    <td><?= (empty($val->project_name) ? "---" : $val->project_name) ?></td>
                                    <td><?= (empty($val->manager_name) ? "---" : $val->manager_name) ?></td>
                                    <td><?= (empty($val->deadline) ? "---" : $val->deadline) ?></td>
                                    <td><?= (($val->amount == '0') ? "---" : "$". number_format($val->amount)) ?></td>
                                </tr>
                            <?php  $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Active Milestones-->

    <!-- Start Active Milestones beyond closed date  -->
    <div class="modal fade" id="active_milestones_beyond" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Active Milestones beyond closed date</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped fs-11" id="active_milestones_beyond_table">
                        <thead class="table-dark">
                                <tr>
                                    <th class="fw_500">#</th>
                                    <th class="fw_500">Milestone Title</th>
                                    <th class="fw_500">Project</th>
                                    <th class="fw_500">Manager</th>
                                    <th class="fw_500">Deadline</th>
                                    <th class="fw_500">Amount</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i= 1; foreach($active_beyond_milstone as $val){ ?>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label" for="row1">
                                                <?= $i ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td><?= (empty($val->title) ? "---" : $val->title) ?></td>
                                    <td><?= (empty($val->project_name) ? "---" : $val->project_name) ?></td>
                                    <td><?= (empty($val->manager_name) ? "---" : $val->manager_name) ?></td>
                                    <td><?= (empty($val->deadline) ? "---" : $val->deadline) ?></td>
                                    <td><?= (($val->amount == '0') ? "---" : "$". number_format($val->amount)) ?></td>
                                </tr>
                            <?php  $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>                                           
    <!-- End Active Milestones beyond closed date -->

    <!-- Start Active Projects without proper allocation -->
    <div class="modal fade" id="active_projects_allocation" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Active Projects without proper allocation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped fs-11" id="active_projects_allocation_table">
                        <thead class="table-dark">
                                <tr>
                                    <th class="fw_500">#</th>
                                    <th class="fw_500">Milestone Title</th>
                                    <th class="fw_500">Project</th>
                                    <th class="fw_500">Manager</th>
                                    <th class="fw_500">Deadline</th>
                                    <th class="fw_500">Amount</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i= 1; foreach($project_allocation as $val){ ?>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label" for="row1">
                                                <?= $i ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td><?= (empty($val->title) ? "---" : $val->title) ?></td>
                                    <td><?= (empty($val->project_name) ? "---" : $val->project_name) ?></td>
                                    <td><?= (empty($val->manager_name) ? "---" : $val->manager_name) ?></td>
                                    <td><?= (empty($val->deadline) ? "---" : $val->deadline) ?></td>
                                    <td><?= (($val->amount == '0') ? "---" : "$". number_format($val->amount)) ?></td>
                                </tr>
                            <?php  $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Active Projects without proper allocation -->

    <!--******************** Start Operations ******************-->

    <!-- Start Late Entries Today -->
    <div class="modal fade" id="late_entries_today" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Late Entries Today</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped fs-11" id="late_entries_today_table">
                        <thead class="table-dark">
                                <tr>
                                    <th class="fw_500">#</th>
                                    <th class="fw_500">Employee</th>
                                    <th class="fw_500">Date</th>
                                    <th class="fw_500">In Time</th>
                                    <th class="fw_500">Out Time</th>
                                    <th class="fw_500">Late By</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i= 1; foreach($late_entry_today as $val){ ?>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label" for="row1">
                                                <?= $i ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td><?= (empty($val->emp) ? "---" : $val->emp) ?></td>
                                    <td><?= (empty($val->dom) ? "---" : $val->dom) ?></td>
                                    <td><?= (empty($val->intime) ? "---" : $val->intime) ?></td>
                                    <td><?= (empty($val->outtime) ? "---" : $val->outtime) ?></td>
                                    <td>
                                        <?php 
                                            $timeParts = explode('.', $val['early_late_by']);
                                            $timeWithoutFraction = $timeParts[0];
                                            echo $timeWithoutFraction;
                                        ?>
                                    </td>
                                </tr>
                            <?php  $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>                           
    <!-- End Late Entries Today -->

    <!-- Start Early Exits Yesterday -->
    <div class="modal fade" id="early_exits_yesterday" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Early Exits Yesterday</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped fs-11" id="early_exits_yesterday_table">
                            <thead class="table-dark">
                                <tr>
                                    <th class="fw_500">#</th>
                                    <th class="fw_500">Employee</th>
                                    <th class="fw_500">Date</th>
                                    <th class="fw_500">In Time</th>
                                    <th class="fw_500">Out Time</th>
                                    <th class="fw_500">Early Exit</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i= 1; foreach($early_exit as $val){ ?>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label" for="row1">
                                                <?= $i ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td><?= (empty($val->emp) ? "---" : $val->emp) ?></td>
                                    <td><?= (empty($val->dom) ? "---" : $val->dom) ?></td>
                                    <td><?= (empty($val->intime) ? "---" : $val->intime) ?></td>
                                    <td><?= (empty($val->outtime) ? "---" : $val->outtime) ?></td>
                                    <td>
                                        <?php 
                                            $timeParts = explode('.', $val['early_exit_by']);
                                            $timeWithoutFraction = $timeParts[0];
                                            echo $timeWithoutFraction;
                                        ?>
                                    </td>
                                </tr>
                            <?php  $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>                           
    <!-- End Early Exits Yesterday -->

    <!-- Start Late Entries Yesterday -->
    <div class="modal fade" id="late_entries_yesterday" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Late Entries Yesterday</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped fs-11" id="late_entries_yesterday_table">
                        <thead class="table-dark">
                                <tr>
                                    <th class="fw_500">#</th>
                                    <th class="fw_500">Employee</th>
                                    <th class="fw_500">Date</th>
                                    <th class="fw_500">In Time</th>
                                    <th class="fw_500">Out Time</th>
                                    <th class="fw_500">Early Exit</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i= 1; foreach($late_entry_yestarday as $val){ ?>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label" for="row1">
                                                <?= $i ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td><?= (empty($val->emp) ? "---" : $val->emp) ?></td>
                                    <td><?= (empty($val->dom) ? "---" : $val->dom) ?></td>
                                    <td><?= (empty($val->intime) ? "---" : $val->intime) ?></td>
                                    <td><?= (empty($val->outtime) ? "---" : $val->outtime) ?></td>
                                    <td>
                                        <?php 
                                            $timeParts = explode('.', $val['early_late_by']);
                                            $timeWithoutFraction = $timeParts[0];
                                            echo $timeWithoutFraction;
                                        ?>
                                    </td>
                                </tr>
                            <?php  $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>                          
    <!-- End Late Entries Yesterday -->

    <!-- Start People on Leave/ WFH Today -->
    <div class="modal fade" id="people_on_leave" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">People on Leave/ WFH Today</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped fs-11" id="people_on_leave_table">
                        <thead class="table-dark">
                                <tr>
                                    <th class="fw_500">#</th>
                                    <th>Name</th>
                                    <th>Leave Type</th>
                                    <th style="width:100px;">Subject</th>
                                    <th>Applied on</th>
                                    <th style="width:100px;">Leave Date</th>
                                    <th style="width:10%;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i= 1; foreach($today_leave_wfh as $val){ ?>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label" for="row1">
                                                <?= $i ?>
                                            </label>
                                        </div>
                                    </td>

                                   
                                    <td><?= (empty($val->emp_name) ? "---" : $val->emp_name) ?></td>
                                    <td><?= (empty($val->leave_type) ? "---" : $val->leave_type) ?></td>
                                    <td><?= (empty($val->subject) ? "---" : $val->subject) ?></td>
                                    <td><?= (empty($val->applied_on) ? "---" : date('d-m-Y',strtotime($val['applied_on']))) ?></td>
                                    <td>
                                    <?= date('d-m-Y',strtotime($val['from_date'])) ?> to <?= date('d-m-Y',strtotime($val['to_date'])) ?>
                                    </td>
                                    <td>
                                    <?php
                                        if ($val['status'] == 'Approved') :
                                    ?>
                                    <span class="badge badge-success"><?= $val['status'] ?></span>
                                    <?php
                                        endif;
                                             if ($val['status'] == 'Pending') :
                                    ?>
                                    <span class="badge badge-info"><?= $val['status'] ?></span>
                                    <?php
                                        endif;
                                    ?>
                                    </td>
                                </tr>
                                <?php  $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>                           
    <!-- End People on Leave/ WFH Today -->

    <!-- Start Current Month Timesheets <90%  -->
    <div class="modal fade" id="timesheet_month_list" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Current Month Timesheets <90%></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped fs-11" id="timesheet_month_list_table">
                        <thead class="table-dark">
                                <tr>
                                    <th class="fw_500">#</th>
                                    <th>Employee</th>
                                    <th>Manager</th>
                                    <th>Month</th>
                                    <th>Hours</th>
                                    <th>Filled</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i= 1; foreach($timesheet_month_list as $val){ ?>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label" for="row1">
                                                <?= $i ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td><?= (empty($val['name']) ? "---" : $val['name']) ?></td>
                                    <td><?= (empty($val['manager']) ? "---" : $val['manager']) ?></td>
                                    <td> <?= $val['month_year'] ?></td>
                                    <td><?= (empty($val['hours']) ? "---" : $val['hours']) ?></td>
                                    <td><?= (empty($val['filled']) ? "---" : $val['filled']) ?></td>
                                    
                                </tr>
                                <?php  $i++; } ?>
                            </tbody>               
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Current Month Timesheets <90%  -->

    <!-- Start Last week Timesheets <90%  -->
    <div class="modal fade" id="weekly_timesheet_report" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-14 fw-semibold" id="exampleModalLabel">Last week Timesheets <90% </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped fs-11" id="weekly_timesheet_report_table">
                        <thead class="table-dark">
                                <tr>
                                    <th class="fw_500">#</th>
                                    <th>Employee</th>
                                    <th>Manager</th>
                                    <th style="width:100px;">Week Date</th>
                                    <th>Hours</th>
                                    <th>Filled</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i= 1; foreach($weekly_timesheet_report as $val){ ?>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label" for="row1">
                                                <?= $i ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td><?= (empty($val->emp_name) ? "---" : $val->emp_name) ?></td>
                                    <td><?= (empty($val->manager_name) ? "---" : $val->manager_name) ?></td>
                                    <td> <?= $start_week_date ?> To <?= $end_week_date ?></td>
                                    <td><?= (empty($val->hours) ? "---" : $val->hours) ?></td>
                                    <td><?= (empty($val->filled_percent) ? "---" : $val['filled_percent']. "%") ?></td>
                                    
                                </tr>
                                <?php  $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Last week Timesheets <90%  -->

    <!--******************** End Operations ******************-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- tooltip -->
    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>
    <!-- tooltip -->

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        var options = {
            series: [{
                name: "Current Year",
                data: [<?= implode(', ', $revenue_chart) ?>]
            }, {
                name: "Previous Year",
                data: [<?= implode(', ', $last_revenue_chart) ?>]
            }],
            chart: {
                type: 'bar',
                height: 165,
                parentHeightOffset: 0,
                toolbar: {
                    show: false
                },
            },
            colors: ["#3fd5db", "#333333"],
            plotOptions: {
                bar: {
                    borderRadius: 0,
                    columnWidth: '60%',
                    horizontal: false,
                    barHeight: '90%',
                    distributed: false,
                    rangeBarOverlap: false,
                }
            },
            dataLabels: {
                enabled: false,
                offsetX: -6,
                style: {
                    fontSize: '12px',
                    colors: ['#fff']
                }
            },
            grid: {
                show: false,
            },
            stroke: {
                show: true,
                width: 0.2,
                colors: ['#fff']
            },
            tooltip: {
                shared: true,
                intersect: false
            },
            xaxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            },
            yaxis: {
                min: 0,
                // max: 1200000
            },
        };

        var chart = new ApexCharts(document.querySelector("#curr_month_revenue_last"), options);
        chart.render();
    </script>
    <script>
        var options = {
            chart: {
                height: 200,
                width: "100%",
                type: "donut",
            },
            colors: ["#3fd5db", "#324B4C", "#95B0B1", "#FFB995", "#C68362","#C90362"],
            dataLabels: {
                enabled: true,
                offsetX: -6,
                dropShadow: false,
                style: {
                    fontSize: "10px",
                    textOutline: false,
                }
            },
            grid: {
                padding: {
                    top: -8,
                    bottom: -10
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '40%',
                    }
                },
            },
            series: [<?= implode(', ', $stage_by_chart) ?>],
            labels: ["Contact", "Req. Gath", "Proposing", "Proposed", "Follow up","Stale"],
            legend: {
                show: false
            }
        };
        var chart = new ApexCharts(document.querySelector("#active_opps"), options);
        chart.render();
    </script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gauge.js/1.2.1/gauge.min.js"></script>
    <script>
       var opts = {
            angle: -0.04, // The span of the gauge arc
            lineWidth: 0.34, // The line thickness
            radiusScale: 0.79, // Relative radius
            pointer: {
                length: 0.55, // // Relative to gauge radius
                strokeWidth: 0.04, // The thickness
                color: '#324B4C' // Fill color
            },
            limitMax: false,     // If false, the max value of the gauge will be updated if value surpass max
            limitMin: false,     // If true, the min value of the gauge will be fixed unless you set it manually
            colorStart: '#30e4d2',   // Colors
            colorStop: '#30e4d2',    // just experiment with them
            strokeColor: '#adfff3',  // to see which ones work best for you
            generateGradient: true,
            highDpiSupport: true,     // High resolution support
            staticZones: [
                {strokeStyle: "#30e4d2", min: 0, max: 150},
                {strokeStyle: "#adfff3", min: 150, max: 220}, // Green
            ],
            staticLabels: {
                    font: "10px sans-serif",  // Specifies font
                    labels: [0, 220],  // Print labels at these values
                    color: "#000000",  // Optional: Label text color
                    fractionDigits: 0,  // Optional: Numerical precision. 0=round off.
                },
            };
            var target = document.getElementById('revenue_opps'); // your canvas element
            var gauge = new Gauge(target).setOptions(opts); // create sexy gauge!
            gauge.maxValue = <?= $revenue_actual_chart->total_expected_amount ?>; // set max gauge value
            gauge.setMinValue(0);  // Prefer setter over gauge.minValue = 0
            gauge.animationSpeed = 30; // set animation speed (32 is default value)
            gauge.setTextField(document.getElementById("preview-textfield"));
            gauge.set(<?= $revenue_actual_chart->total_probability_percentage ?>); // set actual value
    </script>

    <!-- for Datatables  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <!-- for datatables -->
    <!-- datatable -->
    <script>
        $(document).ready(function () {
            $('#current_revenue_table').DataTable();
            $('#ytd_revenue_table').DataTable();
            $('#milestone_revenue_table').DataTable();
            $('#active_opps_updated_table').DataTable();
            $('#new_project_table').DataTable();

            // Delivery Section 
            $('#active_projects_table').DataTable();
            $('#active_projects_beyond_table').DataTable();
            $('#active_milestones_table').DataTable();
            $('#active_milestones_beyond_table').DataTable();
            $('#active_projects_allocation_table').DataTable();
            // End 

            // Operation Section 
            $('#late_entries_today_table').DataTable();
            $('#early_exits_yesterday_table').DataTable();
            $('#late_entries_yesterday_table').DataTable();
            $('#people_on_leave_table').DataTable();
            $('#timesheet_month_list_table').DataTable();
            $('#weekly_timesheet_report_table').DataTable();
            // End 
        });
    </script>
    <!-- table tr add border -->
    <script>
        $('#current_revenue_table input:checkbox').change(function () {
            if ($(this).is(":checked")) {
                $(this).closest('tr').addClass("tr-borderleft table-info");
            } else {
                $(this).closest('tr').removeClass("tr-borderleft table-info");
            }
        });
    </script>
    <!-- table tr add border -->

<!-- Delivery Chart  -->
<script>
        var options = {
            chart: {
                height: 280,
                width: "100%",
                type: "donut",
            },
            colors: ["#3fd5db", "#324B4C", "#95B0B1"],
            dataLabels: {
                enabled: true,
                offsetX: -6,
                dropShadow: false,
                style: {
                    fontSize: "10px",
                    textOutline: false,
                }
            },
            grid: {
                padding: {
                    top: -8,
                    bottom: -10
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '30%',
                    }
                },
            },
            series: [<?= implode(', ', $milestone_chart_amount) ?>],
            labels: <?= $milestone_chart_status ?>,
            legend: {
                show: true
            }
        };
        var chart = new ApexCharts(document.querySelector("#chartdiv"), options);
        chart.render();
    </script>
<!-- End  -->