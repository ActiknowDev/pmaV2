<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Expense extends Entity
{
    protected array $_accessible = [
        'user_id' => true,
        'amount' => true,
        'year' => true,
        'month' => true,
        'amount_type' => true,
        'created_by' => true,
        'createdAt' => true,
        'updatedAt' => true,
        'user' => true,
    ];
}