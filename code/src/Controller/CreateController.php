<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class CreateController extends AppController {

  // TODO: on validate, ensure at least one step, ingredient, title, and description.
  public function addRecipe() {
      //$this->Authorization->skipAuthorization();
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

        $slug = preg_replace('/[^a-z0-9\-]+/', '', preg_replace('/\s/', '-', strtolower($postInfo['title'])));

        $data = [
          'title'       => $postInfo['title'],
          'slug'        => $slug,
          'author'      => $this->getRequest()->getSession()->read('Auth.username'),
          'description' => $postInfo['description'],
          'recipe'      => [
            'ingredients' => $ingredients,
            'steps'       => $steps
          ]
        ];

        $newPost = $postsTable->newEntity($data, [
          'associated' => ['Recipes', 'Recipes.Ingredients', 'Recipes.Steps']
        ]);

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

        if($postsTable->save($newPost)) {
          $id = $newPost->id;

          $tagData = [];
          foreach($tagIdQuery as $t_id) {
            $tagData[] = ['tag_id' => $t_id->id, 'recipe_id' => $id];
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


  public function index() {
    // Authorization is done here. (Does user exist?)
    //$this->Authorization->skipAuthorization();

    // This page shouldn't be accessed if user is not logged in.
    if(!$this->getRequest()->getSession()->check('Auth')) {
      return $this->redirect([
        'controller' => 'Login', 'action' => 'index'
      ]);
    }

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
