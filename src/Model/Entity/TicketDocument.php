<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class TicketDocument extends Entity
{
    protected array $_accessible = [
        'ticket_id' => true,
        'doc_type' => true,
        'document' => true,
        'added_by' => true,
        'created_at' => true,
        'updated_at' => true,
        'ticket' => true,
    ];
}