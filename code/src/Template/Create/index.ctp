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

$title = 'Create a Recipe | GastroHub';
?>

<!DOCTYPE html>
<html>
  <head>
      <?= $this->Html->charset() ?>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>
          <?= $title ?>
      </title>

      <?= $this->Html->css('base') ?>
      <?= $this->Html->css('header') ?>
  </head>
  <body>
    Create Page.
    <?= $this->Form->create('Create Recipe', ['url' => ['controller' => 'Create', 'action' => 'addRecipe'], 'id' => 'create_recipe_form']) ?>
    <?= $this->Form->text('title') ?>
    <?= $this->Form->text('description') ?>
    <?= $this->Form->text('Ingredient Amount 1', ['id' => 'ingamt_1']) ?>
    <?= $this->Form->text('Ingredient Name 1', ['id' => 'ingname_1']) ?>
    <?= $this->Form->button('Add Another Ingredient', ['type' => 'button', 'id' => 'addIngredientButton']) ?>
    <?= $this->Form->text('Step 1', ['id' => 'step_1']) ?>
    <?= $this->Form->button('Add Another Step', ['type' => 'button', 'id' => 'addStepButton']) ?>
    <?= $this->Form->button('Submit Recipe', ['type' => 'submit']) ?>
    <?= $this->Form->end() ?>

    <?php echo $this->Html->script('jquery-3.4.1.min') ?>
    <?php echo $this->Html->script('create_form') ?>
  </body>
</html>
