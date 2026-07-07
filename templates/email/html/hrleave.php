<!-- <h1>Apply Leave</h1> -->

<p>A new <?= $leaveType ?> request has been submitted and is pending your review.</p>
<p>
    <strong>Leave Applicant:</strong>
    <?= $name ?>
</p>
<p>
    <strong>Request Submitted By:</strong>
    <?= $appliedByHr ?>
</p>
<p><strong>Leave Deduction Details:</strong> </p>
   <?php
   foreach($cut_leave as $key=>$value){
      if($value>0) {
         echo "<p>".$key." - ". round($value,2) ."</p>";
      }
   }
   ?>
   <br>
   <p>Please log in to your <a href="https://pma.actiknow.com/" target="_blank"> PMA </a> account to review the complete leave request, including dates, comments, and approval options.</p>
   <p><strong>Thank you.</strong></p>
   <br>
   <p><strong>PMA Leave Management System</strong></p>
</p>