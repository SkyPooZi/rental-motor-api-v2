<?php

namespace App\Repositories\PaymentGateway;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\PaymentGateway;

class PaymentGatewayRepositoryImplement extends Eloquent implements PaymentGatewayRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected PaymentGateway $model;

    public function __construct(PaymentGateway $model)
    {
        $this->model = $model;
    }

    public function getAllWithSearch($request)
    {
        $search = $request->input('search');
        $orderNumber = $request->input('order_number');
        $orderDate = $request->input('order_date');
        $paymentDate = $request->input('payment_date');
        $paymentMethod = $request->input('payment_method');
        $paymentStatus = $request->input('payment_status');
        $totalOrder = $request->input('total_order');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = $this->model->newQuery();

        if ($search) {
            $query->whereHas('history', function ($query) use ($search) {
                $query->where('full_name', 'like', "%$search%");
            });
        }

        if ($orderNumber) {
            $query->where('order_number', 'like', "%$orderNumber%");
        }

        if ($orderDate) {
            $query->where('order_date', '==', $orderDate);
        }

        if ($paymentDate) {
            $query->where('payment_date', '==', $paymentDate);
        }

        if ($paymentMethod) {
            $query->where('payment_method', 'like', "%$paymentMethod%");
        }

        if ($paymentStatus) {
            $query->where('payment_status', 'like', "%$paymentStatus%");
        }

        if ($totalOrder) {
            $query->where('total_order', 'like', "%$totalOrder%");
        }

        if ($startDate || $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function findById($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function insertData($data)
    {
        $paymentGateway = new $this->model;

        $paymentGateway->history_id = $data['history_id'];
        $paymentGateway->order_number = $data['order_number'];
        $paymentGateway->order_date = $data['order_date'];
        $paymentGateway->payment_date = $data['payment_date'];
        $paymentGateway->payment_method = $data['payment_method'];
        $paymentGateway->payment_status = $data['payment_status'];
        $paymentGateway->total_order = $data['total_order'];

        $paymentGateway->save();

        return $paymentGateway;
    }
}
