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
      $newComment->parent_commenter = $parentCommenter->commenter; // Test
      $newComment->post_id = $post_id;
      $newComment->parent_id = $parent_comment_id; // Test
      $newComment->body = $commentInfo['comment'];
      $commentsTable->save($newComment);
    }
    return $this->redirect($this->referer());
  }

  public function postvariant($post_id, $slug, $parent_comment_id) {
    $comment_query = TableRegistry::getTableLocator()->get('Recipes');

    // Check if recipe is from a comment.
    $commented_recipe = TableRegistry::getTableLocator()
      ->get('Recipes')
      ->find()
      ->select('id')
      ->where(['vehicle_id' => $parent_comment_id, 'vehicle_type' => 'comment'])
      ->first();

    $this->log($commented_recipe);

    if(!$commented_recipe) {
      $commented_recipe = TableRegistry::getTableLocator()
        ->get('Recipes')
        ->find()
        ->select('id')
        ->where(['vehicle_id' => $post_id, 'vehicle_type' => 'post'])
        ->first();
    }

    $this->log($commented_recipe);

    if($commented_recipe) {
      $this->log('yeah gurllllll');
      $recipe_query = TableRegistry::getTableLocator()
        ->get('Ingredients')
        ->find()
        ->select(['amount', 'name'])
        ->where(['id' => $commented_recipe->id])
        ->toList();
      $steps_query = TableRegistry::getTableLocator()
        ->get('Steps')
        ->find()
        ->select(['step'])
        ->where(['id' => $commented_recipe->id])
        ->toList();
      $this->set('recipe_info', $recipe_query);
      $this->set('recipe_steps', $steps_query);
    }
  }
}

?>
