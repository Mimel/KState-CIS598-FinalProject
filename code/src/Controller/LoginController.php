<?php

namespace App\Controller;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class LoginController extends AppController {
  public function register() {
    //$this->Authorization->skipAuthorization();

    $usersTable = TableRegistry::getTableLocator()->get('Users');

    if($this->request->is('post')) {
      $newUser = $usersTable->newEntity($this->request->getData());
      if($newUser->errors()) {
        $this->log($newUser->errors());
        return;
      }
      $newUser->password = password_hash($this->request->getData()['password'], PASSWORD_DEFAULT);
      $usersTable->save($newUser);
    }

    return $this->redirect($this->referer());
  }

  public function login() {
    //$this->Authorization->skipAuthorization();

    // TODO Sanitize.
    $result = $this->Authentication->getResult();
    return $this->redirect($this->referer());
  }

  public function logout() {
    //$this->Authorization->skipAuthorization();

    return $this->redirect($this->Authentication->logout());
  }


  public function index() {
    //$this->Authorization->skipAuthorization();
  }
}

?>
