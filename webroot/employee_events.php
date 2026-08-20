<?php
error_reporting(0);
require __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$conn = mysqli_connect("localhost", "root", "Acti@n8n@know25", "pma");
// $conn = mysqli_connect("localhost", "root", "", "pma");

if ($conn) {
   
   $holidaydatas = [];

   // ================== Fetch Holidays ==================
   $holidays = "SELECT start FROM holidays WHERE deleted = 0";
   $holiday_query = mysqli_query($conn, $holidays);

   while ($holidaydata = mysqli_fetch_assoc($holiday_query)) {
      $holidaydatas[] = date('m-d', strtotime($holidaydata['start']));
   }

   // ================== Current Day Check ==================
   $current_day = date('l');
   $today_md = date('m-d');

   // ❌ Do nothing on non-working day
   if ($current_day == 'Saturday' || $current_day == 'Sunday' || in_array($today_md, $holidaydatas)) {
      echo 'Failed';
      exit();
   }

   // ================== Build Birthday Date List ==================
   $check_dates = [];
   $today = new DateTime();
   $check_dates[] = $today->format('m-d');

   $loop_date = clone $today;

   while (true) {
      $loop_date->modify('-1 day');

      $day_name = $loop_date->format('l');
      $md = $loop_date->format('m-d');

      // If holiday OR weekend → include
      if ($day_name == 'Saturday' || $day_name == 'Sunday' || in_array($md, $holidaydatas)) {
         $check_dates[] = $md;
      } else {
         break; // stop at last working day
      }
   }

   // ================== Build SQL Conditions ==================
   $date_conditions = [];
   foreach ($check_dates as $date) {
      $date_conditions[] = "DATE_FORMAT(employee_details.email_dob, '%m-%d') = '$date'";
   }

   $date_sql = implode(' OR ', $date_conditions);

   // ================== Final Birthday Query ==================
   $employee_birthday = "
   SELECT 
      users.id AS uid,
      users.name,
      users.email,
      employee_details.email_dob AS dob,
      employee_details.doj AS doj
   FROM users
   INNER JOIN employee_details ON users.id = employee_details.user_id
   WHERE users.company_id = 10
   AND users.deleted = 1
   AND users.status = 1
   AND ($date_sql)
   ";

   // ================== Execute ==================
   $query = mysqli_query($conn, $employee_birthday);

   // var_dump($query);
   // exit();

   if (!$query) {
      die("Query failed: " . mysqli_error($conn));
   }

   $row = mysqli_num_rows($query);
   if ($row > 0) {
      $data = array();

      while ($row = mysqli_fetch_assoc($query)) {
        $dob = $row['dob'];
        $uid = $row['uid'];
        $dob_date = date('d', strtotime($dob));
        $current_date = date('d');

        if ($dob_date != $current_date) {
            $beleted = 'belated';
        } else {
            $beleted = 'very';
        }

        if (empty($row['email'])) {
            echo "Email missing for ".$row['name']."<br>";
            continue;
         }

   $image_array = array("https://pma.actiknow.com/assets/Birthday/1.jpg", "https://pma.actiknow.com/assets/Birthday/2.jpg", "https://pma.actiknow.com/assets/Birthday/3.jpg", "https://pma.actiknow.com/assets/Birthday/4.jpg", "https://pma.actiknow.com/assets/Birthday/5.jpg","https://pma.actiknow.com/assets/Birthday/6.jpg","https://pma.actiknow.com/assets/Birthday/7.jpg","https://pma.actiknow.com/assets/Birthday/8.jpg","https://pma.actiknow.com/assets/Birthday/9.jpg","https://pma.actiknow.com/assets/Birthday/10.jpg","https://pma.actiknow.com/assets/Birthday/11.jpg","https://pma.actiknow.com/assets/Birthday/12.jpg","https://pma.actiknow.com/assets/Birthday/13.jpg","https://pma.actiknow.com/assets/Birthday/14.jpg","https://pma.actiknow.com/assets/Birthday/15.jpg","https://pma.actiknow.com/assets/Birthday/16.jpg","https://pma.actiknow.com/assets/Birthday/17.jpg","https://pma.actiknow.com/assets/Birthday/18.jpg","https://pma.actiknow.com/assets/Birthday/19.jpg");
   $randomImage = array_rand($image_array);
   $randomImageGet = $image_array[$randomImage];

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
   // $bccEmails = ['devendra.singh@actiknow.com','devendra.singh+1@actiknow.com','seema.rawat@actiknow.com','pinkey.yadav@actiknowbi.com'];
      // var_dump($bccEmails);
      // exit();
         $path = $randomImageGet;
	            $body = '
	                    <!DOCTYPE html>
	                  <html>
	                  <head></head>
	                  <body>
	                    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-family:arial!important;font-size:12px!important;margin:auto;">
	                      <tr>
	                        <td style="font-size: large;">
	                          <p style="color:rgb(204,0,0);font-family:&quot;comic sans ms&quot;,sans-serif;font-size:large;font-weight: bold;">Dear All, </p>
	                          <p style="font-family:&quot;comic sans ms&quot;,sans-serif;font-weight: bold;">Please join me in wishing <span style="color:#ff9900;">'.$row['name'].'</span> a '.$beleted.' <span style="color:#9900ff;">"HAPPY BIRTHDAY"</span>.</p>
	                         
	                        </td>
	                      </tr>
                         <tr>
	                        <td>
	                          <img src="'.$path.'" style="height: auto; width:60%;">
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
						$mail->Subject = 'BIRTHDAY WISHES - '.strtoupper($row['name']);
						$mail->msgHTML($body);
						$mail->addAddress($row['email'],$row['name']);

                  foreach ($bccEmails as $bcc) {
                     $mail->AddBCC($bcc);
                  }
                  
						try {
                     if($mail->send()) {
                        echo 'done';
                     };
                     
                  } catch (Exception $e) {
                     echo "Error for ".$row['name']." : ".$mail->ErrorInfo;
                  }
      }
   
      // echo "<pre>";
      // print_r($data);
   }

   // ===================/Birthday Emails========================================
} else {

   echo "DB connection failed...";
}
