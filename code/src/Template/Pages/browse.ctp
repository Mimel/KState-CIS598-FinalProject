<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;

$this->layout = false;
$regCell = $this->cell('Register');

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
      <title>Browse Recipes</title>

      <?= $this->Html->meta('icon') ?>
      <?= $this->Html->css('zero') ?>
      <?= $this->Html->css('browsepage') ?>
      <?= $this->Html->css('footer') ?>
      <?= $this->Html->css('header') ?>
      <?= $this->Html->css('register_modal') ?>
      <?php echo $this->Html->script('jquery-3.4.1.min') ?>
      <?php echo $this->Html->script('reg_modal_trigger') ?>
  </head>
  <body>
    <?= $regCell ?>
    <?= $this->element('userinfoheader') ?>
    <section id='browse_search_bar'>
      <center>Search from x recipes!</center>
      <?= $this->Form->create('Recipe Search Form', ['url' => ['controller' => 'Search', 'action' => 'lookup'], 'id' => 'recipe_search_form']) ?>
      <?= $this->Form->text('recipequery') ?>
      <?= $this->Form->button('Search', ['type' => 'submit']) ?>
      <?= $this->Form->end() ?>
    </section>
    <section id='browse_recipe_results'>
      <?php foreach ($found_recipes as $recipe): ?>
        <?= $recipe ?>
      <?php endforeach; ?>
    </section>
  </body>
</html>
