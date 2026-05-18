<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Assets Model
 *
 * @property \App\Model\Table\AssetCategoriesTable&\Cake\ORM\Association\BelongsTo $AssetCategories
 * @property \App\Model\Table\AssetAssignedEntriesTable&\Cake\ORM\Association\HasMany $AssetAssignedEntries
 * @property \App\Model\Table\AssetAssignedLogsTable&\Cake\ORM\Association\HasMany $AssetAssignedLogs
 *
 * @method \App\Model\Entity\Asset newEmptyEntity()
 * @method \App\Model\Entity\Asset newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Asset[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Asset get($primaryKey, $options = [])
 * @method \App\Model\Entity\Asset findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Asset patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Asset[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Asset|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Asset saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Asset[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Asset[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Asset[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Asset[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class AssetsTable extends Table
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

        $this->setTable('assets');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('AssetCategories', [
            'foreignKey' => 'asset_categorie_id',
        ]);
        $this->hasMany('AssetAssignedEntries', [
            'foreignKey' => 'asset_id',
        ]);
        $this->hasMany('AssetAssignedLogs', [
            'foreignKey' => 'asset_id',
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
            ->scalar('product_name')
            ->maxLength('product_name', 255)
            ->allowEmptyString('product_name');

        $validator
            ->date('created_at')
            ->notEmptyDate('created_at');

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
        $rules->add($rules->existsIn(['asset_categorie_id'], 'AssetCategories'));

        return $rules;
    }
}
