<?php
$db =   new mysqli('localhost','root','2343Rtrtt4565@#rtrtlotn','pma');
if ($db->connect_errno) {
  echo "Failed to connect to MySQL: " . $db->connect_error;
  exit();
}
 
if(($handle     =   fopen("Leaves.csv", "r")) !== FALSE){
    while(($row =   fgetcsv($handle)) !== FALSE){
        echo 'UPDATE `users` SET el = '.$row[1].', cl='.$row[2].',sl='.$row[3].',comp_off='.$row[4].' WHERE id='.$row[5];
        $db->query('UPDATE `users` SET el = '.$row[1].', cl='.$row[2].',sl='.$row[3].',comp_off='.$row[4].' WHERE id='.$row[5]);
    }
    fclose($handle);
}
?>