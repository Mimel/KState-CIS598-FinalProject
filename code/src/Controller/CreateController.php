<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class CreateController extends AppController {

  public function addRecipe() {
      //$this->Authorization->skipAuthorization();

      if($this->request->is('post')) {
        $postsTable = TableRegistry::getTableLocator()->get('Posts');
        $postInfo = $this->request->getData();

        // Construct Ingredients instances.
        $currentIngredient = 1;
        $ingredients = [];
        while(array_key_exists('Ingredient_Amount_' . $currentIngredient, $postInfo) and array_key_exists('Ingredient_Name_' . $currentIngredient, $postInfo)) {
          $ingredients[] = ['amount' => $postInfo['Ingredient_Amount_' . $currentIngredient],
                                    'name' => $postInfo['Ingredient_Name_' . $currentIngredient]];
          $currentIngredient += 1;
        }

        // Construct Steps instances.
        $currentStep = 1;
        $steps = [];
        while(array_key_exists('Step_' . $currentStep, $postInfo)) {
          $steps[] = ['step' => $postInfo['Step_' . $currentStep]];
          $currentStep += 1;
        }

        $data = [
          'title'       => $postInfo['title'],
          'slug'        => 'hello', //TODO Create auto-slug.
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
        $postsTable->save($newPost);
      }

      return $this->redirect(['controller' => 'Login', 'action' => 'index']);
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
  }
}
?>
