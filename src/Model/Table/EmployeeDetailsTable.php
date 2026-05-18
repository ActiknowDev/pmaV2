<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Authentication\PasswordHasher\DefaultPasswordHasher;

/**
 * EmployeeDetails Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\EmployeeDetail newEmptyEntity()
 * @method \App\Model\Entity\EmployeeDetail newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\EmployeeDetail findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\EmployeeDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeDetail[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeeDetail|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EmployeeDetail saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EmployeeDetail[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\EmployeeDetail[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\EmployeeDetail[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\EmployeeDetail[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class EmployeeDetailsTable extends Table
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

        $this->setTable('employee_details');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
    }

    protected function _setPassword(string $password): ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
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
            ->scalar('guardian_name')
            ->allowEmptyString('guardian_name');

        $validator
            ->date('dob')
            ->allowEmptyString('dob');

        $validator
            ->date('email_dob')
            ->allowEmptyString('email_dob');

        $validator
            ->date('doj')
            ->allowEmptyString('doj');

        $validator
            ->date('email_dob')
            ->allowEmptyString('email_dob');


        $validator
            ->date('dol')
            ->allowEmptyString('dol');

        $validator
            ->scalar('mobile_no')
            ->allowEmptyString('mobile_no');

        $validator
            ->scalar('phone_no')
            ->allowEmptyString('phone_no');

        $validator
            ->scalar('ctc')
            ->allowEmptyString('ctc');

        $validator
            ->scalar('location')
            ->allowEmptyString('location');

        $validator
            ->scalar('pan_no')
            ->maxLength('pan_no', 100)
            ->allowEmptyString('pan_no');

        $validator
            ->scalar('ntc_perd')
            ->allowEmptyString('ntc_perd');

        $validator
            ->scalar('bond')
            ->allowEmptyString('bond');

        $validator
            ->scalar('house_no_prsnt')
            ->allowEmptyString('house_no_prsnt');

        $validator
            ->scalar('locality_prsnt')
            ->allowEmptyString('locality_prsnt');

        $validator
            ->scalar('city_prsnt')
            ->allowEmptyString('city_prsnt');

        $validator
            ->scalar('state_prsnt')
            ->allowEmptyString('state_prsnt');

        $validator
            ->scalar('zip_prsnt')
            ->allowEmptyString('zip_prsnt');

        $validator
            ->scalar('phone_prsnt')
            ->allowEmptyString('phone_prsnt');

        $validator
            ->scalar('house_no_prmnt')
            ->allowEmptyString('house_no_prmnt');

        $validator
            ->scalar('locality_prmnt')
            ->allowEmptyString('locality_prmnt');

        $validator
            ->scalar('city_prmnt')
            ->allowEmptyString('city_prmnt');

        $validator
            ->scalar('state_prmnt')
            ->allowEmptyString('state_prmnt');

        $validator
            ->scalar('zip_prmnt')
            ->allowEmptyString('zip_prmnt');

        $validator
            ->scalar('phone_prmnt')
            ->allowEmptyString('phone_prmnt');

        $validator
            ->date('prev_appraisal')
            ->allowEmptyString('prev_appraisal');

        $validator
            ->date('next_appraisal')
            ->allowEmptyString('next_appraisal');

        $validator
            ->scalar('note')
            ->allowEmptyString('note');

        $validator
            ->scalar('aadhar_card')
            ->allowEmptyString('aadhar_card');

        $validator
            ->scalar('blood_group')
            ->allowEmptyString('blood_group');

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
