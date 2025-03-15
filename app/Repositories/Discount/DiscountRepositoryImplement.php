<?php

namespace App\Repositories\Discount;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Discount;

class DiscountRepositoryImplement extends Eloquent implements DiscountRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Discount $model;

    public function __construct(Discount $model)
    {
        $this->model = $model;
    }

    public function getAllWithSearch($request)
    {
        $search = $request->input('search');
        $discountCode = $request->input('discount_code');
        $discountPrice = $request->input('discount_price');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $isHidden = $request->input('is_hidden');

        $query = $this->model->newQuery();

        if ($search) {
            $query->where('discount_name', 'like', "%$search%");
        }

        if ($discountCode) {
            $query->where('discount_code', 'like', "%$discountCode%");
        }

        if ($discountPrice) {
            $query->where('discount_price', 'like', "%$discountPrice%");
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
        $discount = new $this->model;

        $discount->image = $data['image'];
        $discount->discount_code = $data['discount_code'];
        $discount->discount_name = $data['discount_name'];
        $discount->discount_price = $data['discount_price'];
        $discount->start_date = $data['start_date'];
        $discount->end_date = $data['end_date'];
        $discount->is_hidden = $data['is_hidden'];

        $discount->save();

        return $discount;
    }
}
