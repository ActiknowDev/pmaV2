<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class ProjectMilestonesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable("project_milestones");
    }
}
