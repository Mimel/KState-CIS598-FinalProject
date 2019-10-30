<?php

namespace App\Model\Table;
use Cake\ORM\Table;

class IngredientsTable extends Table {
  public function initialize(array $config) {
    $this->setTable('ingredients');
  }
}

?>
