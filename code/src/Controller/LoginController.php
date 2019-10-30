<?php

namespace App\Controller;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class LoginController extends AppController {
  public function register() {
    //$this->Authorization->skipAuthorization();

    $usersTable = TableRegistry::getTableLocator()->get('Users');
    $newUser = $usersTable->newEntity();

    // TODO Sanitize.
    if($this->request->is('post')) {
      $userInfo = $this->request->getData();
      $newUser->username = $userInfo['username'];
      $newUser->email = $userInfo['email'];
      $newUser->password = password_hash($userInfo['password'], PASSWORD_DEFAULT);
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
