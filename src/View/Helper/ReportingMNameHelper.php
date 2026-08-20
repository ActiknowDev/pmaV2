<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\TableRegistry;

class ReportingMNameHelper extends Helper
{
   public function rName($rId)
   {
      // $users_table = TableRegistry::get('Users');
      $users_table = TableRegistry::getTableLocator()->get('Users');
      if ($rId == null || $rId == 0) {
         $name = "";
      } else {
         $userName = $users_table->find()->select(['name'])->where(['id' => $rId])->toArray();
         $name = $userName[0]->name;
      }

      // return $name;
      return count($userName) > 0 ? $userName[0]->name : "";
   }

   public function dob($uId)
   {

      // $empTbl = TableRegistry::get('employee_details');
      $empTbl = TableRegistry::getTableLocator()->get('EmployeeDetails');
      if ($uId == null || $uId == 0) {
         $dobData = "";
      } else {
         $empDob = $empTbl->find()->select(['dob'])->where(['user_id' => $uId])->toArray();
         // print_r($empDob);
         // die;
         $dobData = $empDob[0]->dob;
      }
      // return count($empDob) > 0 ? $empDob[0]->dob : "";
      return $dobData;
   }

   public function blood_group($uId)
   {

      // $empTbl = TableRegistry::get('employee_details');
      $empTbl = TableRegistry::getTableLocator()->get('EmployeeDetails');
      if ($uId == null || $uId == 0) {
         $blood_groupData = "";
      } else {
         $empblood_group = $empTbl->find()->select(['blood_group'])->where(['user_id' => $uId])->toArray();
         $blood_groupData = $empblood_group[0]->blood_group;
      }

      return $blood_groupData;
      // return count($empblood_group) > 0 ? $empblood_group[0]->blood_group : "";
   }

   public function aadhar_card($uId)
   {
      $empTbl = TableRegistry::getTableLocator()->get('EmployeeDetails');
      // $empTbl = TableRegistry::get('employee_details');
      if ($uId == null || $uId == 0) {
         $aadhar_cardData = "";
      } else {
         $emp_aadhar_card = $empTbl->find()->select(['aadhar_card'])->where(['user_id' => $uId])->toArray();
         $aadhar_cardData = $emp_aadhar_card[0]->aadhar_card;
      }

      return $aadhar_cardData;
      // return count($emp_aadhar_card) > 0 ? $emp_aadhar_card[0]->aadhar_card : "";
   }

   public function note($uId)
   {

      // $empTbl = TableRegistry::get('employee_details');
       $empTbl = TableRegistry::getTableLocator()->get('EmployeeDetails');
      if ($uId == null || $uId == 0) {
         $noteData = "";
      } else {
         $emp_note = $empTbl->find()->select(['note'])->where(['user_id' => $uId])->toArray();
         $noteData = count($emp_note) > 0 ? $emp_note[0]->note : ' ';
         // $noteData = $emp_note[0]->note;
      }

      return $noteData;
      // return count($emp_note) > 0 ? $emp_note[0]->note : "";
   }

   public function empCTC($uId)
   {

      // $empTbl = TableRegistry::get('employee_details');
      $empTbl = TableRegistry::getTableLocator()->get('EmployeeDetails');
      if ($uId == null || $uId == 0) {
         $empCtc = "";
      } else {
         $empCtc = $empTbl->find()->select(['ctc'])->where(['user_id' => $uId])->toArray();
         // print_r($empDob);
         // die;
         $empCtc = $empCtc[0]->ctc;
      }

      // return count($empCtc) > 0 ? $empCtc[0]->ctc : "";
      return $empCtc;
   }

   public function prevAppraisal($uId)
   {

      // $empTbl = TableRegistry::get('employee_details');
      $empTbl = TableRegistry::getTableLocator()->get('EmployeeDetails');
      if ($uId == null || $uId == 0) {
         $prevApp = "";
      } else {
         $prevApp = $empTbl->find()->select(['prev_appraisal'])->where(['user_id' => $uId])->toArray();
         // print_r($empDob);
         // die;
         $prevApp = $prevApp[0]->prev_appraisal;
      }

      return $prevApp;
   //   return count($prevApp) > 0 ? $prevApp[0]->prev_appraisal : "";
   }

