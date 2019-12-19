<?php

namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class RecipesTable extends Table { //TODO what is class Table?
  public function initialize(array $config) {
    $this->setTable('recipes');
    $this->hasMany('Ingredients')->setForeignKey('recipe_id');
    $this->hasMany('Steps')->setForeignKey('recipe_id');
    $this->hasMany('RecipeTagJunction')->setForeignKey('recipe_id');
  }
}

?>
