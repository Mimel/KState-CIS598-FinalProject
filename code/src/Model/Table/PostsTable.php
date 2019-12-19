<?php

namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class PostsTable extends Table { //TODO what is class Table?
  public function initialize(array $config) {
    $this->setTable('posts');
    $this->belongsTo('Users')->setForeignKey('author');
    $this->hasOne('Recipes')->setForeignKey('vehicle_id');
  }

  public function validationDefault(Validator $validator) {
    $validator = new Validator();

    $validator->requirePresence('slug', 'image', 'title', 'author', 'description')
      ->minLength('slug', 1, 'Slug must not be empty.')
      ->minLength('title', 1, 'Title must not be empty.')
      ->minLength('author', 1, 'Author must not be empty.')
      ->minLength('description', 1, 'Description must not be empty.');
      
    return $validator;
  }
}

?>
