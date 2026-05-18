<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class LeavesTable extends Table
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('leaves');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        // $this->setforeignKey('reporting_manager');


        $this->belongsTo('Users', [
            'foreignKey' => 'approved_by ',
        ]);


        $this->belongsTo('CreatedBy', [
            'className' => 'Users',
            'foreignKey' => 'created_by',
            'propertyName' => 'CreatedBy'
        ]);
        
        $this->CreatedBy->belongsTo('Manager', [
            'className' => 'Users', 
            'foreignKey' => 'reporting_manager',
            'propertyName' => 'Manager',
        ]);
    }


    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('leave_type')
            ->maxLength('leave_type', 255)
            ->allowEmptyString('leave_type');

        $validator
            ->scalar('subject')
            ->allowEmptyString('subject');

        $validator
            ->date('applied_on')
            ->allowEmptyDate('applied_on');

        $validator
            ->date('from_date')
            ->allowEmptyDate('from_date');

        $validator
            ->date('to_date')
            ->allowEmptyDate('to_date');

        $validator
            ->integer('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmptyString('created_by');

        $validator
            ->integer('approved_by')
            ->allowEmptyString('approved_by');

        $validator
            ->scalar('status')
            ->allowEmptyString('status');

        $validator
            ->scalar('message')
            ->allowEmptyString('message');

        return $validator;
    }
}
