<?php
error_reporting(0);
require __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$conn = new mysqli('localhost', 'root', 'Acti@n8n@know25', 'pma');

if ($conn) {

   $sdate = date('Y-m-01', strtotime('-1 month'));
   $edate = date('Y-m-31', strtotime('-1 month'));

   echo $sql = "SELECT leaves.leave_type, leaves.from_date,users.id ,leaves.to_date, leaves.status,
  leaves.leave_details,users.name from leaves inner join users on leaves.created_by = users.id
  and leaves.status = 'Approved' and users.status = 1 and  users.deleted = 1 
  and leaves.from_date between '" . $sdate . "' and '" . $edate . "'";
   $query = mysqli_query($conn, $sql);

   $row = mysqli_num_rows($query);

   if ($row > 0) {
      $arr = array();
      while ($result = mysqli_fetch_assoc($query)) {
         if (array_key_exists($result['name'], $arr)) {
            if (array_key_exists($result['leave_type'], $arr[$result['name']])) {
            } else {
               $arr[$result['name']][$result['leave_type']] = 0;
            }
         } else {
            $arr[$result['name']][$result['leave_type']] = 0;
         }
         if ($result['leave_type'] == 'Half Day') {
            $leaveType = json_decode($result['leave_details']);
            foreach ($leaveType as $key => $value) {
               if (isset($value->cl)) {
                  $arr[$result['name']]['Casual Leave'] += $value->cl;
               }
               if (isset($value->sl)) {
                  $arr[$result['name']]['Sick Leave'] += $value->sl;
               }
               if (isset($value->el)) {
                  $arr[$result['name']]['Paid Leave'] += $value->el;
               }
            }
         } else {
            $arr[$result['name']][$result['leave_type']] += dayCalculate($result['from_date'], $result['to_date']);
         }
      }

      // print_r($arr);
      // die;

      if (count($arr) > 0) {
         $delimiter = ",";

         $fileName = date('d-m-Y') . ".csv";

         header('HTTP/1.1 200 OK');
         // header('Content-Type: text/csv; charset=utf-8');
         // header('Content-Disposition: attachment; filename="' . $filename . '"');
         header('Cache-Control: no-cache, no-store, must-revalidate');
         header('Pragma: no-cache');
         header('Expires: 0');


         $createFile = fopen($fileName, 'w');

         // set column
         $fields = ['Username', 'CL', 'SL', 'EL', 'Comp-Off', 'LWP'];

         fputcsv($createFile, $fields, $delimiter);
         foreach ($arr as $key => $val) {
            if (!isset($val['Casual Leave'])) {
               $val['Casual Leave'] = 0;
            }
            if (!isset($val['Sick Leave'])) {
               $val['Sick Leave'] = 0;
            }
            if (!isset($val['Paid Leave'])) {
               $val['Paid Leave'] = 0;
            }
            if (!isset($val['comp_off'])) {
               $val['comp_off'] = 0;
            }
            if (!isset($val['LWP'])) {
               $val['LWP'] = 0;
            }
            $leaveData = [
               $key, $val['Casual Leave'], $val['Sick Leave'], $val['Paid Leave'],
               $val['comp_off'], $val['LWP']
            ];
            fputcsv($createFile, $leaveData, $delimiter);
         }

         $mail = new PHPMailer(true);

         $body = htmlBody();

         $mail->isSMTP();
         // $mail->SMTPDebug = 2;
         $mail->Debugoutput = 'html';
         $mail->Host = 'smtp.gmail.com';
         $mail->Port = 587;

         $mail->SMTPSecure = 'tls';
         $mail->SMTPAuth = true;
         $mail->Username = 'notifications@actiknow.com';
         $mail->Password = 'zang kfmd pqkp spel';
         $mail->setFrom('noreply.n-chatbot@actiknow.com', 'Leaves');
         $mail->Subject = 'Leaves Details';

         // addAttachment function send file with mail
         $mail->addAttachment($fileName);
         $mail->addAddress('arpit.batham@actiknow.com');
         $mail->addAddress('himani.duhan@actiknow.com');
         $mail->addAddress('jyotima.tripathi@actiknow.com');
         $mail->msgHTML($body);

         if ($mail->send()) {
            $lwpSql = "SELECT leave_type,created_by from leaves where leave_type = 'lwp'";
            $lwpQuery = mysqli_query($conn, $lwpSql);

            while ($result = mysqli_fetch_assoc($lwpQuery)) {

               $lwpSQL = "UPDATE Users set lwp  = 0 where id = {$result['created_by']}";
               $lwpUpdateQuery = mysqli_query($conn, $lwpSQL);
            }
            echo "Done";
            fclose($createFile);
         } else {
            echo $mail->ErrorInfo;
         }
      }
   }
} else {

   echo "DB connection failed...";
}


// echo "<pre>";
// print_r($arr);
// die;


function dayCalculate($fromDate, $toDate)
{

   $date1 = date_create($fromDate);
   $date2 = date_create($toDate);
   $diff = date_diff($date1, $date2);
   $dayCount = (int)$diff->format("%R%a");
   return $dayCount + 1;

   /* $nextMonth = date('Y-m-d', strtotime('first day of +1 month', strtotime($fromDate)));
   $to = date('Y-m-d', strtotime($toDate));
   $monthFirstDay = date('Y-m-d', strtotime('first day of 0 month', strtotime($fromDate)));
   $from = date('Y-m-d', strtotime($fromDate));

   // $nextMonthDay = 0;
   if ($to >= $nextMonth) {
      // $nextMonthDay = getDateDiff($nextMonth, $to);
      $monthLastDay = date('Y-m-d', strtotime('last day of 0 month', strtotime($fromDate)));
      $totalDay = 0;
      $totalDay +=  getDateDiff($fromDate, $monthLastDay);

      return $totalDay;
   } else if ($from >= $monthFirstDay) {

      $totalDay = 0;
      $totalDay += getDateDiff($fromDate, $to);
      return $totalDay;
   } */
}

/* function getDateDiff($date1, $date2)
{
   $date1 = date_create($date1);
   $date2 = date_create($date2);
   $diff = date_diff($date1, $date2);
   $dayCount = (int)$diff->format("%R%a");
   return $dayCount + 1;
} */


function htmlBody()
{

   $firstDate = date('Y-m-d');
   $from = date('d-m-Y', strtotime('first day of -1 month', strtotime($firstDate)));
   $to = date('d-m-Y', strtotime('Last day of -1 month', strtotime($firstDate)));

   $body = '
	         <!DOCTYPE html>
	            <html>
	            <head></head>
	            <body>
	               <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-family:arial!important;font-size:12px!important;margin:auto;">
	                  <tr>
	                     <td align=center>
	                     <hr>
	                          
	                     </td>
	                  </tr>
	                      
	                  <tr>
	                     <td style="font-size: 14px;">
	                        <p style="word-spacing: 3px;">Hello Sir or Madam...</p>
	                        <p style="word-spacing: 3px;">
                              Please find attachment of leave details for all employees from '
      . $from . ' to ' . $to . '.
                           </p>
	                        <p style="word-spacing: 3px;">Best,<br>PMA Team</p>
	                     </td>
	                     </tr>
	               </table>
	                    
	            </body>
	            </html>';

   return $body;
}
