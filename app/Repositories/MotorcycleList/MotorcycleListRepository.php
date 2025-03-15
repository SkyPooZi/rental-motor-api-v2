<?php

namespace App\Repositories\MotorcycleList;

use LaravelEasyRepository\Repository;

interface MotorcycleListRepository extends Repository
{
    public function getAllWithSearch($request);
    public function findById($id);
    public function insertData($data);
}
