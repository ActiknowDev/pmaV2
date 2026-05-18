<section class="page page-title">
   <div class="container">
      <div class="row">
         <div class="col-12">
            <h1 class="heading ft-dark"> Change Password</h1>
         </div>
      </div>
   </div>
</section>
<section class="page page-dashboard">
   <div class="container">
      <div class="col-md-4 offset-md-4">
         <div class="block mt-4">
            <?= $this->Form->create(null,array('id'=>'ajax_form')) ?>
            <div class="content">
                <?= $this->Flash->render() ?>
               <div class="form-group">
                  <label for="">Old Password</label>
                  <input type="text" class="form-control" id="old" autocomplete="off">
                  <span class="error old_password"></span>
               </div>
               <div class="form-group">
                  <label for="">New Password</label>
                  <input type="text" class="form-control input-password" name="password" id="password" autocomplete="off">
               </div>
               <div class="form-group">
                  <label for="">Confirm Password</label>
                  <input type="text" class="form-control cnf" name="cnpwd" id="cnpwd" autocomplete="off">
                  <span class="error cnf_password"></span>
               </div>
               <div class="form-group">
                  <button type="submit" class="v-btn v-btn-primary" id="edituser">Change Password</button>
               </div>
            </div>
             <?= $this->Form->end() ?>
         </div>
      </div>
   </div>
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>

   //to match old password
    $(document).ready(function () {

         $('.input-password').focus(function() {

            var old = $('#old').val();
            $.ajax({
                  
                   type:'GET',
                   url:"<?= $this->Url->build('/users/matchpwd/'); ?>"+old,
                  
                   beforeSend: function ()
                   {
                     
                   },
                       success:function(data){
                        var response = $.parseJSON(data);
                        if(response.result==false)
                        {
                            $('.old_password').html('Please Enter Correct Password');
                            $( "#old" ).val('');
                            $( "#old" ).focus();
                        }
                        // console.log(data);
                   }
               });
            
        });

         $('.cnf').focusout(function() {

            var pwd = $('#password').val();
            var cnfpwd = $('#cnpwd').val();
            if(pwd !== cnfpwd)
            {
                $('.cnf_password').html('Confirm password is not same as password');
                $( "#cnpwd" ).val('');
                $( "#cnpwd" ).focus();
                e.preventdefault();
            }
            
        });
    }); 

   </script>