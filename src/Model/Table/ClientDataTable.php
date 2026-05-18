<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class ClientDataTable extends Table
{
         public function initialize(array $config): void
         {
                  parent::initialize($config);
                  $this->setTable('client_data');

                  $this->belongsTo('Users', [
                           'foreignKey' => 'client_id',
                           'joinType' => 'INNER',
                           'dependent' => true,
                  ]);
         }
}
