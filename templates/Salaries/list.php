<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-user-tie"></i></span>Expense Structure
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?= $this->Flash->render() ?>
                    <div class="block">
                    <?= $this->Form->create([],[
                                            'url' => [
                                                'Controller' => 'Salaries',
                                                'action' => 'list'
                                            ],
                                            'id' => 'cost-form'
                                            //'style' => "display: inline-flex;"
                                        ]
                                    
                                    ) 
                                
                            ?>
                        <div class="header row">
                            
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <h4 class="title">Expenses Details</h4>
                                
                            </div>
                            
                            <div class="col-lg-3 col-md-6 col-sm-12 align-bottom text-right">
                                <select class="form-control" onChange="this.form.submit()" name="year">
                                    <?php foreach($yearlist as $y):?>
                                        <option value="<?= $y?>" <?= $y == $this->request->getSession()->read('cost.year') ? 'selected' : '';?> ><?= $y?></option>
                                    <?php endforeach?>
                                </select>
                            </div>
                             <div class="col-lg-3 col-md-6 col-sm-12 text-right">
                                
                                    <?= $this->Form->input('k',[
                                                        'class' => 'form-control',
                                                        'label' => false,
                                                        'div' => false,
                                                        'type' => 'password',
                                                        'placeholder' =>  'Key',
                                                        'value' => isset($key) ? $key : ''
                                                    ]
                                                );
                                    ?>
                                    <div class="align">
                                        <button type="submit" class="v-btn v-btn-primary ml-2">Decrypt/Ecrypt Key</button>
                                        <a type="button" class="v-btn v-btn-primary ml-2" href="<?=$this->Url->build('/Salaries/clear', ['fullBase' => true]);?>">Clear</a>
                                    </div>
                               
                            </div>
                            
                        </div>
                        <?= $this->Form->end() ?>
                        <div class="content ">
                            <table class="table table-default table-according table-timesheet table-responsive">
                                 <!-- <table id="example" style="width:100%" class="table table-default table-striped block table-timesheet"> -->
                                <thead>
                                    <tr>
                                        <th>Employee Name</th>
                                        <th>Jan</th>
                                        <th>Feb</th>
                                        <th>Mar</th>
                                        <th>Apr</th>
                                        <th>May</th>
                                        <th>Jun</th>
                                        <th>Jul</th>
                                        <th>Aug</th>
                                        <th>Sep</th>
                                        <th>Oct</th>
                                        <th>Nov</th>
                                        <th>Dec</th>
                                     
                                    </tr>
                                </thead>
                                    <tbody>
                                        <?php 
                                        $i = 1;
                                        foreach ($expenselist as $list): 
                                        ?>
                                        <tr>
                                        <td><?= isset($list->name) ? $list->name : '-';  ?></td>
                                        <?php
                                                $months = array("1", "2", "3", "4","5","6","7","8","9","10","11","12");
                                                foreach($months as $month)
                                                {
                                                    ${'month'. $month}=0;
                                                }
                                                if($list->expense)
                                                {   
                                                   
                                                    foreach($list->expense as $expense)
                                                    {
                                                       
                                                        if(in_array($expense->month, $months) && $key)
                                                        {
                                                            
                                                         
                                                            ${'month'.$expense->month} =isset($key) ? openssl_decrypt($expense->amount,"AES-128-ECB",$key) : $expense->amount;
                                                            
                                                            //${'month'.$expense->month} = $expense->amount;
                                                        }
                                                    }
                                                }
                                            
                                      
                                        
                                        ?>
                                        <td><input type="text" value="<?php
                                                    if($month1 == 0 || empty($month1)){
                                                        if($key && isset($list->salary->amount)){
                                                            $sal = openssl_decrypt($list->salary->amount,"AES-128-ECB",$key);
                                                            echo empty($sal) ? 0 : $sal;
                                                        }else{
                                                            echo $month1;
                                                        }
                                                    }else{
                                                        echo $month1;
                                                    }
                                                ?>" class="form-control cost-inp" month="1" year="<?= $year?>" u-id="<?= $list->id?>"/>
                                        </td>
                                        <td><input type="text" value="<?php
                                                    if($month2 == 0 || empty($month2)){
                                                        if($key && isset($list->salary->amount)){
                                                            $sal = openssl_decrypt($list->salary->amount,"AES-128-ECB",$key);
                                                            echo empty($sal) ? 0 : $sal;
                                                        }else{
                                                            echo $month2;
                                                        }
                                                    }else{
                                                        echo $month2;
                                                    }
                                                ?>" class="form-control cost-inp" month="2" year="<?= $year?>" u-id="<?= $list->id?>"/>
                                        </td>
                                        <td><input type="text" value="<?php
                                                    if($month3 == 0 || empty($month3)){
                                                        if($key && isset($list->salary->amount)){
                                                            $sal = openssl_decrypt($list->salary->amount,"AES-128-ECB",$key);
                                                            echo empty($sal) ? 0 : $sal;
                                                        }else{
                                                            echo $month3;
                                                        }
                                                    }else{
                                                        echo $month3;
                                                    }
                                                ?>" class="form-control cost-inp" month="3" year="<?= $year?>" u-id="<?= $list->id?>"/>
                                        </td>
                                        <td><input type="text" value="<?php
                                                    if($month4 == 0 || empty($month4)){
                                                        if($key && isset($list->salary->amount)){
                                                            $sal = openssl_decrypt($list->salary->amount,"AES-128-ECB",$key);
                                                            echo empty($sal) ? 0 : $sal;
                                                        }else{
                                                            echo $month4;
                                                        }
                                                    }else{
                                                        echo $month4;
                                                    }
                                                ?>" class="form-control cost-inp" month="4" year="<?= $year?>" u-id="<?= $list->id?>"/>
                                        </td>
                                        <td><input type="text" value="<?php
                                                    if($month5 == 0 || empty($month5)){
                                                        if($key && isset($list->salary->amount)){
                                                            $sal = openssl_decrypt($list->salary->amount,"AES-128-ECB",$key);
                                                            echo empty($sal) ? 0 : $sal;
                                                        }else{
                                                            echo $month5;
                                                        }
                                                    }else{
                                                        echo $month5;
                                                    }
                                                ?>" class="form-control cost-inp" month="5" year="<?= $year?>" u-id="<?= $list->id?>"/>
                                        </td>
                                        <td><input type="text" value="<?php
                                                    if($month6 == 0 || empty($month6)){
                                                        if($key && isset($list->salary->amount)){
                                                            $sal = openssl_decrypt($list->salary->amount,"AES-128-ECB",$key);
                                                            echo empty($sal) ? 0 : $sal;
                                                        }else{
                                                            echo $month6;
                                                        }
                                                    }else{
                                                        echo $month6;
                                                    }
                                                ?>" class="form-control cost-inp" month="6" year="<?= $year?>" u-id="<?= $list->id?>"/>
                                        </td>
                                        <td><input type="text" value="<?php
                                                    if($month7 == 0 || empty($month7)){
                                                        if($key && isset($list->salary->amount)){
                                                            $sal = openssl_decrypt($list->salary->amount,"AES-128-ECB",$key);
                                                            echo empty($sal) ? 0 : $sal;
                                                        }else{
                                                            echo $month7;
                                                        }
                                                    }else{
                                                        echo $month7;
                                                    }
                                                ?>" class="form-control cost-inp" month="7" year="<?= $year?>" u-id="<?= $list->id?>"/>
                                        </td>
                                        <td><input type="text" value="<?php
                                                    if($month8 == 0 || empty($month8)){
                                                        if($key && isset($list->salary->amount)){
                                                            $sal = openssl_decrypt($list->salary->amount,"AES-128-ECB",$key);
                                                            echo empty($sal) ? 0 : $sal;
                                                        }else{
                                                            echo $month8;
                                                        }
                                                    }else{
                                                        echo $month8;
                                                    }
                                                ?>" class="form-control cost-inp" month="8" year="<?= $year?>" u-id="<?= $list->id?>"/>
                                        </td>
                                        <td><input type="text" value="<?php
                                                    if($month9 == 0 || empty($month9)){
                                                        if($key && isset($list->salary->amount)){
                                                            $sal = openssl_decrypt($list->salary->amount,"AES-128-ECB",$key);
                                                            echo empty($sal) ? 0 : $sal;
                                                        }else{
                                                            echo $month9;
                                                        }
                                                    }else{
                                                        echo $month9;
                                                    }
                                                ?>" class="form-control cost-inp" month="9" year="<?= $year?>" u-id="<?= $list->id?>"/>
                                        </td>
                                        <td><input type="text" value="<?php
                                                    if($month10 == 0 || empty($month10)){
                                                        if($key && isset($list->salary->amount)){
                                                            $sal = openssl_decrypt($list->salary->amount,"AES-128-ECB",$key);
                                                            echo empty($sal) ? 0 : $sal;
                                                        }else{
                                                            echo $month10;
                                                        }
                                                    }else{
                                                        echo $month10;
                                                    }
                                                ?>" class="form-control cost-inp" month="10" year="<?= $year?>" u-id="<?= $list->id?>"/>
                                        </td>
                                        <td><input type="text" value="<?php
                                                    if($month11 == 0 || empty($month11)){
                                                        if($key && isset($list->salary->amount)){
                                                            $sal = openssl_decrypt($list->salary->amount,"AES-128-ECB",$key);
                                                            echo empty($sal) ? 0 : $sal;
                                                        }else{
                                                            echo $month11;
                                                        }
                                                    }else{
                                                        echo $month11;
                                                    }
                                                ?>" class="form-control cost-inp" month="11" year="<?= $year?>" u-id="<?= $list->id?>"/>
                                        </td>
                                        <td><input type="text" value="<?php
                                                    if($month12 == 0 || empty($month12)){
                                                        if($key && isset($list->salary->amount)){
                                                            $sal = openssl_decrypt($list->salary->amount,"AES-128-ECB",$key);
                                                            echo empty($sal) ? 0 : $sal;
                                                        }else{
                                                            echo $month12;
                                                        }
                                                    }else{
                                                        echo $month12;
                                                    }
                                                ?>" class="form-control cost-inp" month="12" year="<?= $year?>" u-id="<?= $list->id?>"/>
                                        </td>
                                    
                                        </tr>
                                        <?php 
                                            $i++;
                                        endforeach; 
                                     
                                        ?>
                                        </tbody>
                            </table>
                            <?= $this->element('pagination');?>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    var KEY = '<?= @$key?>';
</script>



