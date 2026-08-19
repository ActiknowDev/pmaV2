<?php

declare(strict_types=1);

namespace App\Controller;


require_once ROOT . '/vendor/autoload.php';

//use Cake\Core\Configure;
use Google\Client;
use Google\Service\Calendar;
use Google_Service_Calendar_Event;
use Cake\Http\Session;
use Symfony\Component\VarDumper\VarDumper;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\FrozenTime;
// define("path", ROOT."/actiknowcalendar.json");

/**
 * Leaves Controller
 *
 * @property \App\Model\Table\LeavesTable $Leaves
 * @method \App\Model\Entity\Leave[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LeavesController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->Authorization->skipAuthorization();
        $this->viewBuilder()->setLayout('default_new');
        // $this->loadModel("Users");
        // $this->loadModel("Leaves");
        $this->fetchTable("Leaves");
        // $this->loadComponent('Csrf');
        // $this->YearlyHoliday = $this->getTableLocator()->get("YearlyHoliday");
        $this->Users = $this->getTableLocator()->get("Users");
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {

        $session = new \Cake\Http\Session();
        $userId = $session->read('user_data')['id'];

        $user_data = $this->Users->get($userId, ['contain' => ['EmpDetail']]);

        $leaveSession = $session->read('data');

        $leaved_data = $this->Leaves->findByCreatedBy($userId)->contain(['Users'])->orderAsc('Leaves.status');

        $leave = $this->Leaves->newEmptyEntity();
        $resources = $this->Users->find()
            ->where(['role' => 3, 'deleted' => 1, 'status' => 1])
            ->select(['name', 'id'])
            ->toArray();

        $rmId = $session->read('user_data')['reporting_manager'];

        $myLeave = $this->sumOfApprovedLeave($userId);

        // echo "<pre>";
        // print_r($myLeave);
        // die;

        $this->set(compact('leave', 'user_data', 'leaved_data', 'leaveSession', 'resources', 'rmId', 'myLeave'));
    }


    public function requestleave()
    {
        $session = new \Cake\Http\Session();
        $userSession = $session->read('data');
        $user_data = $this->Users->get($userSession['id'], [
            'contain' => ['EmpDetail', 'children', 'parent']
        ]);
        // echo "<pre>";
        // print_r($this->request->getQuery('status'));
        // die;

        $check_mail = ['himani.duhan@actiknow.com', 'sumit.jhunjhunwala@actiknow.com', 'arpit.batham@actiknow.com'];

        if (in_array($userSession['email'], $check_mail)) {
            $conditions = [
                'created_by !=' => $userSession['id']
            ];
        } else {

            $child = \Cake\Utility\Hash::extract($user_data['children'], '{n}.id');
            if (count($child) <= 0) {
                $child = [0];
            }
            $conditions = [
                'created_by IN' => $child
            ];
        }

        $leaved_data = [];

        $selectStatus = '';

        if ($this->request->getQuery('status') == "Pending") {

            $leaved_data = $this->Leaves->find('all', [
                'contain' => ['Users', 'CreatedBy'], 'conditions' => $conditions,
                'order' => 'Leaves.from_date DESC'
            ])->where(['Leaves.status' => 'Pending'])->toArray();
            $selectStatus = "Pending";
        } elseif ($this->request->getQuery('status') == "Approved") {

            $leaved_data = $this->Leaves->find('all', [
                'contain' => ['Users', 'CreatedBy'], 'conditions' => $conditions,
                'order' => 'Leaves.from_date DESC'
            ])->where(['Leaves.status' => 'Approved'])->toArray();
            $selectStatus = "Approved";
        } elseif ($this->request->getQuery('status') == "cancelled") {

            $leaved_data = $this->Leaves->find('all', [
                'contain' => ['Users', 'CreatedBy'], 'conditions' => $conditions,
                'order' => 'Leaves.from_date DESC'
            ])->where(['Leaves.status' => 'cancelled'])->toArray();
            $selectStatus = "cancelled";
        } elseif ($this->request->getQuery('status') == "Rejected") {

            $leaved_data = $this->Leaves->find('all', [
                'contain' => ['Users', 'CreatedBy'], 'conditions' => $conditions,
                'order' => 'Leaves.from_date DESC'
            ])->where(['Leaves.status' => 'Rejected'])->toArray();
            $selectStatus = "Rejected";
        } else {

            $leaved_data = $this->Leaves->find('all', [
                'contain' => ['Users', 'CreatedBy','CreatedBy' => ['Manager']], 'conditions' => $conditions,
            ]);
            // $leaved_data = $this->Leaves->find('all', [
            //     'contain' => ['Users', 'CreatedBy'], 'conditions' => $conditions,
            //      'order' => 'Leaves.status DESC'
            // ])->toArray();

            $leaveWhenThen = $leaved_data->newExpr()->case()
                ->when(['Leaves.status' => "Pending"])
                ->then(4)
                ->when(['Leaves.status' => "Approved"])
                ->then(3)
                ->when(['Leaves.status' => "Rejected"])
                ->then(2)
                ->else(1);

            // $result_data = $leaved_data->select([
            //     'id' => 'Leaves.id',
            //     'status' => 'Leaves.status',
            //     'message' => 'Leaves.message',
            //     'created_by' => 'Leaves.created_by',
            //     'leave_type' => 'Leaves.leave_type',
            //     'CreatedBy' => 'CreatedBy.name',
            //     'Manager' => 'Manager.name',
            //     'subject' => 'Leaves.subject',
            //     'applied_on' => 'Leaves.applied_on',
            //     'from_date' => 'Leaves.from_date',
            //     'to_date' => 'Leaves.to_date',
            //     'wfh' => 'Leaves.wfh',
            //     'wfh_type' => 'Leaves.wfh_type',
            //     'wfh_flag' => 'Leaves.wfh_flag',
            //     'value' => $leaveWhenThen,
            // ])->orderDesc($leaveWhenThen, 'Leaves.from_date')->toArray();

          $result_data = $leaved_data->select([
                'id' => 'Leaves.id',
                'status' => 'Leaves.status',
                'message' => 'Leaves.message',
                'created_by' => 'Leaves.created_by',
                'leave_type' => 'Leaves.leave_type',
                'CreatedBy' => 'CreatedBy.name',
                'Manager' => 'Manager.name',
                'subject' => 'Leaves.subject',
                'applied_on' => 'Leaves.applied_on',
                'from_date' => 'Leaves.from_date',
                'to_date' => 'Leaves.to_date',
                'wfh' => 'Leaves.wfh',
                'wfh_type' => 'Leaves.wfh_type',
                'wfh_flag' => 'Leaves.wfh_flag',

                // alias
                'value' => $leaveWhenThen,
            ])
            ->order([
                'value' => 'DESC',
                'Leaves.from_date' => 'DESC'
            ])
            ->toArray();

            $selectStatus = "";
        }
        // echo "<pre>";
        // print_r($leaved_data);
        // die;
        // dd($leaved_data);
        $leave = $this->Leaves->newEmptyEntity();

        // dd($result_data);

        $leaveSession = $session->read('data');

        $this->set(compact('leave', 'selectStatus', 'user_data', 'leaved_data', 'leaveSession', 'result_data'));
    }

    public function leavecheck($data) {
    //    dd($data);

        $session = new \Cake\Http\Session();
        $userSession = $session->read('data');
        $managerName = $userSession['name'];
        // $id = $this->request->getData("id");
        // $status = 'pending';
        $leave = $data['id'];
        $leaveById = $this->Leaves->get($leave);
        $from_date = date_create((string)$data['from_date']);
        $to_date = date_create((string)$data['to_date']);

        $from_date  = date_format($from_date, "Y-m-d");
        $to_date = date_format($to_date, "Y-m-d");

        $fromDate = date_create((string)$data['from_date']);
        $toDate = date_create((string)$data['to_date']);
        $year = date_format($fromDate, "Y");
        $fromDateCheck = date_format($fromDate, 'm');
        $toDateCheck = date_format($toDate, 'm');
        $user = $this->Users->get($data['created_by']);
        $leaveType =  $data['leave_type'];
        // $from_month_date = date_format($leaveById->from_date, "m");
        // $from_year_date = date_format($leaveById->from_date, "Y");
        $from_month_date = $leaveById->from_date->format('m');
        $from_year_date = $leaveById->from_date->format('Y');
        // dd($from_month_date);
        // $wfh=$this->Leaves->find()->select([
        //     'total'=>'SUM(datediff(Leaves.to_date,Leaves.from_date))'
        // ])->where([
        //     'Leaves.created_by'=>$data['created_by'],
        //     'month(from_date)'=>$from_month_date,
        //     'YEAR(from_date)'=>$from_year_date
        // ])
        // ->first();
        // dd($wfh);
        $takenLeave = $this->sumOfApprovedLeave($data['created_by']);

        $leaveCount = $this->getTableLocator()->get('LeaveCount');
        $leaveCountInsert = $leaveCount->newEmptyEntity();
        $leaveCountInsert->user_id  = $data['created_by'];
        $leaveCountInsert->leave_id = $data['id'];
        $leaveCountInsert->leave_date =date("Y-m-d", strtotime($data['from_date']));
        $date_got = $this->getDateDiff((string)$data['from_date'], (string)$data['to_date']);
        if($data['weekend']=='true') {
            $extra_weekend_leave = 2;
        } else {
            $extra_weekend_leave = 0;
        }
        
        if ($leaveType == "Paid Leave") {
            $leaveCountInsert->el = $date_got+$extra_weekend_leave;
        } else if ($leaveType == "Casual Leave") {
            // dd($leaveCountInsert->cl);
            $leaveCountInsert->cl = $date_got+$extra_weekend_leave;
        } else if ($leaveType == "Sick Leave") {
            $leaveCountInsert->sl = $date_got+$extra_weekend_leave;
        } else if ($leaveType == "comp_off") {
            $leaveCountInsert->comp_off = $date_got;
        } else if ($leaveType == "LWP") {
            $leaveCountInsert->lwp = $date_got+$extra_weekend_leave;
        } else if ($leaveType == "Half Day") {
            $allTypeLeave = [];
            if ($user->cl - $takenLeave['sumCL'] >= 0.5) {
                $allTypeLeave[] = ['cl' => 0.5];
                $leaveCountInsert->cl = $date_got / 2;
            } else if ($user->sl - $takenLeave['sumSL'] >= 0.5) {
                $allTypeLeave[] = ['sl' => 0.5];
                $leaveCountInsert->sl = $date_got / 2;
            } else if ($user->el - $takenLeave['sumEL'] >= 0.5) {
                $allTypeLeave[] = ['el' => 0.5];
                $leaveCountInsert->el = $date_got / 2;
            } else if ($user->comp_off - $takenLeave['sumComp'] >= 0.5) {
                $allTypeLeave[] = ['comp_off' => 0.5];
                $leaveCountInsert->el = $date_got / 2;
            } else {
                $allTypeLeave[] = ['lwp' => 0.5];
                $leaveCountInsert->lwp = $date_got / 2;
            }
            // // dd($allTypeLeave);
            // $cutLeave = json_encode($allTypeLeave);
            // // dd($leave->leave_details = $cutLeave);
            // $this->Leaves->save($leave);
        }
        else if($leaveType == "WFH") {
            $leaveDuration=$date_got/2;
            // Check Casual Leave balance
            $casualLeaveBalance = $user->cl - $takenLeave['sumCL'];
            $casualLeavesTaken = min($leaveDuration, $casualLeaveBalance);
            $leaveDuration -= $casualLeavesTaken;
            $leaveCountInsert->cl=$casualLeavesTaken;
            // Check Sick Leave balance
            $sickLeaveBalance = $user->sl - $takenLeave['sumSL'];
            $sickLeavesTaken = min($leaveDuration, $sickLeaveBalance);
            $leaveDuration -= $sickLeavesTaken;
            $leaveCountInsert->sl=$sickLeavesTaken;
            // Check Earned Leave balance
            $earnedLeaveBalance = $user->el - $takenLeave['sumEL'];
            $earnedLeavesTaken = min($leaveDuration, $earnedLeaveBalance);
            $leaveDuration -= $earnedLeavesTaken;
            $leaveCountInsert->el=$earnedLeavesTaken;
            if ($leaveDuration > 0) {
                $leaveWithoutPayLeavesTaken = $leaveDuration;
                $leaveCountInsert->lwp=$leaveWithoutPayLeavesTaken;
            } else {
                $leaveWithoutPayLeavesTaken = 0;
                $leaveCountInsert->lwp=$leaveWithoutPayLeavesTaken;
            }
        }
        // dd($leaveCountInsert);
        $leaveCount->save($leaveCountInsert);

            // $data = $data['id'];
            // $data->status = $status;
            // $data->approved_by = $userSession['id'];

            // if ($this->Leaves->save($data)) {
            //     $empEmail = $this->Users->get($data['created_by'])->email;
            //   //  $this->approveLeaveNotic($empEmail, $status, $managerName, $from_date, $to_date);
            //     echo "Yes";
            // } else {
            //     echo "No";
            // }

    }


    public function approvedleaves()
    {
       

        $this->autoRender = false;

        if ($this->request->is('ajax')) {
             
            // dd($this->request->getData());
            $session = new \Cake\Http\Session();
            $userSession = $session->read('data');
            $managerName = $userSession['name'];
            $id = $this->request->getData("id");
            $status = $this->request->getData("status");
            $leave = $this->Leaves->get($id);
            // $from_date  = date_format($leave->from_date, "Y-m-d");
            // $to_date = date_format($leave->to_date, "Y-m-d");

            $from_date = $leave->from_date->format('Y-m-d');
            $to_date = $leave->to_date->format('Y-m-d');

            
            $leaveCount = $this->getTableLocator()->get('LeaveCount');
            if ($status == "Rejected") {
                // $leaveCount->query()->delete()
                // ->where(['leave_id' => $id])
                // ->execute();
                $leaveCount->deleteQuery()
                    ->where(['leave_id' => $id])
                    ->execute();
            }
            $data = $this->Leaves->get($id);
            $data->status = $status;
            $data->approved_by = $userSession['id'];

            if ($this->Leaves->save($data)) {
                $empEmail = $this->Users->get($data->created_by)->email;
             //  $this->approveLeaveNotic($empEmail, $status, $managerName, $from_date, $to_date);
                echo "Yes";
            } else {
                echo "No";
            }
        }
    }


    public function changestatus()
    {

        $this->autoRender = false;
        if ($this->request->is('ajax')) {

            $id = $this->request->getData("id");
            $leave = $this->Leaves->get($id);
            // dd($leave->id);

            $leaveCount = $this->getTableLocator()->get('LeaveCount');

            // print_r($leave);
            // die;

            if ($leave->status == "Approved") {

                $totalLeave = $this->getDateDiff((string)$leave->from_date, (string)$leave->to_date);

                $user = $this->Users->get($leave->created_by);


                if ($leave->leave_type == "Paid Leave") {
                    $leaveCount->deleteQuery()
                        ->where(['leave_id' => $leave->id])
                        ->execute();
                } else if ($leave->leave_type == "Casual Leave") {

                    $leaveCount->deleteQuery()
                        ->where(['leave_id' => $leave->id])
                        ->execute();
                    // $cl = $user->cl;
                    // $cl = $cl + $totalLeave;
                    // $user->cl = $cl;
                } else if ($leave->leave_type == "Sick Leave") {

                    $leaveCount->deleteQuery()
                        ->where(['leave_id' => $leave->id])
                        ->execute();
                } else if ($leave->leave_type == "Half Day") {
                    // echo $totalLeave;
                    // die;
                    if ($totalLeave == 1) {
                        // $detectLeave = $this->halfLeaveCancel($id);
                        // print_r($detectLeave);
                        // die;
                        $leaveCount->deleteQuery()
                                    ->where(['leave_id' => $leave->id])
                                    ->execute();
                    } else {
                        // $detectLeave = $this->halfLeaveCancel($id);
                        // print_r($detectLeave);
                        // die;
                        $leaveCount->deleteQuery()
                                    ->where(['leave_id' => $leave->id])
                                    ->execute();
                        // foreach ($detectLeave as $value) {
                        //     if ($value->sl) {
                        //         $leaveCount->query()->delete()
                        //             ->where(['leave_id' => $leave->id])
                        //             ->execute();
                        //     } else if ($value->cl) {
                        //         $leaveCount->query()->delete()
                        //             ->where(['leave_id' => $leave->id])
                        //             ->execute();
                        //     } else if ($value->el) {
                        //         $leaveCount->query()->delete()
                        //             ->where(['leave_id' => $leave->id])
                        //             ->execute();
                        //     } else if ($value->lwp) {
                        //         $leaveCount->query()->delete()
                        //             ->where(['leave_id' => $leave->id])
                        //             ->execute();
                        //     }
                        // }
                    }
                } else if ($leave->leave_type == "comp_off") {
                    $leaveCount->deleteQuery()
                        ->where(['leave_id' => $leave->id])
                        ->execute();
                } else if ($leave->leave_type == "LWP") {
                    $leaveCount->deleteQuery()
                        ->where(['leave_id' => $leave->id])
                        ->execute();
                    $user->lwp = $user->lwp - $totalLeave;
                }
                $this->Users->save($user);
            } else {
                $leaveCount->deleteQuery()
                        ->where(['leave_id' => $leave->id])
                        ->execute();
            }

            $leave->status = "cancelled";

            if ($this->Leaves->save($leave)) {
                echo "Yes";
            } else {
                echo "No";
            }
        }
    }


    public function view($id = null)
    {
        $leave = $this->Leaves->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('leave'));
    }


    public function checkvalidemployee($id)
    {

        $user = $this->Users->get($id);
        // print_r($user);
        // die;
        $current_time = date('Y-m-d');
        $user_date = $user->doj->modify('+6 month')->format("Y-m-d");
        if ($current_time > $user_date) {
            return true;
        } else {
            return false;
        }
    }


    // public function sicktypeleave($id){
    //     $user = $this->Users->get($id);
    //     if($user->sl){



    //     }

    // }

    public function getDateDiff($date1, $date2)
    {

        $date1 = date_create($date1);
        $date2 = date_create($date2);
        $diff = date_diff($date1, $date2);
        $days_count = (int)$diff->format("%R%a");
        return $days_count + 1;
    }


    public function add()
    {
        $conn = ConnectionManager::get('default');
        $session = new \Cake\Http\Session();
        $userSession = $session->read('data');
       
        $leave = $this->Leaves->newEmptyEntity();
        if ($this->request->is('post')) {
           // dd($this->request->getSession()->read());
            $run = false;

            $data=$this->request->getData();
            // dd($data);
            $id = $this->request->getSession()->read("user_data")['id'];
            $user = $this->Users->findById($id)->firstOrFail();
            $takenLeave = $this->sumOfApprovedLeave($id);
            // dd($takenLeave);

            $from_date = date("Y-m-d", strtotime($this->request->getData("from_date")));
            $to_date = date("Y-m-d", strtotime($this->request->getData("to_date")));

            $from_date_create = date_create((string)$from_date);
            $from_month_date = date_format($from_date_create, "m");
            $from_year_date = date_format($from_date_create, "Y");
            // dd($from_date);

            $days_count = $this->getDateDiff($from_date, $to_date);
            // $totalPendingLeave =  $days_count + $this->pendingLeave($id, $this->request->getData("leave_type"));
             $totalPendingLeave =  $days_count;
            $wfh=$this->Leaves->find()->select([
                'totalwfh'=>'SUM(Leaves.wfh)'
            ])->where([
                'Leaves.created_by'=>$id,
                'month(from_date)'=>$from_month_date,
                'YEAR(from_date)'=>$from_year_date,
                'Leaves.wfh_type'=>'WFH'
            ])
            ->first();

            $pre_leave = $this->Leaves->find()
            ->where([
                'Leaves.created_by' => $id,
                'OR' => [
                    ['Leaves.status' => 'Pending'],
                    ['Leaves.status' => 'Approved']
                ],
                'NOT' => ['Leaves.leave_type IN' => ['WFH','Short Leave', 'Forgot Card']]
            ])
            ->order(['id' => 'DESC'])
            ->limit(1)
            ->first();
            $query = "SELECT `start` FROM `holidays` WHERE `deleted` = 0 ORDER BY start ASC";
            $stmtList = $conn->execute($query);
            $holidaylist = $stmtList->fetchAll('assoc');
            // dd($holidaylist);

            $holidayDates = array_map(function($holiday) {
                return date('Y-m-d', strtotime($holiday['start']));
            }, $holidaylist);
            if (!empty($pre_leave)) {
                $last_leave_date = $pre_leave->to_date->format('Y-m-d');
                $previousDay = date('Y-m-d', strtotime('-1 day', strtotime($last_leave_date)));
                $nextDay = date('Y-m-d', strtotime('+1 day', strtotime($last_leave_date)));
                $last_leave_day = date('l', strtotime($last_leave_date));
                // check monday pre leave
                $last_leave_mdate = $pre_leave->from_date->format('Y-m-d');
                $last_leave_mday = date('l', strtotime($last_leave_mdate));
                // end
                $current_from_day = date('l', strtotime($from_date));
                $date_diff = $this->getDateDiff($last_leave_date, $from_date);
                $check_leave_type = $pre_leave->leave_type;
                $halfday_type = $pre_leave->halfday_type;
                // $check_leave_type = $pre_leave->leave_type;
            } else {
                $last_leave_day='';
                $last_leave_mday='';
                $current_from_day='';
                $date_diff='';
                $check_leave_type = '';
                $halfday_type = '';
            }


            $doj = new FrozenTime($user['doj']);
            $sixMonthsAfterDoj = $doj->addMonths(6);
            $currentDate = FrozenTime::now();
            if($currentDate < $sixMonthsAfterDoj) {
                $sixmonthcomplete = false;
            } else {
                $sixmonthcomplete = true;
            }

            // dd($check_leave_type);
            // dd($wfh->totalwfh);
            $totalwfh=$wfh->totalwfh;
            $lreason = $this->request->getData("reason");
            $sreason = $this->request->getData("sreason");
            if(!empty($lreason)) {
                $reason = $this->request->getData("reason");
            } else {
                $reason = $this->request->getData("sreason"); 
            }
            if(($this->request->getData("leave_type") == "WFH")) {
                $wfhtype = $this->request->getData("wfhtype");
            } else {
                $wfhtype = '';
            }
            if(($this->request->getData("leave_type") == "Half Day")) {
                $halfdaytype = $this->request->getData("halfdaytype");
            } else {
                $halfdaytype = '';
            }
            // dd($reason);

                    // finacial year restricted
                    $curr_month  = date('m');
                    $curr_year   = date('Y');
                    $start_year  = date('Y', strtotime($from_date));
                    $end_year    = date('Y', strtotime($to_date));
                   
                    // Define financial year start and end
                    $fy_start = ($curr_month >= 4) ? $curr_year : $curr_year - 1;  // Financial year starts in April
                    $fy_end = $fy_start + 1;  // Ends in March next year
                   
                    $start_fy = strtotime("$fy_start-04-01");  // April 1st of current FY
                    $end_fy = strtotime("$fy_end-03-31");      // March 31st of current FY
                    $start_leave = strtotime($from_date);
                    $end_leave = strtotime($to_date);
           
            if ($days_count > 0) {
               if($last_leave_day=='Friday' && $current_from_day=='Monday' && $date_diff==4) {
                    $cut_weekend_leave = 2;
                    $data['weekend']='true';
                  }
                  elseif($last_leave_mday=='Monday' && $current_from_day=='Friday' && $date_diff==4) {
                      $cut_weekend_leave = 2;
                      $data['weekend']='true';
                  }
                  elseif(in_array($previousDay, $holidayDates) && $date_diff==3){
                    $cut_weekend_leave = 1;
                    $data['weekend']='true';
                  }
                  elseif(in_array($nextDay, $holidayDates) && $date_diff==3){
                    $cut_weekend_leave = 1;
                    $data['weekend']='true';
                  }
                  elseif($last_leave_date=='2024-10-30' && $from_date=='2024-11-04'){
                    $cut_weekend_leave = 2;
                    $data['weekend']='true';
                  }
                  elseif(($last_leave_date=='2024-11-04' && $from_date=='2024-10-30') || $last_leave_mdate=='2024-11-04' && $from_date=='2024-10-30'){
                    $cut_weekend_leave = 2; 
                    $data['weekend']='true';
                  }
                  else {
                      $cut_weekend_leave = 0;  
                      $data['weekend']='false';
                  }
                //   dd($cut_weekend_leave);
                  if ($this->request->getData("leave_type") == "Casual Leave") {
                
                    // Restrict CL if leave dates fall in the next financial year
                    if ($start_leave > $end_fy || $end_leave > $end_fy) {
                        $this->Flash->error(__("Casual leave cannot be applied for the new financial year as it is only valid until the current financial year."));
                    } else {
                        if ($user['cl'] - $takenLeave['sumCL'] >= ($days_count + $cut_weekend_leave) && $totalPendingLeave <= $user['cl'] - $takenLeave['sumCL']) {
                            $run = true;
                        } else {
                            $this->Flash->error(__("You don't have enough leaves"));
                        }
                    }
                } else if ($this->request->getData("leave_type") == "Paid Leave") {

                    if ($currentDate < $sixMonthsAfterDoj) {
                        $this->Flash->error(__("You can't apply paid leave."));
                    } else {
                        if ($user['el'] - $takenLeave['sumEL']  >= ($days_count+$cut_weekend_leave) && $totalPendingLeave <= $user['el'] - $takenLeave['sumEL']) {
                            $run = true;
                        } else {
                            $this->Flash->error(__("You Don't have enough leaves"));
                        }
                    }
                } else if ($this->request->getData("leave_type") == "Sick Leave") {

                    if ($user['sl'] - $takenLeave['sumSL']  >= ($days_count+$cut_weekend_leave) && $totalPendingLeave <= $user['sl'] - $takenLeave['sumSL']) {
                        $run = true;
                    } else {
                        $this->Flash->error(__("You Don't have enough leaves"));
                    }
                } else if ($this->request->getData("leave_type") == "Half Day") {
                        // dd($days_count);
                    if ($days_count == 1) {
                        $run = true;
                    } else {
                        $this->Flash->error(__("You Don't have take two half days at a time."));
                    }
                } else if ($this->request->getData("leave_type") == "WFH") {
                   
                    $run = true;
                } 
                else if ($this->request->getData("leave_type") == "comp_off") {

                    if ($user['comp_off'] - $takenLeave['sumComp']  >= $days_count && $totalPendingLeave <= $user['comp_off'] - $takenLeave['sumComp']) {
                        $run  = true;
                    } else {
                        $this->Flash->error(__("You Don't have enough comp_off"));
                    }
                } else if ($this->request->getData("leave_type") == "LWP" || $this->request->getData("leave_type") == "Forgot Card" || $this->request->getData("leave_type") == "Short Leave") {
                    $run = true; 
                }
            } else {
                $this->Flash->error(__("You can't apply previous day leave."));
            }

            if ($run) {
                $resources = $this->request->getData('resources');
                 
                    $leave = $this->Leaves->patchEntity($leave, $this->request->getData());
                    $leave->reason = $reason;
                    $leave->from_date = $from_date;
                    $leave->to_date = $to_date;
                    // $leave->wfh =$totalwfh;
                    $leave->wfh_flag =$wfhtype;
                    $leave->halfday_type =$halfdaytype;
                    $leave->resources = json_encode($resources);
               

                if ($this->Leaves->save($leave)) {

                    $applyBy = $this->request->getSession()->read("user_data")['name'];
                    $reportingManagerId = $this->request->getSession()->read("user_data")['reporting_manager'];
                    $reportingManagerEmial = $this->Users->get($reportingManagerId)->email;

                    $email = $this->Users
                        ->find()
                        ->select(['email', 'id'])
                        ->where([
                            'id in ' => $resources,
                            // 'OR' => [
                            //     'id !=' => $reportingManagerId
                            // ]
                        ])
                        ->toArray();

                    $subject = $this->request->getData('subject');
                   
                    $ids=$this->Leaves->find()->select(['id'])->order(['id'=>'desc'])->limit(1)->toarray();
                    // dd($ids[0]['id']);
                    $lastid=$ids[0]['id'];
                    $data['id'] =$lastid;
                    // dd($data);
                    $leave = $data['id'];
                    $leaveById = $this->Leaves->get($leave);
                    $from_date = date_create((string)$data['from_date']);
                    $to_date = date_create((string)$data['to_date']);

                    $from_date  = date_format($from_date, "Y-m-d");
                    $to_date = date_format($to_date, "Y-m-d");

                    $fromDate = date_create((string)$data['from_date']);
                    $toDate = date_create((string)$data['to_date']);
                    $year = date_format($fromDate, "Y");
                    $fromDateCheck = date_format($fromDate, 'm');
                    $toDateCheck = date_format($toDate, 'm');
                    $user = $this->Users->get($data['created_by']);
                    $leaveType =  $data['leave_type'];
                    // $from_month_date = date_format($leaveById->from_date, "m");
                    // $from_year_date = date_format($leaveById->from_date, "Y");
                    $from_month_date = $leaveById->from_date->format('m');
                    $from_year_date = $leaveById->from_date->format('Y');
                    $takenLeave = $this->sumOfApprovedLeave($data['created_by']);

                    $leaveCount = $this->getTableLocator()->get('LeaveCount');
                    $leaveCountInsert = $leaveCount->newEmptyEntity();
                    $leaveCountInsert->user_id  = $data['created_by'];
                    $leaveCountInsert->leave_id = $data['id'];
                    $leaveCountInsert->leave_date =date("Y-m-d", strtotime($data['from_date']));
                    $date_got = $this->getDateDiff((string)$data['from_date'], (string)$data['to_date']);
                    if($data['weekend']=='true') {
                        $extra_weekend_leave = $cut_weekend_leave;
                    } else {
                        $extra_weekend_leave = $cut_weekend_leave;
                    }
                    
                    if ($leaveType == "Paid Leave") {
                        $leaveCountInsert->el = $date_got+$extra_weekend_leave;
                    } else if ($leaveType == "Casual Leave") {
                        // dd($leaveCountInsert->cl);
                        $leaveCountInsert->cl = $date_got+$extra_weekend_leave;
                    } else if ($leaveType == "Sick Leave") {
                        $leaveCountInsert->sl = $date_got+$extra_weekend_leave;
                    } else if ($leaveType == "comp_off") {
                        $leaveCountInsert->comp_off = $date_got;
                    } else if ($leaveType == "LWP") {
                        $leaveCountInsert->lwp = $date_got+$extra_weekend_leave;
                    } else if ($leaveType == "Half Day") {
                        $allTypeLeave = [];
                        if ($user->cl - $takenLeave['sumCL'] >= 0.5+$extra_weekend_leave) {
                            $allTypeLeave[] = ['cl' => 0.5];
                            $leaveCountInsert->cl = ($date_got / 2)+$extra_weekend_leave;
                        } else if ($user->sl - $takenLeave['sumSL'] >= 0.5+$extra_weekend_leave) {
                            $allTypeLeave[] = ['sl' => 0.5];
                            $leaveCountInsert->sl = ($date_got / 2)+$extra_weekend_leave;
                        } else if ($user->el - $takenLeave['sumEL'] >= 0.5+$extra_weekend_leave && $sixmonthcomplete == true) {
                            $allTypeLeave[] = ['el' => 0.5];
                            $leaveCountInsert->el = ($date_got / 2)+$extra_weekend_leave;
                        } else if ($user->comp_off - $takenLeave['sumComp'] >= 0.5+$extra_weekend_leave) {
                            $allTypeLeave[] = ['comp_off' => 0.5];
                            $leaveCountInsert->el = ($date_got / 2)+$extra_weekend_leave;
                        } else {
                            $allTypeLeave[] = ['lwp' => 0.5+$extra_weekend_leave];
                            $leaveCountInsert->lwp = ($date_got / 2)+$extra_weekend_leave;
                        }
                        // // dd($allTypeLeave);
                        // $cutLeave = json_encode($allTypeLeave);
                        // // dd($leave->leave_details = $cutLeave);
                        // $this->Leaves->save($leave);
                    }
                    else if($leaveType == "WFH") {
                        // $leaveDuration = ($date_got / 2)+$extra_weekend_leave; // Convert to 0.5 increments
                        // dd($leaveDuration);
                        $leaveDuration = ($date_got / 2);
    
                        // Initialize leave count
                        $leaveCountInsert->cl = 0;
                        $leaveCountInsert->sl = 0;
                        $leaveCountInsert->el = 0;
                        $leaveCountInsert->lwp = 0;
                       
                        while ($leaveDuration > 0) {
                            if ($start_leave > $end_fy || $end_leave > $end_fy) {
                                if ($leaveDuration >= 0.5) {
                                    if ($user->sl - $takenLeave['sumSL'] >= 0.5) {
                                        $leaveCountInsert->sl += 0.5;
                                        $takenLeave['sumSL'] += 0.5;
                                        $leaveDuration -= 0.5;
                                    } 
                                    // Deduct from Earned Leave
                                    elseif ($user->el - $takenLeave['sumEL'] >= 0.5 && $sixmonthcomplete == true) {
                                        $leaveCountInsert->el += 0.5;
                                        $takenLeave['sumEL'] += 0.5;
                                        $leaveDuration -= 0.5;
                                    } 
                                    // Deduct from Leave Without Pay
                                    else {
                                        $leaveCountInsert->lwp += $leaveDuration; // Remaining duration
                                        $leaveDuration = 0; // End loop
                                    }
                                } else {
                                    if ($leaveDuration > 0 && $user->sl - $takenLeave['sumSL'] >= $leaveDuration) {
                                        $leaveCountInsert->sl += $leaveDuration;
                                        $takenLeave['sumSL'] += $leaveDuration;
                                        $leaveDuration = 0;
                                    } elseif ($leaveDuration > 0 && $user->el - $takenLeave['sumEL'] >= $leaveDuration && $sixmonthcomplete == true) {
                                        $leaveCountInsert->el += $leaveDuration;
                                        $takenLeave['sumEL'] += $leaveDuration;
                                        $leaveDuration = 0;
                                    } else {
                                        $leaveCountInsert->lwp += $leaveDuration; // Remaining duration
                                        $leaveDuration = 0; // End loop
                                    }
                                }
                            } else {
                                if ($leaveDuration >= 0.5) {
                                    // Deduct from Casual Leave
                                    if ($user->cl - $takenLeave['sumCL'] >= 0.5) {
                                        $leaveCountInsert->cl += 0.5;
                                        $takenLeave['sumCL'] += 0.5;
                                        $leaveDuration -= 0.5;
                                    } 
                                    // Deduct from Sick Leave
                                    elseif ($user->sl - $takenLeave['sumSL'] >= 0.5) {
                                        $leaveCountInsert->sl += 0.5;
                                        $takenLeave['sumSL'] += 0.5;
                                        $leaveDuration -= 0.5;
                                    } 
                                    // Deduct from Earned Leave
                                    elseif ($user->el - $takenLeave['sumEL'] >= 0.5 && $sixmonthcomplete == true) {
                                        $leaveCountInsert->el += 0.5;
                                        $takenLeave['sumEL'] += 0.5;
                                        $leaveDuration -= 0.5;
                                    } 
                                    // Deduct from Leave Without Pay
                                    else {
                                        $leaveCountInsert->lwp += $leaveDuration; // Remaining duration
                                        $leaveDuration = 0; // End loop
                                    }
                                } else {
                                    // Handle remaining leave less than 0.5
                                    if ($leaveDuration > 0 && $user->cl - $takenLeave['sumCL'] >= $leaveDuration) {
                                        $leaveCountInsert->cl += $leaveDuration;
                                        $takenLeave['sumCL'] += $leaveDuration;
                                        $leaveDuration = 0;
                                    } elseif ($leaveDuration > 0 && $user->sl - $takenLeave['sumSL'] >= $leaveDuration) {
                                        $leaveCountInsert->sl += $leaveDuration;
                                        $takenLeave['sumSL'] += $leaveDuration;
                                        $leaveDuration = 0;
                                    } elseif ($leaveDuration > 0 && $user->el - $takenLeave['sumEL'] >= $leaveDuration && $sixmonthcomplete == true) {
                                        $leaveCountInsert->el += $leaveDuration;
                                        $takenLeave['sumEL'] += $leaveDuration;
                                        $leaveDuration = 0;
                                    } else {
                                        $leaveCountInsert->lwp += $leaveDuration; // Remaining duration
                                        $leaveDuration = 0; // End loop
                                    }
                                }
                            }
                        }
                    }
                    // dd($leaveCountInsert);
                   $insert_l = $leaveCount->save($leaveCountInsert);

                   $cut_leave=[];
                   if($insert_l) {
                    $cut_leave = ['CL'=>$insert_l->cl,'SL'=>$insert_l->sl,'EL'=>$insert_l->el,'LWP'=>$insert_l->lwp];
                   }
                //    dd($cut_leave);

                if ($this->request->getData("leave_type") == "WFH") {
                    $leaveType = "Work From Home ";
                  $this->sendApplyLeaveNotic($reportingManagerEmial, $leaveType, $subject, $applyBy, $from_date, $to_date, $email,$reason,$cut_leave);
                } else {
                    $leaveType = $this->request->getData('leave_type');
                   $this->sendApplyLeaveNotic($reportingManagerEmial, $leaveType, $subject, $applyBy, $from_date, $to_date, $email,$reason,$cut_leave);
                }

                    // $this->leavecheck($data);

                    $this->Flash->success(__('The leave has been saved.'));
                    return $this->redirect(['action' => 'index']);
                }
              
            }
            
            
        }
        $this->redirect(
            ['controller' => 'Leaves', 'action' => 'index']
        );
    }

    // Total pending leave

    public function pendingLeave($id, $leaveType)
    {
        $totalPendingLeave = 0;
        $leaveData = $this->Leaves->findByCreated_by($id)
            ->where(['status' => 'Pending', 'leave_type' => "$leaveType"])
            ->toArray();
        foreach ($leaveData as $value) {
            $totalPendingLeave += $this->getDateDiff(date_format($value->from_date, 'Y-m-d'), date_format($value->to_date, 'Y-m-d'));
        }
        return $totalPendingLeave;
    }

    // Edit Leave method

    public function edit($id = null)
    {
        // $this->viewBuilder()->setLayout('ajax');
        $this->autoRender = false;
        $run = false;

        if ($this->request->is(['patch', 'post', 'put'])) {
            $leave_id = $this->request->getData('id');
            $leave = $this->Leaves->get($leave_id, [
                'contain' => ['Users'],
            ]);
            $appliedLeave = $this->getDateDiff(date_format($leave->from_date, "Y-m-d"), date_format($leave->to_date, "Y-m-d"));
            // echo "<pre>";
            // print_r($leave);
            // die;

            $user_id = $this->request->getSession()->read("user_data")['id'];
            $user =  $this->Users->get($user_id);
            $from_date = date("Y-m-d", strtotime($this->request->getData("from_date")));
            $to_date = date("Y-m-d", strtotime($this->request->getData("to_date")));
            $days_count = $this->getDateDiff($from_date, $to_date);
            $takenLeave = $this->sumOfApprovedLeave($user_id);
            $totalPendingLeave =  $this->pendingLeave($user_id, $this->request->getData("leave_type"));
            if ($this->request->getData("leave_type") != $leave->leave_type) {
                $this->Flash->error(__("You cann't change leave type.If you want to change leave type cancelled it and apply leave again."));
            } else if ($days_count > 0) {

                if ($this->request->getData("leave_type") == "Casual Leave") {
                    if ($appliedLeave >= $days_count) {
                        $run = true;
                    } else if ($user['cl'] - $takenLeave[0]->sumCl >= $days_count) {
                        $totalPendingLeave -= $appliedLeave;
                        $totalLeave = ($user['cl'] - $takenLeave[0]->sumCl) - $totalPendingLeave;
                        if ($totalLeave >= $days_count) {
                            $run = true;
                        } else {
                            $this->Flash->error(__("You Don't have enough leaves"));
                        }
                    } else {
                        $this->Flash->error(__("You Don't have enough leaves"));
                    }
                } else if ($this->request->getData("leave_type") == "Paid Leave") {
                    if ($appliedLeave >= $days_count) {
                        $run = true;
                    } else if ($user['el'] - $takenLeave[0]->sumEl >= $days_count) {
                        $totalPendingLeave -= $appliedLeave;
                        $totalLeave = ($user['el'] - $takenLeave[0]->sumEl) - $totalPendingLeave;
                        if ($totalLeave >= $days_count) {
                            $run = true;
                        } else {
                            $this->Flash->error(__("You Don't have enough leaves"));
                        }
                    } else {
                        $this->Flash->error(__("You Don't have enough leaves"));
                    }
                } else if ($this->request->getData("leave_type") == "Sick Leave") {
                    if ($appliedLeave >= $days_count) {
                        $run = true;
                    } else if ($user['sl'] - $takenLeave[0]->sumSl >= $days_count) {
                        $totalPendingLeave -= $appliedLeave;
                        $totalLeave = ($user['sl'] - $takenLeave[0]->sumSl) - $totalPendingLeave;
                        if ($totalLeave >= $days_count) {
                            $run = true;
                        } else {
                            $this->Flash->error(__("You Don't have enough leaves"));
                        }
                    } else {
                        $this->Flash->error(__("You Don't have enough leaves"));
                    }
                } else if ($this->request->getData("leave_type") == "Half Day") {

                    if ($days_count == 1) {
                        if ($user['cl'] - $takenLeave[0]->sumCl > 0) {

                            $run = true;
                        } elseif ($user['sl'] - $takenLeave[0]->sumSl > 0) {

                            $run = true;
                        } elseif ($user['el'] - $takenLeave[0]->sumEl > 0) {

                            $run = true;
                        } else {
                            $this->Flash->error(__("You Don't have enough leaves"));
                        }
                    } else {
                        $this->Flash->error(__("You Don't have take two half days at a time."));
                    }
                } else if ($this->request->getData("leave_type") == "WFH") {
                    $run = true;
                } else if ($this->request->getData("leave_type") == "comp_off") {
                    if ($appliedLeave >= $days_count) {
                        $run = true;
                    } else if ($user['comp_off'] - $takenLeave[0]->sumCompOff >= $days_count) {
                        $totalPendingLeave -= $appliedLeave;
                        $totalLeave = ($user['comp_off'] - $takenLeave[0]->sumCompOff) - $totalPendingLeave;
                        if ($totalLeave >= $days_count) {
                            $run = true;
                        } else {
                            $this->Flash->error(__("You Don't have enough leaves"));
                        }
                    } else {
                        $this->Flash->error(__("You Don't have enough leaves"));
                    }
                } else if ($this->request->getData("leave_type") == "LWP" || $this->request->getData("leave_type") == "Forgot Card") {
                    $run = true;
                }
            } else {
                $this->Flash->error(__("You can't apply previous day leave."));
            }
            $leave->subject = $this->request->getData('subject');
            $leave->leave_type = $this->request->getData('leave_type');
            $leave->from_date = $from_date;
            $leave->to_date = $to_date;
            $leave->message = $this->request->getData('message');
            $leave->reason = $this->request->getData('reason');
            $leave->resources = json_encode($this->request->getData('resources'));
            if ($run) {
                if ($this->Leaves->save($leave)) {
                    $this->Flash->success(__('The leave has been updated.'));
                    return $this->redirect(['controller' => 'Leaves', 'action' => 'index']);
                }
            } else {
                return $this->redirect(['controller' => 'Leaves', 'action' => 'index']);
            }
            // $this->Flash->error(__('The leave could not be saved. Please, try again.'));
        } else {
            $leave = $this->Leaves->get($id, [
                'contain' => ['Users'],
            ]);

            $user_id = $this->request->getSession()->read("user_data")['id'];
            $user =  $this->Users->get($user_id);
            // print_r($leave);
            echo json_encode($leave);
            die;
        }
        // $this->set(compact('leave'));

    }


    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $leave = $this->Leaves->get($id);
        if ($this->Leaves->delete($leave)) {
            $this->Flash->success(__('The leave has been deleted.'));
        } else {
            $this->Flash->error(__('The leave could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    // All Leaves show only HR

    // public function allLeaves()
    // {

    //     $selectStatus = '';
    //     if ($this->request->getQuery('status') == 'Approved') {
    //         $leave = $this->Leaves
    //             ->find('all', ['contain' => ['Users', 'CreatedBy']])
    //             ->where(['Leaves.status' => 'Approved'])
    //             ->toArray();
    //         $selectStatus = 'Approved';
    //     } else if ($this->request->getQuery('status') == 'Cancelled') {

    //         $leave = $this->Leaves
    //             ->find('all', ['contain' => ['Users', 'CreatedBy']])
    //             ->where(['Leaves.status' => 'Cancelled'])
    //             ->toArray();
    //         $selectStatus = "Cancelled";
    //     } else if ($this->request->getQuery('status') == 'Rejected') {

    //         $leave = $this->Leaves
    //             ->find('all', ['contain' => ['Users', 'CreatedBy']])
    //             ->where(['Leaves.status' => 'Rejected'])
    //             ->toArray();
    //         $selectStatus = "Rejected";
    //     }
    //     else if ($this->request->getQuery('status') == 'Pending') {

    //         $leave = $this->Leaves
    //             ->find('all', ['contain' => ['Users', 'CreatedBy']])
    //             ->where(['Leaves.status' => 'Pending'])
    //             ->toArray();
    //             // echo "<pre>"; print_r($leave); exit();
    //         $selectStatus = "Pending";
    //     }
    //     else {

    //         $leave = $this->Leaves
    //             ->find('all', ['contain' => ['Users', 'CreatedBy']])
    //             ->orderAsc('Leaves.status')
    //             ->toArray();
    //         $selectStatus = "";
    //     }
    //     // echo "<pre>";
    //     // print_r($leave);
    //     // die;
    //     $this->set(compact('leave', 'selectStatus'));
    // }

    // public function allLeaves()
    // {
    //     $selectStatus = '';

    //     $query = $this->Leaves->find()
    //         ->contain([
    //             'Users' => function ($q) {
    //                 return $q->select(['id', 'name', 'email']);
    //             },
    //             'CreatedBy' => function ($q) {
    //                 return $q->select(['id', 'name', 'email']);
    //             }
    //         ])
    //         ->limit(100);

    //     if ($this->request->getQuery('status') == 'Approved') {

    //         $query->where(['Leaves.status' => 'Approved']);
    //         $selectStatus = 'Approved';

    //     } elseif ($this->request->getQuery('status') == 'Cancelled') {

    //         $query->where(['Leaves.status' => 'Cancelled']);
    //         $selectStatus = 'Cancelled';

    //     } elseif ($this->request->getQuery('status') == 'Rejected') {

    //         $query->where(['Leaves.status' => 'Rejected']);
    //         $selectStatus = 'Rejected';

    //     } elseif ($this->request->getQuery('status') == 'Pending') {

    //         $query->where(['Leaves.status' => 'Pending']);
    //         $selectStatus = 'Pending';

    //     } else {

    //         $query->orderAsc('Leaves.status');
    //     }

    //     $leave = $query->toArray();

    //     $this->set(compact('leave', 'selectStatus'));
    // }

     public function allLeaves()
    {
        $selectStatus = '';

        // $query = $this->Leaves->find()
        //     ->contain([
        //         'Users' => function ($q) {
        //             return $q->select(['id', 'name', 'email']);
        //         },
        //         'CreatedBy' => function ($q) {
        //             return $q->select(['id', 'name', 'email']);
        //         }
        //     ])
        //     ->limit(100);
        // $query = $this->Leaves->find()
        //     ->contain([
        //         'Users' => function ($q) {
        //             return $q->select(['id', 'name', 'email']);
        //         },
        //         'CreatedBy' => function ($q) {
        //             return $q->select(['id', 'name', 'email']);
        //         }
        //     ]);

        $query = $this->Leaves->find()
            ->innerJoinWith('CreatedBy', function ($q) {
                return $q->where(['CreatedBy.status' => 1]);
            })
            ->contain([
                'Users' => function ($q) {
                    return $q->select(['id', 'name', 'email']);
                },
                'CreatedBy' => function ($q) {
                    return $q->select(['id', 'name', 'email']);
                }
            ]);

        $status = $this->request->getQuery('status');

        if (!empty($status)) {
            $query->where(['Leaves.status' => $status]);
            $selectStatus = $status;
        }

        $leave = $query->toArray();

        $this->set(compact('leave', 'selectStatus'));
    }
    // WFH

    public function workFromHome($id = null)
    {
        $this->autoRender = false;

        $session = new \Cake\Http\Session();

        $user_id = $session->read('data')['id'];

        if ($this->request->is('post')) {
            // print_r($this->request->getData());

            $wfh_days = $this->request->getData('wfh_day');
            $id = $this->request->getData('id');
            $applied_on = $this->request->getData('applied_on');
            $from_date = $this->request->getData('from_date');
            $to_date = $this->request->getData('to_date');
            $created_by = $this->request->getData('created_by');
            $subject = $this->request->getData('subject');
            $message = $this->request->getData('message');
            $leaves = $this->Leaves->get($id);
            $employeeLeave = $this->Users->get($created_by);

            if ($wfh_days == 1 || $wfh_days == 2) {
                $totalDay = ($this->getDateDiff((string)$from_date, (string)$to_date));

                if ($wfh_days == 1) {
                    $leaves->from_date = date("Y-m-d", strtotime($from_date));
                    $leaves->to_date = date("Y-m-d", strtotime($from_date));
                    $leaves->approved_by = $user_id;
                    $leaves->status = "Approved";
                    $leaves->leave_type = "WFH";
                    $this->Leaves->save($leaves);
                    $wfh_HalfDay = $totalDay -  1;
                } else {
                    $leaves->from_date = date("Y-m-d", strtotime($from_date));
                    $leaves->to_date = date("Y-m-d", strtotime($from_date . ' + 1 day'));
                    $leaves->approved_by = $user_id;
                    $leaves->status = "Approved";
                    $leaves->leave_type = "WFH";
                    $this->Leaves->save($leaves);
                    $wfh_HalfDay = $totalDay -  2;
                }
                if ($wfh_HalfDay > 0) {

                    if ($wfh_days == 1) {
                        $from_newDate = date("Y-m-d", strtotime($from_date . ' + 1 day'));
                    } else {
                        $from_newDate = date("Y-m-d", strtotime($from_date . ' + 2 days'));
                    }

                    $leaveTbl = $this->Leaves;
                    $leaveHalfDays = $leaveTbl->newEmptyEntity();
                    // print_r($leaveHalfDays);

                    $leaveHalfDays->leave_type = "Half Day";
                    $leaveHalfDays->applied_on = date("Y-m-d", strtotime($applied_on));
                    $leaveHalfDays->from_date = $from_newDate;
                    $leaveHalfDays->to_date = date("Y-m-d", strtotime($to_date));
                    $leaveHalfDays->created_by = $created_by;
                    $leaveHalfDays->approved_by = $user_id;
                    // $leaveHalfDays->status = "Approved";
                    $leaveHalfDays->subject = $subject;
                    $leaveHalfDays->message = $message;

                    $leaveTbl->save($leaveHalfDays);
                    $id_halfDays = $leaveHalfDays->id;
                    $total_halfDays = $wfh_HalfDay / 2;

                    $this->detectAllTypeLeave($employeeLeave, $total_halfDays, $id_halfDays, $wfh_days, $created_by, $from_newDate);
                }
            } else if ($wfh_days == 0.5) {

                $leaves->leave_type = "Half Day";
                $leaves->approved_by = $user_id;
                $totalDay = ($this->getDateDiff((string)$from_date, (string)$to_date)) / 2;
                // echo $totalDay;
                // die;
                $this->detectAllTypeLeave($employeeLeave, $totalDay, $id, $wfh_days, $created_by, $from_date);
            }
            $this->Users->save($employeeLeave);
            if ($this->Leaves->save($leaves)) {
                $status = $this->Leaves->get($id)->status;
                $empEmail = $employeeLeave->email;
                // echo $status;
                // echo "<br>";
                // echo $empEmail;
                // die;
                // $this->approveLeaveNotic($empEmail, $status);
            }
            return $this->redirect(['controller' => 'leaves', 'action' => 'requestleave']);
        } else {
            $wfhData = $this->Leaves->get($id);
            echo json_encode($wfhData);
            die;
        }
    }

    // elWFHLeave function work when el leave -ve and employee total leave is 0  

    // public function elWFHLeave($el, $id, $inputType = null, $userId = null)
    // {
    //     $left_days = -(int)($el / 0.5);

    //      $userData = $this->Users->get($userId);
    //      $total_leave = $userData->sl + $userData->el + $userData->cl;

    //     $leaveTbl = $this->Leaves->get($id);
    //     $to_date = date_format($leaveTbl->to_date, "Y-m-d");
    //     $new_to_date = date("Y-m-d", (strtotime($to_date . " - $left_days days")));
    //     $from_date = date_format($leaveTbl->from_date, "Y-m-d");

    //     if ($inputType == 0.5) {
    //         $leaveTbl->status = "Rejected";
    //     } else {
    //         if ($new_to_date < $from_date) {
    //             $leaveTbl->leave_type = "Not take";
    //             $leaveTbl->status = "Rejected";
    //         } else {
    //             if ($inputType == 1 || $inputType == 2) {

    //                 $leaveTbl->leave_type = "Half Day";
    //                 $leaveTbl->status = "Approved";
    //                 $leaveTbl->to_date = $new_to_date;
    //             } else {
    //                 $leaveTbl->status = "Approved";
    //                 $leaveTbl->to_date = $new_to_date;
    //             }
    //         }
    //     }
    //     return $this->Leaves->save($leaveTbl);
    // }

    // leave detect function

    public function detectAllTypeLeave($employeeLeave, $totalDay, $id, $inputType = null, $userId = null, $from_newDate = null)
    {
        // $totalDay = $totalDay / 2;
        // echo $totalDay;
        // die;
        $leaveTbl = $this->Leaves->get($id);
        $allTypeLeave = [];
        $leaveCount = $this->getTableLocator()->get('LeaveCount');

        $year = date('Y');
        $nextYear = $year + 1;
        $leaveCountQuery = $leaveCount->find();
        $myLeave = $leaveCountQuery
            ->select([
                'sumEl' => $leaveCountQuery->func()->sum('LeaveCount.el'),
                'sumSl' => $leaveCountQuery->func()->sum('LeaveCount.sl'),
                'sumCl' => $leaveCountQuery->func()->sum('LeaveCount.cl'),
                'sumCompOff' => $leaveCountQuery->func()->sum('LeaveCount.comp_off'),
                'sumLwp' => $leaveCountQuery->func()->sum('LeaveCount.lwp'),
                'u.el',
                'u.cl',
                'u.sl',
                'u.comp_off',
                'u.lwp'
            ])
            ->join([
                'table' => 'users',
                'alias' => 'u',
                'type' => 'right',
                'conditions' => 'u.id = LeaveCount.user_id'
            ])
            ->where([
                'LeaveCount.user_id = ' . $userId,
                'LeaveCount.leave_date BETWEEN :start AND :end'
            ])
            ->bind(':start', $year . '-04-01', 'date')
            ->bind(':end',   $nextYear . '-03-31', 'date')
            ->toArray();
        // echo "<pre>";
        // print_r($myLeave);
        // die;
        $leaveCountInsert = $leaveCount->newEmptyEntity();
        $leaveCountInsert->user_id  = $userId;
        $leaveCountInsert->leave_id = $id;
        $leaveCountInsert->leave_date = $from_newDate;

        if ($employeeLeave->sl >= 0) {
            $totalSl = $employeeLeave->sl - $myLeave[0]->sumSl;
            $sl = $totalSl - $totalDay;

            if ($sl < 0) {
                $allTypeLeave[] = ['sl' => $totalSl];

                $leaveCountInsert->sl = $totalSl;
                $totalCl = $employeeLeave->cl - $myLeave[0]->sumCl;
                $cl = $totalCl + $sl;

                if ($cl  < 0) {
                    // $cutCL = - ($sl - $cl);
                    $allTypeLeave[] = ['cl' => $totalCl];

                    $leaveCountInsert->cl = $totalCl;

                    $totalEl = (int)$employeeLeave->el - $myLeave[0]->sumEl;
                    $el = $totalEl + $cl;
                    if ($el < 0) {
                        // $cutEL = - ($cl - $el);
                        $from_date = date_format($leaveTbl->from_date, "Y-m-d");
                        $to_date = date_format($leaveTbl->to_date, "Y-m-d");

                        $days_count = $this->getDateDiff($from_date, $to_date);

                        $new_to_date = date("Y-m-d", (strtotime($leaveTbl->from_date . " + $totalEl days")));
                        $new_from_date = date("Y-m-d", (strtotime($new_to_date . " + $totalEl days")));
                        if ($inputType == 0.5) {

                            if ($totalEl  > 0) {
                                $allTypeLeave[] = ['el' => $totalEl];
                                $leaveCountInsert->el = $totalEl;
                                $allTypeLeave[] = ['el' => $totalEl];
                                $leaveTbl->from_date = $from_date;
                                $leaveTbl->to_date = $new_to_date;
                                $leaveTbl->leave_type = "Half Day";
                                $leaveTbl->status = "Approved";
                            } else {
                                $leaveTbl->from_date = $new_from_date;
                                $leaveTbl->to_date = $to_date;
                                $leaveTbl->status = "Rejected";
                            }
                        } elseif ($inputType == 1 || $inputType == 2) {
                            if ($totalEl  > 0) {
                                $allTypeLeave[] = ['el' => $totalEl];
                                $leaveCountInsert->el = $totalEl;
                                $leaveTbl->leave_type = "Half Day";
                                $leaveTbl->status = "Approved";
                                $leaveTbl->to_date = $new_to_date;
                            } else {
                                $leaveTbl->from_date = $new_from_date;
                                $leaveTbl->to_date = $to_date;
                                $leaveTbl->status = "Rejected";
                            }
                        }
                        // elWFHLeave function call
                        // $this->elWFHLeave($el, $id, $inputType, $userId);
                    } else {
                        $cutEL = -$cl;
                        $allTypeLeave[] = ['el' => $cutEL];
                        $leaveCountInsert->el = $cutEL;
                        // $employeeLeave->el = $el;
                        $leaveTbl->status = "Approved";
                    }
                } else {

                    $cutCL = -$sl;
                    $leaveCountInsert->cl = $cutCL;
                    $allTypeLeave[] = ['cl' => $cutCL];

                    // $employeeLeave->cl = $cl;
                    $leaveTbl->status = "Approved";
                }
            } else {
                // if ($sl == 0) {
                //     $cutSL = $totalDay;
                //     $allTypeLeave[] = ['sl' => $cutSL];
                //     $leaveCountInsert->sl = $cutSL;
                // } else {}
                $cutSL = $totalDay;
                $allTypeLeave[] = ['sl' => $cutSL];
                $leaveCountInsert->sl = $cutSL;

                $leaveTbl->status = "Approved";
            }
        }

        $cutLeave = json_encode($allTypeLeave);
        $leaveCount->save($leaveCountInsert);
        $leaveTbl->leave_details = $cutLeave;
        $this->Leaves->save($leaveTbl);

        // return $this->redirect(['controller' => 'leaves', 'action' => 'requestleave']);
    }


    // ---  New Working method WFH  ---
    public function wfhLeave($id = null)
    {
        // dd($this->request->getData());
        $this->autoRender = false;
        $session = new \Cake\Http\Session();
        $userSession = $session->read('data');
        $managerId = $session->read('data')['id'];
        if($this->request->getData('manager_comment') && !empty($this->request->getData('manager_comment'))) {
            $leaveById = $this->Leaves->get($this->request->getData('id'));  
        } else {
            $leaveById = $this->Leaves->get($id);
        }
        
        $userData = $this->Users->get($leaveById->created_by);

        if($this->request->getData('manager_comment') && !empty($this->request->getData('manager_comment'))) {
            $data = $this->Leaves->get($this->request->getData('id'));
                    $data->status = "Approved";
                    $data->approved_by = $userSession['id'];
                    $data->manager_comment = $this->request->getData('manager_comment');
                   if($this->Leaves->save($data)) {
                    $leaveCount = $this->getTableLocator()->get('LeaveCount');
                    $update_value = ['cl'=>0,'sl'=>0,'el'=>0,'lwp'=>0];
                    // $leaveCount->query()->update()
                    //             ->set($update_value)
                    //             ->where([
                    //                 'user_id' => $this->request->getData('emp_Id'),
                    //                 'leave_id' => $this->request->getData('id')
                    //             ])
                    //             ->execute();

                    $leaveCount->updateQuery()
                        ->set($update_value)
                        ->where([
                            'user_id' => $this->request->getData('emp_Id'),
                            'leave_id' => $this->request->getData('id')
                        ])
                        ->execute();
                   }
                   return $this->redirect(['controller' => 'leaves', 'action' => 'requestleave']);
        }
        else {
            $data = $this->Leaves->get($id);
            $data->status = "Approved";
            $data->leave_type = "Half Day";
            // $data->wfh_type = "Half Day";
            // $data->wfh = 0;
            $data->approved_by = $userSession['id'];
            $this->Leaves->save($data);
            echo 1;
        }
    }

    // half leave cancel

    public function halfLeaveCancel($leaveId)
    {
        $leave_details = $this->Leaves->get($leaveId)->leave_details;
        $detectLeave = json_decode($leave_details);
        return $detectLeave;
    }

    // Add new comp-off

    public function addCompOff()
    {
        $session = new \Cake\Http\Session();
        $userSession = $session->read('data');
        // echo "<pre>";
        // print_r($userSession);
        // die;

        // implement email functionality for comp off 

        $manager_id = $userSession['reporting_manager'];
        $managerData = $this->Users->get($manager_id);
        $managerEmail = $managerData['email'];

        $compOffTbl = $this->getTableLocator()->get('AddCompOff');

        if ($this->request->is('post')) {

            $empId = $this->request->getSession()->read("user_data")['id'];

            $compData = $compOffTbl->newEmptyEntity();
            $compData->employee_id = $empId;
            $compData->request_date = date(
                'Y-m-d',
                strtotime($this->request->getData('request_date'))
            );
            $compData->description = $this->request->getData('description');

            $compOffTbl->save($compData);

            if($managerEmail) {
                $empName = $userSession['name'];
                $reqDate = date( 'Y-m-d', strtotime($this->request->getData('request_date')));
                $description = $this->request->getData('description');
                $leaveType = 'Comp Off';
                $subject = $empName. ' - Comp Off Request';

                $this->sendCompOffNotification($managerEmail, $leaveType, $subject, $empName, $reqDate, $description);

            }

            return $this->redirect(['action' => 'addCompOff']);
        } else {

            // $compData = $compOffTbl->findByEmployeeId($userSession['id'])->toList();
            $compData = $compOffTbl->findByEmployeeId($userSession['id'])->toArray();
            $this->set(compact('userSession', 'compData'));
        }
    }

    // Edit comp-off

    public function editCompOff($id = null)
    {
        $this->autoRender = false;
        $compOffTbl = $this->getTableLocator()->get('AddCompOff');

        if ($this->request->is('POST')) {
            $compData = $compOffTbl->get($this->request->getData('compOffId'));
            $newDate = date('Y-m-d', strtotime($this->request->getData('request_date')));
            $newDesc = $this->request->getData('description');

            $compData->request_date = $newDate;
            $compData->description = $newDesc;
            if ($compOffTbl->save($compData)) {
                return $this->redirect(['controller' => 'Leaves', 'action' => 'addCompOff']);
            }
        } else {
            $compData = $compOffTbl->get($id);
            echo json_encode($compData);
        }
    }

    // Request Comp-off 

    public function requestCompOff()
    {
        $session = new \Cake\Http\Session();

        $sessionData = $session->read('user_data');

        $userSession = $session->read('data');

        $user_data = $this->Users->get($userSession['id'], [
            'contain' => ['EmpDetail', 'children', 'parent']
        ]);

        $check_mail = ['himani.duhan@actiknow.com', 'sumit.jhunjhunwala@actiknow.com', 'arpit.batham@actiknow.com'];

        if (in_array($userSession['email'], $check_mail)) {
            $conditions = [
                'employee_id !=' => $userSession['id']
            ];
        } else {

            $child = \Cake\Utility\Hash::extract($user_data['children'], '{n}.id');
            // print_r($child);
            // die;
            if (count($child) <= 0) {
                $child = [0];
            }
            $conditions = [
                'employee_id IN' => $child
            ];
        }


        $comp_data = [];

        $compOffTbl = $this->getTableLocator()->get('AddCompOff');

        $comp_data = $compOffTbl->find('all', [
            'conditions' => $conditions,
        ]);

        // print_r($child);
        // echo "<pre>";
        // foreach ($comp_data as $val) {
        //     print_r($val);
        // }
        // die;

        $this->set(compact('userSession', 'comp_data'));
    }

    // Comp-off approved

    public function approveCompOff()
    {
        $this->autoRender = false;

        $session = new \Cake\Http\Session();

        $sessionId = $session->read('user_data')['id'];

        $id = $this->request->getData('id');
        $status = $this->request->getData('status');

        $compOffTbl = $this->getTableLocator()->get('AddCompOff');
          $leaveCountingTbl = $this->getTableLocator()->get('LeaveCount');

        if ($status  == "Approved") {
            $compData = $compOffTbl->get($id);
            $compData->status = $status;
            $compData->approved_by = $sessionId;

            $employeeId = $compData->employee_id;

            $empData = $this->Users->get($employeeId);
            $comp_off = $empData->comp_off;
            $newComp_off = $comp_off + 1;

            
            $approverData = $this->Users->get($sessionId);
            $approverName = $approverData->name; 

            $leaveEntity = $leaveCountingTbl->newEmptyEntity();
            $leaveEntity->user_id = $employeeId;
            $leaveEntity->leave_id = 0;
            $leaveEntity->comp_off = -1;
            $leaveEntity->leave_date = $compData->request_date;
            $leaveEntity->leave_desc = "added on request";
            $leaveEntity->action_by = $approverName;



            if ($compOffTbl->save($compData)) {
                $empData->comp_off = $newComp_off;
                // $this->Users->save($empData);
                 $leaveCountingTbl->save($leaveEntity);
                echo "Yes";
            }
        } else {
            $compData = $compOffTbl->get($id);
            $compData->status = $status;
            $compData->approved_by = $sessionId;

            if ($compOffTbl->save($compData)) {
                echo "Yes";
            }
        }
    }

    public function emailEventStore($leavetype, $emp_name, $fromDataForGoogle, $toDataForGoogle, $emp_id, $finalEmaildata)
    {
        $client_id  = clientId;
        $client_secret = clientSecret;
        $client =  new Client();
        $client->setApplicationName('Gmail API PHP Quickstart');
        $client->setScopes('https://www.googleapis.com/auth/calendar.events');
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        // $tokenPath = ROOT. '/actiknowcalendar.json';
        // $client->setAuthConfig(ROOT.'/actiknowid.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $tokenPath =  ROOT . '\actiknowcalendar.json';

        // print_r($tokenPath);die;

        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        $service = new Calendar($client);
        $event   = new Google_Service_Calendar_Event(array(

            'summary'     =>   $leavetype . '-' . $emp_name,
            'location'    =>   'New Delhi',
            'description' =>   'creating event.',
            'start' => array(
                'dateTime' => $fromDataForGoogle . 'T10:00:00+05:30',
                'timeZone' =>  "Asia/Kolkata",
            ),
            'end' => array(
                'dateTime' => $toDataForGoogle . 'T18:00:00+05:30',
                'timeZone' =>  "Asia/Kolkata",
            ),
            'recurrence' => array(
                'RRULE:FREQ=DAILY;COUNT=1'
            ),

            'attendees' => $emp_id,
            'attendees' => $finalEmaildata,

            'reminders' => array(
                'useDefault' => FALSE,
                'overrides' => array(
                    array('method' => 'email', 'minutes' => 24 * 60),
                    array('method' => 'popup', 'minutes' => 10),
                ),
            ),
        ));

        $calendarId = 'tushar.singh@actiknowbi.com';
        $events = $service->events->insert($calendarId, $event);
        echo "event created successfully.";
    }

    public function sumOfApprovedLeave($userId)
    {
       
        $leaveCount =  $this->getTableLocator()->get('LeaveCount');
        
        $month = date('m');
        if ($month < 4) {

            $year = date('Y', strtotime('-1 year'));
            $nextYear = date('Y');
        } else {

            $year = date('Y');
            $nextYear = date('Y', strtotime('+1 year'));
        }

        // echo $year;
        // echo $nextYear;
        // die;
        $userLeaveCL = $this->Users->find()
            ->select([
                'cl' => 'Users.cl',
                'sumCL' => 'sum(LeaveCount.cl)',
                'comp_off' => 'Users.comp_off',
                'sumComp' => 'sum(LeaveCount.comp_off)',
                'lwp' => 'Users.lwp',
                'sumLWP' => 'sum(LeaveCount.lwp)'
            ])
            ->join([
                'LeaveCount' => [
                    'table' => 'leave_counting',
                    'type' => 'LEFT',
                    'conditions' => ['LeaveCount.user_id = Users.id', "LeaveCount.leave_date >= '$year-04-01'", "LeaveCount.leave_date <= '$nextYear-03-31'"]
                ]
            ])
            ->where(['Users.id' => $userId])
            ->toArray();
            // dd($userLeaveCL);

        $userLeaveELSL = $this->Users->find()
            ->select([
                'el' => 'Users.el',
                'sl' => 'Users.sl',
                'sumEL' => 'SUM(LeaveCount.el)',
                'sumSL' => 'SUM(LeaveCount.sl)',
            ])
            ->join([
                'LeaveCount' => [
                    'table' => 'leave_counting',
                    'type' => 'LEFT',
                    'conditions' => ['LeaveCount.user_id = Users.id']
                ]
            ])
            ->where(['Users.id' => $userId])
            ->toArray();

        $myLeave = ['cl' => $userLeaveCL[0]->cl, 'sumCL' => $userLeaveCL[0]->sumCL, 'el' => $userLeaveELSL[0]->el, 'sl' => $userLeaveELSL[0]->sl, 'sumEL' => $userLeaveELSL[0]->sumEL, 'sumSL' => $userLeaveELSL[0]->sumSL, 'lwp' => $userLeaveCL[0]->lwp, 'sumLWP' => $userLeaveCL[0]->sumLWP, 'comp_off' => $userLeaveCL[0]->comp_off, 'sumComp' => $userLeaveCL[0]->sumComp];

        // echo '<pre>';
        // print_r($myLeave);
        // die;

        // $leaveCountQuery = $leaveCount->find();
        // $myLeave = $leaveCountQuery
        //     ->select([
        //         'sumEl' => $leaveCountQuery->func()->sum('LeaveCount.el'),
        //         'sumSl' => $leaveCountQuery->func()->sum('LeaveCount.sl'),
        //         'sumCl' => $leaveCountQuery->func()->sum('LeaveCount.cl'),
        //         'sumCompOff' => $leaveCountQuery->func()->sum('LeaveCount.comp_off'),
        //         'sumLwp' => $leaveCountQuery->func()->sum('LeaveCount.lwp'),
        //         'u.el',
        //         'u.cl',
        //         'u.sl',
        //         'u.comp_off',
        //         'u.lwp'
        //     ])
        //     ->join([
        //         'table' => 'users',
        //         'alias' => 'u',
        //         'type' => 'right',
        //         'conditions' => 'u.id = LeaveCount.user_id'
        //     ])
        //     ->where([
        //         'u.id = ' . $userId,
        //         'OR' => [
        //             'LeaveCount.leave_date BETWEEN :start AND :end',
        //             'LeaveCount.id is null'
        //         ]
        //     ])
        //     ->bind(':start', $year . '-04-01', 'date')
        //     ->bind(':end',   $nextYear . '-03-31', 'date')
        //     ->toArray();

        return $myLeave;
    }

    public function addAnnualHoliday()
    {
        if ($this->request->is("post")) {

            // echo "<pre>";
            // print_r($this->request->getData());
            // die;

            $holiday = $this->YearlyHoliday->newEmptyEntity();

            $holiday->holiday_date = date("Y-m-d", strtotime($this->request->getData('holiday_date')));

            if ($this->YearlyHoliday->save($holiday)) {
                $this->Flash->success(__("Holiday saved successfully."));
                return $this->redirect("/leaves");
            } else {
                $this->Flash->error(__("Enternal server error.Holiday does not save."));
                return $this->redirect("/leaves");
            }
        }
    }

    // Filter Name and Leave Type
    public function filterNameLeave()
    {
        $this->autoRender = false;

        $session = new \Cake\Http\Session();
        $userId = $session->read('user_data')['id'];

        if ($this->request->is("get")) {

            $val = $this->request->getQuery('val');

            $filterData = $this->Users->find()
                ->select([
                    'id' => 'Users.id',
                    'name' => 'Users.name',
                    'leave_id' => 'Leaves.id',
                    'leave_type' => 'Leaves.leave_type',
                    'subject' => 'Leaves.subject',
                    'created_by' => 'Leaves.created_by',
                    'applied_on' => 'Leaves.applied_on',
                    'from_date' => 'Leaves.from_date',
                    'to_date' => 'Leaves.to_date',
                    'leave_status' => 'Leaves.status',
                    'message' => 'Leaves.message',
                ])
                ->join([
                    'Leaves' => [
                        'table' => 'leaves',
                        'type' => 'LEFT',
                        'conditions' => 'Leaves.created_by = Users.id'
                    ]
                ])
                ->where([
                    'Users.reporting_manager' => $userId,
                    'OR' => [
                        "Leaves.leave_type LIKE" => "%$val%",
                        "Users.name LIKE" => "%$val%"
                    ]
                ])
                ->toArray();

            echo json_encode($filterData);
            // echo $userId;
            // echo '<pre>';
            // print_r($filterData);
            // die;
        }
    }

    public function filterAllLeave()
    {

        $this->autoRender = false;

        if ($this->request->is("GET")) {
            $value = $this->request->getQuery("value");

            $filterData = $this->Users->find()
                ->select([
                    'name' => 'Users.name',
                    'leave_id' => 'Leaves.id',
                    'leave_type' => 'Leaves.leave_type',
                    'subject' => 'Leaves.subject',
                    'created_by' => 'Leaves.created_by',
                    'applied_on' => 'Leaves.applied_on',
                    'from_date' => 'Leaves.from_date',
                    'to_date' => 'Leaves.to_date',
                    'leave_status' => 'Leaves.status',
                    'message' => 'Leaves.message',
                    'r_name' => 'RManager.name',
                ])
                ->join([
                    'Leaves' => [
                        'table' => 'leaves',
                        'type' => 'INNER',
                        'conditions' => ['Leaves.created_by = Users.id']
                    ],
                    'RManager' => [
                        'table' => 'users',
                        'type' => 'INNER',
                        'conditions' => 'RManager.id = Leaves.approved_by'
                    ]
                ])
                ->where([
                    'Leaves.status !=' => 'Pending',
                    'OR' => [
                        "Leaves.leave_type LIKE" => "%$value%",
                        "Users.name LIKE" => "%$value%"
                    ]
                ])
                ->toArray();

            echo json_encode($filterData);
            // echo $userId;
            // echo '<pre>';
            // print_r($filterData);
            die;
        }
    }

    function filterCompOff()
    {

        $this->autoRender = false;
        $session = new \Cake\Http\Session();
        $userId = $session->read('user_data')['id'];

        if ($this->request->is("get")) {

            $value = $this->request->getQuery('value');

            $compOffTbl = $this->getTableLocator()->get('AddCompOff');

            $filterData = $compOffTbl->find()
                ->select([
                    'id' => 'AddCompOff.id',
                    'emp_name' => 'Emp.name',
                    'descr' => 'AddCompOff.description',
                    'req_date' => 'AddCompOff.request_date',
                    'comp_status' => 'AddCompOff.status'
                ])
                ->join([
                    'Emp' => [
                        'table' => 'users',
                        'type' => 'INNER',
                        'conditions' => 'Emp.id = AddCompOff.employee_id'
                    ]
                ])
                ->where([
                    'AddCompOff.approved_by' => $userId,
                    'OR' => [
                        'Emp.name LIKE' => "%$value%",
                    ]
                ])
                ->toArray();

            echo json_encode($filterData);
            // echo '<pre>';
            // print_r($filterData);
            die;
        }
    }

    //     public function hrLeaves()
    // {
    //     $session = new \Cake\Http\Session();

    //     $selectedEmployeeId = $this->request->getQuery('employee_id');
    //     $leave = $this->Leaves->newEmptyEntity();

    //     if (!empty($selectedEmployeeId)) {
    //         $userId = $selectedEmployeeId;
    //     } else {
    //         $userId = $session->read('user_data')['id'];
    //     }

    //     $leaved_data = $this->Leaves->findByCreatedBy($userId)->contain(['Users'])->orderAsc('Leaves.status');

    //     $resources = $this->Users->find()
    //         ->where(['role' => 3, 'deleted' => 1, 'status' => 1])
    //         ->select(['name', 'id'])
    //         ->toArray();
    //     $user_data = $this->Users->get($userId, [
    //         'contain' => ['EmpDetail']
    //     ]);

    //     // $rmId = $session->read('user_data')['reporting_manager'];
    //     $rmId = $user_data->reporting_manager;

    //     $myLeave = $this->sumOfApprovedLeave($userId);

    //     $users_data = $this->Users->find()
    //         ->select(['id', 'name'])
    //         ->where([
    //             'role' => 3,
    //             'status' => 1,
    //             'deleted' => 1
    //         ])
    //         ->order(['name' => 'ASC'])
    //         ->toArray();

    //     $this->set(compact(
    //         'user_data',
    //         'myLeave',
    //         'users_data',
    //         'selectedEmployeeId',
    //         'leave',
    //         'leaved_data',
    //         'resources',
    //         'rmId'
    //     ));
    // }

        public function hrLeaves()
    {
        $selectedEmployeeId = $this->request->getQuery('employee_id');

        $leave = $this->Leaves->newEmptyEntity();

        $resources = $this->Users->find()
            ->where([
                'role' => 3,
                'deleted' => 1,
                'status' => 1
            ])
            ->select(['id', 'name'])
            ->toArray();

        $users_data = $this->Users->find()
            ->select(['id', 'name'])
            ->where([
                'role' => 3,
                'status' => 1,
                'deleted' => 1
            ])
            ->order(['name' => 'ASC'])
            ->toArray();

        // Default values
        $user_data = null;
        $rmId = '';
        $leaved_data = [];
        $myLeave = [
            'cl' => 0,
            'sl' => 0,
            'el' => 0,
            'comp_off' => 0,
            'sumCL' => 0,
            'sumSL' => 0,
            'sumEL' => 0,
            'sumComp' => 0,
            'sumLWP' => 0
        ];

        // Only load data when employee is selected
        if (!empty($selectedEmployeeId)) {

            $user_data = $this->Users->get($selectedEmployeeId, [
                'contain' => ['EmpDetail']
            ]);

            $rmId = $user_data->reporting_manager;

            $leaved_data = $this->Leaves->findByCreatedBy($selectedEmployeeId)
                ->contain(['Users'])
                ->orderAsc('Leaves.status');

            $myLeave = $this->sumOfApprovedLeave($selectedEmployeeId);
        }

        $this->set(compact(
            'user_data',
            'myLeave',
            'users_data',
            'selectedEmployeeId',
            'leave',
            'leaved_data',
            'resources',
            'rmId'
        ));
    }


    public function hrLeavesAdd()
    {
        $conn = ConnectionManager::get('default');
        $session = new \Cake\Http\Session();
        $userSession = $session->read('data');

        $hrId = $userSession['id'];
        $hrName = $userSession['name'];
       
        $leave = $this->Leaves->newEmptyEntity();
        if ($this->request->is('post')) {
           // dd($this->request->getSession()->read());
            $run = false;

            $data=$this->request->getData();
            // dd($data);
            // $id = $this->request->getSession()->read("user_data")['id'];
            $id = $this->request->getData('created_by'); 
            $user = $this->Users->findById($id)->firstOrFail();
            $takenLeave = $this->sumOfApprovedLeave($id);
            // dd($takenLeave);

            $from_date = date("Y-m-d", strtotime($this->request->getData("from_date")));
            $to_date = date("Y-m-d", strtotime($this->request->getData("to_date")));

            $from_date_create = date_create((string)$from_date);
            $from_month_date = date_format($from_date_create, "m");
            $from_year_date = date_format($from_date_create, "Y");
            // dd($from_date);

            $days_count = $this->getDateDiff($from_date, $to_date);
            // $totalPendingLeave =  $days_count + $this->pendingLeave($id, $this->request->getData("leave_type"));
             $totalPendingLeave =  $days_count;
            $wfh=$this->Leaves->find()->select([
                'totalwfh'=>'SUM(Leaves.wfh)'
            ])->where([
                'Leaves.created_by'=>$id,
                'month(from_date)'=>$from_month_date,
                'YEAR(from_date)'=>$from_year_date,
                'Leaves.wfh_type'=>'WFH'
            ])
            ->first();

            $pre_leave = $this->Leaves->find()
            ->where([
                'Leaves.created_by' => $id,
                'OR' => [
                    ['Leaves.status' => 'Pending'],
                    ['Leaves.status' => 'Approved']
                ],
                'NOT' => ['Leaves.leave_type IN' => ['WFH','Short Leave', 'Forgot Card']]
            ])
            ->order(['id' => 'DESC'])
            ->limit(1)
            ->first();
            $query = "SELECT `start` FROM `holidays` WHERE `deleted` = 0 ORDER BY start ASC";
            $stmtList = $conn->execute($query);
            $holidaylist = $stmtList->fetchAll('assoc');
            // dd($holidaylist);

            $holidayDates = array_map(function($holiday) {
                return date('Y-m-d', strtotime($holiday['start']));
            }, $holidaylist);
            if (!empty($pre_leave)) {
                $last_leave_date = $pre_leave->to_date->format('Y-m-d');
                $previousDay = date('Y-m-d', strtotime('-1 day', strtotime($last_leave_date)));
                $nextDay = date('Y-m-d', strtotime('+1 day', strtotime($last_leave_date)));
                $last_leave_day = date('l', strtotime($last_leave_date));
                // check monday pre leave
                $last_leave_mdate = $pre_leave->from_date->format('Y-m-d');
                $last_leave_mday = date('l', strtotime($last_leave_mdate));
                // end
                $current_from_day = date('l', strtotime($from_date));
                $date_diff = $this->getDateDiff($last_leave_date, $from_date);
                $check_leave_type = $pre_leave->leave_type;
                $halfday_type = $pre_leave->halfday_type;
                // $check_leave_type = $pre_leave->leave_type;
            } else {
                $last_leave_day='';
                $last_leave_mday='';
                $current_from_day='';
                $date_diff='';
                $check_leave_type = '';
                $halfday_type = '';
            }

            $doj = new FrozenTime($user['doj']);
            $sixMonthsAfterDoj = $doj->addMonths(6);
            $currentDate = FrozenTime::now();
            if($currentDate < $sixMonthsAfterDoj) {
                $sixmonthcomplete = false;
            } else {
                $sixmonthcomplete = true;
            }

            // dd($check_leave_type);
            // dd($wfh->totalwfh);
            $totalwfh=$wfh->totalwfh;
            $lreason = $this->request->getData("reason");
            $sreason = $this->request->getData("sreason");
            if(!empty($lreason)) {
                $reason = $this->request->getData("reason");
            } else {
                $reason = $this->request->getData("sreason"); 
            }
            if(($this->request->getData("leave_type") == "WFH")) {
                $wfhtype = $this->request->getData("wfhtype");
            } else {
                $wfhtype = '';
            }
            if(($this->request->getData("leave_type") == "Half Day")) {
                $halfdaytype = $this->request->getData("halfdaytype");
            } else {
                $halfdaytype = '';
            }
            // dd($reason);

                    // finacial year restricted
                    $curr_month  = date('m');
                    $curr_year   = date('Y');
                    $start_year  = date('Y', strtotime($from_date));
                    $end_year    = date('Y', strtotime($to_date));
                   
                    // Define financial year start and end
                    $fy_start = ($curr_month >= 4) ? $curr_year : $curr_year - 1;  // Financial year starts in April
                    $fy_end = $fy_start + 1;  // Ends in March next year
                   
                    $start_fy = strtotime("$fy_start-04-01");  // April 1st of current FY
                    $end_fy = strtotime("$fy_end-03-31");      // March 31st of current FY
                    $start_leave = strtotime($from_date);
                    $end_leave = strtotime($to_date);
           
            if ($days_count > 0) {
               if($last_leave_day=='Friday' && $current_from_day=='Monday' && $date_diff==4) {
                    $cut_weekend_leave = 2;
                    $data['weekend']='true';
                  }
                  elseif($last_leave_mday=='Monday' && $current_from_day=='Friday' && $date_diff==4) {
                      $cut_weekend_leave = 2;
                      $data['weekend']='true';
                  }
                  elseif(in_array($previousDay, $holidayDates) && $date_diff==3){
                    $cut_weekend_leave = 1;
                    $data['weekend']='true';
                  }
                  elseif(in_array($nextDay, $holidayDates) && $date_diff==3){
                    $cut_weekend_leave = 1;
                    $data['weekend']='true';
                  }
                  elseif($last_leave_date=='2024-10-30' && $from_date=='2024-11-04'){
                    $cut_weekend_leave = 2;
                    $data['weekend']='true';
                  }
                  elseif(($last_leave_date=='2024-11-04' && $from_date=='2024-10-30') || $last_leave_mdate=='2024-11-04' && $from_date=='2024-10-30'){
                    $cut_weekend_leave = 2; 
                    $data['weekend']='true';
                  }
                  else {
                      $cut_weekend_leave = 0;  
                      $data['weekend']='false';
                  }
                //   dd($cut_weekend_leave);
                  if ($this->request->getData("leave_type") == "Casual Leave") {
                
                    // Restrict CL if leave dates fall in the next financial year
                    if ($start_leave > $end_fy || $end_leave > $end_fy) {
                        $this->Flash->error(__("Casual leave cannot be applied for the new financial year as it is only valid until the current financial year."));
                    } else {
                        if ($user['cl'] - $takenLeave['sumCL'] >= ($days_count + $cut_weekend_leave) && $totalPendingLeave <= $user['cl'] - $takenLeave['sumCL']) {
                            $run = true;
                        } else {
                            $this->Flash->error(__("You don't have enough leaves"));
                        }
                    }
                } else if ($this->request->getData("leave_type") == "Paid Leave") {

                    if ($currentDate < $sixMonthsAfterDoj) {
                        $this->Flash->error(__("You can't apply paid leave."));
                    } else {
                        if ($user['el'] - $takenLeave['sumEL']  >= ($days_count+$cut_weekend_leave) && $totalPendingLeave <= $user['el'] - $takenLeave['sumEL']) {
                            $run = true;
                        } else {
                            $this->Flash->error(__("You Don't have enough leaves"));
                        }
                    }
                } else if ($this->request->getData("leave_type") == "Sick Leave") {

                    if ($user['sl'] - $takenLeave['sumSL']  >= ($days_count+$cut_weekend_leave) && $totalPendingLeave <= $user['sl'] - $takenLeave['sumSL']) {
                        $run = true;
                    } else {
                        $this->Flash->error(__("You Don't have enough leaves"));
                    }
                } else if ($this->request->getData("leave_type") == "Half Day") {
                        // dd($days_count);
                    if ($days_count == 1) {
                        $run = true;
                    } else {
                        $this->Flash->error(__("You Don't have take two half days at a time."));
                    }
                } else if ($this->request->getData("leave_type") == "WFH") {
                   
                    $run = true;
                } else if ($this->request->getData("leave_type") == "comp_off") {

                    if ($user['comp_off'] - $takenLeave['sumComp']  >= $days_count && $totalPendingLeave <= $user['comp_off'] - $takenLeave['sumComp']) {
                        $run  = true;
                    } else {
                        $this->Flash->error(__("You Don't have enough comp_off"));
                    }
                } else if ($this->request->getData("leave_type") == "LWP" || $this->request->getData("leave_type") == "Forgot Card" || $this->request->getData("leave_type") == "Short Leave") {
                    $run = true; 
                }
            } else {
                $this->Flash->error(__("You can't apply previous day leave."));
            }

            if ($run) {
                $resources = $this->request->getData('resources');
                 
                    $leave = $this->Leaves->patchEntity($leave, $this->request->getData());
                    $leave->reason = $reason;
                    $leave->from_date = $from_date;
                    $leave->to_date = $to_date;
                    // $leave->wfh =$totalwfh;
                    $leave->wfh_flag =$wfhtype;
                    $leave->halfday_type =$halfdaytype;
                    $leave->resources = json_encode($resources);
                    $leave->leave_details = 'Applied by: ' . $hrName;
               

                if ($this->Leaves->save($leave)) {

                    $employee = $this->Users->get($id);

                    $reportingManagerId = $employee->reporting_manager;

                    $applyBy = $user->name;
                    // $applyBy = $this->request->getSession()->read("user_data")['name'];
                    // $reportingManagerId = $this->request->getSession()->read("user_data")['reporting_manager'];
                    $reportingManagerEmial = $this->Users->get($reportingManagerId)->email;

                    $email = $this->Users
                        ->find()
                        ->select(['email', 'id'])
                        ->where([
                            'id in ' => $resources,
                            // 'OR' => [
                            //     'id !=' => $reportingManagerId
                            // ]
                        ])
                        ->toArray();

                    $subject = $this->request->getData('subject');
                   
                    $ids=$this->Leaves->find()->select(['id'])->order(['id'=>'desc'])->limit(1)->toarray();
                    // dd($ids[0]['id']);
                    $lastid=$ids[0]['id'];
                    $data['id'] =$lastid;
                    // dd($data);
                    $leave = $data['id'];
                    $leaveById = $this->Leaves->get($leave);
                    $from_date = date_create((string)$data['from_date']);
                    $to_date = date_create((string)$data['to_date']);

                    $from_date  = date_format($from_date, "Y-m-d");
                    $to_date = date_format($to_date, "Y-m-d");

                    $fromDate = date_create((string)$data['from_date']);
                    $toDate = date_create((string)$data['to_date']);
                    $year = date_format($fromDate, "Y");
                    $fromDateCheck = date_format($fromDate, 'm');
                    $toDateCheck = date_format($toDate, 'm');
                    $user = $this->Users->get($data['created_by']);
                    $leaveType =  $data['leave_type'];
                    // $from_month_date = date_format($leaveById->from_date, "m");
                    // $from_year_date = date_format($leaveById->from_date, "Y");
                    $from_month_date = $leaveById->from_date->format('m');
                    $from_year_date = $leaveById->from_date->format('Y');
                    $takenLeave = $this->sumOfApprovedLeave($data['created_by']);

                    $leaveCount = $this->getTableLocator()->get('LeaveCount');
                    $leaveCountInsert = $leaveCount->newEmptyEntity();
                    $leaveCountInsert->user_id  = $data['created_by'];
                    $leaveCountInsert->leave_id = $data['id'];
                    $leaveCountInsert->leave_date =date("Y-m-d", strtotime($data['from_date']));
                    $date_got = $this->getDateDiff((string)$data['from_date'], (string)$data['to_date']);
                    if($data['weekend']=='true') {
                        $extra_weekend_leave = $cut_weekend_leave;
                    } else {
                        $extra_weekend_leave = $cut_weekend_leave;
                    }
                    
                    if ($leaveType == "Paid Leave") {
                        $leaveCountInsert->el = $date_got+$extra_weekend_leave;
                    } else if ($leaveType == "Casual Leave") {
                        // dd($leaveCountInsert->cl);
                        $leaveCountInsert->cl = $date_got+$extra_weekend_leave;
                    } else if ($leaveType == "Sick Leave") {
                        $leaveCountInsert->sl = $date_got+$extra_weekend_leave;
                    } else if ($leaveType == "comp_off") {
                        $leaveCountInsert->comp_off = $date_got;
                    } else if ($leaveType == "LWP") {
                        $leaveCountInsert->lwp = $date_got+$extra_weekend_leave;
                    } else if ($leaveType == "Half Day") {
                        $allTypeLeave = [];
                        if ($user->cl - $takenLeave['sumCL'] >= 0.5+$extra_weekend_leave) {
                            $allTypeLeave[] = ['cl' => 0.5];
                            $leaveCountInsert->cl = ($date_got / 2)+$extra_weekend_leave;
                        } else if ($user->sl - $takenLeave['sumSL'] >= 0.5+$extra_weekend_leave) {
                            $allTypeLeave[] = ['sl' => 0.5];
                            $leaveCountInsert->sl = ($date_got / 2)+$extra_weekend_leave;
                        } else if ($user->el - $takenLeave['sumEL'] >= 0.5+$extra_weekend_leave && $sixmonthcomplete == true) {
                            $allTypeLeave[] = ['el' => 0.5];
                            $leaveCountInsert->el = ($date_got / 2)+$extra_weekend_leave;
                        } else if ($user->comp_off - $takenLeave['sumComp'] >= 0.5+$extra_weekend_leave) {
                            $allTypeLeave[] = ['comp_off' => 0.5];
                            $leaveCountInsert->el = ($date_got / 2)+$extra_weekend_leave;
                        } else {
                            $allTypeLeave[] = ['lwp' => 0.5+$extra_weekend_leave];
                            $leaveCountInsert->lwp = ($date_got / 2)+$extra_weekend_leave;
                        }
                        // // dd($allTypeLeave);
                        // $cutLeave = json_encode($allTypeLeave);
                        // // dd($leave->leave_details = $cutLeave);
                        // $this->Leaves->save($leave);
                    }
                    else if($leaveType == "WFH") {
                        // $leaveDuration = ($date_got / 2)+$extra_weekend_leave; // Convert to 0.5 increments
                        // dd($leaveDuration);
                        $leaveDuration = ($date_got / 2);
    
                        // Initialize leave count
                        $leaveCountInsert->cl = 0;
                        $leaveCountInsert->sl = 0;
                        $leaveCountInsert->el = 0;
                        $leaveCountInsert->lwp = 0;
                       
                        while ($leaveDuration > 0) {
                            if ($start_leave > $end_fy || $end_leave > $end_fy) {
                                if ($leaveDuration >= 0.5) {
                                    if ($user->sl - $takenLeave['sumSL'] >= 0.5) {
                                        $leaveCountInsert->sl += 0.5;
                                        $takenLeave['sumSL'] += 0.5;
                                        $leaveDuration -= 0.5;
                                    } 
                                    // Deduct from Earned Leave
                                    elseif ($user->el - $takenLeave['sumEL'] >= 0.5 && $sixmonthcomplete == true) {
                                        $leaveCountInsert->el += 0.5;
                                        $takenLeave['sumEL'] += 0.5;
                                        $leaveDuration -= 0.5;
                                    } 
                                    // Deduct from Leave Without Pay
                                    else {
                                        $leaveCountInsert->lwp += $leaveDuration; // Remaining duration
                                        $leaveDuration = 0; // End loop
                                    }
                                } else {
                                    if ($leaveDuration > 0 && $user->sl - $takenLeave['sumSL'] >= $leaveDuration) {
                                        $leaveCountInsert->sl += $leaveDuration;
                                        $takenLeave['sumSL'] += $leaveDuration;
                                        $leaveDuration = 0;
                                    } elseif ($leaveDuration > 0 && $user->el - $takenLeave['sumEL'] >= $leaveDuration && $sixmonthcomplete == true) {
                                        $leaveCountInsert->el += $leaveDuration;
                                        $takenLeave['sumEL'] += $leaveDuration;
                                        $leaveDuration = 0;
                                    } else {
                                        $leaveCountInsert->lwp += $leaveDuration; // Remaining duration
                                        $leaveDuration = 0; // End loop
                                    }
                                }
                            } else {
                                if ($leaveDuration >= 0.5) {
                                    // Deduct from Casual Leave
                                    if ($user->cl - $takenLeave['sumCL'] >= 0.5) {
                                        $leaveCountInsert->cl += 0.5;
                                        $takenLeave['sumCL'] += 0.5;
                                        $leaveDuration -= 0.5;
                                    } 
                                    // Deduct from Sick Leave
                                    elseif ($user->sl - $takenLeave['sumSL'] >= 0.5) {
                                        $leaveCountInsert->sl += 0.5;
                                        $takenLeave['sumSL'] += 0.5;
                                        $leaveDuration -= 0.5;
                                    } 
                                    // Deduct from Earned Leave
                                    elseif ($user->el - $takenLeave['sumEL'] >= 0.5 && $sixmonthcomplete == true) {
                                        $leaveCountInsert->el += 0.5;
                                        $takenLeave['sumEL'] += 0.5;
                                        $leaveDuration -= 0.5;
                                    } 
                                    // Deduct from Leave Without Pay
                                    else {
                                        $leaveCountInsert->lwp += $leaveDuration; // Remaining duration
                                        $leaveDuration = 0; // End loop
                                    }
                                } else {
                                    // Handle remaining leave less than 0.5
                                    if ($leaveDuration > 0 && $user->cl - $takenLeave['sumCL'] >= $leaveDuration) {
                                        $leaveCountInsert->cl += $leaveDuration;
                                        $takenLeave['sumCL'] += $leaveDuration;
                                        $leaveDuration = 0;
                                    } elseif ($leaveDuration > 0 && $user->sl - $takenLeave['sumSL'] >= $leaveDuration) {
                                        $leaveCountInsert->sl += $leaveDuration;
                                        $takenLeave['sumSL'] += $leaveDuration;
                                        $leaveDuration = 0;
                                    } elseif ($leaveDuration > 0 && $user->el - $takenLeave['sumEL'] >= $leaveDuration && $sixmonthcomplete == true) {
                                        $leaveCountInsert->el += $leaveDuration;
                                        $takenLeave['sumEL'] += $leaveDuration;
                                        $leaveDuration = 0;
                                    } else {
                                        $leaveCountInsert->lwp += $leaveDuration; // Remaining duration
                                        $leaveDuration = 0; // End loop
                                    }
                                }
                            }
                        }
                    }
                    // dd($leaveCountInsert);
                   $insert_l = $leaveCount->save($leaveCountInsert);

                   $cut_leave=[];
                   if($insert_l) {
                    $cut_leave = ['CL'=>$insert_l->cl,'SL'=>$insert_l->sl,'EL'=>$insert_l->el,'LWP'=>$insert_l->lwp];
                   }
                //    dd($cut_leave);

                if ($this->request->getData("leave_type") == "WFH") {
                    $leaveType = "Work From Home ";
                  $this->sendApplyLeaveNoticHr($reportingManagerEmial, $leaveType, $subject, $applyBy, $from_date, $to_date, $email,$reason,$cut_leave,$hrId,$hrName);
                } else {
                    $leaveType = $this->request->getData('leave_type');
                   $this->sendApplyLeaveNoticHr($reportingManagerEmial, $leaveType, $subject, $applyBy, $from_date, $to_date, $email,$reason,$cut_leave,$hrId,$hrName);
                }

                    // $this->leavecheck($data);

                    $this->Flash->success(__('The leave has been saved.'));
                    // return $this->redirect(['action' => 'index']);
                    return $this->redirect(['action' => 'hrLeaves']);
                }
              
            }
            
            
        }
        $this->redirect(
            ['controller' => 'Leaves', 'action' => 'index']
        );
    }


}