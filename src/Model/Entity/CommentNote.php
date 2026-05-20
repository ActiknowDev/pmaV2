<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class CommentNote extends Entity
{
    protected array $_accessible = [
        'ticket_id' => true,
        'user_id' => true,
        'comment_notes' => true,
        'type' => true,
        'seen' => true,
        'created_at' => true,
        'updated_at' => true,
        'ticket' => true,
        'user' => true,
    ];
}