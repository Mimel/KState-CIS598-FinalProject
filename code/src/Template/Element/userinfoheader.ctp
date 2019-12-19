<header id="auth_header">
  <?= $this->Html->link('<img src="/img/gastrohub_logo_small.png" />',
  '/',
  ['escape' => false]);
  ?>
  <?= $this->Form->create('HeaderSearch', ['url' => ['controller' => 'Search', 'action' => 'lookup'], 'id' => 'header_search_form']) ?>
  <?= $this->Form->text('recipequery', ['placeholder' => 'Search recipes...']) ?>
  <?= $this->Form->button('Submit', ['type' => 'submit', 'hidden']) ?>
  <?= $this->Form->end() ?>
  <?php if(!$this->getRequest()->getSession()->check('Auth')): ?>
    <?= $this->Form->create('Login', ['url' => ['controller' => 'Login', 'action' => 'login'], 'id' => 'header_login_form']) ?>
    <div id="header_salutation"><a class="register_trigger">Register</a> or Login:</div>
    <?= $this->Form->text('username', ['placeholder' => 'Username']) ?>
    <?= $this->Form->password('password', ['placeholder' => 'Password']) ?>
    <?= $this->Form->button('Login', ['type' => 'submit']) ?>
    <?= $this->Form->end() ?>
  <?php else: ?>
    <div id="header_salutation">Greetings, <?= h($this->getRequest()->getSession()->read('Auth.username')) ?>!</div>
    <?= $this->Html->link('Logout', ['controller' => 'Login', 'action' => 'logout']) ?>
  <?php endif ?>
</header>
<?php echo $this->Html->script("reg_modal_trigger") ?>
