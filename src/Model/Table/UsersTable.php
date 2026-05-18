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


class UsersTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->addBehavior('Timestamp');
        $this->setPrimaryKey('id');

        $this->hasMany("children", [
            'className' => 'Users',
            'foreignKey' => 'reporting_manager'
        ]);


        $this->belongsTo('parent', [
            'className' => 'Users',
            'foreignKey' => 'reporting_manager',
        ]);

        $this->hasOne(
            'EmpDetail',
            [
                'className' => 'EmployeeDetails',
                'foreignKey' => 'user_id'
            ]
        );


        $this->hasMany(
            'EmpAcad',
            [
                'className' => 'EmployeeAcademics',
                'foreignKey' => 'user_id'
            ]
        );


        $this->hasOne(
            'EmpOthdetail',
            [
                'className' => 'EmployeeOtherDetails',
                'foreignKey' => 'user_id'
            ]
        );

        $this->hasMany(
            'EmpProof',
            [
                'className' => 'EmployeeProofs',
                'foreignKey' => 'user_id'
            ]
        );

        $this->hasMany(
            'EmpRef',
            [
                'className' => 'EmployeeReferences',
                'foreignKey' => 'user_id'
            ]
        );

        $this->hasMany('EmpWorkHistory', [
            'className' => 'EmployeeWorkHistorys',
            'foreignKey' => 'user_id'
        ]);

        $this->hasOne("EmployeeOtherDetails", [
            'className' => 'EmployeeOtherDetails',
            'foreignKey' => 'user_id'
        ]);


        $this->belongsTo("TeamData", [
            "className" => "MyTeams",
            "foreignKey" => "teamid"
        ]);

        $this->belongsTo("ReportingManagerData", [
            "className" => "Users",
            "foreignKey" => "reporting_manager"
        ]);

        $this->hasOne("TokenData", [
            "className" => "UserTokens",
            "foreignKey" => "user_id"
        ]);

        $this->hasOne("ProfileData", [
            "className" => "UserProfiles",
            "foreignKey" => "user_id"
        ]);

        $this->hasOne("client_data", [
            "className" => "ClientData",
            "foreignKey" => "client_id"
        ]);

        // $this->hasOne('pocName', [
        //     'className' => 'Users',
        //     'foreignKey' => 'point_of_contact'
        // ]);

        $this->belongsTo('pocName', [
            'className' => 'Users',
            'foreignKey' => 'point_of_contact',
        ]);


        $this->hasMany('CredentialsMgts', [
            'className' => 'CredentialsMgts',
            'foreignkey' => 'client_id',

        ]);

        $this->hasMany('Users', [
            'className' => 'Users',
            'foreignkey' => 'created_by'

        ]);
    }


    // Add the following method.

    // public function beforeSave(EventInterface $event, $entity, $options)
    // {
    //     if ($entity->isNew() && !$entity->slug) {
    //         $sluggedTitle = Text::slug($entity->title);
    //         // trim slug to maximum length defined in schema
    //         $entity->slug = substr($sluggedTitle, 0, 191);
    //     }
    // }

    // public function validationDefault(Validator $validator): Validator
    // {
    //     $validator
    //         ->notEmptyString('email');

    //     return $validator;
    // }
}