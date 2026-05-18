<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ContractTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable("contract");
    }
}