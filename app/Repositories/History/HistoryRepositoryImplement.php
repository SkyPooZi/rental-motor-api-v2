<?php

namespace App\Repositories\History;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\History;

class HistoryRepositoryImplement extends Eloquent implements HistoryRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected History $model;

    public function __construct(History $model)
    {
        $this->model = $model;
    }

    public function getAllWithSearch($request)
    {
        $search = $request->input('search');
        $fullName = $request->input('full_name');
        $email = $request->input('email');
        $phoneNumber = $request->input('phone_number');
        $socialMediaAccount = $request->input('social_media_account');
        $address = $request->input('address');
        $renter = $request->input('renter');
        $motorcycleName = $request->input('motorcycle_name');
        $startDate = $request->input('start_date');
        $duration = $request->input('duration');
        $endDate = $request->input('end_date');
        $renterPurpose = $request->input('renter_purpose');
        $motorcycleReceipt = $request->input('motorcycle_receipt');
        $emergencyContactName = $request->input('emergency_contact_name');
        $emergencyContactNumber = $request->input('emergency_contact_number');
        $emergencyContactRelationship = $request->input('emergency_contact_relationship');
        $point = $request->input('point');
        $discountName = $request->input('discount_name');
        $paymentMethod = $request->input('payment_method');
        $totalPayment = $request->input('total_payment');
        $historyStatus = $request->input('history_status');
        $reviewName = $request->input('review_name');
        $cancellationDate = $request->input('cancellation_date');
        $cancellationReason = $request->input('cancellation_reason');

        $query = $this->model->newQuery();

        if ($search) {
            $query->whereHas('user', function ($query) use ($search) {
                $query->where('username', 'like', "%$search%")
                    ->orWhere('full_name', 'like', "%$search%");
            });
        }

        if ($fullName) {
            $query->where('full_name', 'like', "%$fullName%");
        }

        if ($email) {
            $query->where('email', 'like', "%$email%");
        }

        if ($phoneNumber) {
            $query->where('phone_number', 'like', "%$phoneNumber%");
        }

        if ($socialMediaAccount) {
            $query->where('social_media_account', 'like', "%$socialMediaAccount%");
        }

        if ($address) {
            $query->where('address', 'like', "%$address%");
        }

        if ($renter) {
            $query->where('renter', 'like', "%$renter%");
        }

        if ($motorcycleName) {
            $query->whereHas('motorcycleList', function ($query) use ($motorcycleName) {
                $query->where('name', 'like', "%$motorcycleName%");
            });
        }

        if ($startDate) {
            $query->where('start_date', '>=', $startDate);
        }

        if ($duration) {
            $query->where('duration', 'like', "%$duration%");
        }

        if ($endDate) {
            $query->where('end_date', '<=', $endDate);
        }

        if ($renterPurpose) {
            $query->where('renter_purpose', 'like', "%$renterPurpose%");
        }

        if ($motorcycleReceipt) {
            $query->where('motorcycle_receipt', 'like', "%$motorcycleReceipt%");
        }

        if ($emergencyContactName) {
            $query->where('emergency_contact_name', 'like', "%$emergencyContactName%");
        }

        if ($emergencyContactNumber) {
            $query->where('emergency_contact_number', 'like', "%$emergencyContactNumber%");
        }

        if ($emergencyContactRelationship) {
            $query->where('emergency_contact_relationship', 'like', "%$emergencyContactRelationship%");
        }

        if ($point) {
            $query->where('point', 'like', "%$point%");
        }

        if ($discountName) {
            $query->whereHas('discount', function ($query) use ($discountName) {
                $query->where('discount_name', 'like', "%$discountName%");
            });
        }

        if ($paymentMethod) {
            $query->where('payment_method', 'like', "%$paymentMethod%");
        }

        if ($totalPayment) {
            $query->where('total_payment', 'like', "%$totalPayment%");
        }

        if ($historyStatus) {
            $query->where('history_status', 'like', "%$historyStatus%");
        }

        if ($reviewName) {
            $query->whereHas('review', function ($query) use ($reviewName) {
                $query->whereHas('user', function ($query) use ($reviewName) {
                    $query->where('username', 'like', "%$reviewName%")
                        ->orWhere('full_name', 'like', "%$reviewName%");
                });
            });
        }

        if ($cancellationDate) {
            $query->where('cancellation_date', 'like', "%$cancellationDate%");
        }

        if ($cancellationReason) {
            $query->where('cancellation_reason', 'like', "%$cancellationReason%");
        }

        return $query->with(['user', 'motorcycleList', 'discount', 'review'])->orderBy('created_at', 'desc')->get();
    }

    public function findById($id)
    {
        return $this->model->with(['user', 'motorcycleList', 'discount', 'review'])->where('id', $id)->first();
    }

    public function insertData($data)
    {
        $history = new $this->model;

        $history->user_id = $data['user_id'];
        $history->full_name = $data['full_name'];
        $history->email = $data['email'];
        $history->phone_number = $data['phone_number'];
        $history->social_media_account = $data['social_media_account'];
        $history->address = $data['address'];
        $history->renter = $data['renter'];
        $history->motorcycle_id = $data['motorcycle_id'];
        $history->start_date = $data['start_date'];
        $history->duration = $data['duration'];
        $history->end_date = $data['end_date'];
        $history->renter_purpose = $data['renter_purpose'];
        $history->motorcycle_receipt = $data['motorcycle_receipt'];
        $history->emergency_contact_name = $data['emergency_contact_name'];
        $history->emergency_contact_number = $data['emergency_contact_number'];
        $history->emergency_contact_relationship = $data['emergency_contact_relationship'];
        $history->point = $data['point'];
        $history->discount_id = $data['discount_id'];
        $history->payment_method = $data['payment_method'];
        $history->total_payment = $data['total_payment'];
        $history->history_status = $data['history_status'];
        $history->review_id = $data['review_id'];
        $history->cancellation_date = $data['cancellation_date'];
        $history->cancellation_reason = $data['cancellation_reason'];

        $history->save();

        return $history;
    }
}
