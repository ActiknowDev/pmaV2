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


class ProjectsTable extends Table
{
    public function initialize(array $config): void
    {
        $this->addBehavior('Timestamp');

        $this->belongsTo("Client",[
        	'className'=>'Users',
        	"foreignKey"=>"client_id",
        	"propertyName"=>"client"
        ]);
    }


    
	

	public function validationDefault(Validator $validator): Validator
	{
	    $validator
	        ->notEmptyString('project_name')
	        ->notEmptyString('client_name')
	        ->notEmptyString('awarded_on')
	        ->notEmptyString('due_date')
	        ->notEmptyString('amount')
	        ->notEmptyString('project_manager_id')
	        ->notEmptyString('tech_lead_id');

	    return $validator;
	}
}