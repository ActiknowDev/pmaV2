<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class ProjectResourcesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Database table name
        $this->setTable('project_resources');

        // Primary key
        $this->setPrimaryKey('id');

        // Display field
        $this->setDisplayField('id');
    }
}