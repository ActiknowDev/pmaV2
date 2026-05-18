<?php
// src/Model/Table/ArticlesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;
// the Text class
use Cake\Utility\Text;
// the EventInterface class
use Cake\Event\EventInterface;
// the Validator class
use Cake\Validation\Validator;


class CredentialsMgtsTable extends Table
{
    public function initialize(array $config): void
    {


        $this->setTable('credentials_mgts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');


        $this->belongsTo('Users', [
            'foreignKey' => 'client_id',
            'joinType' => 'INNER',
        ]);
    }
}
