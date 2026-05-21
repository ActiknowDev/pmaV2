<section class="page page-dashboard">
    
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-user-tie"></i></span>Salary Structure
                    </div>
                </div>
                <div class="col-6">
                    <div class="actions-ctrl text-md-right">

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
                        <div class="header row">
                            <div class="col-md-3">
                                <h4 class="title">Salary/Bonus Details</h4>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->create([],[
                                            'url' => [
                                                'Controller' => 'Salaries',
                                                'action' => 'index/'.@$user_id
                                            ],
                                            'style' => "display: inline-flex;"
                                        ]
                                    
                                    ) 
                                
                                ?>
                                    <?= $this->Form->input('k',[
                                                        'class' => 'form-control',
                                                        'label' => false,
                                                        'div' => false,
                                                        'type' => 'password',
                                                        'placeholder' =>  'Key'
                                                    ]
                                                );
                                    ?>
                                    <button type="submit" class="v-btn v-btn-primary ml-2">Decrypt Amount</button>
                                </form>
                            </div>
                            <div class="col-md-3 text-right">
                                <a href="#" data-target="#addSalary" data-toggle="modal" class="v-btn v-btn-secondary">
                                    <i class="fa fa-list"></i><span>Add Salary/Bonus</span>
                                </a>
                            </div>
                        </div>
                        <div class="content ">
                            <table>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Amount</th>
                                        <th>From Date</th>
                                        <th>Created At</th>
                                        <th>Amount Type</th>
                                        <th>Created By</th>
                                        
                                        <th>Remark</th>
                                        <th class="actions"><?= __('Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $i = 1;
                                        foreach ($salaries as $salary): 
                                    ?>
                                    <tr>
                                        <td><?= $this->Number->format($i) ?></td>
                                        <td><?php echo $salary->user->name;// $salary->has('user') ? $this->Html->link($salary->user->name, ['controller' => 'Users', 'action' => 'view', $salary->user->id]) : '' ?></td>
                                        <td><?= isset($key) ? openssl_decrypt($salary->amount,"AES-128-ECB",$key) : $salary->amount; ?></td>
                                        <td><?= h($salary->from_date) ?></td>
                                        <td><?= h($salary->created_at) ?></td>
                                        <td><?= h($salary->amount_type) ?></td>
                                        <td><?php echo @$salary->creator->name;// $salary->has('user') ? $this->Html->link($salary->user->name, ['controller' => 'Users', 'action' => 'view', $salary->user->id]) : '' ?></td>
                                        
                                        <td><?= h($salary->remark) ?></td>
                                        <td class="actions">
                                            <!-- <?= $this->Html->link(__('View'), ['action' => 'view', $salary->id]) ?>
                                            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $salary->id]) ?> -->
                                            <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $salary->id], ['confirm' => __('Are you sure you want to delete # {0}?', $salary->id)]) ?>
                                        </td>
                                    </tr>
                                    <?php 
                                            $i++;
                                        endforeach; 
                                    ?>
                                   
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="block">
                        <div class="header row">
                            <div class="col-md-6">
                                <h4 class="title">Bonus Details</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="#" data-target="#addSalary" data-toggle="modal" class="v-btn v-btn-secondary">
                                    <i class="fa fa-list"></i><span>Add Bonus</span>
                                </a>
                            </div>
                        </div>
                        <div class="content ">
                            <table>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?= $this->Paginator->sort('user_id') ?></th>
                                        <th><?= $this->Paginator->sort('amount') ?></th>
                                        <th><?= $this->Paginator->sort('from_date') ?></th>
                                        <th><?= $this->Paginator->sort('created_at') ?></th>
                                        <th><?= $this->Paginator->sort('created_by') ?></th>
                                        <th><?= $this->Paginator->sort('amount_type') ?></th>
                                        <th class="actions"><?= __('Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($salaries as $salary): ?>
                                    <tr>
                                        <td><?= $this->Number->format($salary->id) ?></td>
                                        <td><?= $salary->has('user') ? $this->Html->link($salary->user->name, ['controller' => 'Users', 'action' => 'view', $salary->user->id]) : '' ?></td>
                                        <td><?= $this->Number->format($salary->amount) ?></td>
                                        <td><?= h($salary->from_date) ?></td>
                                        <td><?= h($salary->created_at) ?></td>
                                        <td><?= $this->Number->format($salary->created_by) ?></td>
                                        <td><?= h($salary->amount_type) ?></td>
                                        <td class="actions">
                                            <?= $this->Html->link(__('View'), ['action' => 'view', $salary->id]) ?>
                                            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $salary->id]) ?>
                                            <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $salary->id], ['confirm' => __('Are you sure you want to delete # {0}?', $salary->id)]) ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                   
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>

<!-- UPLOAD CERTIFICATE MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="addSalary">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Salary</h5>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= $this->Form->create($salary,[
                        'url' => [
                            'Controller' => 'Salaries',
                            'action' => 'add'
                        ],
                    ]
                
                ) 
            
            ?>
                <div class="modal-body">
                    
                    <input type="hidden" name="user_id" value="<?= $user_id?>">
                    <div class="content">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="">Key(To encrypt salary)</label>
                                <input type="password" class="form-control" required name="key" value="<?= @$key?>">
                            </div>
                            <div class="col-md-6">
                                <label for="">Date</label>
                                <?= $this->Form->control('from_date',[
                                                    'class' => 'form-control',
                                                    'label' => false,
                                                    'div' => false
                                                ]
                                            );
                                ?>
                            </div>
                            <div class="col-md-6">
                                <label for="">Amount</label>
                                <input type="number" class="form-control" required name="amount" required>
                            </div>
                            <div class="col-md-6">
                                <label for="">Amount Type</label>
                                <?= $this->Form->control('amount_type',[
                                                    'class' => 'form-control',
                                                    'label' => false,
                                                    'div' => false,
                                                    'type' => 'select',
                                                    'options' => [
                                                        'salary' => 'Salary',
                                                        'bonus' => 'Bonus'
                                                    ]
                                                ]
                                            );
                                ?>
                            </div>
                            <div class="col-md-6">
                                <label for="">Remark(optional)</label>
                                <?= $this->Form->control('remark',[
                                                    'class' => 'form-control',
                                                    'label' => false,
                                                    'div' => false
                                                ]
                                            );
                                ?>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
                    <button type="submit" class="v-btn v-btn-primary">Add Salary</button>
                </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
