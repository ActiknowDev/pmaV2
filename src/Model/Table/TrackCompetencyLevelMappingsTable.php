<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TrackCompetencyLevelMappings Model
 *
 * @property \App\Model\Table\CareerTracksTable&\Cake\ORM\Association\BelongsTo $CareerTracks
 * @property \App\Model\Table\CompetenciesTable&\Cake\ORM\Association\BelongsTo $Competencies
 * @property \App\Model\Table\CareerLevelsTable&\Cake\ORM\Association\BelongsTo $CareerLevels
 *
 * @method \App\Model\Entity\TrackCompetencyLevelMapping newEmptyEntity()
 * @method \App\Model\Entity\TrackCompetencyLevelMapping newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\TrackCompetencyLevelMapping[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TrackCompetencyLevelMapping get($primaryKey, $options = [])
 * @method \App\Model\Entity\TrackCompetencyLevelMapping findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\TrackCompetencyLevelMapping patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TrackCompetencyLevelMapping[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\TrackCompetencyLevelMapping|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TrackCompetencyLevelMapping saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TrackCompetencyLevelMapping[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\TrackCompetencyLevelMapping[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\TrackCompetencyLevelMapping[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\TrackCompetencyLevelMapping[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class TrackCompetencyLevelMappingsTable extends Table
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

        $this->setTable('track_competency_level_mappings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('CareerTracks', [
            'foreignKey' => 'career_track_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Competencies', [
            'foreignKey' => 'competency_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('CareerLevels', [
            'foreignKey' => 'career_level_id',
            'joinType' => 'INNER',
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
            ->scalar('content')
            ->requirePresence('content', 'create')
            ->notEmptyString('content');

        $validator
            ->dateTime('created_at')
            ->requirePresence('created_at', 'create')
            ->notEmptyDateTime('created_at');

        $validator
            ->dateTime('modified_at')
            ->notEmptyDateTime('modified_at');

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
        // $rules->add($rules->existsIn(['career_track_id'], 'CareerTracks'));
        // $rules->add($rules->existsIn(['competency_id'], 'Competencies'));
        // $rules->add($rules->existsIn(['career_level_id'], 'CareerLevels'));

        return $rules;
    }
}
