<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class ProbabilityTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('probability');
    }
}
?>