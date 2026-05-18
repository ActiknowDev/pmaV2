<div class="page page-auth">
    <div class="auth-wrap">
        <div class="auth-item content">
            <div class="logo logo-md logo-light">
                <span>PMA</span>
                <p class="tagline">Assign | Track | Report</p>
            </div>
            <p class="copyright">copyright @actiknow.com</p>
        </div>
        <div class="auth-item form">
            <a href="/cakeproject/" class="icon mb-4"><i class="fa fa-arrow-left"></i></a>
            <h4 class="title mb-4">Sign Up</h4>
            <?= $this->Form->create($user,array('id'=>'login')) ?>
            <div class="form-fields">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="example@gmail.com" name="email">
                    <input type="hidden" class="form-control" placeholder="example@gmail.com" name="password" value="password">
                </div>
                <div class="form-group text-md-right">
                    <button type='submit' class="v-btn v-btn-primary">Let's Get Started</button>
                </div>
            </div>
           <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script> 
 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>  
 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
<script>
   if ($("#login").length > 0) {
      $("#login").validate({
      
    rules: {
      
      email: {
        required: true,
        maxlength: 50,
        email: true,
      },   
    },
    messages: {
        
      
      email: {
        required: "Please enter valid email",
        email: "Please enter valid email",
        maxlength: "The email name should less than or equal to 50 characters",
        }, 
    },
  })
}
</script>