   public function nextAppraisal($uId)
   {

      // $empTbl = TableRegistry::get('employee_details');
      $empTbl = TableRegistry::getTableLocator()->get('EmployeeDetails');
      if ($uId == null || $uId == 0) {
         $nextAppraisal = "";
      } else {
         $nextAppraisal = $empTbl->find()->select(['next_appraisal'])->where(['user_id' => $uId])->toArray();
         // print_r($empDob);
         // die;
         $nextAppraisal = $nextAppraisal[0]->next_appraisal;
      }

      return $nextAppraisal;
      // return count($nextAppraisal) > 0 ? $nextAppraisal[0]->next_appraisal : "";
   }

   public function teamName($uId)
   {

      // $empTbl = TableRegistry::get('my_teams');
      $empTbl = TableRegistry::getTableLocator()->get('MyTeams');
      if ($uId == null || $uId == 0) {
         $my_teams = "";
      } else {
         // $my_teams = $empTbl->find()->select(['team_name'])->where(['created_by' => $uId])->toArray();
         $my_teams = $empTbl->find()->select(['team_name'])->where(['id' => $uId])->toArray();
         // print_r($empDob);
         // die;
         $my_teams = count($my_teams) > 0 ? $my_teams[0]->team_name : ' ';
         // $my_teams = $my_teams[0]->team_name;
      }
      return $my_teams;
   }

      public function roleName($roleIds)
   {
      $roles = [
         4  => 'Manager',
         5  => 'Tech Lead',
         6  => 'BD',
         7  => 'Developer',
         8  => 'Designer',
         9  => 'Reporting',
         10 => 'All Project',
         11 => 'Support',
         12 => 'Management'
      ];

      if (empty($roleIds)) {
         return '';
      }

      $ids = explode(',', $roleIds);

      $roleNames = [];

      foreach ($ids as $id) {
         $id = trim($id);

         if (isset($roles[$id])) {
               $roleNames[] = $roles[$id];
         }
      }

      return implode(', ', $roleNames);
   }

   // Leave Date for Employee Attendance

   public function leaveDate($id, $date)
   {
      $date = date_create($date);
      $leaveTbl = TableRegistry::getTableLocator()->get('Leaves');

      $leaveDate = $leaveTbl->find()->select(['leave_type', 'from_date', 'to_date'])->where(['created_by' => $id, 'from_date' => $date, 'status' => 'Approved'])->first();

      if ($leaveDate->leave_type == "Forgot Card")
         return $leaveDate->leave_type;
      else if ($leaveDate)
         return  $leaveDate->from_date;
      else {
         $leaveDate = $leaveTbl->find()->select(['to_date'])->where(['created_by' => $id, 'to_date' => $date, 'status' => 'Approved'])->first();
         if ($leaveDate)
            return  $leaveDate->to_date;
         else {
            $leaveDate = $leaveTbl->find()->select(['from_date'])->where(['created_by' => $id, 'from_date <' => $date, 'to_date >' => $date, 'status' => 'Approved'])->toArray();
            if ($leaveDate)
               return date_format($date, "Y/m/d");

            return null;
         }
      }
   }

   public function employeeAttendance($emp, $presentDate, $type)
   {
      $EmployeePunchTime = TableRegistry::getTableLocator()->get('EmployeePunchTime');
      $leaveTbl = TableRegistry::getTableLocator()->get('Leaves');
      $usersId = TableRegistry::getTableLocator()->get('Users')
         ->find()->select(['id'])->where(['name' => $emp])->first()->id;

      $wfh = $leaveTbl->find()->select(['leave_type'])->where(['created_by' => $usersId, 'leave_type' => 'WFH', 'from_date' => $presentDate, 'status' => 'Approved'])->first();
      if ($wfh)
         return  $wfh->leave_type;


      $empDate = $EmployeePunchTime->find()
         ->where(['emp' => $emp, 'dom' => $presentDate])
         ->first();
      if ($type == "date") {
         $dateData = $empDate->dom;
         return date("Y-m-d", strtotime($dateData));
      } else if ($type == "punchInTime") {
         $inTime = $empDate->intime;
         return $inTime;
      } else {
         $outTime = $empDate->outtime;
         return $outTime;
      }
   }

   // Total Leave
   public function totalLeave($id, $month, $year)
   {
      $leaveTbl = TableRegistry::getTableLocator()->get('LeaveCount');
      $leaveData = $leaveTbl->find()
         ->select(['total' => 'SUM(cl + el + sl + comp_off + lwp)'])
         ->where(['user_id' => $id, 'MONTH(leave_date)' => $month, 'YEAR(leave_date)' => $year])
         ->toArray();
      $totalDays = $leaveData[0]->total ? $leaveData[0]->total + $_SESSION['leaveAdd'] : 0;
      return $totalDays;
   }

