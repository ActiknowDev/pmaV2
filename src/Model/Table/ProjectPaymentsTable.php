<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ProjectPaymentsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('project_payments');
        $this->setPrimaryKey('id');

        // Association
        $this->belongsTo('Projects', [
            'foreignKey' => 'project_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('description')
            ->maxLength('description', 555)
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->date('payment_date')
            ->requirePresence('payment_date', 'create')
            ->notEmptyDate('payment_date');

        $validator
            ->decimal('receive_amt')
            ->requirePresence('receive_amt', 'create')
            ->notEmptyString('receive_amt');

        $validator
            ->scalar('status')
            ->inList('status', ['Billed', 'Paid', 'Estimated'])
            ->notEmptyString('status');

        $validator
            ->integer('project_id')
            ->requirePresence('project_id', 'create')
            ->notEmptyString('project_id');

        return $validator;
    }
}