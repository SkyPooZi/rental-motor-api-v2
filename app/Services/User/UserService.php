<?php

namespace App\Services\User;

use LaravelEasyRepository\BaseService;

interface UserService extends BaseService
{
    public function createUserWithRole($dataUser, $role);
    public function deleteUserData($id);
    public function banUser($id, $reason);
    public function unBanUser($id);
    public function updateUserData($id, $data);
    public function changePassword($user, $data);
}
