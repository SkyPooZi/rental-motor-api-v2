<?php

namespace App\Repositories\History;

use LaravelEasyRepository\Repository;

interface HistoryRepository extends Repository
{
    public function getAllWithSearch($request);
    public function findById($id);
    public function insertData($data);
}
