<?php

namespace App\Controller;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class LoginController extends AppController {

  // Registers a new user based on the register modal form data.
  public function register() {
    //$this->Authorization->skipAuthorization();

    $usersTable = TableRegistry::getTableLocator()->get('Users');

    if($this->request->is('post')) {
      // Registers a new user.
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

  // Logs a user in.
  public function login() {
    // TODO Sanitize.
    $result = $this->Authentication->getResult();
    return $this->redirect($this->referer());
  }

  // Logs the user out.
  public function logout() {
    return $this->redirect($this->Authentication->logout());
  }

  // Loads the /login page.
  public function index() {
    // This function should do nothing/
  }
}

?>
