<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class MilestoneExtend extends Entity
{
    protected array $_accessible = [
        'project_id' => true,
        'extend_date' => true,
        'created_at' => true,
        'updated_at' => true,
    ];
}