<?php

namespace App\Repositories\PaymentGateway;

use LaravelEasyRepository\Repository;

interface PaymentGatewayRepository extends Repository
{
    public function getAllWithSearch($request);
    public function findById($id);
    public function insertData($data);
}
