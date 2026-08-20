<?php
require_once('/var/www/html/nodom/common/PHPMailerAutoload.php');
$conn = new mysqli('localhost', 'root', '2343Rtrtt4565@#rtrtlotn', 'sumit_db');

$input = fopen('/var/www/html/pma/webroot/test_data.csv', 'r');
$ctr = 0;
while (false !== ( $data = fgetcsv($input) )) {  //read each line as an array
    
    if ($ctr != 0) { 

    	 $date = date('Y-m-d H:i:s',$data[2]); 

    	echo $sql = "INSERT INTO actions SET action_ts='".$date."',user_id=".$data[0].",session_id='".$data[1]."',action='".$data[3]."'";

		mysqli_query($conn, $sql);
}
$ctr++;
}

?>