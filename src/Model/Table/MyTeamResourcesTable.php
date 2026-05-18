<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MyTeamResources Model
 *
 * @property \App\Model\Table\MyTeamsTable&\Cake\ORM\Association\BelongsTo $MyTeams
 *
 * @method \App\Model\Entity\MyTeamResource newEmptyEntity()
 * @method \App\Model\Entity\MyTeamResource newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\MyTeamResource[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MyTeamResource get($primaryKey, $options = [])
 * @method \App\Model\Entity\MyTeamResource findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\MyTeamResource patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MyTeamResource[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\MyTeamResource|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MyTeamResource saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MyTeamResource[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MyTeamResource[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\MyTeamResource[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MyTeamResource[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class MyTeamResourcesTable extends Table
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

        $this->setTable('my_team_resources');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('MyTeams', [
            'foreignKey' => 'my_team_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo("Resource",[
            "foreignKey"=>"resid",
            "className"=>"Users"
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
            ->integer('resid')
            ->requirePresence('resid', 'create')
            ->notEmptyString('resid');

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
        $rules->add($rules->existsIn(['my_team_id'], 'MyTeams'));

        return $rules;
    }
}
