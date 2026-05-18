<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class InterviewCondidateTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('interview_condidate');
    }
}