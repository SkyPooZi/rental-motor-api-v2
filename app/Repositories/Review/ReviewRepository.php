<?php

namespace App\Repositories\Review;

use LaravelEasyRepository\Repository;

interface ReviewRepository extends Repository
{
    public function getAllWithSearch($request);
    public function findById($id);
    public function insertData($data);
}
