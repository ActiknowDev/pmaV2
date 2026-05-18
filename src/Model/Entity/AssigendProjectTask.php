<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AssigendProjectTask Entity
 *
 * @property int $id
 * @property int $assigned_to
 * @property int $assigned_by
 * @property int $completed
 * @property \Cake\I18n\FrozenDate|null $completed_date
 * @property string $status
 * @property int $approved
 * @property string $task_name
 * @property string|null $description
 * @property \Cake\I18n\FrozenDate $due_date
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime $modified_at
 * @property int|null $project_id
 * @property \Cake\I18n\FrozenDate|null $extend_days
 * @property int|null $extend_count
 *
 * @property \App\Model\Entity\Project $project
 * @property \App\Model\Entity\User $assigned_to_data
 * @property \App\Model\Entity\User $assigned_by_data
 */
class AssigendProjectTask extends Entity
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
        'assigned_to' => true,
        'assigned_by' => true,
        'completed' => true,
        'completed_date' => true,
        'status' => true,
        'approved' => true,
        'task_name' => true,
        'description' => true,
        'due_date' => true,
        'created_at' => true,
        'modified_at' => true,
        'project_id' => true,
        'extend_days' => true,
        'extend_count' => true,
        'project' => true,
        'assigned_to_data' => true,
        'assigned_by_data' => true,
    ];
}
