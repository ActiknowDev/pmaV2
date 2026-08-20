<?php

require __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if (date('N') != 6 && date('N') != 7) {
    $conn = mysqli_connect('localhost', 'root', 'Acti@n8n@know25', 'pma');
    $today =  Date("Y-m-d");
    // $today =  '2023-03-23';//Date("2023-03-15");

    $query = "SELECT * from emp_punch_time where `dom` = '$today' GROUP BY emp";
    $todayData = mysqli_query($conn, $query);


    $fileName = '/var/www/html/pmaV2/webroot/emp_punch/' . 'emp_punch_' . $today . ".csv";

    $file = fopen($fileName, 'w');
    fputcsv($file, ['S.No.', 'Employee Name', 'Date'], ',');

    if (mysqli_num_rows($todayData) > 0) {

        $x = 1;
        $row = [];
        while ($data = mysqli_fetch_assoc($todayData)) {
            $row = [
                $x,
                $data['emp'],
                date("Y-m-d", strtotime($data['dom']))
            ];

            if (count($row) > 0) {
                fputcsv($file, $row, ',');
                ++$x;
            }
        }
        fclose($file);



        $mail = new PHPMailer(true);

        //Enable SMTP debugging.
        $mail->SMTPDebug = 3;
        //Set PHPMailer to use SMTP.
        $mail->isSMTP();
        //Set SMTP host name                          
        $mail->Host = "smtp.gmail.com";
        //Set this to true if SMTP host requires authentication to send email
        $mail->SMTPAuth = true;
        //Provide username and password     
        $mail->Username = 'notifications@actiknow.com';
        $mail->Password = 'zang kfmd pqkp spel';
        //If SMTP requires TLS encryption then set it
        $mail->SMTPSecure = "tls";
        //Set TCP port to connect to
        $mail->Port = 587;

        $mail->From = "noreply.n-chatbot@actiknow.com";
        $mail->FromName = "PMA";

        // $mail->addAddress("himani.duhan@actiknow.com", "Recepient Name");
        $mail->addAddress("himani.duhan@actiknow.com", "Himani Duhan");

        $mail->addAddress("seema.rawat@actiknow.com", "Seema Rawat");
        $mail->addAttachment("$fileName");

        $mail->isHTML(true);

        $mail->Subject = "Today Employee Present $today";
        $mail->Body = "<i>Employee data in CSV file</i>";
        $mail->AltBody = "This is the plain text version of the email content";

        try {
            $mail->send();
            echo "Message has been sent successfully";
        } catch (Exception $e) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    }
}
