<?php

namespace App\Model\Table;
use Cake\ORM\Table;

class PostsTable extends Table { //TODO what is class Table?
  public function initialize(array $config) {
    $this->setTable('posts');
    $this->belongsTo('Users')->setForeignKey('author');
    $this->hasOne('Recipes')->setForeignKey('vehicle_id');
  }
}

?>
