<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class ContractResourcesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable("contract_resources");
    }
}