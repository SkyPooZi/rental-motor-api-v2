<?php

namespace App\Services\Finance;

use LaravelEasyRepository\BaseService;

interface FinanceService extends BaseService
{
    public function getAllWithSearch($request);
    public function findById($id);
    public function insertData(array $data);
    public function updateData($id, array $data);
    public function deleteData($id);
}
