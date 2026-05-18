<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class PublishNoticeTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('publish_notice');
    }
}
