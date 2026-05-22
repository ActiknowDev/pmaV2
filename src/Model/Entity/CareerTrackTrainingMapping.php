<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class CareerTrackTrainingMapping extends Entity
{
    protected array $_accessible = [
        'career_track_id' => true,
        'training_id' => true,
        'career_level_id' => true,
        'created_at' => true,
        'modified_at' => true,

        'career_track' => true,
        'training' => true,
        'career_level' => true,
    ];
}