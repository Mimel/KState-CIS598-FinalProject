<?php

namespace App\Model\Table;
use Cake\ORM\Table;

class UserInfo extends Table { //TODO what is class Table?
  public function initialize(array $config) {
    $this->setTable('users');
  }
}

?>
