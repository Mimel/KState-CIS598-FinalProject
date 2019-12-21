<?php

namespace App\Controller;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class RecipesController extends AppController {

  // Loads the /recipes page.
  public function index($id, $slug) {
    //TODO sanitize slug parameter.

    // Gets the recipe base data and ingredients.
    $recipe_query = TableRegistry::getTableLocator()
      ->get('Posts')
      ->find()
      ->leftJoinWith('Recipes')
      ->leftJoinWith('Recipes.Ingredients')
      ->select(['title', 'author', 'image', 'description', 'Recipes.id', 'ing_amts' => 'Ingredients.amount', 'ing_names' => 'Ingredients.name'])
      ->where(['Posts.id' => $id])
      ->toList();
    // Gets the recipe steps.
    $steps_query = TableRegistry::getTableLocator()
      ->get('Posts')
      ->find()
      ->leftJoinWith('Recipes')
      ->leftJoinWith('Recipes.Steps')
      ->select(['steps' => 'Steps.step'])
      ->where(['Posts.id' => $id])
      ->toList();
    // Gets the recipe comments.
    $comments_query = TableRegistry::getTableLocator()
      ->get('Comments')
      ->find()
      ->where(['post_id' => $id])
      ->toList();
    // Gets the recipe tags.
    $tags_query = TableRegistry::getTableLocator()
      ->get('RecipeTagJunction')
      ->find()
      ->select(['tag_id'])
      ->where(['recipe_id' => $recipe_query[0]->_matchingData['Recipes']['id']])
      ->toList();

    // Get ALL variants of this recipe.
    $allCommentIds = [];
    for($x = 0; $x < sizeof($comments_query); $x++) {
      $allCommentIds[] = $comments_query[$x]['id'];
    }

    if(sizeof($allCommentIds) == 0) {
      $allCommentIds = [0];
    }

    $variants_query = TableRegistry::getTableLocator()
      ->get('Comments')
      ->find()
      ->leftJoinWith('Recipes')
      ->leftJoinWith('Recipes.Ingredients')
      ->leftJoinWith('Recipes.Steps')
      ->leftJoinWith('Recipes.RecipeTagJunction')
      ->select(['id', 'commenter', 'Recipes.id', 'ing_amts' => 'Ingredients.amount', 'ing_names' => 'Ingredients.name', 'steps' => 'Steps.step', 'tags' => 'RecipeTagJunction.tag_id'])
      ->where(['Recipes.vehicle_type' => 'comment', 'Recipes.vehicle_id IN' => $allCommentIds])
      ->toList();

    // Build variants table.
    $variantsTable = [];
    foreach($variants_query as $v_entry) {
      if(!$variantsTable[$v_entry->id]) {
        $variantsTable[$v_entry->id] = [
          'commenter' => $v_entry->commenter,
          'ingredients' => [$v_entry->ing_amts . ' ' . $v_entry->ing_names],
          'steps' => [$v_entry->steps],
          'tags' => [$v_entry->tags]
        ];
      }

      if(!in_array($v_entry->ing_amts . ' ' . $v_entry->ing_names, $variantsTable[$v_entry->id]['ingredients'])) {
        $variantsTable[$v_entry->id]['ingredients'][] = $v_entry->ing_amts . ' ' . $v_entry->ing_names;
      }

      if(!in_array($v_entry->steps, $variantsTable[$v_entry->id]['steps'])) {
        $variantsTable[$v_entry->id]['steps'][] = $v_entry->steps;
      }

      if(!in_array($v_entry->tags, $variantsTable[$v_entry->id]['tags'])) {
        $variantsTable[$v_entry->id]['tags'][] = $v_entry->tags;
      }
    }

    foreach($variantsTable as $key => $v_entry) {
      $this->log($key);
      $variantsTable[$key]['tags'] = TableRegistry::getTableLocator()
        ->get('Tags')
        ->find()
        ->select(['name'])
        ->where(['id IN' => $v_entry['tags']])
        ->toList();
    }

    $tIds = [];
    for($x = 0; $x < sizeof($tags_query); $x++) {
      $tIds[] = $tags_query[$x]['tag_id'];
    }

    // Set view variables.
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
    $this->set('variants', $variantsTable);
    $this->set('comments', $comments_query);
  }

