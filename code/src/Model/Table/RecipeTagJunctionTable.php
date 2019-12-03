<?php

namespace App\Model\Table;
use Cake\ORM\Table;

class RecipeTagJunctionTable extends Table {
  public function initialize(array $config) {
    $this->setTable('recipe_tag_junction');
  }
}

?>
