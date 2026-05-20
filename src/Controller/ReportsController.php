<?php
namespace App\Controller;

use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Http\Exception\ForbiddenException;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Security;
use Cake\ORM\Query;
use Cake\Database\Expression\QueryExpression;

class ReportsController extends AppController
{
	public function initialize(): void
    {
        parent::initialize();

        // $this->loadComponent('Paginator');
        $this->loadComponent('Flash'); // Include the FlashComponent
        // $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
	{
	    parent::beforeFilter($event);
	    // Configure the login action to not require authentication, preventing
	    // the infinite redirect loop issue
	    $this->Authentication->addUnauthenticatedActions(['login', 'signup']);
	}

	// Add this function inside your ReportsController class, but OUTSIDE the revenues() function

	private function getChartRevenueForPeriod($conn, $from_date, $to_date, $monthOrder, $wh, $whereMgr, $whereBd, $whereSrc)
	{
		// $query = "
		// 			SELECT 
		// 		MONTH(pm.due_date) AS month, 
		// 		IFNULL(SUM(pm.amount), 0) AS total,
		// 		ANY_VALUE(p.payment_id) AS payment_id,
		// 		ANY_VALUE(p.milestone_id) AS milestone_id
		// 	FROM project_milestones pm
		// 	JOIN projects p ON pm.project_id = p.id
		// 	JOIN users u ON p.client_id = u.id
		// 	WHERE pm.due_date BETWEEN '".$from_date."' AND '".$to_date."'
		// 	AND pm.deleted = 0
		// 	AND p.deleted = 1 ".$wh."
		// 	AND p.id != 0
		// 	".$whereMgr." ".$whereBd." ".$whereSrc."
		// 	GROUP BY MONTH(pm.due_date)
		// 	";

		$query = "
					SELECT 
				MONTH(pm.due_date) AS month, 
				IFNULL(SUM(pm.amount), 0) AS total,
				(p.payment_id) AS payment_id,
				(p.milestone_id) AS milestone_id
			FROM project_milestones pm
			JOIN projects p ON pm.project_id = p.id
			JOIN users u ON p.client_id = u.id
			WHERE pm.due_date BETWEEN '".$from_date."' AND '".$to_date."'
			AND pm.deleted = 0
			AND p.deleted = 1 ".$wh."
			AND p.id != 0
			".$whereMgr." ".$whereBd." ".$whereSrc."
			GROUP BY MONTH(pm.due_date)
			";
		$stmt = $conn->execute($query);
		$data = $stmt->fetchAll('assoc');
		$map = [];
		foreach ($data as $row) {
			$map[(int)$row['month']] = (float)$row['total'];
		}

		$list = [];
		foreach ($monthOrder as $m) {
			$list[] = $map[$m] ?? 0;
		}
		return $list;
	}

	public function revenues($type=null,$value=null)
	{
		$this->viewBuilder()->setLayout('default_new');
		$this->Authorization->skipAuthorization();
		$conn = ConnectionManager::get('default');
		$this->Users = $this->fetchTable("Users");
		// $this->Users = TableRegistry::get('Users');
		$session = new \Cake\Http\Session();
        $userSession = $session->read('data');
        $role = $userSession['role'];
		$roleArray = $userSession['role_name'];
		$validList = [9];
		$this->routeValidation($roleArray,$validList);
        $parent_id = ($role==1)?$userSession['id']:$userSession['parent_id'];

        if($role != 0)
        {
        	$wh = " AND p.user_id=".$parent_id;
        	$uwh = " AND u.company_id=".$parent_id;
        }
        else
        	$wh = "";

		if ($this->request->is('post')) {
				$date = explode('/', $this->request->getData('from_date'));
	            $financial_year_from = $date[2] . '-' . $date[0] . '-' . $date[1];

	            $date = explode('/', $this->request->getData('to_date'));
	            $financial_year_to = $date[2] . '-' . $date[0] . '-' . $date[1];

	            $monthData = $this->request->getData('months');
	            $month_id = ($monthData != '')?implode(',',$monthData):""; 
	            $bdData = $this->request->getData('bde');
	            $bd_id = ($bdData != '')?implode(',',$bdData):""; 
	            $managerData = $this->request->getData('managers'); 
	            $manager_id = ($managerData != '')?implode(',',$managerData):"";

	            $source = (!empty($this->request->getData('source')))?$this->request->getData('source'):"";

	            $whereMileMonth = ($month_id != '')?" AND month(due_date) IN (".$month_id.")":"";
				$wherePaymentMonth = ($month_id != '')?" AND month(payment_date) IN (".$month_id.")":"";
				$whereMgr =($manager_id != '')?" AND p.project_manager_id IN (".$manager_id.")":"";
				$whereBd = ($bd_id != '')?" AND p.bd_id IN (".$bd_id.")":"";
				$whereSrc = ($source != '')?" AND p.source='".$source."'":"";

				if($month_id)
				{
					for($i=0;$i<count($monthData);$i++)
					$months[$monthData[$i]] = date('F', mktime(0, 0, 0, $monthData[$i], 10));
				}
				else
				$months = array('04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December','01'=>'January','02'=>'February','03'=>'March');
			}
			else{
				// financial year
				if(date('Y-m-d')<date("Y-04-01"))
				{
					$financial_year_from = date("Y-04-01", strtotime(date('Y-04-01').' -1 year'));
					$financial_year_to = date("Y-03-31");
				}
				else{
					$financial_year_from = date("Y-04-01");
					$financial_year_to = date('Y-m-d', strtotime(date("Y-03-31").' + 1 year'));
				}
				

				$month_id =0; $bd_id = 0; $manager_id = 0; $source="Regular";
				$whereMileMonth = $whereMgr = $whereBd = $wherePaymentMonth = "";
				$whereSrc = " AND p.source='Regular'";
				
				$months = array('04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December','01'=>'January','02'=>'February','03'=>'March');
			}

			$from = explode('-', $financial_year_from);
			$to = explode('-', $financial_year_to);
			$fromYear = $from[0];
			$toYear = $to[0];

			
		//echo $financial_year_from.'---'.$financial_year_to;die;	
		$monthArray = array(); $newArray = array();
		foreach ($months as $key => $month) 
		{
		    $monthRevenue = 0;
		    $totalPaid = 0;
		    $totalUnPaid = $mp = 0;
		    $m =array(); $projectArray= array();

		    $m['month_name'] = $month;
		    $m['projects'] = array();
		    // milestone
			$queryMilestone = "SELECT amount,id,project_id from project_milestones where MONTH(due_date) = ".$key." AND (due_date >='".$financial_year_from."' AND due_date <='".$financial_year_to."' AND deleted=0 AND project_id != 0)"; 
		    $stmtProMan = $conn->execute($queryMilestone);
			$monthData = $stmtProMan->fetchAll('assoc');
			//pr($monthData);die;
			$amount = $paid =0; 
			if(count($monthData)>0)
			{ 
				foreach($monthData as $md)
				{ 
					$queryMilestone = "SELECT p.id,p.project_name,u.client_name,IF(p.payment_id != '',p.payment_id,0) as payment_id,IF(p.milestone_id != '',p.milestone_id,0) as milestone_id from projects p JOIN users u ON p.client_id=u.id  where p.deleted = 1".$wh." AND p.id=".$md['project_id'].$whereMgr.$whereBd.$whereSrc; 
				    $stmtProMan = $conn->execute($queryMilestone);
					$monthMile = $stmtProMan->fetchAll('assoc');
					if(count($monthMile)>0)
					{ 
						$amount += $md['amount']; 
						// paid
						if(!in_array($monthMile[0]['id'], $projectArray))
						{
							$projectArray[] = $monthMile[0]['id'];
							$newArray[] = $monthMile[0]['id'];
							if($key < 4)
							{
								
								$year = date('Y',strtotime('+1 year'));
								$quest = "'".date('Y')."-04' AND "."'".$year."-".$key."'";
								
							}
							else
							{
								
								$year = date('Y');
								$quest = "'".date('Y')."-04' AND "."'".$year."-".$key."'";
								
							}
							$queryPaid = "SELECT IFNULL(SUM(amount),0) as mile_amt FROM project_milestones WHERE DATE_FORMAT(due_date, '%Y-%m') BETWEEN ".$quest." AND project_id =".$monthMile[0]['id']." AND deleted=0";
						    $stmtPaid = $conn->execute($queryPaid);
							$totalMileData = $stmtPaid->fetchAll('assoc');//3168->k9
							
							$queryPaid = "SELECT IFNULL(SUM(receive_amt),0) as paid_amt,count(id) as total_no FROM project_payments WHERE status = 'Paid' AND id IN (".$monthMile[0]['payment_id'].")";
						    $stmtPaid = $conn->execute($queryPaid);
							$totalPaidData = $stmtPaid->fetchAll('assoc');//1889->L9
							
							$t['project_id'] = $monthMile[0]['id'];
							$t['project_name'] = $monthMile[0]['project_name'];
							$t['client_name'] = $monthMile[0]['client_name']; 
							$t['paid'] = 0;
							//milestone amount
							$queryPaid = "SELECT IFNULL(SUM(amount),0) as mile_amt FROM project_milestones WHERE MONTH(due_date) = ".$key." AND project_id =".$monthMile[0]['id']." AND (due_date >='".$financial_year_from."' AND due_date <='".$financial_year_to."' AND deleted=0)";
						    $stmtPaid = $conn->execute($queryPaid);
							$totalMileAmt = $stmtPaid->fetchAll('assoc');
							//pr($totalMileAmt);
							$t['revenue'] = $totalMileAmt[0]['mile_amt'];
							
							if(count($totalPaidData)>0)
							{
								if($totalPaidData[0]['paid_amt'] >= $totalMileData[0]['mile_amt'])
								{
									$paid += $totalMileAmt[0]['mile_amt']; 
									$t['paid'] = $totalMileAmt[0]['mile_amt'];
									
								}
								else if(($totalMileAmt[0]['mile_amt']-$totalMileData[0]['mile_amt']+$totalPaidData[0]['paid_amt'])>0)
								{
									$paid += ($totalMileAmt[0]['mile_amt']-$totalMileData[0]['mile_amt']+$totalPaidData[0]['paid_amt']); 
									$t['paid'] = ($totalMileAmt[0]['mile_amt']-$totalMileData[0]['mile_amt']+$totalPaidData[0]['paid_amt']); 
								}
								
							}


							//get paid amount
							$queryPaid = "SELECT IFNULL(SUM(receive_amt),0) as paid_amt FROM project_payments WHERE status = 'Paid' AND id IN (".$monthMile[0]['payment_id'].") AND MONTH(payment_date) = ".$key." AND (payment_date >='".$financial_year_from."' AND payment_date <='".$financial_year_to."')";
						    $stmtPaid = $conn->execute($queryPaid);
							$totalPaidData = $stmtPaid->fetchAll('assoc');

							$t['monthlypaid'] = $totalPaidData[0]['paid_amt'];
							$mp += $totalPaidData[0]['paid_amt'];

							//get paid amount
							$queryPaid = "SELECT IFNULL(SUM(receive_amt),0) as paid_amt FROM project_payments WHERE status != 'Paid' AND id IN (".$monthMile[0]['payment_id'].") AND MONTH(payment_date) = ".$key." AND (payment_date >='".$financial_year_from."' AND payment_date <='".$financial_year_to."')";
						    $stmtPaid = $conn->execute($queryPaid);
							$totalUnPaidData = $stmtPaid->fetchAll('assoc');

							$t['unpaid'] = $totalUnPaidData[0]['paid_amt'];
							//pr($t);
							if($t['revenue'] > 0)
								$m['projects'][] = $t;

						}	
						
					}
					
				}
				
			} 
			
			$monthRevenue =  $amount;  $totalPaid = $paid;

			// paid
			$queryPaid = "SELECT receive_amt,id FROM project_payments WHERE status = 'Paid' AND MONTH(payment_date) = ".$key." AND (payment_date >='".$financial_year_from."' AND payment_date <='".$financial_year_to."')";
		    $stmtPaid = $conn->execute($queryPaid);
			$totalPaidData = $stmtPaid->fetchAll('assoc');
			// $amount = 0;
			if(count($totalPaidData)>0)
			{ 
				foreach($totalPaidData as $md)
				{
					$queryPayment = "SELECT p.id,p.project_name,u.client_name,IF(p.payment_id != '',p.payment_id,0) as payment_id from projects p JOIN users u ON p.client_id=u.id where p.deleted = 1".$wh." AND FIND_IN_SET(".$md['id'].",p.payment_id) != 0".$whereMgr.$whereBd.$whereSrc;
				    $stmtProMan = $conn->execute($queryPayment);
					$monthPaid = $stmtProMan->fetchAll('assoc');
					if(count($monthPaid)>0)
					{
						if(!(in_array($monthPaid[0]['id'], $projectArray)))
						{
							$mp += $md['receive_amt'];

							$projectArray[] = $monthPaid[0]['id'];
							$t['project_name'] = $monthPaid[0]['project_name'];
							$t['client_name'] = $monthPaid[0]['client_name'];
							$t['revenue'] = 0;
							$t['paid'] = 0;
							$t['monthlypaid'] = $md['receive_amt'];

							$queryUnPaid = "SELECT IFNULL(SUM(receive_amt),0) as paid_amt FROM project_payments WHERE status != 'Paid' AND id IN (".$monthPaid[0]['payment_id'].") AND MONTH(payment_date) = ".$key." AND (payment_date >='".$financial_year_from."' AND payment_date <='".$financial_year_to."')";
						    $stmtUnPaid = $conn->execute($queryUnPaid);
							$totalUnPaidData = $stmtUnPaid->fetchAll('assoc');
							$t['unpaid'] = $totalUnPaidData[0]['paid_amt'];
							$m['projects'][] = $t;
						}
					}
				}
			}
		    $monthlyPaid = $mp;//$amount;

		    // unpaid
		    $queryUnPaid = "SELECT receive_amt,id FROM project_payments WHERE status !='Paid' AND MONTH(payment_date) = ".$key." AND (payment_date >='".$financial_year_from."' AND payment_date <='".$financial_year_to."')";
		    $stmtUnPaid = $conn->execute($queryUnPaid);
			$totalUnPaidData = $stmtUnPaid->fetchAll('assoc');
			$amount = 0;
			if(count($totalUnPaidData)>0)
			{ 
				foreach($totalUnPaidData as $md)
				{
					$queryPayment = "SELECT p.id,p.project_name,u.client_name from projects p JOIN users u ON p.client_id=u.id where p.deleted = 1".$wh." AND FIND_IN_SET(".$md['id'].",p.payment_id) != 0".$whereMgr.$whereBd.$whereSrc;
				    $stmtProMan = $conn->execute($queryPayment);
					$monthUnpaid = $stmtProMan->fetchAll('assoc');
					if(count($monthUnpaid)>0)
					{
						$amount += $md['receive_amt'];

						if(!(in_array($monthUnpaid[0]['id'], $projectArray)))
						{
							
							$projectArray[] = $monthUnpaid[0]['id'];
							$t['project_name'] = $monthUnpaid[0]['project_name'];
							$t['client_name'] = $monthUnpaid[0]['client_name'];
							$t['revenue'] = 0;
							$t['paid'] = 0;
							$t['monthlypaid'] = 0;
							$t['unpaid'] = $md['receive_amt'];
							$m['projects'][] = $t;
						}
					}
				}
			}
		    $totalUnPaid = $amount;


				
				$m['revenue'] = $monthRevenue;
				$m['monthlypaid'] = $monthlyPaid;
				$m['paid']   = $totalPaid;
				$m['unpaid']   = $totalUnPaid;
				$monthArray[] = $m;
				 
		}
		// echo '<pre>'; print_r($monthArray); exit;
		// manager
		// client wise data (dev code)
		$clientWise = [];

		foreach ($monthArray as $monthData) {

			$monthName = $monthData['month_name'];

			foreach ($monthData['projects'] as $project) {

				$clientName = $project['client_name'];
				$projectId = $project['project_id'];

				if (!isset($clientWise[$clientName])) {
					$clientWise[$clientName] = [
						'client_name' => $clientName,
						'projects' => []
					];
				}

				if (!isset($clientWise[$clientName]['projects'][$projectId])) {
					$clientWise[$clientName]['projects'][$projectId] = [
						'project_id' => $projectId,
						'project_name' => $project['project_name'],
						'months' => []
					];
				}

				$clientWise[$clientName]['projects'][$projectId]['months'][] = [
					'month' => $monthName,
					'paid' => $project['paid'],
					'revenue' => $project['revenue'],
					'monthlypaid' => $project['monthlypaid'],
					'unpaid' => $project['unpaid']
				];
			}
		}

		$clientWise = array_values($clientWise);

		// echo '<pre>'; print_r($clientWise); exit;

		$labels = ['April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'January', 'February', 'March'];
		$monthOrder = [4, 5, 6, 7, 8, 9, 10, 11, 12, 1, 2, 3];

		
		$selectedYear = $value;
		$chartFyStartYear = null; 

		if (!empty($selectedYear)) {
		
			$normalizedYear = str_replace('–', '-', $selectedYear); 
			$parts = explode('-', $normalizedYear);
			if (isset($parts[0]) && is_numeric($parts[0])) {
				$chartFyStartYear = (int)$parts[0];
			}
		}
		if ($chartFyStartYear === null) {
			$chartFyStartYear = (date('m') >= 4) ? (int)date('Y') : (int)date('Y') - 1;
			$selectedYear = $chartFyStartYear . '–' . ($chartFyStartYear + 1);
		}

		$chart_current_from = $chartFyStartYear . '-04-01';
		$chart_current_to = ($chartFyStartYear + 1) . '-03-31';

		$chart_previous_from = ($chartFyStartYear - 1) . '-04-01';
		$chart_previous_to = $chartFyStartYear . '-03-31';
		$currentRevenueList = $this->getChartRevenueForPeriod($conn, $chart_current_from, $chart_current_to, $monthOrder, $wh, $whereMgr, $whereBd, $whereSrc);
		$previousRevenueList = $this->getChartRevenueForPeriod($conn, $chart_previous_from, $chart_previous_to, $monthOrder, $wh, $whereMgr, $whereBd, $whereSrc);
		$toYear = $chartFyStartYear;
		$fromYear = $chartFyStartYear - 1;
		if (!function_exists('getCumulative')) {
			function getCumulative($list) {
				$out = [];
				$total = 0;
				foreach ($list as $v) {
					$total += $v;
					$out[] = $total;
				}
				return $out;
			}
		}
		$cumulativeCurrent = getCumulative($currentRevenueList);
		$cumulativePrevious = getCumulative($previousRevenueList);

		$growthPercentages = [];
		for ($i = 0; $i < count($monthOrder); $i++) {
			$current = $currentRevenueList[$i] ?? 0;
			$previous = $previousRevenueList[$i] ?? 0;

			if ($previous > 0) {
				$percentage = (($current - $previous) / $previous) * 100;
				$growthPercentages[] = round($percentage, 1); 
			} else {
				$growthPercentages[] = null;
			}
		}

		$cummulativeGrowthPercentages = [];
		for ($i = 0; $i < count($monthOrder); $i++) {
			$cummulativecurrent = $cumulativeCurrent[$i] ?? 0;
			$cummulativeprevious = $cumulativePrevious[$i] ?? 0;

			if ($cummulativeprevious > 0) {
				$percentage1 = (($cummulativecurrent - $cummulativeprevious) / $cummulativeprevious) * 100;
				$cummulativeGrowthPercentages[] = round($percentage1, 1); 
			} else {
				$cummulativeGrowthPercentages[] = null;
			}
		}

		$queryManager = "SELECT u.id,u.name,u.role_name from users u  where u.deleted = 1 and u.role = 3  and find_in_set('4',u.role_name)!=0".$uwh;
		$stmtManager = $conn->execute($queryManager);
		$projectManagers = $stmtManager->fetchAll('assoc');

		// bd
		$queryBd = "SELECT  u.id,u.name,u.role_name from users u  where u.deleted = 1 and u.role = 3  and find_in_set('6',u.role_name)!=0".$uwh;
		$stmtBd = $conn->execute($queryBd);
		$projectBd = $stmtBd->fetchAll('assoc');

		// total revenue
	    $queryFinancial = "SELECT amount,id,project_id FROM project_milestones WHERE deleted=0 AND date(due_date) >= '$financial_year_from' AND date(due_date) <= '$financial_year_to'".$whereMileMonth;

	    $stmtFinancial = $conn->execute($queryFinancial);
		$totalRevenueData = $stmtFinancial->fetchAll('assoc');
		$amount = 0;
		if(count($totalRevenueData)>0)
		{ 
			foreach($totalRevenueData as $m)
			{
				$queryMilestone = "SELECT p.id from projects p where p.deleted = 1".$wh." AND p.id=".$m['project_id'].$whereMgr.$whereBd.$whereSrc;
			    $stmtProMan = $conn->execute($queryMilestone);
				$monthMile = $stmtProMan->fetchAll('assoc');
				if(count($monthMile)>0)
					$amount += $m['amount'];

				
			}
		} 
		$totalRevenue = $amount;


		// YTD revenue
	    $queryFinancial = "SELECT amount,id,project_id FROM project_milestones WHERE deleted=0 AND date(due_date) >= '$financial_year_from' AND date(due_date) <= '".date('Y-m-d')."'".$whereMileMonth;
	    $stmtFinancial = $conn->execute($queryFinancial);
		$totalRevenueData = $stmtFinancial->fetchAll('assoc');

		$amount = 0;
		if(count($totalRevenueData)>0)
		{ 
			foreach($totalRevenueData as $m)
			{
				$queryMilestone = "SELECT p.id from projects p where p.deleted = 1".$wh." AND p.id=".$m['project_id'].$whereMgr.$whereBd.$whereSrc;
			    $stmtProMan = $conn->execute($queryMilestone);
				$monthMile = $stmtProMan->fetchAll('assoc');
				if(count($monthMile)>0)
					$amount += $m['amount'];
			}
		}
		$ytdRevenue = $amount;


		    // total paid
	    $queryPaid = "SELECT receive_amt,id FROM project_payments WHERE status ='Paid' AND date(payment_date) >= '$financial_year_from' AND date(payment_date) <= '$financial_year_to'".$wherePaymentMonth;
	    $stmtPaid = $conn->execute($queryPaid);
		$totalPaidData = $stmtPaid->fetchAll('assoc');
		$amount = 0;
		if(count($totalPaidData)>0)
		{ 
			foreach($totalPaidData as $m)
			{
				$queryPayment = "SELECT p.id from projects p where p.deleted = 1".$wh." AND FIND_IN_SET(".$m['id'].",p.payment_id) != 0".$whereMgr.$whereBd.$whereSrc;
			    $stmtProMan = $conn->execute($queryPayment);
				$monthPaid = $stmtProMan->fetchAll('assoc');
				if(count($monthPaid)>0)
					$amount += $m['receive_amt'];
			}
		}
		$totalPaid = $amount;

	    // total Unpaid
	    $queryBilledUnPaid = "SELECT receive_amt,id FROM project_payments WHERE status !='Paid' AND date(payment_date) >= '$financial_year_from' AND date(payment_date) <= '$financial_year_to'".$wherePaymentMonth;
	    $stmtBilledUnPaid = $conn->execute($queryBilledUnPaid);
		$totalBilledUnPaidData = $stmtBilledUnPaid->fetchAll('assoc');
		$amount = 0;
		if(count($totalBilledUnPaidData)>0)
		{ 
			foreach($totalBilledUnPaidData as $m)
			{
				$queryPayment = "SELECT p.id from projects p where p.deleted = 1".$wh." AND FIND_IN_SET(".$m['id'].",p.payment_id) != 0".$whereMgr.$whereBd.$whereSrc;
			    $stmtProMan = $conn->execute($queryPayment);
				$monthUnpaid = $stmtProMan->fetchAll('assoc');
				if(count($monthUnpaid)>0)
					$amount += $m['receive_amt'];
			}
		}
		$totalBilledUnPaid = $amount;
		   
		// manager list		   
	    $queryProjectManager = "SELECT GROUP_CONCAT(p.id) project_id,p.project_manager_id,u.status,u.name manager_name from projects p LEFT JOIN users u ON p.project_manager_id = u.id WHERE p.deleted = 1".$wh." AND FIND_IN_SET('4',u.role_name)!=0".$whereMgr.$whereBd.$whereSrc." GROUP By p.project_manager_id";
	    $stmtProMan = $conn->execute($queryProjectManager);
		$managerLists = $stmtProMan->fetchAll('assoc');
		 

		$projetManagersData = array();
		   
	    foreach($managerLists as $managerList)
	    {
		   	$p = array();
			$p['manager_id'] = $managerList['project_manager_id'];
			$p['manager_name'] = $managerList['manager_name'];
			$p['status'] = $managerList['status'];
			$p['projects'] = array();
			
			$project_id = trim($managerList['project_id'],',');

			$managerUnpaid = $managerRevenue= $managerPaid = 0;
		   	
		   	//get project wise amount
			$projects = explode(',', $project_id);

			foreach($projects as $project)
			{
				$pRevenue = $pUnpaid = $pPaid = 0;

				$queryMilestone = "SELECT p.project_name,u.client_name,IF((p.milestone_id != '' or p.milestone_id != NULL),p.milestone_id,0) as milestone_id,IF((p.payment_id != '' or p.payment_id != NULL),p.payment_id,0) as payment_id FROM projects p JOIN users u ON p.client_id=u.id WHERE p.id=".$project;

				$stmtProMan = $conn->execute($queryMilestone);
				$prData = $stmtProMan->fetchAll('assoc');
				// echo '<pre>'; print_r($prData);
				if(count($prData)>0)
				{
					//calculate total amount from milestone
					$queryMilestone = "SELECT IFNULL(sum(amount),0) as revenue from project_milestones where deleted=0 AND (date(due_date) >= '$financial_year_from' AND date(due_date) <= '$financial_year_to') AND project_id =".$project.$whereMileMonth;

					$stmtProMan = $conn->execute($queryMilestone);
					$managerData = $stmtProMan->fetchAll('assoc');
				    $pRevenue = $managerData[0]['revenue'];
				    // calculate total amount for payment
				    $queryPaid = "SELECT IFNULL(sum(receive_amt),0) as paid from project_payments where (date(payment_date) >= '$financial_year_from' AND date(payment_date) <= '$financial_year_to') AND id IN(".$prData[0]['payment_id'].") and status ='Paid'".$wherePaymentMonth;

					$stmtPaid = $conn->execute($queryPaid);
					$managerData = $stmtPaid->fetchAll('assoc');
					$pPaid = $managerData[0]['paid'];

				    $queryUnPaid = "SELECT IFNULL(sum(receive_amt),0) as unpaid from project_payments where (date(payment_date) >= '$financial_year_from' AND date(payment_date) <= '$financial_year_to') AND id IN(".$prData[0]['payment_id'].") and status !='Paid'".$wherePaymentMonth;

					$stmtUnPaid = $conn->execute($queryUnPaid);
					$managerData = $stmtUnPaid->fetchAll('assoc');
				    $pUnpaid = $managerData[0]['unpaid'];

				    $c['project_name'] = $prData[0]['project_name'];
				    $c['client_name'] = $prData[0]['client_name'];
				    $c['revenue'] = $pRevenue;
				    $c['paid'] = $pPaid;
				    $c['unpaid'] = $pUnpaid;
					if($c['revenue'] > 0)
				    	$p['projects'][] = $c;

				    $managerRevenue += $pRevenue;
				    $managerUnpaid += $pUnpaid;
				    $managerPaid += $pPaid;
				}
			}
			//calculate total amount from milestone
			// if(!empty($milestone_id))
		 //    {
			// 	$queryMilestone = "SELECT sum(amount) revenue FROM project_milestones where (date(due_date) >= '$financial_year_from' AND date(due_date) <= '$financial_year_to') AND id IN(".$milestone_id.")".$whereMileMonth;

			// 	$stmtProMan = $conn->execute($queryMilestone);
			// 	$managerData = $stmtProMan->fetchAll('assoc');
			//     $managerRevenue = $managerData[0]['revenue'];
			// }
		    

		    // calculate total amount for payment
		  //   if(!empty($payment_id))
		  //   {
			 //    $queryPaid = "SELECT sum(receive_amt) paid FROM project_payments where (date(payment_date) >= '$financial_year_from' AND date(payment_date) <= '$financial_year_to') AND id IN(".$payment_id.") and status ='Paid'".$wherePaymentMonth;

				// $stmtPaid = $conn->execute($queryPaid);
				// $managerData = $stmtPaid->fetchAll('assoc');
				// $managerPaid = $managerData[0]['paid'];

				// $queryUnPaid = "SELECT sum(receive_amt) unpaid from project_payments where (date(payment_date) >= '$financial_year_from' AND date(payment_date) <= '$financial_year_to') AND id IN(".$payment_id.") and status !='Paid'".$wherePaymentMonth;

				// $stmtUnPaid = $conn->execute($queryUnPaid);
				// $managerData = $stmtUnPaid->fetchAll('assoc');
			 //    $managerUnpaid = $managerData[0]['unpaid'];
		  //   }
		    
		    $p['revenue'] = $managerRevenue;
		    $p['paid'] = $managerPaid;
		    $p['unpaid'] = $managerUnpaid;
		   
		    $projetManagersData[] =$p;
		}

		// BD List
		$conn->execute("SET SESSION group_concat_max_len = 1000000");
		$queryBd = "SELECT GROUP_CONCAT(p.id) project_id,p.bd_id,u.name bd_name from projects p LEFT JOIN users u on p.bd_id = u.id where p.deleted = 1".$wh." AND find_in_set('6',u.role_name)!=0".$whereBd.$whereMgr.$whereSrc." GROUP By p.bd_id";
		    $stmtBd = $conn->execute($queryBd);
		$bdLists = $stmtBd->fetchAll('assoc');
		   
	    $projectBdData = array();

	    foreach($bdLists as $bdList)
	    {
	    	$p = array();
			$p['bd_id'] = $bdList['bd_id'];
		    $p['bd_name'] = $bdList['bd_name'];
		    $p['projects'] = array();
			
			$project_id = trim($bdList['project_id'],',');

			$bdRevenue = $bdUnpaid = $bdPaid = 0;

			//get project wise amount
			$projects = explode(',', $project_id);

			foreach($projects as $project)
			{
				$pRevenue = $pUnpaid = $pPaid = 0;

				$queryMilestone = "SELECT p.project_name,u.client_name,IF((p.milestone_id != '' or p.milestone_id != NULL),p.milestone_id,0) as milestone_id,IF((p.payment_id != '' or p.payment_id != NULL),p.payment_id,0) as payment_id FROM projects p JOIN users u ON p.client_id=u.id WHERE p.id=".$project;

				$stmtProMan = $conn->execute($queryMilestone);
				$prData = $stmtProMan->fetchAll('assoc');
				// echo '<pre>'; print_r($prData);
				if(count($prData)>0)
				{
					//calculate total amount from milestone
					$queryMilestone = "SELECT IFNULL(sum(amount),0) as revenue from project_milestones where deleted=0 AND (date(due_date) >= '$financial_year_from' AND date(due_date) <= '$financial_year_to') AND project_id =".$project.$whereMileMonth;

					$stmtProMan = $conn->execute($queryMilestone);
					$bdData = $stmtProMan->fetchAll('assoc');
				    $pRevenue = $bdData[0]['revenue'];
				    // calculate total amount for payment
				    $queryPaid = "SELECT IFNULL(sum(receive_amt),0) as paid from project_payments where (date(payment_date) >= '$financial_year_from' AND date(payment_date) <= '$financial_year_to') AND id IN(".$prData[0]['payment_id'].") and status ='Paid'".$wherePaymentMonth;

					$stmtPaid = $conn->execute($queryPaid);
					$bdData = $stmtPaid->fetchAll('assoc');
				    $pPaid = $bdData[0]['paid'];

				    $queryUnPaid = "SELECT IFNULL(sum(receive_amt),0) as unpaid from project_payments where (date(payment_date) >= '$financial_year_from' AND date(payment_date) <= '$financial_year_to') AND id IN(".$prData[0]['payment_id'].") and status !='Paid'".$wherePaymentMonth;

					$stmtUnPaid = $conn->execute($queryUnPaid);
					$bdData = $stmtUnPaid->fetchAll('assoc');
				    $pUnpaid = $bdData[0]['unpaid'];

				    $c['project_name'] = $prData[0]['project_name'];
				    $c['client_name'] = $prData[0]['client_name'];
				    $c['revenue'] = $pRevenue;
				    $c['paid'] = $pPaid;
				    $c['unpaid'] = $pUnpaid;
					if($c['revenue'] > 0)
				    	$p['projects'][] = $c;

				    $bdRevenue += $pRevenue;
				    $bdUnpaid += $pUnpaid;
				    $bdPaid += $pPaid;
				}
			    
			}
			
		    
		    $p['revenue'] = $bdRevenue;
		    $p['paid'] = $bdPaid;
		    $p['unpaid'] = $bdUnpaid;
		   
		    $projectBdData[] =$p;
		}

		// echo "<pre>";
		// print_r($projetManagersData);
		// die;

		// echo '<pre>'; print_r($monthArray); exit;

		   $this->set(compact('projectManagers','projetManagersData','projectBdData','monthArray','projectBd','totalRevenue','ytdRevenue','totalPaid','totalBilledUnPaid','manager_id','bd_id','financial_year_from','financial_year_to','month_id','source','labels','currentRevenueList','previousRevenueList','cumulativeCurrent', 'cumulativePrevious','selectedYear','fromYear','toYear','cummulativeGrowthPercentages','growthPercentages','clientWise'));    
	}

	public function getChartData($year = null)
	{
		$this->request->allowMethod('ajax');
		$this->Authorization->skipAuthorization();

		$conn = ConnectionManager::get('default');
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$role = $userSession['role'];
		$roleArray = $userSession['role_name'];
		$validList = [9];
		$this->routeValidation($roleArray,$validList);
        $parent_id = ($role==1)?$userSession['id']:$userSession['parent_id'];


		if ($role != 0) {
			$wh = " AND p.user_id=".$parent_id;
        	$uwh = " AND u.company_id=".$parent_id;
		} else {
			$wh = "";
		}
		// $whereMgr = $whereBd = $whereSrc = "";
		$bdData = $this->request->getQuery('bde');
		$bd_id = (!empty($bdData)) ? implode(',', $bdData) : "";
		
		$managerData = $this->request->getQuery('managers');
		$manager_id = (!empty($managerData)) ? implode(',', $managerData) : "";
		
		$source = $this->request->getQuery('source');

		// Note: We don't need a month filter for the chart, as it groups by month anyway.
		$whereMgr = ($manager_id != '') ? " AND p.project_manager_id IN (" . $manager_id . ")" : "";
		$whereBd = ($bd_id != '') ? " AND p.bd_id IN (" . $bd_id . ")" : "";
		$whereSrc = ($source != '') ? " AND p.source='" . $source . "'" : "";

		$monthOrder = [4, 5, 6, 7, 8, 9, 10, 11, 12, 1, 2, 3];
		$chartFyStartYear = null;

		if (!empty($year)) {
			$normalizedYear = str_replace('–', '-', $year);
			$parts = explode('-', $normalizedYear);
			if (isset($parts[0]) && is_numeric($parts[0])) {
				$chartFyStartYear = (int)$parts[0];
			}
		}
		if ($chartFyStartYear === null) {
			$chartFyStartYear = (date('m') >= 4) ? (int)date('Y') : (int)date('Y') - 1;
		}

		$chart_current_from = $chartFyStartYear . '-04-01';
		$chart_current_to = ($chartFyStartYear + 1) . '-03-31';
		$chart_previous_from = ($chartFyStartYear - 1) . '-04-01';
		$chart_previous_to = $chartFyStartYear . '-03-31';

		$currentRevenueList = $this->getChartRevenueForPeriod($conn, $chart_current_from, $chart_current_to, $monthOrder, $wh, $whereMgr, $whereBd, $whereSrc);
		$previousRevenueList = $this->getChartRevenueForPeriod($conn, $chart_previous_from, $chart_previous_to, $monthOrder, $wh, $whereMgr, $whereBd, $whereSrc);

        

		$toYear = $chartFyStartYear;
		$fromYear = $chartFyStartYear - 1;

		$cumulative = function ($list) {
			$out = []; $total = 0;
			foreach ($list as $v) { $total += $v; $out[] = $total; }
			return $out;
		};
		$cumulativeCurrent = $cumulative($currentRevenueList);
		$cumulativePrevious = $cumulative($previousRevenueList);

		$growthPercentages = [];
		for ($i = 0; $i < count($monthOrder); $i++) {
			$current = $currentRevenueList[$i] ?? 0;
			$previous = $previousRevenueList[$i] ?? 0;

			if ($previous > 0) {
				$percentage = (($current - $previous) / $previous) * 100;
				$growthPercentages[] = round($percentage, 1); 
			} else {
				$growthPercentages[] = null;
			}
		}

		$cummulativeGrowthPercentages = [];
		for ($i = 0; $i < count($monthOrder); $i++) {
			$cummulativecurrent = $cumulativeCurrent[$i] ?? 0;
			$cummulativeprevious = $cumulativePrevious[$i] ?? 0;

			if ($cummulativeprevious > 0) {
				$percentage1 = (($cummulativecurrent - $cummulativeprevious) / $cummulativeprevious) * 100;
				$cummulativeGrowthPercentages[] = round($percentage1, 1); 
			} else {
				$cummulativeGrowthPercentages[] = null;
			}
		}

		// Package the data for the JSON response
		$chartData = [
			'monthlyCurrent' => $currentRevenueList,
			'monthlyPrevious' => $previousRevenueList,
			'cumulativeCurrent' => $cumulativeCurrent,
			'cumulativePrevious' => $cumulativePrevious,
			'growthPercentages' => $growthPercentages,
			'cummulativeGrowthPercentages' => $cummulativeGrowthPercentages,
			'toYear' => $toYear,
			'fromYear' => $fromYear,
		];

		$this->viewBuilder()->setClassName('Json');
		$this->set(compact('chartData'));
		$this->set('_serialize', 'chartData');
	}


}

