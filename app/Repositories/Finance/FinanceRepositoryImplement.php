<?php

namespace App\Repositories\Finance;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Finance;

class FinanceRepositoryImplement extends Eloquent implements FinanceRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Finance $model;

    public function __construct(Finance $model)
    {
        $this->model = $model;
    }

    public function getAllWithSearch($request)
    {
        $search = $request->input('search');
        $totalMotorcyclePrice = $request->input('total_motorcycle_price');
        $totalOvertimeFee = $request->input('total_overtime_fee');
        $totalDeliveryFee = $request->input('total_delivery_fee');
        $totalPointDeduction = $request->input('total_point_deduction');
        $totalDiscountFee = $request->input('total_discount_fee');
        $totalAdminFee = $request->input('total_admin_fee');
        $totalRescheduleFee = $request->input('total_reschedule_fee');
        $totalPayment = $request->input('total_payment');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = $this->model->newQuery();

        if ($search) {
            $query->whereHas('history', function ($query) use ($search) {
                $query->where('full_name', 'like', "%$search%");
            });
        }

        if ($totalMotorcyclePrice) {
            $query->where('total_motorcycle_price', 'like', "%$totalMotorcyclePrice%");
        }

        if ($totalOvertimeFee) {
            $query->where('total_overtime_fee', 'like', "%$totalOvertimeFee%");
        }

        if ($totalDeliveryFee) {
            $query->where('total_delivery_fee', 'like', "%$totalDeliveryFee%");
        }

        if ($totalPointDeduction) {
            $query->where('total_point_deduction', 'like', "%$totalPointDeduction%");
        }

        if ($totalDiscountFee) {
            $query->where('total_discount_fee', 'like', "%$totalDiscountFee%");
        }

        if ($totalAdminFee) {
            $query->where('total_admin_fee', 'like', "%$totalAdminFee%");
        }

        if ($totalRescheduleFee) {
            $query->where('total_reschedule_fee', 'like', "%$totalRescheduleFee%");
        }

        if ($totalPayment) {
            $query->where('total_payment', 'like', "%$totalPayment%");
        }

        if ($startDate) {
            $query->where('start_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('end_date', '<=', $endDate);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function findById($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function insertData($data)
    {
        $finance = new $this->model;

        $finance->history_id = $data['history_id'];
        $finance->total_motorcycle_price = $data['total_motorcycle_price'];
        $finance->total_overtime_fee = $data['total_overtime_fee'];
        $finance->total_delivery_fee = $data['total_delivery_fee'];
        $finance->total_point_deduction = $data['total_point_deduction'];
        $finance->total_discount_fee = $data['total_discount_fee'];
        $finance->total_admin_fee = $data['total_admin_fee'];
        $finance->total_reschedule_fee = $data['total_reschedule_fee'] ?? null;
        $finance->total_payment = $data['total_payment'];

        $finance->save();

        return $finance;
    }
}
