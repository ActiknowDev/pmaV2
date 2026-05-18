<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MyTeamResource Entity
 *
 * @property int $id
 * @property int $my_team_id
 * @property int $resid
 * @property \Cake\I18n\FrozenTime $created_at
 *
 * @property \App\Model\Entity\MyTeam $my_team
 */
class MyTeamResource extends Entity
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
        'my_team_id' => true,
        'resid' => true,
        'created_at' => true,
        'my_team' => true,
    ];
}
