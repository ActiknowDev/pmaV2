<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class CompaniesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('companies');   // your DB table name
        $this->setPrimaryKey('id');
        $this->setDisplayField('company_name');
    }
}