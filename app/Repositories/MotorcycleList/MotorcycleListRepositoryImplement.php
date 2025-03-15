<?php

namespace App\Repositories\MotorcycleList;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\MotorcycleList;

class MotorcycleListRepositoryImplement extends Eloquent implements MotorcycleListRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected MotorcycleList $model;

    public function __construct(MotorcycleList $model)
    {
        $this->model = $model;
    }

    public function getAllWithSearch($request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $brand = $request->input('brand');
        $stock = $request->input('stock');
        $pricePerDay = $request->input('price_per_day');
        $pricePerWeek = $request->input('price_per_week');
        $deliveryPrice = $request->input('delivery_price');
        $unavailableStartDate = $request->input('unavailable_start_date');
        $unavailableEndDate = $request->input('unavailable_end_date');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $isHidden = $request->input('is_hidden');

        $query = $this->model->newQuery();

        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        if ($type) {
            $query->where('type', 'like', "%$type%");
        }

        if ($brand) {
            $query->where('brand', 'like', "%$brand%");
        }

        if ($stock) {
            $query->where('stock', 'like', "%$stock%");
        }

        if ($pricePerDay) {
            $query->where('price_per_day', 'like', "%$pricePerDay%");
        }

        if ($pricePerWeek) {
            $query->where('price_per_week', 'like', "%$pricePerWeek%");
        }

        if ($deliveryPrice) {
            $query->where('delivery_price', 'like', "%$deliveryPrice%");
        }

        if ($unavailableStartDate) {
            $query->where('unavailable_start_date', '>=', $unavailableStartDate);
        }

        if ($unavailableEndDate) {
            $query->where('unavailable_end_date', '<=', $unavailableEndDate);
        }

        if ($startDate || $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
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
        $motorcycleList = new $this->model;

        $motorcycleList->image = $data['image'];
        $motorcycleList->name = $data['name'];
        $motorcycleList->type = $data['type'];
        $motorcycleList->brand = $data['brand'];
        $motorcycleList->stock = $data['stock'];
        $motorcycleList->price_per_day = $data['price_per_day'];
        $motorcycleList->price_per_week = $data['price_per_week'];
        $motorcycleList->delivery_price = $data['delivery_price'];
        $motorcycleList->status = $data['status'];
        $motorcycleList->unavailable_start_date = $data['unavailable_start_date'] ?? null;
        $motorcycleList->unavailable_end_date = $data['unavailable_end_date'] ?? null;
        $motorcycleList->is_hidden = $data['is_hidden'];

        $motorcycleList->save();

        return $motorcycleList;
    }
}
