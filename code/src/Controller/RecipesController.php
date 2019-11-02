<?php

namespace App\Controller;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class RecipesController extends AppController {
  public function index($slug) {
    //TODO sanitize slug parameter.
    $recipe_query = TableRegistry::getTableLocator()
      ->get('Posts')
      ->find()
      ->leftJoinWith('Recipes')
      ->leftJoinWith('Recipes.Ingredients')
      ->leftJoinWith('Recipes.Steps')
      ->select(['title', 'author', 'description', 'ing_amts' => 'Ingredients.amount', 'ing_names' => 'Ingredients.name', 'step' => 'Steps.step'])
      ->where(['slug' => $slug])
      ->toList();
    $this->set('recipe_info', implode("|", $recipe_query));
  }
}

?>
