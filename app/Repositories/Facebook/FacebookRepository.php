<?php

namespace App\Repositories\Facebook;

use LaravelEasyRepository\Repository;

interface FacebookRepository extends Repository
{
    public function getAllWithSearch($request);
    public function findById($id);
    public function insertData($data);
}
