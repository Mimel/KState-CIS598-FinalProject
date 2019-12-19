<?php

namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class UsersTable extends Table { //TODO what is class Table?
  public function initialize(array $config) {
    $this->setTable('users');
    $this->setPrimaryKey('username');
  }

  public function validationDefault(Validator $validator) {
    $validator = new Validator();

    $validator->requirePresence('username', 'email', 'password')
      ->minlength('username', 3, 'Username must be at least 3 characters.')
      ->email('email', 'Email must be valid.')
      ->minLength('password', 8, 'Password must contain at least 8 characters.');

    return $validator;
  }
}

?>
