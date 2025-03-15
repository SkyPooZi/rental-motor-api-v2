<?php

namespace App\Services\Notification;

use LaravelEasyRepository\BaseService;

interface NotificationService extends BaseService
{
    public function getAllWithSearch($request);
    public function findById($id);
    public function insertData(array $data);
    public function updateData($id, array $data);
    public function deleteData($id);
}
