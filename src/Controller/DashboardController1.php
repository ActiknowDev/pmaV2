<?php
namespace App\Controller;
use Cake\ORM\Query;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\ConnectionManager;
// use Cake\Datasource\ConnectionManager;

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

}
?>