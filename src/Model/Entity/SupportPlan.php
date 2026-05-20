<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Asset Entity
 *
 * @property int $id
 * @property int|null $asset_categorie_id
 * @property string|null $product_name
 * @property \Cake\I18n\FrozenDate $created_at
 *
 * @property \App\Model\Entity\AssetCategory $asset_category
 * @property \App\Model\Entity\AssetAssignedEntry[] $asset_assigned_entries
 * @property \App\Model\Entity\AssetAssignedLog[] $asset_assigned_logs
 */
class SupportPlan extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected array $_accessible = [
        'project_id' => true,
        'client_id' => true,
        'client_email' => true,
        'user_id' => true,
        'plan_id' => true,
        'project_manager_id' => true,
        'start_date' => true,
        'end_date' => true,
        'numbet_of_months' => true,
        'billing_frequency' => true,
        'amount' => true,
        'document' => true,
        'notes' => true,
        'created_at' => true,
        'updated_at' => true,
        'deleted' => true,
        'status' => true
    ];
}
