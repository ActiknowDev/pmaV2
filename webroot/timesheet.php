<?php
// require_once('/var/www/html/pma/webroot/common/PHPMailerAutoload.php');
require __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$conn = new mysqli('localhost', 'root', 'Acti@n8n@know25', 'pma');


if(date('N') != 6 && date('N') != 7)
{
		$date = date('Y-m-d');
	 $sql = "SELECT name,email FROM users WHERE id NOT IN (SELECT resource_id FROM user_timesheets WHERE work_date ='{$date}') AND role= 3 and status=1 and emp_type=1 AND deleted=1"; 
	$user = mysqli_query($conn, $sql);
	if(mysqli_num_rows($user) > 0)
	{
		
		while($r = mysqli_fetch_assoc($user))
		{
				$path = 'https://pma.actiknow.com/actiknow-new-logo.png';
	            $body = '
	                    <!DOCTYPE html>
	                  <html>
	                  <head></head>
	                  <body>
	                    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-family:arial!important;font-size:12px!important;margin:auto;">
	                      <tr>
	                        <td align=center>
	                          <img src="'.$path.'" style="height: 67px;">
	                          <hr>
	                          
	                        </td>
	                      </tr>
	                      
	                      <tr>
	                        <td style="font-size: 14px;">
	                          <p style="word-spacing: 3px;">Hi '.$r['name'].', </p>
	                          <p style="word-spacing: 3px;">Just a gentle reminder that you have not completely filled (8 Hrs) in your timesheet for today. Kindly fill the Timesheet ASAP.</p>
	                         
	                          <p style="word-spacing: 3px;">Best,<br>PMA Team</p>
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
						$mail->setFrom('noreply.n-chatbot@actiknow.com', 'pma');
						$mail->Subject = 'Timesheet Reminder';
						$mail->msgHTML($body);
						$mail->addAddress($r['email']);
						if($mail->send())
						 echo "done";
						else
						 echo $mail->ErrorInfo;
		}

	}
}


// $body = 'hello jyotima';
// $mail = new PHPMailer;
// $mail->isSMTP();
// $mail->SMTPDebug = 2;
// $mail->Debugoutput = 'html';
// $mail->Host = 'smtp.gmail.com';
// $mail->Port = 587;

// $mail->SMTPSecure = 'tls';
// $mail->SMTPAuth = true;
// $mail->Username = 'notifications@actiknow.com';
// $mail->Password = 'password';
// $mail->setFrom('noreply.n-chatbot@actiknow.com', 'n-chatbot');
// $mail->Subject = 'ChatBot user issue';
// $mail->msgHTML($body);
// $mail->addAddress('jyotima.tripathi@actiknow.com');
// if($mail->send())
//  echo "done";
// else
//  echo $mail->ErrorInfo;
?>