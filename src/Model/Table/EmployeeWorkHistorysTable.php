<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EmployeeWorkHistorys Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\EmployeeWorkHistorySlipsTable&\Cake\ORM\Association\HasMany $EmployeeWorkHistorySlips
 *
 * @method \App\Model\Entity\EmployeeWorkHistory newEmptyEntity()
 * @method \App\Model\Entity\EmployeeWorkHistory newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistory get($primaryKey, $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistory findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistory[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistory|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistory saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistory[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistory[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistory[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\EmployeeWorkHistory[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class EmployeeWorkHistorysTable extends Table
{



    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */

    // public $useTable = false;

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('employee_work_historys');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('EmployeeWorkHistorySlips', [
            'foreignKey' => 'employee_work_history_id'
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
            ->scalar('cmp_name')
            ->allowEmptyString('cmp_name');

        $validator
            ->scalar('cmp_desgnation')
            ->allowEmptyString('cmp_desgnation');

        $validator
            ->scalar('cmp_location')
            ->allowEmptyString('cmp_location');

        $validator
            ->scalar('cmp_doj')
            ->allowEmptyString('cmp_doj');

        $validator
            ->scalar('cmp_dor')
            ->allowEmptyString('cmp_dor');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
