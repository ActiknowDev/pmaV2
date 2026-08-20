<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// require_once('common/PHPMailerAutoload.php');
require __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = mysqli_connect("localhost", "root", "Acti@n8n@know25", "pma");
// $conn = mysqli_connect("localhost", "root", "", "pma");

if ($conn) {

   function convertToWords($number) {
      $words = array(
          '',
          'one',
          'two',
          'three',
          'four',
          'five',
          'six',
          'seven',
          'eight',
          'nine',
          'ten',
          'eleven',
          'twelve',
          'thirteen',
          'fourteen',
          'fifteen',
          'sixteen',
          'seventeen',
          'eighteen',
          'nineteen'
      );
  
      $tens = array(
          '',
          'ten',
          'twenty',
          'thirty',
          'forty',
          'fifty',
          'sixty',
          'seventy',
          'eighty',
          'ninety'
      );
  
      if ($number == 0) {
          return 'zero';
      }
  
      if ($number < 20) {
          return $words[$number];
      }
  
      if ($number < 100) {
          return $tens[substr($number, 0, 1)] . ' ' . $words[substr($number, 1)];
      }
  
      if ($number < 1000) {
          return $words[substr($number, 0, 1)] . ' hundred ' . convertToWords(substr($number, 1));
      }
  
      return 'number too large to convert';
  }

   //=======================

   $holidaydatas = array();

   $holidays = "SELECT start FROM `holidays` WHERE `deleted` = 0 ORDER BY start ASC";
   $holiday_query = mysqli_query($conn, $holidays);

   while ($holidaydata = mysqli_fetch_assoc($holiday_query)) {
      $holidaydatas[] = date('m-d', strtotime($holidaydata['start']));
   }

   $current_date = date('Y-m-d');
   $previous_day = date('Y-m-d', strtotime($current_date . ' -1 day'));
   $previous_day_date = date('m-d', strtotime($previous_day));
   $previous_day_days = date('l', strtotime($previous_day));
   $holiday = in_array($previous_day_date, $holidaydatas);
  
   $current_day_check = date('l');
   $today = date('m-d');
   if($current_day_check=='Monday') {
     // Calculate the date of the previous Sunday
    $previous_sunday = date('m-d', strtotime('last Sunday'));
    // Calculate the date of the previous Saturday
    $previous_saturday = date('m-d', strtotime('last Saturday'));

   $employee_anniversary = "SELECT users.id as uid, users.name as name, users.email as email, employee_details.doj as doj 
      FROM users 
      INNER JOIN employee_details ON users.id = employee_details.user_id 
      WHERE users.company_id = 10 AND users.deleted = 1 AND users.status = 1 
         AND (DATE_FORMAT(employee_details.doj, '%m-%d') = '$today' OR DATE_FORMAT(employee_details.doj, '%m-%d') = '$previous_sunday' OR DATE_FORMAT(employee_details.doj, '%m-%d') = '$previous_saturday')";

   } 
   elseif($holiday==true) {
     
     $employee_anniversary = "SELECT users.id as uid, users.name as name, users.email as email, employee_details.doj as doj 
      FROM users 
      INNER JOIN employee_details ON users.id = employee_details.user_id 
      WHERE users.company_id = 10 AND users.deleted = 1 AND users.status = 1 
         AND (DATE_FORMAT(employee_details.doj, '%m-%d') = '$today' OR DATE_FORMAT(employee_details.doj, '%m-%d') = '$previous_day_date')";
      }
   else {
   
   $employee_anniversary = "SELECT users.id as uid, users.name as name, users.email as email, employee_details.doj as doj 
   FROM users 
   INNER JOIN employee_details ON users.id = employee_details.user_id 
   WHERE users.company_id = 10 AND users.deleted = 1 AND users.status = 1 
      AND DATE_FORMAT(employee_details.doj, '%m-%d') = '$today'";
   }


   if($current_day_check=='Saturday' || $current_day_check=='Sunday' || in_array($today, $holidaydatas)) {
            echo 'Failed';
            exit();
   }

   // ==============Aniversary email query====================================

   $anni_query = mysqli_query($conn, $employee_anniversary);
   $anni_row = mysqli_num_rows($anni_query);

   if ($anni_row > 0) {
      $data1 = array();
      while ($row = mysqli_fetch_assoc($anni_query)) {

         $doj =$row['doj'];
         $uid = $row['uid'];
         $employeeJoinDate = $doj;
         $currentDate = date('Y-m-d');
         if($employeeJoinDate != $currentDate) {
         $joinDateObj = new DateTime($employeeJoinDate);
         $currentDateObj = new DateTime($currentDate);
         $interval = $currentDateObj->diff($joinDateObj);
         $yearsSinceJoining = $interval->y;
         $yearinword = convertToWords($yearsSinceJoining);
         if($yearsSinceJoining==1) {
            $year_str ='Year';
         } else {
            $year_str ='Years';
         }

         if($yearsSinceJoining==0) {
            echo "current year joining..";
            exit();
         }

         if (empty($row['email'])) {
            echo "Email missing for ".$row['name']."<br>";
            continue;
         }

         // echo $yearsSinceJoining;
         // $data1[] = $row;
         // echo "<pre>";
         // print_r($data1);
         // exit();

         $users="SELECT users.id as uid, users.name as name, users.email as email, employee_details.dob as dob, employee_details.doj as doj 
         FROM users 
         INNER JOIN employee_details ON users.id = employee_details.user_id 
         WHERE users.id != $uid AND users.company_id = 10 AND users.deleted = 1 AND users.status = 1";

         $user_query = mysqli_query($conn, $users);
         $rows = mysqli_fetch_all($user_query, MYSQLI_ASSOC);
         // echo "<pre>";
         // print_r($rows);
         // exit();

         if ($rows) {
            $bccEmails = [];
      
            foreach ($rows as $rowdata) {
               if (filter_var($rowdata['email'], FILTER_VALIDATE_EMAIL)) {
                  $bccEmails[] = $rowdata['email'];
               }
            }
            // $allBccEmails = implode(',', $bccEmails);
         }

         // var_dump($bccEmails);
         // exit();

         $path1 = 'https://pma.actiknow.com/assets/anniversary/'.$yearsSinceJoining.'.png';
	            $body = '
	                    <!DOCTYPE html>
	                  <html>
	                  <head></head>
	                  <body>
	                    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-family:arial!important;font-size:12px!important;margin:auto;">
	                      <tr>
	                        <td style="font-size: large; font-style: italic;">
                           <p><i style="font-size:12.8px;font-family:&quot;times new roman&quot;,serif"><b style="font-size:large"><font color="#cc0000">Dear '.$row['name'].',</font></b></i></p>
                           <p style="font-family:arial,sans-serif;font-size:13px;margin:0px"><i style="font-family:&quot;times new roman&quot;,serif"><font style="font-weight:bold;font-size:large">We would like to&nbsp;congratulate</font><font style="font-weight:bold;font-size:large">&nbsp;you&nbsp;on&nbsp;your&nbsp;<wbr>successful&nbsp;completion&nbsp;of&nbsp;</font><font color="#9900ff" style="font-weight:bold;font-size:large">'.ucwords($yearinword).'&nbsp;<wbr>'.$year_str.'</font><b><font color="#9900ff">&nbsp;</font></b><font style="font-weight:bold;font-size:large">with&nbsp;<font color="#9900ff">Actiknow</font></font><font style="font-weight:bold;font-size:large">.</font></i></p>
                             <p style="font-family:arial,sans-serif;font-size:13px;margin:0px"><i style="font-family:&quot;times new roman&quot;,serif"><span style="font-weight:bold;font-size:large;color:rgb(56,118,29)">Your contribution to the company is highly appreciated.</span></i><i style="font-family:&quot;times new roman&quot;,serif"><font style="font-weight:bold;font-size:large"><br></font></i></p>
	                         
	                        </td>
	                      </tr>
                         <tr>
	                        <td>
	                          <img src="'.$path1.'" style="height: auto; width:60%;">
	                          <br><br>
                             <span style="color: #000;">--<span>
	                        </td>
	                      </tr>
                         <tr>
	                        <td style="">
                           <span style="color:rgb(11,83,148);font-family:arial,helvetica,sans-serif">Best Regards,</span><br><br>
                           <font color="#0b5394"><b>ActiKnow Consulting Pvt Ltd</b><br><b>M:&nbsp;</b>011 45052711<br><b>Skype: </b>actiknow<br><b>W:&nbsp;</b></font>
                           <span style="color:rgb(34,34,34)">&nbsp;</span>
                           <a href="http://www.actiknow.com/" style="color:rgb(17,85,204);font-family:arial,helvetica,sans-serif;font-size:12.8px" target="_blank">www.actiknow.com</a>
	                        </td>
	                      </tr>
                         <tr>
                         <td>
                         <div dir="ltr"><p style="font-size:12.8px;"><font color="#0b5394"><b><img width="420" height="109" src="https://pma.actiknow.com/assets/footer_mail_logo.png" class="CToWUd a6T" data-bit="iit" tabindex="0"><div class="a6S" dir="ltr" style="opacity: 0.01; left: 380px; top: 847.656px;"><span data-is-tooltip-wrapper="true" class="a5q" jsaction="JIbuQc:.CLIENT"><button class="VYBDae-JX-I VYBDae-JX-I-ql-ay5-ays CgzRE" jscontroller="PIVayb" jsaction="click:h5M12e; clickmod:h5M12e;pointerdown:FEiYhc;pointerup:mF5Elf;pointerenter:EX0mI;pointerleave:vpvbp;pointercancel:xyn4sd;contextmenu:xexox;focus:h06R8; blur:zjh6rb;mlnRJb:fLiPzd;" data-idom-class="CgzRE" jsname="hRZeKc" aria-label="Download attachment " data-tooltip-enabled="true" data-tooltip-id="tt-c8" data-tooltip-classes="AZPksf" id="" jslog="91252; u014N:cOuCgd,Kr2w4b,xr6bB; 4:WyIjbXNnLWY6MTc5NzY0NTc5NDY3MjkxNDY3MiJd; 43:WyJpbWFnZS9qcGVnIl0."><span class="OiePBf-zPjgPe VYBDae-JX-UHGRz"></span><span class="bHC-Q" data-unbounded="false" jscontroller="LBaJxb" jsname="m9ZlFb" soy-skip="" ssk="6:RWVI5c"></span><span class="VYBDae-JX-ank-Rtc0Jf" jsname="S5tZuc" aria-hidden="true"><span class="bzc-ank" aria-hidden="true"><svg height="20" viewBox="0 -960 960 960" width="20" focusable="false" class=" aoH"><path d="M480-336 288-528l51-51 105 105v-342h72v342l105-105 51 51-192 192ZM263.72-192Q234-192 213-213.15T192-264v-72h72v72h432v-72h72v72q0 29.7-21.16 50.85Q725.68-192 695.96-192H263.72Z"></path></svg></span></span><div class="VYBDae-JX-ano"></div></button><div class="ne2Ple-oshW8e-J9" id="tt-c8" role="tooltip" aria-hidden="true">Download</div></span></div><br></b></font></p><p style="font-size:12.8px"><font color="#0b5394"><span style="font-size:9pt;font-family:Arial,sans-serif"></span></font></p><div style="font-size:12.8px"><span style="font-size:9pt;font-family:Arial,sans-serif"><hr width="100%" size="1" align="left" noshade="" style="color:black"></span></div><p style="font-size:12.8px"></p><p style="font-size:12.8px"><span style="color:black"><font size="1">This message is intended only for the named recipient and may contain confidential, proprietary or legally privileged information. Unauthorized individuals or entities are not permitted access to this information. Any dissemination, distribution, or copying of this information is strictly prohibited. If you have received this message in error, please advise the sender by reply e-mail, and delete this message and any attachments</font></span></p></div>
                         </td>
                         </tr>
	                    </table>
	                    
	                  </body>
	                  </html>';


	               // $mail = new PHPMailer;
                  $mail = new PHPMailer(true);
						$mail->isSMTP();
						$mail->SMTPDebug = 2;
						$mail->Debugoutput = 'html';
						$mail->Host = 'smtp.gmail.com';
						$mail->Port = 587;

						$mail->SMTPSecure = 'tls';
						$mail->SMTPAuth = true;
						$mail->Username = 'notifications@actiknow.com';
						$mail->Password = 'zang kfmd pqkp spel';
						$mail->setFrom('noreply.n-chatbot@actiknow.com', 'Admin Actiknow');
						$mail->Subject = 'Congratulations for Completion of '.$yearsSinceJoining.' '.$year_str.' - '.strtoupper($row['name']);
						$mail->msgHTML($body);
						$mail->addAddress($row['email'],$row['name']);

                  foreach ($bccEmails as $bcc) {
                     $mail->AddBCC($bcc);
                  }

						// if($mail->send()) {
                  //    echo "done";
                  // }

                  try {
                     if($mail->send()) {
                        echo 'done';
                     };
                     
                  } catch (Exception $e) {
                     echo "Error for ".$row['name']." : ".$mail->ErrorInfo;
                  }
         }
      }
   }

   // echo "<pre>";
   // print_r($anni_row);
   // exit();
   
   // ===================/Aniversary email query ===========================
} else {

   echo "DB connection failed...";
}
