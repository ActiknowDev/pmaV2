<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AssetAssignedEntries Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\AssetAssignedEntry newEmptyEntity()
 * @method \App\Model\Entity\AssetAssignedEntry newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\AssetAssignedEntry[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AssetAssignedEntry get($primaryKey, $options = [])
 * @method \App\Model\Entity\AssetAssignedEntry findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\AssetAssignedEntry patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AssetAssignedEntry[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\AssetAssignedEntry|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AssetAssignedEntry saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AssetAssignedEntry[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AssetAssignedEntry[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\AssetAssignedEntry[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AssetAssignedEntry[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class AssetAssignedEntriesTable extends Table
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

        $this->setTable('asset_assigned_entries');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
        $this->belongsTo('AssetDatas', [
            'foreignKey' => 'asset_id',
            // 'joinType' => 'INNER',
        ]);
        $this->hasMany('AssetAssignedLogs', [
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
            ->dateTime('created_at')
            ->notEmptyDateTime('created_at');

        $validator
            ->date('dor')
            ->allowEmptyDate('dor');

        $validator
            ->integer('active')
            ->notEmptyString('active');
        
        $validator
            ->scalar('asset_release_remark')
            ->allowEmptyString('asset_release_remark');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['asset_id'], 'AssetDatas'));

        return $rules;
    }
}