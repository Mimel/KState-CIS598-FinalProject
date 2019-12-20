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
    <section id='browse_search'>
      <div id='browse_headliner'>Search from our recipe database!</div>
      <?= $this->Form->create('Recipe Search Form', ['url' => ['controller' => 'Search', 'action' => 'lookup'], 'id' => 'recipe_search_form']) ?>
      <?= $this->Form->text('recipequery', ['placeholder' => 'Search by Name']) ?>
      <div id='browse_tag_section'>
        <?php foreach ($tags as $name => $genre): ?>
          <div class='browse_tag_genre'>
            <u><center><?= $name ?></center></u>
            <?php foreach ($genre as $tag): ?>
              <label for='<?= '_' . $tag ?>'>
                <?= $this->Form->checkbox('_' . $tag) ?>
                <?= $tag ?>
              </label>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      </div>
      <?= $this->Form->button('Search', ['type' => 'submit']) ?>
      <?= $this->Form->end() ?>
    </section>
    <section id='browse_recipe_results'>
      <?php if(isset($found_recipes)): ?>
        <?php foreach ($found_recipes as $recipe): ?>
          <a href=<?= 'recipes/' . $recipe->id . '/' . $recipe->slug ?> class='browse_recipe_container'>
            <?php if(isset($recipe->image) && $recipe->image != ''): ?>
              <?= $this->Html->image($recipe->image, ['class' => 'browse_recipe_image']) ?>
            <?php endif; ?>
            <div class='browse_recipe_info'>
              <div class='browse_recipe_title'>
                <?= $recipe->title ?>
              </div>
              <div class='browse_recipe_author'>
                By <?= $recipe->author ?>
              </div>
              <div class='browse_recipe_description'>
                <?= $recipe->description ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>
  </body>
</html>
