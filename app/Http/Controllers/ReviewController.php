<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\Review\ReviewService;

class ReviewController extends Controller
{
    // Review Services
    private $reviewService;

    public function __construct(
        ReviewService $reviewService,
    ) {
        $this->reviewService = $reviewService;
    }

    public function getAllWithSearch(Request $request)
    {
        $reviewData = $this->reviewService->getAllWithSearch($request);

        if (!$reviewData) {
            return response()->json([
                'success' => false,
                'code' => 200,
                'data' => [],
            ], 200);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Data berhasil ditemukan',
            'data' => $reviewData,
        ], 200);
    }

    public function findById($id)
    {
        $reviewData = $this->reviewService->findById($id);

        if (!$reviewData) {
            return response()->json([
                'success' => false,
                'code' => 200,
                'data' => [],
            ], 200);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Data berhasil ditemukan',
            'data' => $reviewData,
        ], 200);
    }

    public function createData(Request $request)
    {
        $data = $request->only([
            'image',
            'user_id',
            'rating',
            'comment',
        ]);

        try {
            $reviewData = $this->reviewService->insertData($data);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Data berhasil dibuat',
                'data' => $reviewData,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => 'Gagal membuat data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateData($id, Request $request)
    {
        $data = $request->only([
            'image',
            'user_id',
            'rating',
            'comment',
        ]);

        try {
            $this->reviewService->updateData($id, $data);

            $reviewData = $this->reviewService->findByid($id);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Data berhasil disimpan',
                'data' => $reviewData,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteData($id)
    {
        try {
            $reviewData = $this->reviewService->deleteData($id);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Data berhasil dihapus',
                'data' => $reviewData,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => 'Gagal menghapus data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
