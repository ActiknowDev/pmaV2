<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class GithubReportsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('github_reports');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }
}