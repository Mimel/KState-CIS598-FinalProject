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
      <title>GastroHub</title>

      <?= $this->Html->meta('icon') ?>
      <?= $this->Html->css('zero') ?>
      <?= $this->Html->css('homepage') ?>
      <?= $this->Html->css('footer') ?>
      <?= $this->Html->css('header') ?>
      <?= $this->Html->css('register_modal') ?>
      <?php echo $this->Html->script('jquery-3.4.1.min') ?>
      <?php echo $this->Html->script('reg_modal_trigger') ?>
  </head>
  <body>
    <?= $regCell ?>
    <?= $this->element('userinfoheader') ?>
    <header id='intro_placard'>
      <img src='/img/gastrohublogo.png'/> <!-- TODO add logo -->
      <p>A collaborative, creative recipe site!</p>
    </header>
    <section id='purpose_placard'>
      <p class='med_opacity_white'>GastroHub is for everyone. For novice cooks who want to learn through iterative design. For advanced chefs who want to improve on myriad recipes. For gourmands who want to make sure their meal is perfect for them. For planners who need to make sure their recipe abides by all their guests' preferences.</p>
      <p class='med_opacity_white'>You can not only submit your own recipes, but create and upload variants of other peoples' recipes, for whatever reason! Whether it be for ethics, diet, health, religion, expense, or simply taste, GastroHub welcomes all variants, to serve a growing world with wonderful and diverse palates.</p>
    </section>
    <section id='popular_placard'>
      <p>See what's popular!</p>
      <!-- TODO add popular recipes -->
    </section>
    <footer id='outro_placard'>
      <p class='med_opacity_white'>Create an account with us!</p>

      <nav class='med_opacity_white'>
        <ul id='nav_sitemap'>
          <?= $this->Html->link('<li>Home</li>',
          '/',
          ['escape' => false]);
          ?>
          <li>Browse</li>
          <li>Create a Recipe</li>
          <?= $this->Html->link('<li>About Us</li>',
          '/aboutus',
          ['escape' => false]);
          ?>
        </ul>
      </nav>
    </footer>
  </body>
</html>
