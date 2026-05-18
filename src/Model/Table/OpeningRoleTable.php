<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class OpeningRoleTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('opening_role');
    }
}