  // Posts a comment to the table.
  public function comment($post_id, $parent_comment_id = NULL) {
    $commentsTable = TableRegistry::getTableLocator()->get('Comments');

    // Gets parent commenter.
    $parentCommenter = TableRegistry::getTableLocator()
      ->get('Comments')
      ->find()
      ->select(['commenter'])
      ->where(['id' => $parent_comment_id])
      ->first();

    $newComment = $commentsTable->newEntity();
    if($this->request->is('post')) {
      // Assembles and adds comment to database.
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

  // Loads the postvariant page.
  public function postvariant($post_id, $slug, $parent_comment_id) {
    // This page shouldn't be accessed if user is not logged in.
    if(!$this->getRequest()->getSession()->check('Auth')) {
      return $this->redirect([
        'controller' => 'Login', 'action' => 'index'
      ]);
    }

    $comment_query = TableRegistry::getTableLocator()->get('Recipes');

    // Check if recipe is from a comment.
    $commented_recipe = TableRegistry::getTableLocator()
      ->get('Recipes')
      ->find()
      ->select('id')
      ->where(['vehicle_id' => $parent_comment_id, 'vehicle_type' => 'comment'])
      ->first();

    // If this recipe is not from a comment, retrieve the post recipe.
    if(!$commented_recipe) {
      $commented_recipe = TableRegistry::getTableLocator()
        ->get('Recipes')
        ->find()
        ->select('id')
        ->where(['vehicle_id' => $post_id, 'vehicle_type' => 'post'])
        ->first();
    }

    // IF there actually is a recipe to alter...
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

    // List all tags.
    $tagsTable = TableRegistry::getTableLocator()->get('Tags');
    $allTags = $tagsTable->find()
      ->select(['name', 'genre'])
      ->toList();

    $tagDictionary = [];
    foreach($allTags as $tag) {
      if(!isset($tagDictionary[$tag->genre])) {
        $tagDictionary[$tag->genre] = [$tag->name];
      } else {
        $tagDictionary[$tag->genre][] = $tag->name;
      }
    }

    $this->set('tags', $tagDictionary);
  }

  //Adds a variant to the database.
  public function addvariant($post_id, $parent_comment_id, $parent_recipe_id) {
    // This page shouldn't be accessed if user is not logged in.
    if(!$this->getRequest()->getSession()->check('Auth')) {
      return $this->redirect([
        'controller' => 'Login', 'action' => 'index'
      ]);
    }

    $slug;
    $id;

    if($this->request->is('post')) {
      // Initialization.
      $commentsTable = TableRegistry::getTableLocator()->get('Comments');
      $recipeTable = TableRegistry::getTableLocator()->get('Recipes');
      $recipeInfo = $this->request->getData();

      $parentCommenter = TableRegistry::getTableLocator()
        ->get('Comments')
        ->find()
        ->select(['commenter'])
        ->where(['id' => $parent_comment_id])
        ->first();

      $newComment = $commentsTable->newEntity();
      $newComment->commenter = $this->getRequest()->getSession()->read('Auth.username');
      $newComment->parent_commenter = $parentCommenter->commenter;
      $newComment->post_id = $post_id;
      $newComment->parent_id = $parent_comment_id; // Test
      $newComment->body = $recipeInfo['comment'];

      if($commentsTable->save($newComment)) {
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

        // Create tag entry in junction table.
        $allTags = [];
        foreach($recipeInfo as $key => $value) {
          if(substr($key, 0, 1) == '_' && $value == 1) {
            $allTags[] = substr($key, 1);
          }
        }
        $allTags = str_replace('_', ' ', $allTags);

        $tagsTable = TableRegistry::getTableLocator()->get('Tags');

        $tagIdQuery = [];
        if(sizeof($allTags) > 0) {
          $tagIdQuery = $tagsTable
            ->find()
            ->select(['id'])
            ->where(['name IN' => $allTags])
            ->toList();
        }

        $data = [
          'vehicle_id' => $newComment->id,
          'vehicle_type' => 'comment',
          'parent' => $parent_recipe_id,
          'ingredients' => $ingredients,
          'steps'       => $steps,
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

          $tagData = [];
          foreach($tagIdQuery as $tInst) {
            $tagData[] = ['tag_id' => $tInst->id, 'recipe_id' => $id];
          }

          $rtjTable = TableRegistry::getTableLocator()->get('RecipeTagJunction');
          $tagData = $rtjTable->newEntities($tagData);
          foreach($tagData as $rtjInst) {
            $rtjTable->save($rtjInst);
          }
          return $this->redirect(['controller' => 'Recipes', 'action' => 'index', $post_id, $slug->slug]);
        }
      }
    }
  }
}

?>
