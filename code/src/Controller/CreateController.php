<?php
namespace App\Controller;

use App\Controller\AppController;

class CreateController extends AppController {

  public function addrecipe() {
    
  }


  public function index() {

    // This page shouldn't be accessed if user is not logged in.
    if(!$this->getRequest()->getSession()->check('Auth')) {
      return $this->redirect([
        'controller' => 'Login', 'action' => 'index'
      ]);
    }
  }
}
?>
