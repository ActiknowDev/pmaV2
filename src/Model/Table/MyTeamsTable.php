<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MyTeams Model
 *
 * @property \App\Model\Table\MyTeamResourcesTable&\Cake\ORM\Association\HasMany $MyTeamResources
 *
 * @method \App\Model\Entity\MyTeam newEmptyEntity()
 * @method \App\Model\Entity\MyTeam newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\MyTeam[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MyTeam get($primaryKey, $options = [])
 * @method \App\Model\Entity\MyTeam findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\MyTeam patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MyTeam[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\MyTeam|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MyTeam saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MyTeam[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MyTeam[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\MyTeam[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MyTeam[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class MyTeamsTable extends Table
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

        $this->setTable('my_teams');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('MyTeamResources', [
            'foreignKey' => 'my_team_id',
        ]);

        $this->belongsTo("TechLeadData",[
            "className"=>"Users",
            "foreignKey"=>"tech_lead"
        ]);

           $this->belongsTo("ProjectManagerData",[
            "className"=>"Users",
            "foreignKey"=>"project_manager"
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
            ->integer('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmptyString('created_by');

        $validator
            ->integer('tech_lead')
            ->allowEmptyString('tech_lead');

        $validator
            ->integer('project_manager')
            ->allowEmptyString('project_manager');

        $validator
            ->dateTime('created_at')
            ->notEmptyDateTime('created_at');

        $validator
            ->scalar('team_name')
            ->requirePresence('team_name', 'create')
            ->notEmptyString('team_name');

        return $validator;
    }
}
