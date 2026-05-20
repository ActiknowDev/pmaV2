<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class TicketDocumentsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('ticket_documents');
        $this->setPrimaryKey('id');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('ticket_id')
            ->requirePresence('ticket_id', 'create')
            ->notEmptyString('ticket_id');

        $validator
            ->integer('doc_type')
            ->allowEmptyString('doc_type');

        $validator
            ->scalar('document')
            ->maxLength('document', 150)
            ->allowEmptyString('document');

        $validator
            ->integer('added_by')
            ->requirePresence('added_by', 'create')
            ->notEmptyString('added_by');

        return $validator;
    }
}