<?php

namespace App\Controller;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Http\Exception\ForbiddenException;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Utility\Security;
use Cake\ORM\Query;
use Cake\Database\Expression\QueryExpression;
use Cake\Http\Client;
use Cake\Validation\Validator;
use Cake\View\Helper\formHelper;
use Cake\Mailer\TransportFactory;
use Cake\Mailer\Email;
use Cake\Mailer\Mailer;

class ScoreCardController extends AppController
{
	public function initialize(): void
	{
		parent::initialize();
		// $this->loadComponent('Paginator');
		$this->loadComponent('Flash'); 
		// $this->loadComponent('RequestHandler');
		// $this->SupportPlan = TableRegistry::get('SupportPlan');
		// $this->loadModel("AssigendProjectTasks");
		// $this->loadModel("Projects");
		// $this->loadModel("MyTeams");
		// $this->loadModel("Users");
		// $this->loadModel("EmployeeDetails");
		// $this->loadModel("MyTeamResources");
		// $this->loadModel("Leaves");
		// $this->EmployeePunchTime = $this->getTableLocator()->get('EmployeePunchTime');
		// $this->Holidays = TableRegistry::get('Holidays');
		// $this->Users = TableRegistry::get('Users');
	}

	public function beforeFilter(\Cake\Event\EventInterface $event)
	{
		parent::beforeFilter($event);
		$this->Authentication->addUnauthenticatedActions(['login', 'signup']);
	}

