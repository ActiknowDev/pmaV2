<?php
// require_once('/var/www/html/pma/webroot/common/PHPMailerAutoload.php');
$conn = new mysqli('localhost', 'root', 'Acti@n8n@know25', 'pma');
// $conn = new mysqli('localhost', 'root', '', 'pma');

$date = date('Y-m-d');

echo $sql = "SELECT el,cl,sl,id FROM users WHERE role= 3 and status=1 and emp_type=1 AND deleted=1";
$user = mysqli_query($conn, $sql);
if(mysqli_num_rows($user) > 0)
{
    
    while($r = mysqli_fetch_assoc($user))
    {
        $sql1 = "SELECT SUM(el) as leave_el FROM `leave_counting` WHERE user_id=".$r['id'];
        $userleave = mysqli_query($conn, $sql1);
        $ur = mysqli_fetch_assoc($userleave);
        $leave = $ur['leave_el'];
        $total = $r['el']-$leave;
        $total=number_format($total,2);
        // echo "<br>";
        // echo $total;
        // exit();
       
        $userid = $r['id'];
        $el = $r['el'];
        $cl = $r['cl'];
        $sl = $r['sl'];
        $pre_el=$el;
        $pre_cl=$cl;
        $pre_sl=$sl;
        $add_cl = 0;
        $add_sl = 0;
        $add_el = 0;
        

            if(($date == date('Y-04-01')) || ($date == date('Y-05-01')) || ($date == date('Y-06-01')) || ($date == date('Y-07-01')))
            {
                $add_el = -0.66;
            } elseif(($date == date('Y-08-01')) || ($date == date('Y-09-01')) || ($date == date('Y-10-01')) || ($date == date('Y-11-01')) || ($date == date('Y-12-01')) || ($date == date('Y-01-01')) || ($date == date('Y-02-01')) || ($date == date('Y-03-01'))) {
               
                $add_el = -0.67;
            }

            if($date == date('Y-05-01'))
            {
                $add_sl = -1;
            }

            if($date == date('Y-04-01'))
            {
                $add_cl = -1;
            }
            elseif(($date == date('Y-06-01')) || ($date == date('Y-07-01')) || ($date == date('Y-09-01')) || ($date == date('Y-10-01')) || ($date == date('Y-12-01')) || ($date == date('Y-01-01')) || ($date == date('Y-03-01')))
            {
                $add_cl = -1;
            }
            elseif(($date == date('Y-08-01')) || ($date == date('Y-11-01')) || ($date == date('Y-02-01')))
            {
                $add_sl = -1;
            }

            if($total>30) {
                $add_el = 0;
            } else {
                $add_el = $add_el;
            }
            $addon_el = ($el-$pre_el);
            $addon_cl = ($cl-$pre_cl);
            $addon_sl = ($sl-$pre_sl);

            $total_el = ($pre_el+$addon_el);
            $total_cl = ($pre_cl+$addon_cl);
            $total_sl = ($pre_sl+$addon_sl);
            
            // echo $sql = "UPDATE users SET el = ".$el.",cl = ".$cl.",sl = ".$sl." WHERE id = ".$r['id']; 
            // mysqli_query($conn, $sql);

            echo $insert_leave = "INSERT INTO `leave_counting` (`user_id`, `leave_id`, `cl`, `sl`, `el`, `leave_desc`, `leave_date`) VALUES ($userid, '0', $add_cl, $add_sl, $add_el, 'Addon leave by Cron','$date')";
            mysqli_query($conn, $insert_leave);

            echo $insert_log = "INSERT INTO `employee_leaves_logs` (`user_id`, `user_el`, `user_cl`, `user_sl`, `el`, `cl`, `sl`, `total_el`, `total_cl`, `total_sl`, `addon_date`) VALUES ($userid, $pre_el, $pre_cl, $pre_sl, $addon_el, $addon_cl, $addon_sl, $total_el, $total_cl, $total_sl, '$date')";
            mysqli_query($conn, $insert_log);

    }

    
     
}



?>