<?php

namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class CommentsTable extends Table {
  public function initialize(array $config) {
    $this->setTable('comments');
    $this->belongsTo('Users')->setForeignKey('commenter');
    $this->belongsTo('Posts')->setForeignKey('post_id');
    $this->hasOne('Recipes')->setForeignKey('vehicle_id');
  }
}

?>
