<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class CreateController extends AppController {

  // TODO: on validate, ensure at least one step, ingredient, title, and description.
  // Adds a recipe (created from the /create form) to the database.
  public function addRecipe() {

      // This page shouldn't be accessed if user is not logged in.
      if(!$this->getRequest()->getSession()->check('Auth')) {
        return $this->redirect([
          'controller' => 'Login', 'action' => 'index'
        ]);
      }

      $slug;
      $id;

      if($this->request->is('post')) {
        $postsTable = TableRegistry::getTableLocator()->get('Posts');
        $postInfo = $this->request->getData();

        // Construct Ingredients instances.
        $currentIngredient = 1;
        $ingredients = [];
        while(array_key_exists('Ingredient_Amount_' . $currentIngredient, $postInfo) and array_key_exists('Ingredient_Name_' . $currentIngredient, $postInfo)) {
          if($postInfo['Ingredient_Amount_' . $currentIngredient] != '' && $postInfo['Ingredient_Name_' . $currentIngredient] != '') {
            $ingredients[] = ['amount' => $postInfo['Ingredient_Amount_' . $currentIngredient],
                                      'name' => $postInfo['Ingredient_Name_' . $currentIngredient]];
          }
          $currentIngredient += 1;
        }

        // Construct Steps instances.
        $currentStep = 1;
        $steps = [];
        while(array_key_exists('Step_' . $currentStep, $postInfo)) {
          if($postInfo['Step_' . $currentStep] != '') {
            $steps[] = ['step' => $postInfo['Step_' . $currentStep]];
          }
          $currentStep += 1;
        }

        // Constructs a valid slug.
        $slug = preg_replace('/[^a-z0-9\-]+/', '', preg_replace('/\s/', '-', strtolower($postInfo['title'])));

        // Places uploaded image inside directory.
        $imageFilePath = WWW_ROOT . 'img\\post_images\\' . $slug . substr($postInfo['image']['name'], strpos($postInfo['image']['name'], '.'));
        move_uploaded_file($postInfo['image']['tmp_name'], $imageFilePath);

        // Create recipe structure.
        $data = [
          'title'       => $postInfo['title'],
          'slug'        => $slug,
          'image'       => '/img/post_images/' . $slug . substr($postInfo['image']['name'], strpos($postInfo['image']['name'], '.')),
          'author'      => $this->getRequest()->getSession()->read('Auth.username'),
          'description' => $postInfo['description'],
          'recipe'      => [
            'vehicle_type' => 'post',
            'ingredients' => $ingredients,
            'steps'       => $steps
          ]
        ];
        $newPost = $postsTable->newEntity($data, [
          'associated' => ['Recipes' => ['validation' => 'asdf'], 'Recipes.Ingredients' => ['validate' => 'default'], 'Recipes.Steps']
        ]);
        if($newPost->errors()) {
          $this->log($newPost->errors());
          return;
        }

        // Create tag entry in junction table.
        $allTags = [];
        foreach($postInfo as $key => $value) {
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

        // If the recipe and associated components are successfully saved..
        if($postsTable->save($newPost)) {
          $id = $newPost->id;
          $r_id = TableRegistry::getTableLocator()->get('Recipes')
            ->find()
            ->select(['id'])
            ->where(['vehicle_id' => $newPost->id, 'vehicle_type' => 'post'])
            ->first();

          $tagData = [];
          foreach($tagIdQuery as $t_id) {
            $tagData[] = ['tag_id' => $t_id->id, 'recipe_id' => $r_id->id];
          }

          $rtjTable = TableRegistry::getTableLocator()->get('RecipeTagJunction');
          $tagData = $rtjTable->newEntities($tagData);
          foreach($tagData as $rtjInst) {
            $rtjTable->save($rtjInst);
          }

        }
      }

      return $this->redirect(['controller' => 'Recipes', 'action' => 'index', $id, $slug]);
  }

  // Opens the /create page.
  public function index() {

    // This page shouldn't be accessed if user is not logged in.
    if(!$this->getRequest()->getSession()->check('Auth')) {
      return $this->redirect([
        'controller' => 'Login', 'action' => 'index'
      ]);
    }

    // Loads all tags from database dynamically.
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
}
?>
