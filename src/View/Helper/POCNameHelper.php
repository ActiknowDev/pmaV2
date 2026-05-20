<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\TableRegistry;

class POCNameHelper extends Helper
{
    public function pocData($pId)
    {
        $users_table = TableRegistry::getTableLocator()->get('Users');

        if ($pId == null || $pId == 0) {

            $name = "-";

        } else {

            $userName = $users_table
                ->find()
                ->select(['name'])
                ->where(['id' => (int)$pId])
                ->first();

            $name = $userName ? $userName->name : "-";
        }

        return $name;
    }
}