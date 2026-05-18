<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class LeaveCountTable extends Table
{
   public function initialize(array $config): void
   {
      parent::initialize($config);

      $this->setTable('leave_counting');
   }
}
