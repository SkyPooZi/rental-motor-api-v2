<?php

namespace App\Repositories\Finance;

use LaravelEasyRepository\Repository;

interface FinanceRepository extends Repository
{
    public function getAllWithSearch($request);
    public function findById($id);
    public function insertData($data);
}
