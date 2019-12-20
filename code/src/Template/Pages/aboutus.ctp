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
      <title>About GastroHub</title>

      <?= $this->Html->meta('icon') ?>
      <?= $this->Html->css('zero') ?>
      <?= $this->Html->css('aboutuspage') ?>
      <?= $this->Html->css('footer') ?>
      <?= $this->Html->css('header') ?>
      <?= $this->Html->css('register_modal') ?>
      <?php echo $this->Html->script('jquery-3.4.1.min') ?>
      <?php echo $this->Html->script('reg_modal_trigger') ?>
  </head>
  <body>
    <?= $regCell ?>
    <?= $this->element('userinfoheader') ?>
    <section id='au_body_placard'>
      <p class='lrg-text'>About GastroHub</p>
      <p id='au_upper' class='med-text'>
        GastroHub is not a standard recipe website. GastroHub allows its users to not only create recipes, but to
        embed recipes in comments called "variants" that alter either a main recipe or another variant. These variants
        can be made for all purposes; for ethical, health, diet, expense, cultural, portion, taste or any other reason!
        By adapting recipes for a litany of tongues, we hope that we can spread the joys of cooking and eating to a world
        with diverse values and tastes.
      </p>
      <div id='au_lower'>
        <p id='au_credits' class='med-text'>
          GastroHub was created by Matt Imel, for CIS 598 in the Fall 2019 semester. The class may end in December 2019, but
          I hope to continue to improve GastroHub long after graduation. 💖
        </p>
        <img id='au_cupcake' src='img/aboutus_cupcake.png' />
      </div>
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
