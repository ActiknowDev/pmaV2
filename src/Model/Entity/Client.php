<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Client extends Entity
{
    protected array $_accessible = [
        'name' => true,
        'email' => true,
        'phone' => true,
        'location' => true,
        'role' => true,
        'status' => true,
        'deleted' => true,
        'created' => true,
        'modified' => true,
    ];
}