<?php
/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

/*
 * This file is loaded in the context of the `Application` class.
  * So you can use  `$this` to reference the application class instance
  * if required.
 */
return function (RouteBuilder $routes): void {
    /*
     * The default class to use for all routes
     *
     * The following route classes are supplied with CakePHP and are appropriate
     * to set as the default:
     *
     * - Route
     * - InflectedRoute
     * - DashedRoute
     *
     * If no call is made to `Router::defaultRouteClass()`, the class used is
     * `Route` (`Cake\Routing\Route\Route`)
     *
     * Note that `Route` does not do any inflections on URLs which will result in
     * inconsistently cased URLs when used with `{plugin}`, `{controller}` and
     * `{action}` markers.
     */
    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {
        /*
         * Here, we are connecting '/' (base path) to a controller called 'Pages',
         * its action called 'display', and we pass a param to select the view file
         * to use (in this case, templates/Pages/home.php)...
         */
        //$builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);
        
        $builder->connect('/', ['controller' => 'Users', 'action' => 'login']);
        $builder->connect('/signup', ['controller' => 'Users', 'action' => 'signup']);
        $builder->connect('/logout', ['controller' => 'Users', 'action' => 'logout']);
        $builder->connect('/change-password', ['controller' => 'Users', 'action' => 'changePassword']);
        
        $builder->connect('/dashboard', [ 'controller' => 'Dashboard', 'action' => 'index' ]);

        /*
         * ...and connect the rest of 'Pages' controller's URLs.
         */
        // $builder->connect('/pages/*', 'Pages::display');

        /*
         * Connect catchall routes for all controllers.
         *
         * The `fallbacks` method is a shortcut for
         *
         * ```
         * $builder->connect('/{controller}', ['action' => 'index']);
         * $builder->connect('/{controller}/{action}/*', []);
         * ```
         *
         * You can remove these routes once you've connected the
         * routes you want in your application.
         */

        $builder->connect('/companies', ['controller' => 'Companies', 'action' => 'index']);
        $builder->connect('/add-project/*', ['controller' => 'Companies', 'action' => 'addProject']);
        $builder->connect('/add-opportunity/*', ['controller' => 'Companies', 'action' => 'addOpportunity']);
        $builder->connect('/list-project/*', ['controller' => 'Companies', 'action' => 'listProject']);
        $builder->connect('/active-project/*', ['controller' => 'Companies', 'action' => 'activeProject']);
        $builder->connect('/my-project', ['controller' => 'Companies', 'action' => 'myProject']);
        $builder->connect('/edit-project/*', ['controller' => 'Companies', 'action' => 'editProject']);
        $builder->connect('/edit-opportunity/*', ['controller' => 'Companies', 'action' => 'editOpportunity']);
        $builder->connect('/project-view/*', ['controller' => 'Companies', 'action' => 'viewProject']);
        $builder->connect('/project-payment/*', ['controller' => 'Companies', 'action' => 'paymentProject']);
        $builder->connect('/list-opportunity/*', ['controller' => 'Companies', 'action' => 'opportunity']);

        // Maintenance section routes 
        $builder->connect('/plan/*', ['controller' => 'Plans', 'action' => 'index']);
        $builder->connect('/support-plans/*', ['controller' => 'Companies', 'action' => 'supportPlans']);
        // End 

        $builder->connect('/clients', ['controller' => 'Clients', 'action' => 'index']);
        $builder->connect('/client_detail/*', ['controller' => 'Clients', 'action' => 'clientView']);

        $builder->connect('/users', ['controller' => 'Users', 'action' => 'index']);
        $builder->connect('/user-detail/*', ['controller' => 'Users', 'action' => 'userView']);
        $builder->connect('/timesheet_1', ['controller' => 'Users', 'action' => 'timesheet']);
        $builder->connect('/prev-week/*', ['controller' => 'Users', 'action' => 'prevWeek']);
        $builder->connect('/next-week/*', ['controller' => 'Users', 'action' => 'nextWeek']);
        $builder->connect('/timesheet_report/*', ['controller' => 'Users', 'action' => 'timesheetReport']);
        $builder->connect('/punch_report/*', ['controller' => 'Users', 'action' => 'employeePunchTimeReport']);
        $builder->connect('/timesheet_filled_report/*', ['controller' => 'Users', 'action' => 'employeeTimesheetFilledReport']);
        $builder->connect('/attendance/*', ['controller' => 'Users', 'action' => 'attendancePunchTimeReport']);
        $builder->connect('/export_punch_time_report/*', ['controller' => 'Users', 'action' => 'exportPunchTimeReport']);


        $builder->connect('/revenue/*', ['controller' => 'Reports', 'action' => 'revenues']);

        $builder->connect('/myteam/*', ['controller' => 'Users', 'action' => 'myteam']);

        $builder->connect('/mytask', ['controller' => 'Users', 'action' => 'mytask']);
        $builder->connect('/costing', ['controller' => 'Salaries', 'action' => 'list']);
        $builder->connect('/addTask', ['controller' => 'Users', 'action' => 'addTask']);
        $builder->connect('/completedmytask/*', ['controller' => 'Users', 'action' => 'completedmytask']);

        $builder->connect('/approvedtask', ['controller' => 'Users', 'action' => 'approvedtask']);

        $builder->connect("/mytask/*", ['controller' => "Users", "action" => "mytask"]);
        $builder->connect('/users/forgotpassword', ['controller' => 'Users', 'action' => 'forgotpassword']);
        $builder->connect('/users/resetpassword', ['controller' => 'Users', 'action' => 'resetpassword']);

        $builder->connect('/report2/*', ['controller' => 'Users', 'action' => 'report2']);
        $builder->connect('/roles_responsibilities', ['controller' => 'CareerTracks', 'action' => 'rolesResponsibilities']);
        $builder->connect('/training', ['controller' => 'Training', 'action' => 'index']);
        $builder->connect('/credential', ['controller' => 'CredentialsMgts', 'action' => 'credentials']);
        $builder->connect('/record', ['controller' => 'Users', 'action' => 'records']);
        $builder->connect('/publish_notice', ['controller' => 'PublishNotice', 'action' => 'index']);
        $builder->connect('/opening', ['controller' => 'Hiring', 'action' => 'index']);
        $builder->connect('/candidate-list', ['controller' => 'Hiring', 'action' => 'CandidateList']);
        $builder->connect('/profile', ['controller' => 'Users', 'action' => 'userProfile']);
        $builder->connect('/contract', ['controller' => 'Contract', 'action' => 'index']);
        $builder->connect('/add-contract', ['controller' => 'Contract', 'action' => 'addContract']);
        $builder->connect('/entity', ['controller' => 'BillingEntity', 'action' => 'index']);
        $builder->connect('/add-entity', ['controller' => 'BillingEntity', 'action' => 'addEntity']);
        $builder->connect('/edit-entity', ['controller' => 'BillingEntity', 'action' => 'editEntity']);
        $builder->connect('/draft-invoice', ['controller' => 'BillingEntity', 'action' => 'draftInvoice']);
        $builder->connect('/sent-invoice', ['controller' => 'BillingEntity', 'action' => 'sentInvoice']);
        $builder->connect('/paid-invoice', ['controller' => 'BillingEntity', 'action' => 'paidInvoice']);
        $builder->connect('/all-invoice', ['controller' => 'BillingEntity', 'action' => 'allInvoice']);
        $builder->connect('/partial-invoice', ['controller' => 'BillingEntity', 'action' => 'partialInvoice']);
        $builder->connect('/employee-attendance', ['controller' => 'Salaries', 'action' => 'employeeAttendance']);
        $builder->connect('/invoice_pdf', ['controller' => 'BillingEntity', 'action' => 'invoiceFilePdf']);
        $builder->connect('/edit-profile', ['controller' => 'EmployeeDetails', 'action' => 'editProfile']);
        // Dashboard 
        $builder->connect('/score-card/*', ['controller' => 'ScoreCard', 'action' => 'index']);
        $builder->connect('/assets-list/*', ['controller' => 'AssetAssignedEntries', 'action' => 'assetsList']);


        $builder->fallbacks();
    });

    /*
     * If you need a different set of middleware or none at all,
     * open new scope and define routes there.
     *
     * ```
     * $routes->scope('/api', function (RouteBuilder $builder): void {
     *     // No $builder->applyMiddleware() here.
     *
     *     // Parse specified extensions from URLs
     *     // $builder->setExtensions(['json', 'xml']);
     *
     *     // Connect API actions here.
     * });
     * ```
     */
};
