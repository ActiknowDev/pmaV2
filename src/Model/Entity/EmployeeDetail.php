<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EmployeeDetail Entity
 *
 * @property int $user_id
 * @property string|null $guardian_name
 * @property \Cake\I18n\FrozenDate $dob
 * @property int $doj
 * @property string $mobile_no
 * @property string $phone_no
 * @property int|null $ctc
 * @property string|null $location
 * @property string|null $pan_no
 * @property int|null $ntc_perd
 * @property int|null $bond
 * @property string|null $house_no_prsnt
 * @property string|null $locality_prsnt
 * @property string|null $city_prsnt
 * @property string|null $state_prsnt
 * @property string|null $zip_prsnt
 * @property string|null $phone_prsnt
 * @property string|null $house_no_prmnt
 * @property string|null $locality_prmnt
 * @property string|null $city_prmnt
 * @property string|null $state_prmnt
 * @property string|null $zip_prmnt
 * @property string|null $phone_prmnt
 *
 * @property \App\Model\Entity\User $user
 */
class EmployeeDetail extends Entity
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
        'user_id' => true,
        'guardian_name' => true,
        'dob' => true,
        'email_dob' => true,
        'doj' => true,
        "dol" => true,
        'mobile_no' => true,
        'phone_no' => true,
        'ctc' => true,
        'location' => true,
        'pan_no' => true,
        'ntc_perd' => true,
        'bond' => true,
        'house_no_prsnt' => true,
        'locality_prsnt' => true,
        'city_prsnt' => true,
        'state_prsnt' => true,
        'zip_prsnt' => true,
        'phone_prsnt' => true,
        'house_no_prmnt' => true,
        'locality_prmnt' => true,
        'city_prmnt' => true,
        'state_prmnt' => true,
        'zip_prmnt' => true,
        'phone_prmnt' => true,
        'user' => true,
        'prev_appraisal' => true,
        'next_appraisal' => true,
        'note' => true,
        'aadhar_card' => true,
        'blood_group' => true
    ];
}
