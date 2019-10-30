<?php

namespace App\Model\Table;
use Cake\ORM\Table;

class UsersTable extends Table { //TODO what is class Table?
  public function initialize(array $config) {
    $this->setTable('users');
    $this->setPrimaryKey('username');
  }
}

?>
