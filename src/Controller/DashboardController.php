<?php
namespace App\Controller;
use Cake\ORM\Query;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\ConnectionManager;
// use Cake\Datasource\ConnectionManager;
use Cake\Http\Client;

class DashboardController extends AppController
{
	public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('Paginator');
        $this->loadComponent('Flash');
        // $this->loadModel('ProjectMilestones');
        // $this->loadModel('Projects');
        // $this->loadModel('SupportPlan');
        // $this->loadModel('Opportunity');
        // $this->loadModel('Users');
        // $this->loadModel('Leaves');
        // $this->loadModel('EmployeePunchTime');
        // $this->loadModel('UserTimesheets');
        $this->Projects = $this->fetchTable('Projects');
        $this->ProjectMilestones = $this->fetchTable('ProjectMilestones');
        $this->SupportPlan = $this->fetchTable('SupportPlan');
        $this->Opportunity = $this->fetchTable('Opportunity');
        $this->Users = $this->fetchTable('Users');
        $this->Leaves = $this->fetchTable('Leaves');
        $this->EmployeePunchTime = $this->fetchTable('EmployeePunchTime');
        $this->UserTimesheets = $this->fetchTable('UserTimesheets');

    }

    public function index(){
      
        $this->viewBuilder()->setLayout('default_new');
		$this->Authorization->skipAuthorization();
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
        
        $date = date('Y-m-d');
        $today = date('Y-m-d');
        $currentMonth = date('m');
        $currentYear  = date('Y');

        if ($currentMonth >= 4) {
            $financial_year_from = $currentYear . '-04-01';
            $financial_year_to   = ($currentYear + 1) . '-03-31';
        } else {
            $financial_year_from = ($currentYear - 1) . '-04-01';
            $financial_year_to   = $currentYear . '-03-31';
        }


        /**
         * Last Month Calculation (FY aware)
         */
        if ($currentMonth == 4) {
            // April → last month is March (previous FY)
            $last_month = 3;
            $last_month_year = $financial_year_from;
        } else {
            $last_month = (int) date('m', strtotime('-1 month'));
            $last_month_year = (int) date('Y', strtotime('-1 month'));
        }

        // previous varriables
        $current_month = date('m');
        $year = date('Y');

       // Last Financial Year
        $last_financial_year_from = date('Y-m-d', strtotime($financial_year_from . ' -1 year'));
        $last_financial_year_to   = date('Y-m-d', strtotime($financial_year_to . ' -1 year'));
        # End Month

        $condition = ['Projects.deleted' => 1, 'Projects.user_id' => 10,'ProjectMilestones.amount !=' => 0, 'Projects.source IN' =>['Regular','External'],'ProjectMilestones.deleted' => 0,'ProjectMilestones.project_id !=' => 0];

        /** Start Current Month Revenue (vs Last Month) */
        $current_month_revenue = $this->getMonthlyRevenue([
            'MONTH(ProjectMilestones.due_date)' => $current_month,
            'YEAR(ProjectMilestones.due_date)' => $year,
            $condition]);

        $last_month_revenue = $this->getMonthlyRevenue([
            'MONTH(ProjectMilestones.due_date)' => $last_month,
            'YEAR(ProjectMilestones.due_date)' => $last_month_year,
            $condition]);

        $current_month_revenue_list = $this->getMonthlyRevenueList(['MONTH(ProjectMilestones.due_date)' => $current_month,'YEAR(ProjectMilestones.due_date)' => $year, $condition]);
        /** End Current Month Revenue (vs Last Month) */

        /** Start YTD Revenue (vs Last Year)  */
        $ytd_revenue =  $this->getMonthlyRevenue(['date(ProjectMilestones.due_date) >=' => $financial_year_from, 'date(ProjectMilestones.due_date) <=' => date('Y-m-d'),$condition]);

        $last_ytd_revenue = $this->getMonthlyRevenue(['date(ProjectMilestones.due_date) >=' => date('Y-m-d', strtotime($last_financial_year_from)), 'date(ProjectMilestones.due_date) <=' => $last_financial_year_to, $condition]);

        $ytd_revenue_list = $this->getMonthlyRevenueList(['date(ProjectMilestones.due_date) >=' => $financial_year_from, 'date(ProjectMilestones.due_date) <=' => date('Y-m-d'),$condition]);
        /** End YTD Revenue (vs Last Year) */

        /** Start Active opps updated > & days Ago */ 
        $opportunity = $this->Opportunity->find()
        ->select([
            'count' => $this->Opportunity->find()->func()->count('id'),
            'updated_date' => $this->Opportunity->find()->func()->max('updated_at')
        ])
        ->where([
            'deleted' => 0,
            'stage NOT IN' => [7, 8],
            'created_at >=' => $financial_year_from,
            'created_at <=' => $financial_year_to
        ])
        ->first();

        $daysAgo = $this->daysAgo($opportunity->updated_date);

        $last_opportunity = $this->Opportunity->find()
        ->select([
            'count' => $this->Opportunity->find()->func()->count('id')
        ])
        ->where([
            'deleted' => 0,
            'stage NOT IN' => [7, 8],
            'created_at >=' => $last_financial_year_from,
            'created_at <=' => $last_financial_year_to
        ])
        ->first();

        $opportunity_list = $this->Opportunity->find()
        ->select([
            'opportunity_name' => 'Opportunity.opportunity_name',
            'id' => 'Opportunity.id',
            'client_name' => 'Opportunity.client_name',
            'stage' => 'Stage.name',
            'type' => 'Opportunity.type',
            'assigne_name' => 'Users.name',
            'expected_amount' => 'Opportunity.expected_amount',
            'probability' => 'Opportunity.probability',
            'next_step' => 'Opportunity.next_step',
            'expected_closed_date' => 'Opportunity.expected_closed_date',
            'probability_name' => 'Probability.name',
            'probability_percentage' => 'Probability.percentage',
            'probability_color_code' => 'Probability.color_code'
        ])
        ->join([
            'Users' => [
                'table' => 'users',
                'type' => 'LEFT',
                'conditions' => 'Users.id = Opportunity.assigned_to'
            ],
            'Probability' => [
                'table' => 'probability',
                'type' => 'LEFT',
                'conditions' => 'Probability.id = Opportunity.probability'
            ],
            'Stage' => [
                'table' => 'opportunity_stage',
                'type' => 'LEFT',
                'conditions' => 'Stage.id = Opportunity.stage'
            ],
        ])
        ->where([
            'Opportunity.deleted' => 0,
            'Opportunity.stage NOT IN' => [7, 8],
            'Opportunity.created_at >=' => $financial_year_from,
            'Opportunity.created_at <=' => $financial_year_to
        ])
        ->order(['Opportunity.id' => 'desc'])
        ->toArray();
        /** End Active opps updated > & days Ago */

        /** Start New Projects */
        $last_con = ['CASE WHEN Projects.extend_date IS NULL THEN Projects.due_date ELSE Projects.extend_date END >=' => date('Y-m-d', strtotime($last_financial_year_from)),
        'CASE WHEN Projects.extend_date IS NULL THEN Projects.due_date ELSE Projects.extend_date END <=' => date('Y-m-d', strtotime($last_financial_year_to))];
        $current_con = ['CASE WHEN Projects.extend_date IS NULL THEN Projects.due_date ELSE Projects.extend_date END >=' => $financial_year_from,
        'CASE WHEN Projects.extend_date IS NULL THEN Projects.due_date ELSE Projects.extend_date END <=' => $financial_year_to];

        $current_year_project = $this->getProjectList($current_con);   

        $last_year_project = $this->Projects->find()
        ->select([
                  'id' => 'count(Projects.id)'
                  ])
        ->where(['Projects.deleted' => 1, 'Projects.user_id' => 10, 'Projects.source IN' =>['Regular','External'], $last_con])
        ->count();
        /** End New Projects */
        /** START Client on Maintainance & ARR **/
        //  $current_maintainace_plan = $this->SupportPlan->find()->Select(['id' => 'count(id)','amount' =>'SUM(amount)'])->where(['deleted' => 0, 'status' => 1, 'start_date >=' => $financial_year_from,
        //  'start_date <=' => $financial_year_to])->first();
        $current_maintainace_plan = $this->SupportPlan->find()
            ->select([
                'id' => 'COUNT(DISTINCT SupportPlan.project_id)',
                // 'id' => 'COUNT(SupportPlan.id)',
                'amount' => 'SUM(SupportPlan.amount)'
            ])
            ->join([
                'Payments' => [
                    'table' => 'support_plans_payments',
                    'type' => 'LEFT',
                    'conditions' => [
                        'Payments.project_id = SupportPlan.project_id',
                        'Payments.invoice_sent' => 1
                    ]
                ]
            ])
            ->where([
                'SupportPlan.deleted' => 0,
                'SupportPlan.status' => 1,
                'Payments.start_date >=' => $financial_year_from,
                'Payments.start_date <=' => $financial_year_to
            ])
            ->first();

            $last_maintainace_plan = $this->SupportPlan->find()
                ->select([
                    'id' => 'COUNT(DISTINCT SupportPlan.project_id)',
                    // 'id' => 'COUNT(SupportPlan.id)',
                    'amount' => 'SUM(SupportPlan.amount)'
                ])
                ->join([
                    'Payments' => [
                        'table' => 'support_plans_payments',
                        'type' => 'LEFT',
                        'conditions' => [
                            'Payments.project_id = SupportPlan.project_id',
                            'Payments.invoice_sent' => 1
                        ]
                    ]
                ])
                ->where([
                    'SupportPlan.deleted' => 0,
                    'SupportPlan.status' => 1,
                    'Payments.start_date >=' => date('Y-m-d', strtotime($last_financial_year_from)),
                    'Payments.start_date <=' => date('Y-m-d', strtotime($last_financial_year_to))
                ])
                ->first();
            
        //  $last_maintainace_plan = $this->SupportPlan->find()->Select(['id' => 'count(id)','amount' =>'SUM(amount)'])->where(['deleted' => 0, 'status' => 1, 'start_date >=' => date('Y-m-d', strtotime($last_financial_year_from)),
        //  'start_date <=' => date('Y-m-d', strtotime($last_financial_year_to))])->first();

         $maintainace_plan_list = $this->SupportPlan->find()
         ->select([
            //  'id' => 'SupportPlan.id',
             'name' => 'Plans.plan_name',
             'client_name' => 'Clients.client_name',
             'assigne_name' => 'Assignees.name',
             'project_name'     => 'Projects.project_name',
             'start_date'   => 'SupportPlan.start_date', 
             'end_date'     => 'SupportPlan.end_date', 
             'amount' => 'sum(Payments.amount)',
             'status' => 'SupportPlan.status'
         ])
         ->join([
             'Assignees' => [
                 'table' => 'users',
                 'type' => 'LEFT',
                 'conditions' => 'Assignees.id = SupportPlan.project_manager_id'
             ],
             'Clients' => [
                 'table' => 'users',
                 'type' => 'LEFT',
                 'conditions' => 'Clients.id = SupportPlan.client_id'
             ],
             'Projects' => [
                 'table' => 'projects',
                 'type' => 'LEFT',
                 'conditions' => 'Projects.id = SupportPlan.project_id'
             ],
             'Plans' => [
                 'table' => 'plans',
                 'type' => 'LEFT',
                 'conditions' => 'Plans.id = SupportPlan.plan_id'
             ],
            'Payments' => [
                'table' => 'support_plans_payments',
                'type' => 'LEFT',
                'conditions' => [
                    'Payments.project_id = Projects.id',
                    'Payments.invoice_sent' => 1
                ]
            ]
         ])
         ->where(['SupportPlan.status' => 1, 'Payments.start_date >=' => $financial_year_from,
         'Payments.start_date <=' => $financial_year_to, 'SupportPlan.deleted' => '0'])
         ->group([
            // 'Payments.project_id',   
            'Plans.plan_name',
            'Clients.client_name',
            'Assignees.name',
            'Projects.project_name',
            'SupportPlan.start_date',
            'SupportPlan.end_date',
            'SupportPlan.status',
            'SupportPlan.amount'
        ])
        //  ->order(['SupportPlan.id' => 'desc'])
         ->toArray();

        /** END  Client on Maintainance & ARR */

        /** Chart Section */
        
            /**Current Year Revenue (vs Previous Year) */
            $year_revenue_chart = $this->Projects->find()
            ->select([
                      'amount' => 'SUM(ProjectMilestones.amount)',
                      'month' => 'month(ProjectMilestones.due_date)'
                      ])
            ->join([
              'ProjectMilestones' => [
                  'table' => 'project_milestones',
                  'type' => 'INNER',
                  'conditions' => 'Projects.id = ProjectMilestones.project_id',
              ]
              ])
            ->where([$condition, 'ProjectMilestones.due_date >=' => $financial_year_from,
            'ProjectMilestones.due_date <=' => $financial_year_to])
            ->group(['month(ProjectMilestones.due_date)'])
            ->toArray();
           
            $revenue_chart = array_fill(1, 12, 0.00);
            foreach ($year_revenue_chart as $entity) {
                $month = $entity->month;
                $amount = $entity->amount;
                $revenue_chart[$month] = $amount;
            }

            $last_year_revenue_chart = $this->Projects->find()
            ->select([
                      'amount' => 'SUM(ProjectMilestones.amount)',
                      'month' => 'month(ProjectMilestones.due_date)'
                      ])
            ->join([
              'ProjectMilestones' => [
                  'table' => 'project_milestones',
                  'type' => 'INNER',
                  'conditions' => 'Projects.id = ProjectMilestones.project_id',
              ]
              ])
            ->where([$condition, 'ProjectMilestones.due_date >=' => $last_financial_year_from,
            'ProjectMilestones.due_date <=' => $last_financial_year_to])
            ->group(['month(ProjectMilestones.due_date)'])
            ->toArray();

            $last_revenue_chart = array_fill(1, 12, 0.00);
            foreach ($last_year_revenue_chart as $entity) {
                $month = $entity->month;
                $amount = $entity->amount;
                $last_revenue_chart[$month] = $amount;
            }

            $oportunity_by_stage = $this->Opportunity->find()->select(['count' =>'count(id)', 'stage' =>'stage'])->where(['stage NOT IN' =>[7,8], 'deleted' => 0])->group(['stage'])->toArray();
              
            $stage_by_chart = array_fill(1, 6, 0.00);
            foreach ($oportunity_by_stage as $entity) {
                $stage = $entity->stage;
                $count = $entity->count;
                $stage_by_chart[$stage] = $count;
            }
    
            // Expected Revenue Calculation
            $revenue_actual_chart = $this->Opportunity->find()
            ->select([
                'total_expected_amount' => 'SUM(Opportunity.expected_amount)',
                'total_probability_percentage' => 'SUM((Opportunity.expected_amount * Probability.percentage) / 100)'
            ])
            ->join([
                'Probability' => [
                    'table' => 'probability',
                    'type' => 'LEFT',
                    'conditions' => 'Probability.id = Opportunity.probability'
                ]
            ])
            ->where(['Opportunity.deleted' => 0])
            ->first();
            // End 

            /** End Current Year Revenue (vs Previous Year) **/
        /** End  */

        /************** DELIVERY SECTION START ****************/
        $active_project = $this->getProjectList(['CASE WHEN extend_date IS NULL THEN due_date ELSE extend_date END >' => date('Y-m-d')]); 
      
        $active_date_beyond = $this->getProjectList(['CASE WHEN extend_date IS NULL THEN due_date ELSE extend_date END <' => date('Y-m-d')]); 

        $active_project_ids = array_column($active_project, 'id');

        $active_date_beyond_ids = array_column($active_date_beyond, 'id');

        $active_milestone = $this->getProjectMilestoneList(['ProjectMilestones.due_date >' => date('Y-m-d'), 'ProjectMilestones.project_id IN' => $active_project_ids,'ProjectMilestones.deleted' => 0, 'ProjectMilestones.status !=' => 'Completed']); 

        $active_beyond_milstone = $this->getProjectMilestoneList(['ProjectMilestones.due_date <' => date('Y-m-d'),'ProjectMilestones.project_id IN' => $active_date_beyond_ids,'ProjectMilestones.deleted' => 0, 'ProjectMilestones.status !=' => 'Completed']);

        $project_allocation = $this->ProjectMilestones->find()
                                ->select([
                                            'title' => 'ProjectMilestones.title',
                                            'deadline' => 'ProjectMilestones.due_date',
                                            'amount' => 'ProjectMilestones.amount',
                                            'manager_name' => 'Users.name',
                                            'project_name' => 'Projects.project_name'
                                        ])
                                ->join([
                                            'Projects' => [
                                                'table' => 'projects',
                                                'type' => 'INNER',
                                                'conditions' => 'Projects.id = ProjectMilestones.project_id',
                                            ],
                                            'Users' => [
                                                'table' => 'users',
                                                'type' => 'LEFT',
                                                'conditions' => 'Users.id = Projects.project_manager_id',
                                            ]
                                ])
                                ->where(['ProjectMilestones.project_id IN' => $active_project_ids, 'ProjectMilestones.due_date <' => date('Y-m-d'),'ProjectMilestones.due_date >=' => date('Y-m-').'01', 'ProjectMilestones.deleted' => 0])
                                ->toArray();   

          // Milestone by status 
          $milestone_chart = $this->ProjectMilestones->find()
                                ->select([
                                    'count' => 'COUNT(ProjectMilestones.id)',
                                    'status' => 'ProjectMilestones.status'
                                ])
                                ->where([
                                    'ProjectMilestones.deleted' => 0
                                ])
                                ->group(['ProjectMilestones.status'])
                                ->toArray();
    
            // Initialize the arrays
            $milestone_chart_status = [];
            $milestone_chart_amount = [];
      
            // Loop through the milestones and extract the data
            foreach ($milestone_chart as $milestone) {
                $milestone_chart_status[] = $milestone->status;
                $milestone_chart_amount[] = $milestone->count;
            }

          $milestone_chart_status = json_encode($milestone_chart_status);
          
        /************** END DELIVERY SECTION *****************/
        
        /******************** Start Operations ******************/
        // $early_exit =  $this->EmployeePunchTime->find()->select(['id'])->
        // where(['dom' => date($date, strtotime('-1 day')), 'outtime <' => '18:45:00'])->count();

        $early_exit =  $this->employeePunchTime(['dom' => date('Y-m-d', strtotime($date . ' -1 day')), 'outtime <' => '18:45:00']);

        $late_entry_today =  $this->employeePunchTime(['dom' => $date, 'intime >' => '10:00:00']);

        $late_entry_yestarday =  $this->employeePunchTime(['dom' => date('Y-m-d', strtotime($date . ' -1 day')), 'intime >' => '10:00:00']);

        $today_leave_wfh = $this->Leaves->find()
                           ->select([
                                        'id' => 'Leaves.id',
                                        'subject' => 'Leaves.subject',
                                        'leave_type' => 'Leaves.leave_type',
                                        'applied_on' => 'Leaves.applied_on',
                                        'from_date' => 'Leaves.from_date', 
                                        'to_date' => 'Leaves.to_date',
                                        'status' => 'Leaves.status',
                                        'emp_name' => 'Users.name'
                                    ])
                            ->join([
                                    'Users' => [
                                        'table' => 'users',
                                        'type' => 'LEFT',
                                        'conditions' => 'Users.id = Leaves.created_by',
                                    ]
                            ])
                           ->where([
                            'OR' => [
                                "Leaves.from_date BETWEEN '$date' AND '$date'",
                                "Leaves.to_date BETWEEN '$date' AND '$date'"
                            ],
                            'Leaves.status IN' => ['Approved', 'Pending']
                            ])
                           ->toArray();

        // Start Week TimeSheet 
        $end_week_date = date('Y-m-d', strtotime('last Friday', strtotime($date)));
        $start_week_date =  date('Y-m-d', strtotime($end_week_date . ' -4 day'));

        $weekly_timesheet_report = $this->UserTimesheets->find()
        ->select([
            'hours' => 'SUM(UserTimesheets.time_used)',
            'emp_name' => 'Users.name',
            'manager_name' => 'Manager.name',
            'filled_percent' => 'ROUND((SUM(UserTimesheets.time_used) / 40) * 100, 2)'
        ])
        ->join([
            'Users' => [
                'table' => 'users',
                'type' => 'LEFT',
                'conditions' => 'Users.id = UserTimesheets.resource_id',
            ],
            'Manager' => [
                'table' => 'users',
                'type' => 'LEFT',
                'conditions' => 'Users.reporting_manager = Manager.id',
            ]
        ])
        ->where([
            "UserTimesheets.work_date BETWEEN '$start_week_date' AND '$end_week_date'"
        ])
        ->group(['UserTimesheets.resource_id', 'Users.name', 'Manager.name'])
        ->having(['SUM(UserTimesheets.time_used) / 40 * 100 <' => 90])
        ->toArray();
        // End Week Timesheet 

        // Start Monthly 
        try {
            $conn = ConnectionManager::get('default');
            // $query = 'SELECT c.name AS "manager", DATE_FORMAT(d.work_date, "%b %Y") AS "month_year", d.name AS "name", SUM(d.time_used) AS "hours", CONCAT(ROUND((Count_Filled_Percentage("'.$date.'", SUM(d.time_used))), 2), "%") AS "filled" FROM users c LEFT JOIN ( SELECT a.id, a.name, a.reporting_manager, b.resource_id, b.time_used, b.work_date FROM users a LEFT JOIN user_timesheets b ON a.id = b.resource_id ) d ON c.id = d.reporting_manager WHERE DATE_FORMAT(d.work_date, "%b %Y") = "'.date('M Y').'" GROUP BY c.name, d.name, DATE_FORMAT(d.work_date, "%b %Y") HAVING Count_Filled_Percentage("'.$date.'", SUM(d.time_used)) < 90 ORDER BY c.name, d.work_date DESC, d.resource_id'; 
           
            $query = ' SELECT c.name AS "manager", DATE_FORMAT(d.work_date, "%b %Y") AS "month_year", d.name AS "name",  SUM(d.time_used) AS "hours",
                CONCAT( ROUND((Count_Filled_Percentage("'.$date.'", SUM(d.time_used))), 2 ), "%" ) AS "filled" FROM  users c LEFT JOIN ( SELECT a.id, a.name, a.reporting_manager, b.resource_id, b.time_used, b.work_date FROM users a LEFT JOIN user_timesheets b ON a.id = b.resource_id ) d ON c.id = d.reporting_manager WHERE DATE_FORMAT(d.work_date, "%b %Y") = "'.date('M Y').'" GROUP BY  c.name, d.name, DATE_FORMAT(d.work_date, "%b %Y")  HAVING Count_Filled_Percentage("'.$date.'", SUM(d.time_used)) < 90 ORDER BY  c.name,MAX(d.work_date) DESC, d.name';
            // exit();
            $stmtProduct = $conn->execute($query);
            $timesheet_month_list = $stmtProduct->fetchAll('assoc'); 
        } catch (\Exception $e) {
            // Handle or log the error
            echo 'Query failed: ' . $e->getMessage();
        }

         
        // echo "<pre>";
        // print_r($query);
        // die();   
        // End Monthly 

        /********************** End Operations ****************/

        $this->set(compact('userSession', 'current_month_revenue', 'last_month_revenue', 'current_month_revenue_list', 'ytd_revenue', 'last_ytd_revenue', 'ytd_revenue_list','opportunity', 'last_opportunity','daysAgo', 'opportunity_list', 'current_year_project','last_year_project','current_maintainace_plan','last_maintainace_plan','maintainace_plan_list','last_revenue_chart','revenue_chart','revenue_actual_chart','stage_by_chart','active_project','active_date_beyond', 'active_milestone','active_beyond_milstone','project_allocation', 'milestone_chart_status','milestone_chart_amount', 'early_exit','late_entry_today','late_entry_yestarday','today_leave_wfh','weekly_timesheet_report','end_week_date','start_week_date','timesheet_month_list'));

    }

    // Current Month Revenue (vs Last Month) & YTD Revenue (vs Last Year) 
    private function getMonthlyRevenue($condition) {
        return $this->Projects->find()
            ->select(['amount' => 'SUM(ProjectMilestones.amount)'])
            ->join([
                'ProjectMilestones' => [
                    'table' => 'project_milestones',
                    'type' => 'INNER',
                    'conditions' => 'Projects.id = ProjectMilestones.project_id',
                ]
            ])
            ->where($condition)
            ->first();
    }
    // End 

    // Current Month Revenue (vs Last Month) & YTD Revenue (vs Last Year) List Details 
    private function getMonthlyRevenueList($condition) {
        return $this->Projects->find()
        ->select([
                'company_name' => 'Users.company_name',
                'client_name' => 'Users.client_name',
                'email' => 'Users.email',
                'billing' => 'ProjectMilestones.amount',
                'project_name' => 'Projects.project_name',
                'status' => 'Projects.status'
                ])
        ->join([
                'ProjectMilestones' => [
                    'table' => 'project_milestones',
                    'type' => 'INNER',
                    'conditions' => 'Projects.id = ProjectMilestones.project_id',
                ],
                'Users' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'Projects.client_id = Users.id',
                ]
        ])
        ->where($condition)->toArray();
    }
    // End 

    // Project list details 
    private function getProjectList($condition) {
    return $this->Projects->find()
        ->select([
                  'id' => 'Projects.id',
                  'project_name' =>'Projects.project_name',
                  'due_date' => 'CASE WHEN Projects.extend_date IS NULL THEN Projects.due_date ELSE Projects.extend_date END',
                  'status' => 'Projects.status',
                  'project_manager' => 'Manager.name',
                  'client' => 'Users.client_name'
                  ])
        ->join([
                  'Users' => [
                        'table' => 'users',
                        'type' => 'LEFT',
                        'conditions' => 'Users.id = Projects.client_id	'
                    ],
                  'Manager' => [
                        'table' => 'users',
                        'type' => 'LEFT',
                        'conditions' => 'Manager.id = Projects.project_manager_id'
                    ]
        ])
        ->where(['Projects.deleted' => 1, 'Projects.active' => 1, 'Projects.user_id' => 10, 'Projects.source IN' =>['Regular','External'], $condition, 'Projects.status !=' =>'Completed'])
        ->toArray(); 
    }
    // End 

    // Projects By milestones 
    private function getProjectMilestoneList($condition) {
    return $active_milestone_list = $this->ProjectMilestones->find()
    ->select([
                'title' => 'ProjectMilestones.title',
                'deadline' => 'ProjectMilestones.due_date',
                'amount' => 'ProjectMilestones.amount',
                'manager_name' => 'Users.name',
                'project_name' => 'Projects.project_name'
            ])
    ->join([
                'Projects' => [
                    'table' => 'projects',
                    'type' => 'INNER',
                    'conditions' => 'Projects.id = ProjectMilestones.project_id',
                ],
                'Users' => [
                    'table' => 'users',
                    'type' => 'LEFT',
                    'conditions' => 'Users.id = Projects.project_manager_id',
                ]
    ])
    ->where(['ProjectMilestones.project_id !=' => 0, $condition])->toArray();
    }

    private function employeePunchTime($condition) {

        return $this->EmployeePunchTime->find()
        ->select([
                    'id' => 'EmployeePunchTime.id',
                    'emp' => 'EmployeePunchTime.emp',
                    'dom' => 'EmployeePunchTime.dom',
                    'intime' => 'EmployeePunchTime.intime',
                    'outtime' => 'EmployeePunchTime.outtime',
                    'early_late_by' => 'TIMEDIFF(EmployeePunchTime.intime, "10:00:00")',
                    'early_exit_by' => 'TIMEDIFF(EmployeePunchTime.outtime, "18:45:00")'
                ])->
        where($condition)->toArray();

    }
    // End 

    function daysAgo($date) {
        $timestamp = strtotime($date);
        $currentTimestamp = time();
        $differenceInSeconds = $currentTimestamp - $timestamp;
        $differenceInDays = floor($differenceInSeconds / (60 * 60 * 24));
        return $differenceInDays;
    }

    public function dashboard()
    {
        $this->viewBuilder()->setLayout('default_new');
        $this->Authorization->skipAuthorization();

        $conn = \Cake\Datasource\ConnectionManager::get('default');
        $this->Projects = $this->fetchTable('Projects');

        /*
        * ---------------------------------------------------------
        * SESSION / USER
        * ---------------------------------------------------------
        */
            $session = $this->request->getSession();
            $userSession = $session->read('data') ?? [];

            $userId   = (int)($userSession['id'] ?? 0);
            $role     = (int)($userSession['role'] ?? 0);
            $parentId = (int)($userSession['parent_id'] ?? 0);

            $session->write('managerId', $userId);
            $session->write('page', 'myproject');

        /*
        * ---------------------------------------------------------
        * PROJECT LIST
        * ---------------------------------------------------------
        */
            $projectSql = "
                SELECT
                    p.*,
                    c.client_name AS client_name,
                    pm.name AS project_manager
                FROM projects p
                LEFT JOIN users c
                    ON p.client_id = c.id
                LEFT JOIN users pm
                    ON p.project_manager_id = pm.id
                WHERE p.deleted = 1
                    AND p.status != 'Completed'
                    AND p.active = 1
                    AND p.project_name NOT LIKE '%Internal Projects%'
                    AND p.project_name NOT LIKE '%General Tasks - Non Billable%'
                ORDER BY p.id DESC
            ";

            $projectList = $conn->execute($projectSql)->fetchAll('assoc');

            $projectIds = array_map( static fn($project) => (int)$project['id'], $projectList );

        /*
        * ---------------------------------------------------------
        * DEFAULT VALUES
        * ---------------------------------------------------------
        */
            $projects = [];
            $total = count($projectList);

            $milestoneData = [];
            $paymentData = [];
            $actualHoursData = [];
            $allocatedHoursData = [];

        /*
        * ---------------------------------------------------------
        * MILESTONE DATA
        * ---------------------------------------------------------
        */
            if (!empty($projectIds)) {
                $placeholders = [];
                $params = [];

                foreach ($projectIds as $index => $id) {
                    $key = "project_id_{$index}";
                    $placeholders[] = ":{$key}";
                    $params[$key] = $id;
                }
                $milestoneSql = "
                    SELECT
                        project_id,
                        COALESCE(SUM(amount), 0) AS total_amount,
                        SUM(
                            CASE
                                WHEN due_date < CURDATE()
                                    AND status != 'Completed'
                                THEN 1
                                ELSE 0
                            END
                        ) AS overdue,
                        SUM(
                            CASE
                                WHEN due_date >= CURDATE()
                                    AND due_date <= DATE_ADD(CURDATE(), INTERVAL 15 DAY)
                                    AND status != 'Completed'
                                THEN 1
                                ELSE 0
                            END
                        ) AS due
                    FROM project_milestones
                    WHERE deleted = 0
                        AND project_id IN (" . implode(',', $placeholders) . ")
                    GROUP BY project_id
                ";
                $rows = $conn->execute($milestoneSql, $params)->fetchAll('assoc');

                foreach ($rows as $row) {
                    $projectId = (int)$row['project_id'];
                    $milestoneData[$projectId] = [
                        'amount'  => (float)$row['total_amount'],
                        'overdue' => (int)$row['overdue'],
                        'due'     => (int)$row['due'],
                    ];
                }
            }

        /*
        * ---------------------------------------------------------
        * PAYMENT DATA
        * ---------------------------------------------------------
        */
            if (!empty($projectIds)) {
                $placeholders = [];
                $params = [];

                foreach ($projectIds as $index => $id) {
                    $key = "payment_project_{$index}";
                    $placeholders[] = ":{$key}";
                    $params[$key] = $id;
                }
                $paymentSql = "
                    SELECT
                        project_id,
                        COALESCE(SUM(receive_amt), 0) AS paid
                    FROM project_payments
                    WHERE status = 'Paid'
                        AND project_id IN (" . implode(',', $placeholders) . ")
                    GROUP BY project_id
                ";
                $rows = $conn->execute($paymentSql, $params)->fetchAll('assoc');
                foreach ($rows as $row) {
                    $paymentData[(int)$row['project_id']] = (float)$row['paid'];
                }
            }

        /*
        * ---------------------------------------------------------
        * ACTUAL HOURS
        * ---------------------------------------------------------
        */
            if (!empty($projectIds)) {
                $placeholders = [];
                $params = [];

                foreach ($projectIds as $index => $id) {
                    $key = "hours_project_{$index}";
                    $placeholders[] = ":{$key}";
                    $params[$key] = $id;
                }
                $actualHoursSql = "
                    SELECT
                        pm.project_id,
                        COALESCE(SUM(ut.time_used), 0) AS actual_hours
                    FROM user_timesheets ut
                    INNER JOIN project_milestones pm
                        ON pm.id = ut.milestone_id
                    WHERE pm.project_id IN (" . implode(',', $placeholders) . ")
                    GROUP BY pm.project_id
                ";
                $rows = $conn->execute($actualHoursSql, $params)->fetchAll('assoc');
                foreach ($rows as $row) {
                    $actualHoursData[(int)$row['project_id']] = (float)$row['actual_hours'];
                }
            }

        /*
        * ---------------------------------------------------------
        * ALLOCATED HOURS
        * ---------------------------------------------------------
        */
            if (!empty($projectIds)) {
                $placeholders = [];
                $params = [];

                foreach ($projectIds as $index => $id) {
                    $key = "allocation_project_{$index}";
                    $placeholders[] = ":{$key}";
                    $params[$key] = $id;
                }

                $allocatedHoursSql = "
                    SELECT
                        pm.project_id,
                        COALESCE(SUM(pa.time_slot), 0) AS allocated_hours
                    FROM project_allocations pa
                    INNER JOIN project_milestones pm
                        ON pm.id = pa.milestone_id
                    WHERE pm.project_id IN (" . implode(',', $placeholders) . ")
                    GROUP BY pm.project_id
                ";

                $rows = $conn->execute( $allocatedHoursSql, $params )->fetchAll('assoc');

                foreach ($rows as $row) {
                    $allocatedHoursData[(int)$row['project_id']] = (float)$row['allocated_hours'];
                }
            }

        /*
        * ---------------------------------------------------------
        * BUILD PROJECT DATA
        * ---------------------------------------------------------
        */
            foreach ($projectList as $project) {
                $projectId = (int)$project['id'];

                $milestone = $milestoneData[$projectId] ?? [
                    'amount'  => 0,
                    'overdue' => 0,
                    'due'     => 0,
                ];

                $amount = $milestone['amount'];
                $paid = $paymentData[$projectId] ?? 0;
                $actualHours = $actualHoursData[$projectId] ?? 0;
                $allocatedHours = $allocatedHoursData[$projectId] ?? 0;

                $type = !empty($project['project_type']) ? $project['project_type'] : 'Other';
                $awardDate = !empty($project['awarded_on']) ? date('d F Y', strtotime($project['awarded_on'])) : '';
                $dueDate = !empty($project['due_date']) ? date('d-m-Y', strtotime($project['due_date'])) : '';
                $hourlyRate = (float)$project['hourly_rate'];
                $budget = $hourlyRate > 0 ? $amount / $hourlyRate : 'Na';

                $projects[] = [
                    'id'                => $projectId,
                    'bill'              => $project['bill'],
                    'project_name'      => $project['project_name'],
                    'client_id'         => $project['client_id'],
                    'client'            => $project['client_name'] ?? '',
                    'project_manager'   => $project['project_manager'] ?? '',
                    'award'             => $awardDate,
                    'due_date'          => $dueDate,
                    'type'              => $type,
                    'project_type'      => $type,
                    'hourly_rate'       => $hourlyRate,
                    'amount'            => $amount,
                    'paid'              => $paid,
                    'status'            => $project['status'],
                    'active'            => $project['active'],
                    'overdue'           => $milestone['overdue'],
                    'due'               => $milestone['due'],
                    'pm_amount'         => $amount,
                    'actual_hours'      => $actualHours,
                    'allocated_hours'   => $allocatedHours,
                    'budget'            => $budget,
                ];
            }

        /*
        * ---------------------------------------------------------
        * PENDING / OVERDUE MILESTONES
        * ---------------------------------------------------------
        */
            $pendingMilestones = [];
            if (!empty($projectIds)) {
                $placeholders = [];
                $params = [];

                foreach ($projectIds as $index => $id) {
                    $key = "pending_project_{$index}";
                    $placeholders[] = ":{$key}";
                    $params[$key] = $id;
                }

                $pendingSql = "
                    SELECT
                        m.*,
                        p.project_name,
                        pm.name AS project_manager
                    FROM project_milestones m
                    INNER JOIN projects p
                        ON p.id = m.project_id
                    LEFT JOIN users pm
                        ON pm.id = p.project_manager_id
                    WHERE m.deleted = 0
                        AND m.status != 'Completed'
                        AND m.due_date < CURDATE()
                        AND m.project_id IN (" . implode(',', $placeholders) . ")
                    ORDER BY m.due_date ASC
                ";
                $pendingMilestones = $conn->execute($pendingSql, $params)->fetchAll('assoc');
            }
            $pendingMilestoneCount = count($pendingMilestones);

        /*
        * ---------------------------------------------------------
        * EMPLOYEE / WORKING HOURS
        * ---------------------------------------------------------
        */
            $employees = [];

            $monthStart = date('Y-m-01');
            $today = date('Y-m-d');
            $dailyRequiredHours = 8;

            $leaveEmployees = [];
            $wfhEmployees   = [];
            $presentEmployees = [];

            ////////////// COMPANY HOLIDAYS 
            $this->Holidays = $this->fetchTable('Holidays');
            $holidays = $this->Holidays->find()->where([
                    'deleted' => 0,
                    'start <=' => $today,
                    'end >=' => $monthStart,
                ])->all();

            $holidayDates = [];

            foreach ($holidays as $holiday) {
                $holidayStart = new \DateTime($holiday->start);
                $holidayEnd = new \DateTime($holiday->end);

                $periodStart = new \DateTime($monthStart);
                $periodEnd = new \DateTime($today);

                if ($holidayStart < $periodStart) {
                    $holidayStart = clone $periodStart;
                }

                if ($holidayEnd > $periodEnd) {
                    $holidayEnd = clone $periodEnd;
                }

                while ($holidayStart <= $holidayEnd) {
                    if ((int)$holidayStart->format('N') <= 5) {
                        $holidayDates[$holidayStart->format('Y-m-d')] = true;
                    }

                    $holidayStart->modify('+1 day');
                }
            }

        /*
        * ---------------------------------------------------------
        * EMPLOYEE CONDITION
        * ---------------------------------------------------------
        */
            $employeeWhere = "
                u.id > 0
                AND u.status = 1
                AND u.deleted = 1
                AND (
                    FIND_IN_SET('4', u.role_name)
                    OR FIND_IN_SET('5', u.role_name)
                    OR FIND_IN_SET('6', u.role_name)
                    OR FIND_IN_SET('7', u.role_name)
                    OR FIND_IN_SET('8', u.role_name)
                )
            ";

            $employeeParams = [];

            if ($role == 1) {
                $employeeWhere .= " AND u.company_id = :employee_company";
                $employeeParams['employee_company'] = $userId;
            } elseif ($role == 3) {
                $employeeWhere .= " AND u.company_id = :employee_company";
                $employeeParams['employee_company'] = $parentId;
            }

        /*
        * ---------------------------------------------------------
        * EMPLOYEE LEAVES
        *
        * Get leaves for ALL employees once.
        * This fixes the previous missing employee ID issue.
        * ---------------------------------------------------------
        */
            $this->LeaveCounting = $this->fetchTable('LeaveCount');
            $employeeLeaves = $this->LeaveCounting->find()->where([
                    'leave_date >=' => $monthStart,
                    'leave_date <=' => $today,
                    'OR' => [
                        ['cl' => 1],
                        ['sl' => 1],
                        ['el' => 1],
                        ['comp_off' => 1],
                        ['lwp' => 1],
                    ],
                ])->all();

            $leaveDatesByEmployee = [];
            foreach ($employeeLeaves as $leave) {
                if (empty($leave->user_id) || empty($leave->leave_date)) {
                    continue;
                }
                $leaveDate = (new \DateTime($leave->leave_date))->format('Y-m-d');
                $dayOfWeek = (int)(new \DateTime($leaveDate))->format('N');
                if ($dayOfWeek <= 5) {
                    $leaveDatesByEmployee[(int)$leave->user_id][$leaveDate] = true;
                }
            }

        /*
        * ---------------------------------------------------------
        * EMPLOYEE QUERY
        * ---------------------------------------------------------
        */
            $employeeSql = "
                SELECT
                    u.id,
                    u.name,
                    u.user_image,
                    u.role_name,
                    COALESCE(
                        SUM(
                            CASE
                                WHEN p.bill = 'Billable'
                                    AND ut.work_date >= DATE_FORMAT(
                                        CURDATE(),
                                        '%Y-%m-01'
                                    )
                                    AND ut.work_date <= CURDATE()
                                THEN ut.time_used
                                ELSE 0
                            END
                        ),
                        0
                    ) AS occupied_hours
                FROM users u
                LEFT JOIN user_timesheets ut
                    ON ut.resource_id = u.id
                LEFT JOIN project_milestones pm
                    ON pm.id = ut.milestone_id
                LEFT JOIN projects p
                    ON p.id = pm.project_id
                WHERE {$employeeWhere}
                GROUP BY u.id, u.name, u.role_name
                ORDER BY u.name ASC
            ";

            try {
                $employeeList = $conn->execute($employeeSql, $employeeParams)->fetchAll('assoc');
                foreach ($employeeList as $employee) {
                    $employeeId = (int)$employee['id'];
                    /*
                    * Calculate working days separately for this employee.
                    * Company holidays + employee-specific leave are excluded.
                    */
                    $workingDays = 0;
                    $periodStart = new \DateTime($monthStart);
                    $periodEnd = new \DateTime($today);
                    $employeeLeaveDates = $leaveDatesByEmployee[$employeeId] ?? [];

                    while ($periodStart <= $periodEnd) {
                        $date = $periodStart->format('Y-m-d');
                        $dayOfWeek = (int)$periodStart->format('N');

                        if ( $dayOfWeek <= 5 && !isset($holidayDates[$date]) && !isset($employeeLeaveDates[$date]) ) {
                            $workingDays++;
                        }
                        $periodStart->modify('+1 day');
                    }

                    $totalEmployeeHours = $workingDays * $dailyRequiredHours;
                    $occupiedHours = (float)$employee['occupied_hours'];

                    if ($totalEmployeeHours > 0) {
                        $occupancy = ($occupiedHours / $totalEmployeeHours) * 100;
                        $availability = (($totalEmployeeHours - $occupiedHours) / $totalEmployeeHours) * 100;
                    } else {
                        $occupancy = 0;
                        $availability = 0;
                    }

                    $occupancy = min(100, max(0, $occupancy));
                    $availability = min(100, max(0, $availability));

                    if ($occupancy >= 80) {
                        $status = 'High Load';
                    } elseif ($occupancy >= 60) {
                        $status = 'Normal';
                    } else {
                        $status = 'Available';
                    }

                    $employees[] = [
                        'id'              => $employeeId,
                        'name'            => $employee['name'] ?? '',
                        'role_name'       => $employee['role_name'] ?? '',
                        'total_hours'     => $totalEmployeeHours,
                        'occupied_hours'  => $occupiedHours,
                        'occupancy'       => $occupancy,
                        'availability'    => $availability,
                        'status'          => $status,
                    ];

                }
            } catch (\Exception $e) {
                $employees = [];
            }

        /*
        * ---------------------------------------------------------
        * AVERAGE AVAILABILITY
        * ---------------------------------------------------------
        */
            $averageAvailability = 0;
            if (!empty($employees)) {
                $availabilityTotal = array_sum( array_column($employees, 'availability') );
                $averageAvailability = $availabilityTotal / count($employees);
            }

        /*
        * ---------------------------------------------------------
        * GITHUB DATA
        * ---------------------------------------------------------
        */
            // $githubData = [];
            // try {
            //     $http = new Client();
            //     $response = $http->get( 'http://44.230.62.131:5016/github-report?days=7');

            //     if ($response->isOk()) {
            //         $apiResponse = $response->getJson();

            //         if (!empty($apiResponse['success']) && !empty($apiResponse['data'])) {
            //             $githubData = $apiResponse['data'];
            //         } else {
            //             echo '<pre>';
            //             echo 'API Error: ';
            //             print_r($apiResponse);
            //             echo '</pre>';
            //         }
            //     } else {
            //         echo '<pre>';
            //         echo 'HTTP Error: ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase() . PHP_EOL;
            //         echo 'Response: ' . $response->getStringBody();
            //         echo '</pre>';
            //     }
            // } catch (\Exception $e) {
            //     $githubData = [];
            //     echo '<pre>';
            //     echo 'Exception Error: ' . $e->getMessage() . PHP_EOL;
            //     echo 'File: ' . $e->getFile() . PHP_EOL;
            //     echo 'Line: ' . $e->getLine();
            //     echo '</pre>';
            // }

            $this->GithubReports = $this->fetchTable("GithubReports");
            $githubData = $this->GithubReports->find()->order([ "last_commit_date" => "DESC" ])->all();

        
        /*
        * ---------------------------------------------------------
        * TODAY'S LEAVE / WFH
        * ---------------------------------------------------------
        */

            $leaveEmployees = [];
            $wfhEmployees = [];
            $presentEmployees = [];

            $leaveEmployeeIds = [];
            $wfhEmployeeIds = [];

            $attendanceSql = "
                SELECT
                    u.id,
                    u.name,
                    u.user_image,
                    l.id AS leave_id,
                    l.from_date,
                    l.to_date,
                    l.wfh_flag,
                    l.leave_type
                FROM users u
                INNER JOIN leaves l
                    ON l.created_by = u.id
                WHERE {$employeeWhere}
                    AND l.status IN ('Approved', 'Pending')
                    AND CURDATE() BETWEEN DATE(l.from_date) AND DATE(l.to_date)
            ";

            $attendanceEmployees = $conn->execute($attendanceSql, $employeeParams)->fetchAll('assoc');

            foreach ($attendanceEmployees as $attendance) {

                $employeeId = (int)$attendance['id'];
                $employeeData = [
                    'id'         => $employeeId,
                    'name'       => $attendance['name'] ?? '',
                    'user_image' => $attendance['user_image'] ?? '',
                    'leave_id'   => $attendance['leave_id'],
                    'from_date'  => $attendance['from_date'],
                    'to_date'    => $attendance['to_date'],
                    'leave_type' => $attendance['leave_type'],
                    'wfh_flag'   => $attendance['wfh_flag'],
                ];

                if ((int)$attendance['wfh_flag'] === 1) {
                    $wfhEmployees[$employeeId] = $employeeData;
                    $wfhEmployeeIds[$employeeId] = true;

                } else {
                    $leaveEmployees[$employeeId] = $employeeData;
                    $leaveEmployeeIds[$employeeId] = true;
                }
            }

        /*
        * ---------------------------------------------------------
        * PRESENT EMPLOYEES
        * ---------------------------------------------------------
        */
            foreach ($employeeList as $employee) {
                $employeeId = (int)$employee['id'];
                if ( !isset($leaveEmployeeIds[$employeeId]) && !isset($wfhEmployeeIds[$employeeId]) ) {
                    $presentEmployees[] = $employee;
                }
            }
            $leaveEmployees = array_values($leaveEmployees);
            $wfhEmployees = array_values($wfhEmployees);
        /*
        * ---------------------------------------------------------
        * FINAL DATA TO VIEW
        * ---------------------------------------------------------
        */    
            $this->set([
                'projects' => $projects,
                'total' => $total,
                'pendingMilestones' => $pendingMilestones,
                'pendingMilestoneCount' => $pendingMilestoneCount,
                'employees' => $employees,
                'averageAvailability' => $averageAvailability,

                'githubData' => $githubData,

                'leaveCount' => count($leaveEmployees),
                'leaveEmployees' => $leaveEmployees,

                'wfhCount' => count($wfhEmployees),
                'wfhEmployees' => $wfhEmployees,

                'presentCount' => count($presentEmployees),
            ]);
        /*
        * ---------------------------------------------------------
        * END
        * ---------------------------------------------------------
        */
    }

