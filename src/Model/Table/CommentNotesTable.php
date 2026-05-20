<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class CommentNotesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('comment_notes');
        $this->setPrimaryKey('id');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('ticket_id')
            ->requirePresence('ticket_id', 'create')
            ->notEmptyString('ticket_id');

        $validator
            ->integer('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        $validator
            ->scalar('comment_notes')
            ->requirePresence('comment_notes', 'create')
            ->notEmptyString('comment_notes');

        $validator
            ->integer('type')
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->integer('seen')
            ->allowEmptyString('seen');

        return $validator;
    }
}