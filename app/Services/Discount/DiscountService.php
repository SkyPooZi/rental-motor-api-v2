<?php

namespace App\Services\Discount;

use LaravelEasyRepository\BaseService;

interface DiscountService extends BaseService
{
    public function getAllWithSearch($request);
    public function findById($id);
    public function insertData(array $data);
    public function updateData($id, array $data);
    public function deleteData($id);
}
