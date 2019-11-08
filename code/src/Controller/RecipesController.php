<?php

namespace App\Controller;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class RecipesController extends AppController {

  public function index($id, $slug) {
    //TODO sanitize slug parameter.
    $recipe_query = TableRegistry::getTableLocator()
      ->get('Posts')
      ->find()
      ->leftJoinWith('Recipes')
      ->leftJoinWith('Recipes.Ingredients')
      ->select(['title', 'author', 'description', 'ing_amts' => 'Ingredients.amount', 'ing_names' => 'Ingredients.name'])
      ->where(['Posts.id' => $id])
      ->toList();
    $steps_query = TableRegistry::getTableLocator()
      ->get('Posts')
      ->find()
      ->leftJoinWith('Recipes')
      ->leftJoinWith('Recipes.Steps')
      ->select(['steps' => 'Steps.step'])
      ->where(['Posts.id' => $id])
      ->toList();
    $comments_query = TableRegistry::getTableLocator()
      ->get('Comments')
      ->find()
      ->where(['post_id' => $id])
      ->toList();
    $this->set('id', $id);
    $this->set('slug', $slug);
    $this->set('recipe_info', $recipe_query);
    $this->set('recipe_steps', $steps_query);
    $this->set('comments', $comments_query);
  }

  public function comment($post_id, $parent_comment_id = NULL) {
    $commentsTable = TableRegistry::getTableLocator()->get('Comments');
    $parentCommenter = TableRegistry::getTableLocator()
      ->get('Comments')
      ->find()
      ->select(['commenter'])
      ->where(['id' => $parent_comment_id])
      ->first();
    $this->log($parentCommenter);
    $newComment = $commentsTable->newEntity();
    if($this->request->is('post')) {
      $commentInfo = $this->request->getData();
      $newComment->commenter = $this->getRequest()->getSession()->read('Auth.username');
      $newComment->parent_commenter = $parentCommenter->commenter;
      $newComment->post_id = $post_id;
      $newComment->parent_id = $parent_comment_id;
      $newComment->body = $commentInfo['comment'];
      $commentsTable->save($newComment);
    }
    return $this->redirect($this->referer());
  }
}

?>
