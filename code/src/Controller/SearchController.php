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

    $allTags = '';
    foreach($queryData as $key => $value) {
      if(substr($key, 0, 1) == '_' && $value == 1) {
        $allTags = $allTags . ',' . substr($key, 1);
      }
    }

    $this->log($allTags);

    $url = '/browse';
    if($queryString != '') {
      $url = $url . '?s=' . $queryString;
      if($allTags != '') {
        $url = $url . '&t=' . substr($allTags, 1);
      }
    } else if($allTags != '') {
      $url = $url . '?t=' . substr($allTags, 1);
    }

    return $this->redirect($url);
  }
}

?>
