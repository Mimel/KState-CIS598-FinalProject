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
    $comments_query = TableRegistry::getTableLocator()
      ->get('Comments')
      ->find()
      ->where(['post_id' => $slug])
      ->toList();
    $this->set('slug', $slug);
    $this->set('recipe_info', implode("|", $recipe_query));
    $this->set('comments', $comments_query);
  }

  public function comment($slug) {
    $commentsTable = TableRegistry::getTableLocator()->get('Comments');
    $newComment = $commentsTable->newEntity();
    if($this->request->is('post')) {
      $commentInfo = $this->request->getData();
      //$newComment->parent_id
      $newComment->commenter = $this->getRequest()->getSession()->read('Auth.username');
      $newComment->post_id = $slug;
      $newComment->body = $commentInfo['comment'];
      $commentsTable->save($newComment);
    }
    return $this->redirect($this->referer());
  }
}

?>
