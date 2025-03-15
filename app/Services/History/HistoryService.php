<?php

namespace App\Services\History;

use LaravelEasyRepository\BaseService;

interface HistoryService extends BaseService
{
    public function getAllWithSearch($request);
    public function findById($id);
    public function insertData(array $data);
    public function updateData($id, array $data);
    public function deleteData($id);
}
