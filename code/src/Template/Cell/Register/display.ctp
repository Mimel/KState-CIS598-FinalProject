<div id="register_modal">
  <div id="flex_container">
    <div id="register_modal_window">
      <?= $this->Form->create('Login', ['controller' => 'Login', 'action' => 'register', 'id' => 'header_register_form']) ?>
      <?= $this->Form->text('username') ?>
      <?= $this->Form->email('email') ?>
      <?= $this->Form->password('password') ?>
      <?= $this->Form->button('Register', ['type' => 'submit']) ?>
      <?= $this->Form->end() ?>
    </div>
  </div>
</div>
