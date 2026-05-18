
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
            <h4 class="title mb-4">Reset Password</h4>
            <?= $this->Form->create(null,["type"=>"post"]) ?>
            <div class="form-fields">
                <div class="form-group">
        
                <input type="password" class="form-control" placeholder="New Password" required="required" name="password">
                <br>
                <input type="password" class="form-control" placeholder="Confirm  Password" required="required" name="confirmpassword">
                 </div>
                        <div class="form-group">
                       <?php
                        echo $this->Form->button('reset password',['class'=>'v-btn v-btn-primary']);
                        echo $this->Form->end();
                       ?>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
































































