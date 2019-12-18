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
      ->select(['title', 'author', 'image', 'description', 'Recipes.id', 'ing_amts' => 'Ingredients.amount', 'ing_names' => 'Ingredients.name'])
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

    $tags_query = TableRegistry::getTableLocator()
      ->get('RecipeTagJunction')
      ->find()
      ->select(['tag_id'])
      ->where(['recipe_id' => $recipe_query[0]->_matchingData['Recipes']['id']])
      ->toList();

    $tIds = [];
    for($x = 0; $x < sizeof($tags_query); $x++) {
      $tIds[] = $tags_query[$x]['tag_id'];
    }

    $tags_names = TableRegistry::getTableLocator()
      ->get('Tags')
      ->find()
      ->select(['name'])
      ->where(['id IN' => $tIds])
      ->toList();
    $this->set('id', $id);
    $this->set('slug', $slug);
    $this->set('recipe_info', $recipe_query);
    $this->set('recipe_steps', $steps_query);
    $this->set('recipe_tags', $tags_names);
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
      if($parentCommenter == NULL) {
        $newComment->parent_commenter = NULL;
      } else {
        $newComment->parent_commenter = $parentCommenter->commenter;
      }
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

    if(!$commented_recipe) {
      $commented_recipe = TableRegistry::getTableLocator()
        ->get('Recipes')
        ->find()
        ->select('id')
        ->where(['vehicle_id' => $post_id, 'vehicle_type' => 'post'])
        ->first();
    }

    if($commented_recipe) {
      $recipe_query = TableRegistry::getTableLocator()
        ->get('Ingredients')
        ->find()
        ->select(['amount', 'name'])
        ->where(['recipe_id' => $commented_recipe->id])
        ->toList();
      $this->log(implode(" ", $recipe_query));
      $steps_query = TableRegistry::getTableLocator()
        ->get('Steps')
        ->find()
        ->select(['step'])
        ->where(['recipe_id' => $commented_recipe->id])
        ->toList();
      $this->set('post_id', $post_id);
      $this->set('parent_commenter_id', $parent_comment_id);
      $this->set('parent_recipe_id', $commented_recipe->id);
      $this->set('recipe_info', $recipe_query);
      $this->set('recipe_steps', $steps_query);
    }
  }

  // TODO edit to function
  public function addvariant($post_id, $parent_comment_id, $parent_recipe_id) {
    $slug;
    $id;

    if($this->request->is('post')) {
      $recipeTable = TableRegistry::getTableLocator()->get('Recipes');
      $recipeInfo = $this->request->getData();

      // Construct Ingredients instances.
      $currentIngredient = 1;
      $ingredients = [];
      while(array_key_exists('Ingredient_Amount_' . $currentIngredient, $recipeInfo) and array_key_exists('Ingredient_Name_' . $currentIngredient, $recipeInfo)) {
        $ingredients[] = ['amount' => $recipeInfo['Ingredient_Amount_' . $currentIngredient],
                                  'name' => $recipeInfo['Ingredient_Name_' . $currentIngredient]];
        $currentIngredient += 1;
      }

      // Construct Steps instances.
      $currentStep = 1;
      $steps = [];
      while(array_key_exists('Step_' . $currentStep, $recipeInfo)) {
        $steps[] = ['step' => $recipeInfo['Step_' . $currentStep]];
        $currentStep += 1;
      }

      $this->log($ingredients);

      $data = [
        'vehicle_type' => 'comment',
        'ingredients' => $ingredients,
        'steps'       => $steps
      ];

      $newRecipe = $recipeTable->newEntity($data, [
        'associated' => ['Ingredients', 'Steps']
      ]);

      if($recipeTable->save($newRecipe)) {
        $id = $newRecipe->id;
        $postsTable = TableRegistry::getTableLocator()->get('Posts');
        $slug = $postsTable
          ->find()
          ->select(['slug'])
          ->where(['id' => $post_id])
          ->first();
        return $this->redirect(['controller' => 'Recipes', 'action' => 'index', $post_id, $slug->slug]);
      }


    }
  }
}

?>
