<!-- <h1>Apply Leave</h1> -->

<p>You have a new <?= $reason ?> <?= $leaveType ?> from <?= $name ?>.</p>
<p>Leaves deducted from - </p>
   <?php
   foreach($cut_leave as $key=>$value){
      if($value>0) {
         echo "<p>".$key." - ". round($value,2) ."</p>";
      }
   }
   ?>
   <br>
  <p> Please check your<a href="http://54.214.147.102/pmaV2/leaves/requestleave" target="_blank"> PMA </a>
   account for more info.
</p>