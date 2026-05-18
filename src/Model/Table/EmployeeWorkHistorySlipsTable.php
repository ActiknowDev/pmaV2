<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EmployeeWorkHistorySlips Model
 *
 * @property \App\Model\Table\EmployeeWorkHistoriesTable&\Cake\ORM\Association\BelongsTo $EmployeeWorkHistories
 *
 * @method \App\Model\Entity\EmployeeWorkHistorySlip newEmptyEntity()
 * @method \App\Model\Entity\EmployeeWorkHistorySlip newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistorySlip[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistorySlip get($primaryKey, $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistorySlip findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistorySlip patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistorySlip[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistorySlip|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistorySlip saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistorySlip[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistorySlip[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistorySlip[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistorySlip[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class EmployeeWorkHistorySlipsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('employee_work_history_slips');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('EmployeeWorkHistorys', [
            'foreignKey' => 'employee_work_history_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('cmp_splip')
            ->allowEmptyString('cmp_splip');

        $validator
            ->dateTime('created_at')
            ->notEmptyDateTime('created_at');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['employee_work_history_id'], 'EmployeeWorkHistorys'));

        return $rules;
    }
}
