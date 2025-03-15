<?php

namespace App\Repositories\PaymentNotification;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\PaymentNotification;

class PaymentNotificationRepositoryImplement extends Eloquent implements PaymentNotificationRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected PaymentNotification $model;

    public function __construct(PaymentNotification $model)
    {
        $this->model = $model;
    }

    public function getAllWithSearch($request)
    {
        $search = $request->input('search');
        $historyName = $request->input('history_name');
        $message = $request->input('message');
        $totalAmount = $request->input('total_amount');
        $dueDate = $request->input('due_date');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $isHidden = $request->input('is_hidden');

        $query = $this->model->newQuery();

        if ($search) {
            $query->whereHas('user', function ($query) use ($search) {
                $query->where('username', 'like', "%$search%")
                    ->orWhere('full_name', 'like', "%$search%");
            });
        }

        if ($historyName) {
            $query->whereHas('history', function ($query) use ($historyName) {
                $query->where('full_name', 'like', "%$historyName%");
            });
        }

        if ($message) {
            $query->where('message', 'like', "%$message%");
        }

        if ($totalAmount) {
            $query->where('total_amount', 'like', "%$totalAmount%");
        }

        if ($dueDate) {
            $query->where('due_date', '==', $dueDate);
        }

        if ($startDate) {
            $query->where('start_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('end_date', '<=', $endDate);
        }

        if ($isHidden) {
            $query->where('is_hidden', $isHidden);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function findById($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function insertData($data)
    {
        $paymentNotification = new $this->model;

        $paymentNotification->user_id = $data['user_id'];
        $paymentNotification->history_id = $data['history_id'];
        $paymentNotification->message = $data['message'];
        $paymentNotification->total_amount = $data['total_amount'];
        $paymentNotification->due_date = $data['due_date'];
        $paymentNotification->is_hidden = $data['is_hidden'];

        $paymentNotification->save();

        return $paymentNotification;
    }
}
