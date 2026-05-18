<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProjectMilestonesLogs Model
 *
 * @property \App\Model\Table\ProjectsTable&\Cake\ORM\Association\BelongsTo $Projects
 * @property \App\Model\Table\ProjectMilestonesTable&\Cake\ORM\Association\BelongsTo $ProjectMilestones
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\ProjectMilestonesLog newEmptyEntity()
 * @method \App\Model\Entity\ProjectMilestonesLog newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\ProjectMilestonesLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProjectMilestonesLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProjectMilestonesLog findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\ProjectMilestonesLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProjectMilestonesLog[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProjectMilestonesLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProjectMilestonesLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProjectMilestonesLog[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ProjectMilestonesLog[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\ProjectMilestonesLog[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ProjectMilestonesLog[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ProjectMilestonesLogsTable extends Table
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

        $this->setTable('project_milestones_logs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Projects', [
            'foreignKey' => 'project_id',
        ]);
        $this->belongsTo('ProjectMilestones', [
            'foreignKey' => 'project_milestone_id',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
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
            ->dateTime('created_at')
            ->notEmptyDateTime('created_at');

        $validator
            ->numeric('old_price')
            ->allowEmptyString('old_price');

        $validator
            ->date('old_due_date')
            ->allowEmptyDate('old_due_date');

        $validator
            ->integer('is_price_changed')
            ->notEmptyString('is_price_changed');

        $validator
            ->integer('is_due_changed')
            ->notEmptyString('is_due_changed');

        $validator
            ->scalar('action_performed')
            ->maxLength('action_performed', 255)
            ->allowEmptyString('action_performed');

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
        $rules->add($rules->existsIn(['project_milestone_id'], 'ProjectMilestones'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
