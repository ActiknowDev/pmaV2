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
            <?= $this->Flash->render() ?>
            <h4 class="title mb-4">Sign In</h4>
            <?= $this->Form->create(null,array('id'=>'login')) ?>
            <div class="form-fields">
                <!-- <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    Check Email id & password.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> -->
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="example@gmail.com" required="required" name="email" autocomplete="off" value="<?php if(isset($_POST['email'])){echo $_POST['email'];} ?>">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" required="required" placeholder="Password" name="password">
                </div>
                <div class="form-group text-md-right action-field">
                    <!-- <a href="#">  Forgot Password? </a> -->
                    <?=$this->Html->link('Forgot Password?',['class'=>'link','controller'=>'Users','action'=>'forgotpassword'])?>
                    <button type='submit' class="v-btn v-btn-primary">Sign in</button>
                </div>
            </div>
             <?= $this->Form->end() ?>
            
            <!-- <span class="v-btn v-btn-acent btn-block btn-lg mt-5">Don't have an account?  <?= $this->Html->link('Sign up','/signup',['class' => 'fw fw-bold v-btn v-btn-acent']); ?></span> -->
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
      password: {
        required: true,
      },
  
      email: {
        required: true,
        maxlength: 50,
        email: true,
      },   
    },
    messages: {
        
      password: {
        required: "Please enter password",
      },
      email: {
        required: "Please enter valid email",
        email: "Please enter valid email",
        maxlength: "The email name should less than or equal to 50 characters",
        }, 
    },
  })
}
</script>