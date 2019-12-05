<?php

namespace App\Controller;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class SearchController extends AppController {

  public function lookup() {
    $this->log('line');
    $queryData = $this->request->getData();
    $queryString = strtolower($queryData['recipequery']);

    return $this->redirect('/browse?s=' . $queryString);
  }
}

?>
