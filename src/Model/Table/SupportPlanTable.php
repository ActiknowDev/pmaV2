<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class SupportPlanTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('support_plans');
    }
}
?>