<?php

namespace App\Repositories\PaymentNotification;

use LaravelEasyRepository\Repository;

interface PaymentNotificationRepository extends Repository
{
    public function getAllWithSearch($request);
    public function findById($id);
    public function insertData($data);
}
