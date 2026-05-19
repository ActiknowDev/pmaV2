<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class ProjectPayment extends Entity
{
     protected array $_accessible = [
        'description' => true,
        'payment_date' => true,
        'receive_amt' => true,
        'status' => true,
        'project_id' => true,
        'created' => true,
        'modified' => true,
        'project' => true,
    ];
}