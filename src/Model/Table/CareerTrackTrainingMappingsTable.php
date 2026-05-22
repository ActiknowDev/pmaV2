<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class CareerTrackTrainingMappingsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('career_track_training_mappings');
        $this->setPrimaryKey('id');

        $this->belongsTo('CareerTracks', [
            'foreignKey' => 'career_track_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Training', [
            'foreignKey' => 'training_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('CareerLevels', [
            'foreignKey' => 'career_level_id',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('career_track_id')
            ->requirePresence('career_track_id', 'create')
            ->notEmptyString('career_track_id');

        $validator
            ->integer('training_id')
            ->requirePresence('training_id', 'create')
            ->notEmptyString('training_id');

        $validator
            ->integer('career_level_id')
            ->requirePresence('career_level_id', 'create')
            ->notEmptyString('career_level_id');

        return $validator;
    }
}