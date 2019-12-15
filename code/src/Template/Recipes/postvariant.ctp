<?php

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;

//?
$this->layout = false;

// Initialize Cells.
$regCell = $this->cell('Register');
?>

<!DOCTYPE html>
<html>
  <head>
      <?= $this->Html->charset() ?>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>
          Test
      </title>

      <?= $this->Html->css('base') ?>
      <?= $this->Html->css('header') ?>
      <?= $this->Html->css('register_modal') ?>
      <?= $this->Html->css('create_recipe') ?>
      <?php echo $this->Html->script('jquery-3.4.1.min') ?>
      <?php echo $this->Html->script('open_comment') ?>
  </head>
  <body>
    <?= $this->element('userinfoheader') ?>

    <?= $this->Form->create('Create Variant', ['url' => ['controller' => 'Recipes', 'action' => 'addvariant', $post_id, $parent_commenter_id, $parent_recipe_id], 'id' => 'create_recipe_form']) ?>
    <div id='create_recipe_form_encapsulator'>
      <h1 id='create_recipe_instruction'>Edit a Recipe</h1>
      <?php for ($i = 1; $i < count($recipe_info) + 1; $i++): ?>
        <div class='create_recipe_input_wrapper'>
          <?= $this->Form->text('Ingredient Amount ' . $i, ['id' => 'ingamt_' . $i, 'class' => 'ing_amt_template', 'value' => $recipe_info[$i - 1]->amount]) ?>
        </div>
        <div class='create_recipe_input_wrapper'>
          <?= $this->Form->text('Ingredient Name ' . $i, ['id' => 'ingname_' . $i, 'value' => $recipe_info[$i - 1]->name]) ?>
        </div>
      <?php endfor; ?>
      <?= $this->Form->button('Add Another Ingredient', ['type' => 'button', 'id' => 'addIngredientButton']) ?>

      <?php for ($i = 1; $i < count($recipe_steps) + 1; $i++): ?>
        <div class='create_recipe_input_wrapper'>
          <?= $this->Form->text('Step ' . $i, ['id' => 'step_' . $i, 'class' => 'ing_step_template', 'value' => $recipe_steps[$i - 1]->step]) ?>
        </div>
      <?php endfor; ?>
      <?= $this->Form->button('Add Another Step', ['type' => 'button', 'id' => 'addStepButton']) ?>
      <?= $this->Form->button('Submit Variant', ['type' => 'submit']) ?>
    </div>
    <?= $this->Form->end() ?>


    <?php echo $this->Html->script('jquery-3.4.1.min') ?>
    <?php echo $this->Html->script('create_form') ?>
  </body>
</html>
