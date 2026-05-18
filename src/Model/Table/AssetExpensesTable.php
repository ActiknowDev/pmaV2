<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class AssetExpensesTable extends Table
{

    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('asset_expenses');

        $this->belongsTo('AssetDatas', [
            'foreignKey' => 'asset_id',
        ]);
    }
}
