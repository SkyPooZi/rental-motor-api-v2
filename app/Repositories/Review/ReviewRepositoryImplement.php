<?php

namespace App\Repositories\Review;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Review;

class ReviewRepositoryImplement extends Eloquent implements ReviewRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Review $model;

    public function __construct(Review $model)
    {
        $this->model = $model;
    }

    public function getAllWithSearch($request)
    {
        $search = $request->input('search');
        $rating = $request->input('rating');
        $comment = $request->input('comment');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = $this->model->newQuery();

        if ($search) {
            $query->whereHas('user', function ($query) use ($search) {
                $query->where('username', 'like', "%$search%")
                    ->orWhere('full_name', 'like', "%$search%");
            });
        }

        if ($rating) {
            $query->where('rating', 'like', "%$rating%");
        }

        if ($comment) {
            $query->where('comment', 'like', "%$comment%");
        }

        if ($startDate || $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->with(['user'])->orderBy('created_at', 'desc')->get();
    }

    public function findById($id)
    {
        return $this->model->with(['user'])->where('id', $id)->first();
    }

    public function insertData($data)
    {
        $review = new $this->model;

        $review->image = $data['image'] ?? null;
        $review->user_id = $data['user_id'];
        $review->rating = $data['rating'];
        $review->comment = $data['comment'];

        $review->save();

        return $review;
    }
}
