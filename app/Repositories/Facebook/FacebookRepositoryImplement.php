<?php

namespace App\Repositories\Facebook;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Facebook;

class FacebookRepositoryImplement extends Eloquent implements FacebookRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Facebook $model;

    public function __construct(Facebook $model)
    {
        $this->model = $model;
    }

    public function getAllWithSearch($request)
    {
        $search = $request->input('search');
        $accessToken = $request->input('access_token');
        $userId = $request->input('user_id');
        $loginDate = $request->input('login_date');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = $this->model->newQuery();

        if ($search) {
            $query->whereHas('user', function ($query) use ($search) {
                $query->where('username', 'like', "%$search%")
                    ->orWhere('full_name', 'like', "%$search%");
            });
        }

        if ($accessToken) {
            $query->where('access_token', 'like', "%$accessToken%");
        }

        if ($userId) {
            $query->where('user_id', 'like', "%$userId%");
        }

        if ($loginDate) {
            $query->where('login_date', '==', $loginDate);
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
        $google = new $this->model;

        $google->access_token = $data['access_token'];
        $google->user_id = $data['user_id'];
        $google->login_date = $data['login_date'];

        $google->save();

        return $google;
    }
}
