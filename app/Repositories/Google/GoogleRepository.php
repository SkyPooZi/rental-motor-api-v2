<?php

namespace App\Repositories\Google;

use LaravelEasyRepository\Repository;

interface GoogleRepository extends Repository
{
    public function getAllWithSearch($request);
    public function findById($id);
    public function insertData($data);
}
