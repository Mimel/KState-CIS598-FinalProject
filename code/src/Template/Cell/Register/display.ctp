<div id="register_modal">
  <div id="register_flex_container">
    <div id="register_modal_window">
      <?= $this->Form->create('Login', ['url' => ['controller' => 'Login', 'action' => 'register'], 'id' => 'register_form']) ?>
        <h2 id="register_header">Create an Account!</h2>
        <div class="register_singleline_wrapper">
          <?= $this->Form->text('username', ['class' => 'register_input', 'placeholder' => 'Username']) ?>
        </div>
        <div class="register_singleline_wrapper">
          <?= $this->Form->email('email', ['class' => 'register_input', 'placeholder' => 'Email Address']) ?>
        </div>
        <div class="register_singleline_wrapper">
          <?= $this->Form->password('password', ['class' => 'register_input', 'placeholder' => 'Password']) ?>
        </div>
      <?= $this->Form->button('Register!', ['type' => 'submit']) ?>
      <?= $this->Form->end() ?>
    </div>
  </div>
</div>
