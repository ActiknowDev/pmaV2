<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class UserTimesheetsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // DB table name
        $this->setTable('user_timesheets');

        // Primary key
        $this->setPrimaryKey('id');

        // Display field
        $this->setDisplayField('id');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('milestone_id')
            ->requirePresence('milestone_id', 'create')
            ->notEmptyString('milestone_id');

        $validator
            ->integer('resource_id')
            ->requirePresence('resource_id', 'create')
            ->notEmptyString('resource_id');

        $validator
            ->numeric('time_used')
            ->requirePresence('time_used', 'create')
            ->notEmptyString('time_used');

        $validator
            ->allowEmptyString('notes');

        $validator
            ->date('work_date')
            ->requirePresence('work_date', 'create')
            ->notEmptyDate('work_date');

        return $validator;
    }
}