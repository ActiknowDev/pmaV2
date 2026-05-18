<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MyTeam Entity
 *
 * @property int $id
 * @property int $created_by
 * @property int|null $tech_lead
 * @property int|null $project_manager
 * @property \Cake\I18n\FrozenTime $created_at
 * @property string $team_name
 *
 * @property \App\Model\Entity\MyTeamResource[] $my_team_resources
 */
class MyTeam extends Entity
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
    protected $_accessible = [
        'created_by' => true,
        'tech_lead' => true,
        'project_manager' => true,
        'created_at' => true,
        'team_name' => true,
        'my_team_resources' => true,
    ];
}
