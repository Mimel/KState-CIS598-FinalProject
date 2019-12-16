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

    // Collect all form tags. TODO work here!
    $allTags = '';
    foreach($queryData as $key => $value) {
      if(substr($key, 0, 1) == '_' && $value == 1) {
        $allTags = $allTags . ',' . substr($key, 1);
      }
    }

    $this->log($allTags);

    return $this->redirect('/browse?s=' . $queryString . '&t=' . substr($allTags, 1));
  }
}

?>