	public function getDateDiff($date1, $date2)
    {

        $date1 = date_create($date1);
        $date2 = date_create($date2);
        $diff = date_diff($date1, $date2);
        $days_count = (int)$diff->format("%R%a");
        return $days_count + 1;
    }

	
	public function index($empId=null,$month=null,$year=null)
	{
		$this->viewBuilder()->setLayout('default_new');
		$this->Authorization->skipAuthorization();
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$user_session_id = $userSession['id'];
		$role = $userSession['role'];
		$roleArray = $userSession['role_name'];
		$conn = ConnectionManager::get('default');
		$this->Users = $this->fetchTable('Users');
		$this->EmployeeDetails = $this->fetchTable('EmployeeDetails');
		$this->Leaves = $this->fetchTable('Leaves');


		// validation for valid user
		$validList = [4, 6, 9, 10];
		$this->routeValidation($roleArray,$validList);
		if(empty($empId)) {
			$userId=$user_session_id;
		} else {
			$userId=$empId;
		}
		$year = date('Y');
		if(empty($month)) {
			$month = date('m');
			$from=date('Y-m-d');
			$to=date('Y-m-t');
		} else {
			$month=$month;
			$from=date('Y-'.$month.'-d');
			$to=date('Y-'.$month.'-t');
		}

		$emp_details_table = $this->EmployeeDetails->find()->where(['user_id'=>$userId])->first();

		$emp_details = $this->Users->find()->where(['id'=>$userId])->first();
		$userId=$emp_details->id;
		$userName = $emp_details->name;

		$emp_list = $this->Users->find()
			->select(['id', 'name'])
			->where([
				'deleted' => 1,
				'role' => 3,
				'status' => 1,
				'company_id' => 10
			])
			->order(['name' => 'ASC']) // Alphabetical order (A-Z)
			->toArray();

		// $emp_list = $this->Users->find()->select(['id','name'])->where(['deleted'=>1,'role'=>3,'status'=>1,'company_id'=>10])->toArray();

		$project_assigned = "SELECT p.*,c.client_name,pm.name as project_manager FROM projects p LEFT JOIN users c ON p.client_id = c.id LEFT JOIN users pm ON p.project_manager_id=pm.id WHERE p.deleted=1 AND p.status != 'Completed' AND (p.project_manager_id=".$userId." OR p.tech_lead_id=".$userId." OR p.bd_id=".$userId." OR FIND_IN_SET(".$userId.",resources)!=0)";
		$stmtProject = $conn->execute($project_assigned);
		$total_project_assigned = $stmtProject->fetchAll('assoc');
		$total_project_assign = count($total_project_assigned);
		$total_miles=0;
		$mlist1 = [];
		$melist1 = [];
		$total_billable_project_assign=0;
		$total_non_billable_project_assign=0;
		foreach ($total_project_assigned as $l) {

			if ($l['bill'] =='Billable') {
				$total_billable_project_assign++;
			}
			if ($l['bill'] =='Non Billable') {
				$total_non_billable_project_assign++;
			}
			if ($l['milestone_id'] && $l['bill'] =='Billable') {

				$query = "SELECT id,title FROM project_milestones p 
				WHERE p.id IN (" . $l['milestone_id'] . ") 
				AND p.deleted=0 AND status != 'Completed'";

				$stmtProduct = $conn->execute($query);
				$mlist = $stmtProduct->fetchAll('assoc');

				// dd($mlist);
				
				$total_miles += count($mlist);

				// echo "Total Milestones: " . $abc ."<br>"; 
				// echo " Milestones: " . count($mlist) ."<br>"; 
				$query1 = "SELECT p.id, p.title,pr.project_name, p.due_date
				FROM project_milestones p
				LEFT JOIN projects pr ON pr.id = p.project_id
				WHERE p.id IN (" . $l['milestone_id'] . ")
				AND p.deleted = 0
				AND p.status != 'Completed'
			";
			$stmt  = $conn->execute($query1);
			$response = $stmt->fetchAll('assoc');
			if (!empty($response)) {
					$melist1 = array_merge($melist1, $response); // Merge to flatten
				}
			}
		}
		 $time_used_query = "
        SELECT 
            ANY_VALUE(user_timesheets.id) as id,
            ANY_VALUE(user_timesheets.milestone_id) as milestone_id,
            ANY_VALUE(user_timesheets.resource_id) as resource_id,
            SUM(user_timesheets.time_used) as time_used,
            ANY_VALUE(user_timesheets.work_date) as work_date,
            ANY_VALUE(project_milestones.title) as title,
            ANY_VALUE(project_milestones.project_id) as project_id,
            projects.project_name,
            ANY_VALUE(projects.bill) as bill,
            users.name as username,
            ANY_VALUE(users.id) as userid
        FROM user_timesheets 
        LEFT JOIN project_milestones ON user_timesheets.milestone_id=project_milestones.id 
        LEFT JOIN projects ON projects.id=project_milestones.project_id 
        LEFT JOIN users ON users.id=user_timesheets.resource_id 
        WHERE month(work_date)=" .$month. " AND year(work_date)=". $year ." AND user_timesheets.resource_id=" . $userId . " 
        GROUP BY projects.project_name, users.name";
		//  $time_used_query = "
        // SELECT 
        //     (user_timesheets.id) as id,
        //     (user_timesheets.milestone_id) as milestone_id,
        //     (user_timesheets.resource_id) as resource_id,
        //     SUM(user_timesheets.time_used) as time_used,
        //     (user_timesheets.work_date) as work_date,
        //     (project_milestones.title) as title,
        //     (project_milestones.project_id) as project_id,
        //     projects.project_name,
        //     (projects.bill) as bill,
        //     users.name as username,
        //     (users.id) as userid
        // FROM user_timesheets 
        // LEFT JOIN project_milestones ON user_timesheets.milestone_id=project_milestones.id 
        // LEFT JOIN projects ON projects.id=project_milestones.project_id 
        // LEFT JOIN users ON users.id=user_timesheets.resource_id 
        // WHERE month(work_date)=" .$month. " AND year(work_date)=". $year ." AND user_timesheets.resource_id=" . $userId . " 
        // GROUP BY projects.project_name, users.name";

		$stmtProduct = $conn->execute($time_used_query);
		$time_used = $stmtProduct->fetchAll('assoc');
		$user_time_use=0;
		$billable_user_time_use=0;
		foreach($time_used as $tu) {
			$user_time_use += $tu['time_used'];
			if ($tu['bill'] =='Billable') {
				$billable_user_time_use += $tu['time_used'];
			}
		}

		$allocated_time_query = "SELECT sum(project_allocations.time_slot) as time_slot,projects.project_name,project_allocations.resource_id,project_milestones.id as pmid FROM `project_allocations` LEFT JOIN project_milestones ON project_milestones.id=project_allocations.milestone_id LEFT JOIN projects ON projects.id=project_milestones.project_id WHERE month(project_milestones.due_date)=".$month." AND year(project_milestones.due_date)=".$year." AND project_allocations.resource_id=".$userId." GROUP by project_allocations.resource_id,projects.project_name,project_milestones.id";
		$qr_run = $conn->execute($allocated_time_query);
		$allocated_time = $qr_run->fetchAll('assoc');
		$total_time_slot=0;
		foreach($allocated_time as $at) {
			$total_time_slot += $at['time_slot'];
		}

		// // Get leave records for the user and selected month/year
		$total_leave = $this->Leaves->find()->where([
			'created_by' => $userId,
			'month(from_date)' => $month,
			'year(from_date)' => $year,
			'status !=' => 'cancelled' 
		])->andWhere([
			'OR' => [
				['leave_type' => 'Paid Leave'],
				['leave_type' => 'Casual Leave'],
				['leave_type' => 'Sick Leave'],
				['leave_type' => 'LWP']
			]
		])->toArray();

		$total_diff = 0;
		$total_lv = 0;
		$leave_details_list = [];
		$avg_leave_details = []; 
		$count = 1;

		foreach ($total_leave as $lv) {
			$from_date = date('Y-m-d', strtotime($lv['from_date'])); // Use $wfh['from_date']
			$to_date = date('Y-m-d', strtotime($lv['to_date'])); // Use $wfh['to_date']
			$diff = $this->getDateDiff($from_date, $to_date);
			$total_lv += $diff; 

			// echo "<pre />";
			// 	print_r($avg_leave_details);
			// exit;
		}

		$total_average_leave_plan = $count > 1 ? $total_diff / ($count - 1) : 0;

		$total_wfh_leave = $this->Leaves->find()->where([
			'created_by' => $userId,
			'month(from_date)'=> $month,
			'year(from_date)'=> $year,
			'leave_type' => 'WFH'
		])
		->toArray();
		$total_wfh = 0;
		$wfh_dates = []; 

		foreach ($total_wfh_leave as $wfh) {
			$from_date = date('Y-m-d', strtotime($wfh['from_date'])); 
			$to_date = date('Y-m-d', strtotime($wfh['to_date'])); 
			$current = strtotime($from_date);
			$end = strtotime($to_date);
			while ($current <= $end) {
				$wfh_dates[] = date('Y-m-d', $current);
				$current = strtotime('+1 day', $current);
				$total_wfh++; 
			}
		}
		
		$average_leave_plan = $this->Leaves->find()->where([
			'created_by' => $userId,
			'month(from_date)'=> $month,
			'year(from_date)'=> $year
		])->andWhere([
			'OR' => [
				['status' => 'Pending'],
				['status' => 'Approved']
			]
		])
		->toArray();

		// dd($average_leave_plan);
		$at = count($average_leave_plan);
		$total_diff=0;
		$total_average_leave_plan=0;
		foreach($average_leave_plan as $avrl) {
			$appiled_date = date('Y-m-d', strtotime($avrl['applied_on']));
			$from_date = date('Y-m-d', strtotime($avrl['from_date']));
			$diff = $this->getDateDiff($appiled_date, $from_date);
			$total_diff += $diff; 
		}
		if($total_diff>0) {
			$total_average_leave_plan = $total_diff/$at;
		}
		
		$query ="SELECT DISTINCT u.emp_id, u.emp_name AS emp, u.manager_name, e.dom AS date, e.intime, e.outtime, TIMEDIFF(e.outtime, e.intime) AS total_time, TIMEDIFF(e.intime, '10:00:00') AS Late_by, CASE WHEN e.emp IS NOT NULL THEN 'Present' ELSE 'Absent' END AS status FROM ( SELECT u1.id AS emp_id, u1.name AS emp_name, u2.name AS manager_name FROM users u1 INNER JOIN users u2 ON u1.reporting_manager = u2.id WHERE u1.deleted = 1 AND u1.company_id = 10 AND u1.role = 3 AND u1.status = 1 ) AS u INNER JOIN emp_punch_time e ON e.emp = u.emp_name AND month(e.dom)=". $month ." AND year(e.dom)=". $year ." AND e.emp = "."'".$userName."'";
		
		$stmtProduct = $conn->execute($query);
		$emp_attendence_list = $stmtProduct->fetchAll('assoc');
		
		$query1 = "SELECT leave_type, from_date, to_date, status, wfh_type FROM `leaves` WHERE `created_by` = ".$userId." AND month(from_date) = ".$month." AND year(from_date) = ".$year." AND (status = 'Pending' OR status = 'Approved')";
		$stmtProduct1 = $conn->execute($query1);
		$leaves = $stmtProduct1->fetchAll('assoc');

		$late_entries = 0;
		$late_entries_list = [];
		$scheduledInTime = strtotime('10:05:00');
		$count = 1;

		foreach ($emp_attendence_list as $lt) {
			if (!empty($lt['intime'])) {
				$actualInTime = strtotime($lt['intime']);
				if ($actualInTime > $scheduledInTime) {
					$late_entries++;

					$late_entries_list[] = [
						'count' => $count++,
						'date' => date('Y-m-d', strtotime($lt['date'])),
						'intime' => $lt['intime'],
						'outtime' => $lt['outtime'],
						'difference' => gmdate('H:i', $actualInTime - $scheduledInTime)
					];
				}
			}
		}

		$early_exits = 0;
		$early_exits_list = [];
		$scheduledOutTime = strtotime('18:45:00');
		$count = 1;

		foreach ($emp_attendence_list as $lt) {
			if (!empty($lt['outtime'])) {
				$actualOutTime = strtotime($lt['outtime']);
				if ($actualOutTime < $scheduledOutTime) {
					$early_exits++;

					$early_exits_list[] = [
						'count' => $count++,
						'date' => date('Y-m-d', strtotime($lt['date'])),
						'intime' => $lt['intime'],
						'outtime' => $lt['outtime'],
						'difference' => gmdate('H:i', $scheduledOutTime - $actualOutTime)
					];
				}
			}
		}
		 $summary_query = "
        SELECT 
            ANY_VALUE(projects.id) AS project_id,
            projects.project_name,
            ANY_VALUE(projects.bill) AS bill,
            ANY_VALUE(users.id) AS userid,
            users.name AS username,
            SUM(user_timesheets.time_used) AS time_used
        FROM user_timesheets
        LEFT JOIN project_milestones ON user_timesheets.milestone_id = project_milestones.id
        LEFT JOIN projects ON projects.id = project_milestones.project_id
        LEFT JOIN users ON users.id = user_timesheets.resource_id
        WHERE MONTH(work_date) = :month AND YEAR(work_date) = :year AND user_timesheets.resource_id = :userId
        GROUP BY projects.project_name, users.name";

		// 	 $summary_query = "
        // SELECT 
        //     (projects.id) AS project_id,
        //     projects.project_name,
        //     (projects.bill) AS bill,
        //     (users.id) AS userid,
        //     users.name AS username,
        //     SUM(user_timesheets.time_used) AS time_used
        // FROM user_timesheets
        // LEFT JOIN project_milestones ON user_timesheets.milestone_id = project_milestones.id
        // LEFT JOIN projects ON projects.id = project_milestones.project_id
        // LEFT JOIN users ON users.id = user_timesheets.resource_id
        // WHERE MONTH(work_date) = :month AND YEAR(work_date) = :year AND user_timesheets.resource_id = :userId
        // GROUP BY projects.project_name, users.name";


		$summary_list = $conn->execute($summary_query, compact('month', 'year', 'userId'))->fetchAll('assoc');

		$projects = [];
		foreach ($summary_list as $row) {
			$slot_query = "
				SELECT SUM(project_allocations.time_slot) AS time_slot 
				FROM project_allocations 
				LEFT JOIN project_milestones ON project_allocations.milestone_id = project_milestones.id 
				WHERE project_milestones.project_id = :projectId 
				AND MONTH(project_milestones.due_date) = :month 
				AND YEAR(project_milestones.due_date) = :year 
				AND project_allocations.resource_id = :userId";
			$slot_result = $conn->execute($slot_query, [
				'projectId' => $row['project_id'],
				'month' => $month,
				'year' => $year,
				'userId' => $row['userid']
			])->fetch('assoc');

			$row['time_slot'] = $slot_result['time_slot'] ?? 0;
			$projects[] = $row;
		}

		$query2 = "SELECT title, start, end 
		FROM holidays 
		WHERE (MONTH(start) = " . $month . ") 
		AND YEAR(start) = " . $year . "
		AND deleted = 0
		";
		$stmtProduct2 = $conn->execute($query2);
		$holidays = $stmtProduct2->fetchAll('assoc');

		// get timesheet filled percentage
		$last_date_of_month = date(''.$year.'-'.$month.'-t', strtotime(''.$year.'-'.$month.''));
		$strto = date('Y-m',strtotime($last_date_of_month));
		$currentDt = date('Y-m');
		if($strto==$currentDt) {
			$last_date_of_month = date(''.$year.'-'.$month.'-d');
		} else {
			$last_date_of_month = date(''.$year.'-'.$month.'-t', strtotime(''.$year.'-'.$month.''));
		}

		$queryPer = "
			SELECT
				CONCAT(
					ROUND(
						Count_Filled_Percentage(
							'".$last_date_of_month."',
							SUM(ut.time_used)
						),
						2
					)
				) AS filled
			FROM user_timesheets ut
			WHERE
				ut.resource_id = ".$userId."
				AND MONTH(ut.work_date) = ".$month."
				AND YEAR(ut.work_date) = ".$year."
		";
		$stmtPer = $conn->execute($queryPer);
		$timesheet_percentage = $stmtPer->fetch('assoc');

		$timesheet_percentage = $timesheet_percentage['filled'] ?? 0;

		$this->set(compact('total_project_assign','total_billable_project_assign','total_non_billable_project_assign','total_miles','total_time_slot','user_time_use','billable_user_time_use','emp_details','emp_details_table','emp_list','total_leave','total_lv','total_average_leave_plan','total_wfh_leave','total_wfh','emp_attendence_list','leaves','month','year','late_entries','early_exits','wfh_dates','late_entries_list','early_exits_list','leave_details_list','avg_leave_details','total_project_assigned','time_used','projects','melist1','average_leave_plan','holidays','timesheet_percentage'));
	}
}
?>
