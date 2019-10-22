<?php

namespace App\Controller;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class LoginController extends AppController {
  public function login() {
    $result = $this->Authentication->getResult();

    //$usersTable = TableRegistry::getTableLocator()->get('Users');
    //$newUser = $usersTable->newEntity();
    //$newUser->username = 'admin';
    //$newUser->email = 'whatev@whatev.com';
    //$newUser->password = password_hash('pass', PASSWORD_DEFAULT);

    //$usersTable->save($newUser);

    if($result->isValid()) {
      $this->log($this->Authentication->getIdentity());
    } else {
      $this->log($this->Authentication->getIdentity());
    }

    $this->log($result);
    //return $this->here;
  }

  public function logout() {
    return $this->redirect($this->Authentication->logout());
  }

  public function beforeFilter(Event $event) {
    $this->Authentication->allowUnauthenticated(['view']);
  }

  public function index() {

  }
}

?>
