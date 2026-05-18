<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class UpworkContractTable extends Table
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('upwork_contract');
    }
}
