<?php

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;

//?
$this->layout = false;

if (!Configure::read('debug')) :
    throw new NotFoundException(
        'Please replace src/Template/Pages/home.ctp with your own version or re-enable debug mode.'
    );
endif;
?>

<!DOCTYPE html>
<html>
  <head>
      <?= $this->Html->charset() ?>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>
          Create a Recipe | Gastrohub
      </title>

      <?= $this->Html->css('base') ?>
      <?= $this->Html->css('header') ?>
      <?= $this->Html->css('create_recipe') ?>
  </head>
  <body>
    <?= $this->element('userinfoheader') ?>


      <?= $this->Form->create('Create Recipe', ['url' => ['controller' => 'Create', 'action' => 'addRecipe'], 'id' => 'create_recipe_form']) ?>
      <div id='create_recipe_form_encapsulator'>
        <h1 id='create_recipe_instruction'>Create a Recipe</h1>
        <div class='create_recipe_input_wrapper'>
          <?= $this->Form->text('title', ['id' => 'create_recipe_title', 'placeholder' => 'Recipe Title']) ?>
        </div>
        <div class='create_recipe_input_wrapper'>
          <?= $this->Form->textarea('description', ['id' => 'create_recipe_title', 'placeholder' => 'Recipe Description: this can be how a recipe was made, how it\'s special, or anything you want!']) ?>
        </div>
        <div class='create_recipe_input_wrapper'>
          <?= $this->Form->text('Ingredient Amount 1', ['id' => 'ingamt_1', 'placeholder' => 'Ingredient Amount (e.g. "1 cup")']) ?>
        </div>
        <div class='create_recipe_input_wrapper'>
          <?= $this->Form->text('Ingredient Name 1', ['id' => 'ingname_1', 'placeholder' => 'Ingredient Name (e.g. "Flour")']) ?>
        </div>
        <?= $this->Form->button('Add Another Ingredient', ['type' => 'button', 'id' => 'addIngredientButton']) ?>
        <div class='create_recipe_input_wrapper'>
          <?= $this->Form->text('Step 1', ['id' => 'step_1', 'placeholder' => 'Recipe Step']) ?>
        </div>
        <?= $this->Form->button('Add Another Step', ['type' => 'button', 'id' => 'addStepButton']) ?>
        <?= $this->Form->button('Submit Recipe', ['type' => 'submit']) ?>
      </div>
      <?= $this->Form->end() ?>


    <?php echo $this->Html->script('jquery-3.4.1.min') ?>
    <?php echo $this->Html->script('create_form') ?>
  </body>
</html>
