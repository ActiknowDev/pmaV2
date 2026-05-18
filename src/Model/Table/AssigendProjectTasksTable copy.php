<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AssigendProjectTasks Model
 *
 * @property \App\Model\Table\ProjectsTable&\Cake\ORM\Association\BelongsTo $Projects
 *
 * @method \App\Model\Entity\AssigendProjectTask newEmptyEntity()
 * @method \App\Model\Entity\AssigendProjectTask newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\AssigendProjectTask[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AssigendProjectTask get($primaryKey, $options = [])
 * @method \App\Model\Entity\AssigendProjectTask findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\AssigendProjectTask patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AssigendProjectTask[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\AssigendProjectTask|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AssigendProjectTask saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AssigendProjectTask[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AssigendProjectTask[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\AssigendProjectTask[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AssigendProjectTask[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class AssigendProjectTasksTable extends Table
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

        $this->setTable('assigend_project_tasks');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Projects', [
            'foreignKey' => 'project_id',
        ]);


        $this->belongsTo('Assigned_to_data', [
            'foreignKey' => 'assigned_to',
            'className'=>"Users"
        ]);


        $this->belongsTo('Assigned_by_data', [
            'foreignKey' => 'assigned_by',
            'className'=>"Users"
        ]);

        $this->hasOne("Profiles",[
            "foreignKey"=>"user_id",
            "bindingKey"=>"assigned_by",
            "className"=>"UserProfiles"

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
            ->integer('assigned_to')
            ->requirePresence('assigned_to', 'create')
            ->notEmptyString('assigned_to');

        $validator
            ->integer('assigned_by')
            ->requirePresence('assigned_by', 'create')
            ->notEmptyString('assigned_by');

        $validator
            ->integer('completed')
            ->notEmptyString('completed');

        $validator
            ->date('completed_date')
            ->allowEmptyDate('completed_date');

        $validator
            ->scalar('status')
            ->maxLength('status', 20)
            ->notEmptyString('status');

        $validator
            ->integer('approved')
            ->notEmptyString('approved');

        $validator
            ->scalar('task_name')
            ->requirePresence('task_name', 'create')
            ->notEmptyString('task_name');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->date('due_date')
            ->requirePresence('due_date', 'create')
            ->notEmptyDate('due_date');

        $validator
            ->dateTime('created_at')
            ->notEmptyDateTime('created_at');

        $validator
            ->dateTime('modified_at')
            ->notEmptyDateTime('modified_at');

        $validator
            ->date('extend_days')
            ->allowEmptyDate('extend_days');

        $validator
            ->integer('extend_count')
            ->allowEmptyString('extend_count');

        $validator
        ->integer('project_id')
        ->requirePresence('project_id', 'create')
        ->notEmptyString('project_id');

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
        $rules->add($rules->existsIn(['project_id'], 'Projects'));

        return $rules;
    }
}
