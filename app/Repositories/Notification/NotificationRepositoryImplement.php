<?php

namespace App\Repositories\Notification;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Notification;

class NotificationRepositoryImplement extends Eloquent implements NotificationRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Notification $model;

    public function __construct(Notification $model)
    {
        $this->model = $model;
    }

    public function getAllWithSearch($request)
    {
        $search = $request->input('search');
        $discountName = $request->input('discount_name');
        $historyName = $request->input('history_name');
        $historyStatus = $request->input('history_status');
        $message = $request->input('message');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $query = $this->model->newQuery();

        if ($search) {
            $query->whereHas('user', function ($query) use ($search) {
                $query->where('username', 'like', "%$search%")
                    ->orWhere('full_name', 'like', "%$search%");
            });
        }

        if ($discountName) {
            $query->whereHas('discount', function ($query) use ($discountName) {
                $query->where('discount_name', 'like', "%$discountName%");
            });
        }

        if ($historyName) {
            $query->whereHas('history', function ($query) use ($historyName) {
                $query->where('full_name', 'like', "%$historyName%");
            });
        }

        if ($historyStatus) {
            $query->where('history_status', 'like', "%$historyStatus%");
        }

        if ($message) {
            $query->where('message', 'like', "%$message%");
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
        $notification = new $this->model;

        $notification->user_id = $data['user_id'];
        $notification->discount_id = $data['discount_id'];
        $notification->history_id = $data['history_id'];
        $notification->history_status = $data['history_status'];
        $notification->message = $data['message'];

        $notification->save();

        return $notification;
    }
}
