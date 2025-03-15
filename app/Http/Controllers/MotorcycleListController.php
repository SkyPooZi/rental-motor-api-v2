<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\MotorcycleList\MotorcycleListService;

class MotorcycleListController extends Controller
{
    // MotorcycleList Services
    private $motorcycleListService;

    public function __construct(
        MotorcycleListService $motorcycleListService,
    ) {
        $this->motorcycleListService = $motorcycleListService;
    }

    public function getAllWithSearch(Request $request)
    {
        $motorcycleListData = $this->motorcycleListService->getAllWithSearch($request);

        if (!$motorcycleListData) {
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
            'data' => $motorcycleListData,
        ], 200);
    }

    public function findById($id)
    {
        $motorcycleListData = $this->motorcycleListService->findById($id);

        if (!$motorcycleListData) {
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
            'data' => $motorcycleListData,
        ], 200);
    }

    public function createData(Request $request)
    {
        $data = $request->only([
            'image',
            'discount_code',
            'discount_name',
            'discount_price',
            'start_date',
            'end_date',
            'is_hidden',
        ]);

        try {
            $motorcycleListData = $this->motorcycleListService->insertData($data);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Data berhasil dibuat',
                'data' => $motorcycleListData,
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
            'discount_code',
            'discount_name',
            'discount_price',
            'start_date',
            'end_date',
            'is_hidden',
        ]);

        try {
            $this->motorcycleListService->updateData($id, $data);

            $motorcycleListData = $this->motorcycleListService->findByid($id);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Data berhasil disimpan',
                'data' => $motorcycleListData,
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
            $motorcycleListData = $this->motorcycleListService->deleteData($id);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Data berhasil dihapus',
                'data' => $motorcycleListData,
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
