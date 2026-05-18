<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class OpportunityStageTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('opportunity_stage');
    }
}
?>