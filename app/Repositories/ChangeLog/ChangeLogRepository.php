<?php

namespace App\Repositories\ChangeLog;

use LaravelEasyRepository\Repository;

interface ChangeLogRepository extends Repository
{
    public function getAllWithSearch($request);
    public function findById($id);
    public function insertData($data);
}
