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
      <?= $this->Html->css('recipe_page') ?>
      <?php echo $this->Html->script('jquery-3.4.1.min') ?>
  </head>
  <body>
    <?= $this->element('userinfoheader') ?>
    <section id='recipe_section'>
      <div id='recipe_title' class='center'>
        <?= h($recipe_info[0]->title) ?>
      </div>
      <hr />
      <div id='recipe_author' class='center'>
        Created by <?= h($recipe_info[0]->author) ?>
      </div>
      <div id='recipe_desc'>
        <?= h($recipe_info[0]->description) ?>
      </div>
      <br />
      <div id='recipe_prep_block'>
        <div id='recipe_ingredients'>
          Ingredients
          <br />
          <?php for ($i = 0; $i < count($recipe_info); $i++): ?>
              <?= h($recipe_info[$i]->ing_amts) . ' ' . h($recipe_info[$i]->ing_names) ?>
              <br />
          <?php endfor; ?>
        </div>
        <br />
        <div id='steps'>
          Steps
          <br />
          <?php for ($i = 0; $i < count($recipe_info); $i++): ?>
              <?= h($recipe_steps[$i]->steps) ?>
              <br />
          <?php endfor; ?>
        </div>
      </div>
    </section>

    <div><a class='comment_trigger'>Submit a Comment</a></div>
    <div id='comment_container'>
      <?= $this->Form->create('Comment Form', ['url' => ['controller' => 'Recipes', 'action' => 'comment', $slug], 'id' => 'comment_submit_form']) ?>
      <?= $this->Form->textarea('comment', ['resize' => 'none']) ?>
      <?= $this->Form->button('Submit Comment', ['type' => 'submit']) ?>
      <?= $this->Form->end() ?>
    </div>
    <section id='comment_section'>
      <?php foreach ($comments as $comment): ?>
        <div id='comment_block'>
          <div id='comment_header'>
            <?= $comment->commenter ?>
          </div>
          <div id='comment_body'>
            <?= $comment->body ?>
          </div>
          <div id='comment_footer'>
            <a class='comment_trigger'>Reply</a>
          </div>
        </div>
      <?php endforeach; ?>
    </section>
  </body>
</html>
