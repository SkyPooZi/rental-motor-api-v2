<?php

namespace App\Repositories\Discount;

use LaravelEasyRepository\Repository;

interface DiscountRepository extends Repository
{
    public function getAllWithSearch($request);
    public function findById($id);
    public function insertData($data);
}
