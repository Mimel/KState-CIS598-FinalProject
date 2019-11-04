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
  </head>
  <body>
    <?= $this->element('userinfoheader') ?>
    <?= h($recipe_info) ?>
    <div><a class='comment_trigger'>Submit a Comment</a></div>
    <div id='comment_container'>
      <?= $this->Form->create('Comment Form', ['url' => ['controller' => 'Recipes', 'action' => 'comment', $slug], 'id' => 'comment_submit_form']) ?>
      <?= $this->Form->textarea('comment', ['resize' => 'none']) ?>
      <?= $this->Form->button('Submit Comment', ['type' => 'submit']) ?>
      <?= $this->Form->end() ?>
    </div>
    <section id='comment_section'>
      <?php foreach ($comments as $comment): ?>
        <div><?= $comment ?></div>
      <?php endforeach; ?>
    </section>
  </body>
</html>
