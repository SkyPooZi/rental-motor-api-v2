<?php

namespace App\Repositories\Notification;

use LaravelEasyRepository\Repository;

interface NotificationRepository extends Repository
{
    public function getAllWithSearch($request);
    public function findById($id);
    public function insertData($data);
}
