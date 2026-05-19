<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class ProjectTask extends Entity
{
    protected array $_accessible = [
        'milestone_id' => true,
        'task' => true,
        'due_date' => true,
        'status' => true,
        'created' => true,
    ];
}