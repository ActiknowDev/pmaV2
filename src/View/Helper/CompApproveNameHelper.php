<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\TableRegistry;

class CompApproveNameHelper extends Helper
{
         public function approvedName($id = null)
         {
                //   $approveTbl = TableRegistry::get('Users');
                  $approveTbl = TableRegistry::getTableLocator()->get('Users');

                  if ($id == null) {
                           $name = "---";
                  } else {

                           $userName = $approveTbl->find()
                                    ->select(['name'])
                                    ->where(['id' => $id])
                                    ->toArray();
                           $name = $userName[0]->name;
                  }
                  return $name;
         }
}
