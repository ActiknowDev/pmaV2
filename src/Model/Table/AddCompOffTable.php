<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class AddCompOffTable extends Table
{
         public function initialize(array $config): void
         {
                  parent::initialize($config);

                  $this->setTable('add_comp_off');
         }
}
