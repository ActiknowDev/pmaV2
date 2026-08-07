<?php $session = new \Cake\Http\Session();
$userSession = $session->read('data');
$menu = $session->read('menu');
?>
<div class="app-header">
    <nav class="bg-base">
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fa fa-bars"></i>
        </label>
        <div class="logo logo-sm logo-light">
            <a href=""><span>PMA</a>
        </div>
        <ul class="nav-links">
            <!-- 
       <li><?= $this->Html->link('Employee', '/EmployeeDetails/index', ['class' => 'link']); ?></li>
       -->
       <?php
               if (($userSession['role'] != 3) || ($userSession['role'] == 3 && array_intersect($userSession['role_name'], array(10,4,13)))) {
                  ?>
            <div class="account-setting">
                <ul>
                    <li class="dropdown">
                        <a href="#" class="link dropdown-toggle" id="" data-toggle="dropdown">
                            Reports
                        </a>
                        <div class="dropdown-menu nav-dropdown">
                        <!-- <= $this->Html->link('Timesheet Report', ['class' => 'link', 'controller' => 'Companies', 'action' => 'timesheetRecord']) ?> -->
                        <?= $this->Html->link('Resources Allocation', ['class' => 'link', 'controller' => 'Users', 'action' => 'timesheetReport']) ?>
                        <?= $this->Html->link('Punch Time Report', ['class' => 'link', 'controller' => 'Users', 'action' => 'EmployeePunchTimeReport']) ?>
                        <?= $this->Html->link('Timesheet Filled Report', ['class' => 'link', 'controller' => 'Users', 'action' => 'employeeTimesheetFilledReport']) ?>
                        <!-- <?= $this->Html->link('Employee Attendance', '/employee-attendance', ['class' => 'link']); ?> -->
                        <?= $this->Html->link('Employee Leaves', '/users/user-total-leave-report', ['class' => 'link']); ?>
                        <?= $this->Html->link('Employee Score Card', ['class' => 'link', 'controller' => 'ScoreCard', 'action' => 'index']) ?>
                   
                    </div>
                    </li>
                </ul>
            </div>
            <?php  } ?>

            <?php if ($userSession['role'] != 0 && $menu != 0) { ?>

            <?php if (($userSession['role'] != 3) || ($userSession['role'] == 3 && array_intersect($userSession['role_name'], array(4, 6, 9, 10)))) { ?>
            <li><?= $this->Html->link('Client', '/clients', ['class' => 'link']); ?></li>

            <?php } ?>

            <li><?= $this->Html->link('Projects', '/my-project', ['class' => 'link']); ?></li>

            <?php
                if ($userSession['role'] != 1) { ?>

            <li><?= $this->Html->link('Timesheet', $this->Url->build('/timesheet_1', ['fullBase' => true]), ['class' => 'link']); ?>
            </li>

            <li><?= $this->Html->link('My Task', '/mytask', ['class' => 'link']); ?></li>

            <?php }
                if (($userSession['type'] == 'Admin') && ($userSession['role'] != 0)) { ?>

            <li><?= $this->Html->link('Go Back', '/companies', ['class' => 'link']); ?></li>

            <?php } ?>

            <?php } ?>

            <!-- <?php if (($userSession['role'] != 3) || (($userSession['role'] == 3) && (array_intersect($userSession['role_name'], array(4, 6, 9, 10))))) { ?>
            <div class="account-setting">
                <ul>
                    <li class="dropdown">
                        <a href="#" class="link dropdown-toggle" id="" data-toggle="dropdown">
                            Contract
                        </a>
                        <div class="dropdown-menu nav-dropdown">
                            <?= $this->Html->link('Contract', '/contract', ['class' => 'link']); ?>
                            <?= $this->Html->link('Entity', '/entity', ['class' => 'link']); ?>
                            <?= $this->Html->link('Invoice', '/draft-invoice', ['class' => 'link']); ?>
                        </div>
                    </li>
                </ul>
            </div>
            <?php } ?> -->

            <?php if (($userSession['role'] != 3) || (($userSession['role'] == 3) && (array_intersect($userSession['role_name'], array(4, 6, 9, 10, 12))))) { ?>
            <div class="account-setting">
                <ul>
                    <li class="dropdown">
                        <a href="#" class="link dropdown-toggle" id="" data-toggle="dropdown">
                            Revenue
                        </a>
                        <div class="dropdown-menu nav-dropdown">
                            
                            <?php
                                if (array_intersect($userSession['role_name'], array(9)))
                                    echo $this->Html->link('Revenue', '/revenue', ['class' => 'link']);
                                ?>
                            <?php
                                if (array_intersect($userSession['role_name'], array(12)))
                                    echo $this->Html->link('Cost', '/costing', ['class' => 'link']);
                                ?>
                            <?php
                                echo $this->Html->link('Revenue Dashboard', '/dashboard', ['class' => 'link']);
                            ?>
                    
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Client Management section -->
            <div class="account-setting">
                <ul>
                    <li class="dropdown">
                        <a href="#" class="link dropdown-toggle" id="" data-toggle="dropdown">
                        Maintenance 
                        </a>
                        <div class="dropdown-menu nav-dropdown">
                        <?= $this->Html->link('Plans', '/plan', ['class' => 'link']); ?>
                        <?= $this->Html->link('Clients', '/support-plans', ['class' => 'link']); ?>
                        <?= $this->Html->link('Tickets', '/tickets', ['class' => 'link']); ?>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- End  -->
            <?php } ?>

            <li>
                <?php
                if (array_intersect($userSession['role_name'], array(4, 6, 9, 10))) {
                    // echo $this->Html->link("Assets", ["class" => "index", "controller" => "AssetAssignedEntries"]);
                ?>
                <!-- <a href="<?//= $this->Url->build('/asset-assigned-entries') ?>">Assets</a> -->
                <a href="<?= $this->Url->build('/assets-list') ?>">Assets</a>
                <?php
                }
                ?>
            </li>
        </ul>
        <div class="account-setting">
            <ul>
                <li class="dropdown">
                    <a href="#" class="link dropdown-toggle" id="" data-toggle="dropdown">
                        <?= $userSession['name']; ?>
                    </a>
                    <div class="dropdown-menu nav-dropdown">
                    <?= $this->Html->link('Profile', '/profile', ['class' => 'link']); ?>
                        <?php if (($userSession['role'] == 3) && (array_intersect($userSession['role_name'], array(4)))) { ?>
                        <?= $this->Html->link('Team', '/myteam', ['class' => 'link']); ?>
                        <!-- <?= $this->Html->link('Management Report', ['class' => 'link', 'controller' => 'Companies', 'action' => 'managerReport']) ?> -->
                        <?= $this->Html->link('Opportunity', '/list-opportunity', ['class' => 'link']); ?>
                        <?php } ?>
                        <?= $this->Html->link('My Leaves', ['class' => 'link', 'controller' => 'Leaves', 'action' => 'index']) ?>
                        
                        <?= $this->Html->link('Change Password', '/change-password', ['class' => 'link']); ?>
                        
                            <?php
                            if (($userSession['role'] != 3) || ($userSession['role'] == 3 && array_intersect($userSession['role_name'], array(12))))
                                echo $this->Html->link('User', '/users', ['class' => 'link']);

                            echo $this->Html->link('Edit Profile', '/edit-profile', ['class' => 'link']);
                            ?>
                        <?php if (array_intersect($userSession['role_name'], array(12,4))) { ?>
                        <?= $this->Html->link('Training', '/training', ['class' => 'link']); ?>
                        <?php } ?>
                        <?= $this->Html->link('Roles & Responsibilities', '/roles_responsibilities', ['class' => 'link']); ?>

                        <!-- Publish Notic -->

                        <?= $this->Html->link('Notice', '/publish_notice', ['class' => 'link']); ?>

                        <?php if (($userSession['role'] != 3) || ($userSession['role'] == 3 && array_intersect($userSession['role_name'], array(4, 6, 9, 10)))) { ?>
                        <!-- <?= $this->Html->link('Hiring Opening', '/opening', ['class' => 'link']); ?> -->
                        <?php } ?>

                        <?php if (($userSession['role'] != 3) || ($userSession['role'] == 3 && array_intersect($userSession['role_name'], array(4, 6, 9, 10)))) { ?>
                        <!-- <?= $this->Html->link('Candidate List', '/candidate-list', ['class' => 'link']); ?> -->
                        <?php } ?>

                        
                        <?= $this->Html->link('Attendance', ['class' => 'link', 'controller' => 'Users', 'action' => 'attendancePunchTimeReport']) ?>

                        <?php
                        if (($userSession['role'] != 3) || ($userSession['role'] == 3 && array_intersect($userSession['role_name'], array(4, 6, 9, 10)))) {
                        ?>
                        <!-- <?= $this->Html->link('Upwork Data', '/companies/upworkData', ['class' => 'link']) ?> -->

                        <?php
                        }
                        ?>

                        <?php
                        if (array_intersect($userSession['role_name'], array(4, 6, 12))) {
                            // echo $this->Html->link("Assets", ["class" => "index", "controller" => "AssetAssignedEntries"]);
                        ?>
                        <?= $this->Html->link('Holidays', '/holidays', ['class' => 'link']); ?>
                        <?php
                        }
                        ?>
                        <?= $this->Html->link(
                            'POSH Training',
                            ['controller' => 'Users', 'action' => 'poshTraining'],
                            ['class' => 'link']
                        ); ?>

                        <?= $this->Html->link('Logout', '/logout', ['class' => 'link']); ?>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>