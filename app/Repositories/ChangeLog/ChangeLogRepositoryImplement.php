<?php

namespace App\Repositories\ChangeLog;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\ChangeLog;

class ChangeLogRepositoryImplement extends Eloquent implements ChangeLogRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected ChangeLog $model;

    public function __construct(ChangeLog $model)
    {
        $this->model = $model;
    }

    public function getAllWithSearch($request)
    {
        $search = $request->input('search');
        $motorcycleName = $request->input('motorcycle_name');
        $historyName = $request->input('history_name');
        $previousData = $request->input('previous_data');
        $updatedData = $request->input('updated_data');
        $changedAt = $request->input('changed_at');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = $this->model->newQuery();

        if ($search) {
            $query->whereHas('user', function ($query) use ($search) {
                $query->where('username', 'like', "%$search%")
                    ->orWhere('full_name', 'like', "%$search%");
            });
        }

        if ($motorcycleName) {
            $query->whereHas('motorcycleList', function ($query) use ($motorcycleName) {
                $query->where('name', 'like', "%$motorcycleName%");
            });
        }

        if ($historyName) {
            $query->whereHas('history', function ($query) use ($historyName) {
                $query->where('full_name', 'like', "%$historyName%");
            });
        }

        if ($previousData) {
            $query->where('previous_data', 'like', "%$previousData%");
        }

        if ($updatedData) {
            $query->where('updated_data', 'like', "%$updatedData%");
        }

        if ($changedAt) {
            $query->where('changed_at', '==', $changedAt);
        }

        if ($startDate || $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->with(['user', 'history', 'motorcycleList', 'discount', 'review'])->orderBy('created_at', 'desc')->get();
    }

    public function findById($id)
    {
        return $this->model->with(['user', 'history', 'motorcycleList', 'discount', 'review'])->where('id', $id)->first();
    }

    public function insertData($data)
    {
        $changeLog = new $this->model;
        $changeLog->user_id = $data['user_id'];
        $changeLog->motorcycle_id = $data['motorcycle_id'];
        $changeLog->history_id = $data['history_id'];
        $changeLog->previous_data = $data['previous_data'];
        $changeLog->updated_data = $data['updated_data'];
        $changeLog->changed_at = $data['changed_at'];

        $changeLog->save();

        return $changeLog;
    }
}
