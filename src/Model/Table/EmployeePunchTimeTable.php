<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class EmployeePunchTimeTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable("emp_punch_time");
    }
}
