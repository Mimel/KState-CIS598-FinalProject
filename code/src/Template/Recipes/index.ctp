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
      <?php echo $this->Html->script('open_comment') ?>
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
        <br />
        Tags:
        <?php for ($i = 0; $i < count($recipe_tags); $i++): ?>
            <?=h($recipe_tags[$i]->name)?>
            <?php if($i != count($recipe_tags) - 1): ?>
                <?= '- ' ?>
            <?php endif; ?>
        <?php endfor; ?>
      </div>
      <div id='recipe_desc'>
        <?= $this->Html->image($recipe_info[0]->image) ?>
        <?= h($recipe_info[0]->description) ?>
      </div>
      <br />
      <div id='recipe_prep_block'>
        <div id='recipe_ingredients'>
          <div id='recipe_ingredients_header'>Ingredients</div>
          <?php for ($i = 0; $i < count($recipe_info); $i++): ?>
              <?= h($recipe_info[$i]->ing_amts) . ' ' . h($recipe_info[$i]->ing_names) ?>
              <br />
          <?php endfor; ?>
        </div>
        <br />
        <div id='recipe_steps'>
          <div id='recipe_steps_header'>Steps</div>
          <?php for ($i = 0; $i < count($recipe_steps); $i++): ?>
              <?= h($recipe_steps[$i]->steps) ?>
              <br />
          <?php endfor; ?>
        </div>
      </div>
      <hr />
      <a id='post_comment_trigger'><div>Submit a Comment</div></a>
      <div id='post_comment_container'>
        <?= $this->Form->create('Comment Form', ['url' => ['controller' => 'Recipes', 'action' => 'comment', $id], 'id' => 'comment_submit_form']) ?>
        <?= $this->Form->textarea('comment', ['resize' => 'none']) ?>
        <?= $this->Form->button('Submit Comment', ['type' => 'submit']) ?>
        <?= $this->Form->end() ?>
      </div>
    </section>
    <section id='comment_section'>
      <?php foreach ($comments as $comment): ?>
        <div class='comment_block'>
          <div class='comment_header'>
            <div class='comment_avatar'></div> <!-- TODO Change to img once image uploading is implemented. -->
            <?php if($comment->parent_id == NULL): ?>
            <?= $comment->commenter?>
            <?php else: ?>
            <?= $comment->commenter ?>, replying to <?= $comment->parent_commenter ?>
            <?php endif; ?>
          </div>
          <div class='comment_lower'>
            <div class='comment_vert_bar'></div>
            <div class='comment_bodyfooter_container'>
              <div class='comment_body'>
                <?= $comment->body ?>
              </div>
              <div class='comment_footer'>
                <?php
                  $commentButton = "<a class='comment_trigger' id=" . $comment->id . "><img src='/img/icons/comments_icon.png'/></a>";
                  echo $commentButton;
                ?>
              </div>
            </div>
          </div>
          <div class='comment_container'>
            <?= $this->Form->create('Comment Form', ['url' => ['controller' => 'Recipes', 'action' => 'comment', $id, $comment->id], 'class' => 'comment_submit_form', 'id' => 'comment_box_' . $comment->id]) ?>
            <?= $this->Form->textarea('comment', ['class' => 'comment_add_comment_textarea']) ?>
            <div class='comment_submission'>
              <?= $this->Form->button('Submit Comment', ['type' => 'submit']) ?>
              <div>OR</div>
              <?php echo
                $this->Html->link(
                  $this->Form->button('Revise ' . $comment->commenter . '\'s Recipe' , ['type' => 'button']),
                  'recipes/postvariant/' . $id . '/' . $slug . '/' . $comment->id,
                  ['escape' => false]
                );
              ?>
            </div>
            <?= $this->Form->end() ?>
          </div>
        </div>
      <?php endforeach; ?>
    </section>
  </body>
</html>
