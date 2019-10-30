<?php

namespace App\Model\Table;
use Cake\ORM\Table;

class StepsTable extends Table {
  public function initialize(array $config) {
    $this->setTable('steps');
  }
}

?>
