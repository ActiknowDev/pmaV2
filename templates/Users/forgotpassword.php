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
            <div class="row">
            <div class="col-md-8">
            <h4 class="title mb-3">Forgot Password</h4>
            </div>
            <div class="col-md-4 text-right">
            <p><?php echo $this->Html->link('Login','/Users/login');?></p>
            </div>
            </div>
            <!-- <h4 class="title mb-4">Forgot Password   </h4> -->
            <?= $this->Form->create(null,array('id'=>'email')) ?>
            <div class="form-fields">
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="example@gmail.com"  name="email" autocomplete="off" value="<?php if(isset($_POST['email'])){echo $_POST['email'];} ?>">
                 </div>
                <div class="form-group">
                    <?php
                        echo $this->Form->button('Submit',['class'=>'v-btn v-btn-primary mr-5']);
                        // echo $this->Form->button('login',["controller"=>"Users","action"=>"login",'class'=>'v-btn v-btn-primary text-right']);
                        
                        echo $this->Form->end();
                    ?>
                </div>   
            </div>
        </div>
    </div>
</div>