   // short leave
   // $name, $month, $year
   public function shortLeave($name, $month, $year)
   {
      $EmployeePunchTime = TableRegistry::getTableLocator()->get('EmployeePunchTime');
      if ($month == date('m') - 1) {
         $first = date('Y-m-d', strtotime('first day of last month'));
         $end = date('Y-m-d', strtotime('last day of last month'));
      } else {
         $monthIs = "$year-$month-05";
         $first = date('Y-m-01', strtotime($monthIs));
         $end = date('Y-m-t', strtotime($monthIs));
         // print_r($monthIs);
      }
      $totalTime = date('H:i:s', strtotime("8:45"));
      $empShort = $EmployeePunchTime->find()
         ->select([
            'emp' => 'emp',
            'dom' => 'dom',
            'time_diff' => 'time(time(outtime)-time(intime))',
            'outtime',
            'intime'
         ])
         ->where([
            'emp' => $name,
            'dom >=' => $first,
            'dom <=' => $end,
            'time(time(outtime)-time(intime)) <' => $totalTime,
            // 'time(time(outtime)-time(intime)) >' => $shortTime
         ])->toArray();

      $shortLeave = 0;
      $leaveAdd = 0;
      foreach ($empShort as $val) {
         $timeDiff = - (strtotime($val->intime) - strtotime($val->outtime)) / 3600;
         if ($timeDiff < 8.75) {
            if ($timeDiff >= 6) {
               $shortLeave += 1;
            }
            if ($timeDiff < 6) {
               if ($timeDiff < 4) $leaveAdd += 1;
               else $leaveAdd += 0.5;
            }
         }
      }
      $_SESSION['leaveAdd'] = $leaveAdd;
      return $shortLeave;
   }

   public function issueCount($id, $month, $year, $name)
   {
      $totalIssue = $this->totalLeave($id, $month, $year);
      $EmployeePunchTime = TableRegistry::getTableLocator()->get('EmployeePunchTime');
      // echo $month;
      if ($month == date('m') - 1) {
         $first = date('Y-m-d', strtotime('first day of last month'));
         $end = date('Y-m-d', strtotime('last day of last month'));
      } else {
         $monthIs = "$year-$month-05";
         $first = date('Y-m-01', strtotime($monthIs));
         $end = date('Y-m-t', strtotime($monthIs));
         // print_r($monthIs);
      }
      $totalTime = date('H:i:s', strtotime("8:45"));
      $empIssue = $EmployeePunchTime->find()
         ->select([
            'emp' => 'emp',
            'dom' => 'dom',
            'outtime',
            'intime'
         ])
         ->where([
            'emp' => $name,
            'dom >=' => $first,
            'dom <=' => $end,
            'TIMEDIFF(outtime,intime) <' => $totalTime,
            'issue_type' => '0'
         ])->toArray();

      $totalIssue += count($empIssue);
      return $totalIssue;
   }

   public function issueType($name, $presentDate)
   {
      $EmployeePunchTime = TableRegistry::getTableLocator()->get('EmployeePunchTime');
      $empIssue = $EmployeePunchTime->find()
         ->select(['issue_type'])
         ->where([
            'emp' => $name,
            'dom' => $presentDate,
            'OR' => [
               ['issue_type' => 1],
               ['issue_type' => 0]
            ]
         ])->first();

      return $empIssue->issue_type;
   }

   public function holiday($dayVal)
   {
      $holiday = "";
      switch ($dayVal) {
         case "2023-01-26":
            $holiday = "Holiday";
            break;
         case "2023-03-08":
            $holiday = "Holiday";
            break;
         case "2023-04-07":
            $holiday = "Holiday";
            break;
         case "2023-07-04":
            $holiday = "Holiday";
            break;
         case "2023-08-15":
            $holiday = "Holiday";
            break;
         case "2023-08-30":
            $holiday = "Holiday";
            break;
         case "2023-10-02":
            $holiday = "Holiday";
            break;
         case "2023-10-24":
            $holiday = "Holiday";
            break;
         case "2023-11-13":
            $holiday = "Holiday";
            break;
         case "2023-12-25":
            $holiday = "Holiday";
            break;
         default:
            $holiday = "";
      }
      return $holiday;
   }

   // Latest Updated

   public function latestUpdate($empName)
   {
      $EmployeePunchTime = TableRegistry::getTableLocator()->get('EmployeePunchTime');
      $latestDate = $EmployeePunchTime->find()
         ->select(['dom' => 'MAX(dom)'])
         ->where([
            'emp' => $empName,
         ])->first()->dom;

      return $latestDate;
   }
}
