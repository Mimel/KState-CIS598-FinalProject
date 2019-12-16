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
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Login | GastroHub</title>

      <?= $this->Html->css('base') ?>
      <?= $this->Html->css('header') ?>
      <?= $this->Html->css('login') ?>
      <?= $this->Html->css('zero') ?>
      <?= $this->Html->css('register_modal') ?>
      <?php echo $this->Html->script('jquery-3.4.1.min') ?>
  </head>
  <body>
    <?= $regCell ?>
    <?= $this->element('userinfoheader') ?>
    <div id='l_form_wrapper'>
      <?= $this->Form->create('Login', ['url' => ['controller' => 'Login', 'action' => 'login'], 'id' => 'l_login_form']) ?>
      <h1 id='login_form_header'>Sign in</h1>
      <div class='login_field_wrapper'>
        <?= $this->Form->text('username', ['placeholder' => 'Username', 'class' => 'login_field']) ?>
      </div>
      <div class='login_field_wrapper'>
        <?= $this->Form->password('password', ['placeholder' => 'Password', 'class' => 'login_field']) ?>
      </div>
      <?= $this->Form->button('Login', ['type' => 'submit', 'id' => 'login_button']) ?>
      <?= $this->Form->end() ?>
      <h3 id='login_acc_inquiry'>Don't have an account? <a class='register_trigger'>Create one for free!<a></h3>
    </div>
  </body>
</html>
