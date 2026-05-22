<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
// use SoftDelete\Model\Table\SoftDeleteTrait;

/**
 * Competencies Model
 *
 * @method \App\Model\Entity\Competency newEmptyEntity()
 * @method \App\Model\Entity\Competency newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Competency[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Competency get($primaryKey, $options = [])
 * @method \App\Model\Entity\Competency findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Competency patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Competency[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Competency|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Competency saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Competency[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Competency[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Competency[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Competency[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class CompetenciesTable extends Table
{
    // use SoftDeleteTrait;
    // protected $softDeleteField = 'deleted_at';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('competencies');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->integer('status')
            ->notEmptyString('status');

        $validator
            ->dateTime('created_at')
            ->notEmptyDateTime('created_at');

        $validator
            ->dateTime('modified_at')
            ->allowEmptyDateTime('modified_at');

        return $validator;
    }
}