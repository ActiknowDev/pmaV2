<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

class ProjectResource extends Entity
{
    protected array $_accessible = [
        '*' => true,
        'id' => false,
    ];
}