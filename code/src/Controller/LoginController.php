<?php

namespace App\Controller;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class LoginController extends AppController {
  public function register() {

    $usersTable = TableRegistry::getTableLocator()->get('Users');
    $newUser = $usersTable->newEntity();
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
    // TODO Sanitize.
    $result = $this->Authentication->getResult();
    return $this->redirect($this->referer());
  }

  public function logout() {
    return $this->redirect($this->Authentication->logout());
  }


  public function index() {

  }
}

?>