public function refreshGithubData()
{
    $this->Authorization->skipAuthorization();
    $this->GithubReports = $this->fetchTable("GithubReports");
    try {

        $http = new \Cake\Http\Client();
        $response = $http->get("http://44.230.62.131:5016/github-report?days=7");

        if (!$response->isOk()) {
            throw new \Exception("GitHub API request failed.");
        }

        $github_data = $response->getJson();
        if (
            empty($github_data["success"]) ||
            empty($github_data["data"])
        ) {
            throw new \Exception("No GitHub data found.");
        }

        foreach ($github_data["data"] as $data) {
            if ( empty($data["repository"]) || empty($data["user"]) ) {
                continue;
            }

            $github_report = $this->GithubReports->find()->where([
                    "repository" => $data["repository"],
                    "github_user" => $data["user"]
                ])->first();

            if ($github_report) {
                $github_report->commits = $data["commits"];
                $github_report->last_commit_date = $data["lastCommitDate"];

            } else {
                $github_report = $this->GithubReports->newEntity([
                    "repository" => $data["repository"],
                    "github_user" => $data["user"],
                    "commits" => $data["commits"],
                    "last_commit_date" => $data["lastCommitDate"]
                ]);
            }

            if (!$this->GithubReports->save($github_report)) {
                throw new \Exception(
                    "Unable to save GitHub data for " .
                    $data["repository"]
                );
            }
        }
        return $this->response
            ->withType("application/json")
            ->withStringBody(json_encode([
                "success" => true,
                "message" => "GitHub data refreshed successfully."
            ]));
    } catch (\Exception $e) {
        return $this->response
            ->withType("application/json")
            ->withStringBody(json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ]));
    }
}
    

}
?>