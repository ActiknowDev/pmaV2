<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\TableRegistry;

class CompOffNameHelper extends Helper
{
         public function compOffName($id = null)
         {
                  $userTbl = TableRegistry::getTableLocator()->get('Users');
                  if ($id == null) {
                           $name = '---';
                  } else {
                           $userName = $userTbl->find()
                                    ->select(['name'])
                                    ->where(['id' => $id])
                                    ->toArray();

                           $name = $userName[0]->name;
                  }
                  return $name;
         }
}
