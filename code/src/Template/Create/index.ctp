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
    <?= $this->Form->create('Create Recipe', ['controller' => 'Create', 'action' => 'addRecipe', 'id' => 'create_recipe_form']) ?>
    <?= $this->Form->text('Title') ?>
    <?= $this->Form->text('Description') ?>
    <?= $this->Form->button('Submit Recipe', ['type' => 'submit']) ?>
    <?= $this->Form->end() ?>
  </body>
</html>
