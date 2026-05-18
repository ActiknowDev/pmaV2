<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AssetAssignedLogs Model
 *
 * @method \App\Model\Entity\AssetAssignedLog newEmptyEntity()
 * @method \App\Model\Entity\AssetAssignedLog newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\AssetAssignedLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AssetAssignedLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\AssetAssignedLog findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\AssetAssignedLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AssetAssignedLog[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\AssetAssignedLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AssetAssignedLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AssetAssignedLog[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AssetAssignedLog[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\AssetAssignedLog[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AssetAssignedLog[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class AssetAssignedLogsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('asset_assigned_logs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('AssetAssignedEntries', [
            'foreignKey' => 'asset_assigned_entry_id',
        ]);
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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->date('created_at')
            ->notEmptyDate('created_at');

        $validator
            ->date('updated_at')
            ->notEmptyDate('updated_at');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['asset_assigned_entry_id'], 'AssetAssignedEntries'));

        return $rules;
    }


    

}
