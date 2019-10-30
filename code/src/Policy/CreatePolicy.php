<?php
namespace App\Policy;

use App\Model\Entity\User;
use Authorization\IdentityInterface;
use Authorization\Policy\Result;

class CreatePolicy {
  public function canCreate(IdentityInterface $user, User $u) {
    return new Result(true);
  }
}

?>
