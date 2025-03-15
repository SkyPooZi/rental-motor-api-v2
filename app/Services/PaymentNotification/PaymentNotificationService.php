<?php

namespace App\Services\PaymentNotification;

use LaravelEasyRepository\BaseService;

interface PaymentNotificationService extends BaseService
{
    public function getAllWithSearch($request);
    public function findById($id);
    public function insertData(array $data);
    public function updateData($id, array $data);
    public function deleteData($id);
}
