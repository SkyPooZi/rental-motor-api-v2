<?php

namespace App\Repositories\User;

use LaravelEasyRepository\Repository;

interface UserRepository extends Repository
{
    public function createUserWithRole($dataUser, $role);
    public function banUser($id, $reason);
    public function unBanUser($id);
}
