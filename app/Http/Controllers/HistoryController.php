<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\History\HistoryService;

class HistoryController extends Controller
{
    // History Services
    private $historyService;

    public function __construct(
        HistoryService $historyService,
    ) {
        $this->historyService = $historyService;
    }

    public function getAllWithSearch(Request $request)
    {
        $historyData = $this->historyService->getAllWithSearch($request);

        if (!$historyData) {
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
            'data' => $historyData,
        ], 200);
    }

    public function findById($id)
    {
        $historyData = $this->historyService->findById($id);

        if (!$historyData) {
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
            'data' => $historyData,
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
            $historyData = $this->historyService->insertData($data);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Data berhasil dibuat',
                'data' => $historyData,
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
            $this->historyService->updateData($id, $data);

            $historyData = $this->historyService->findByid($id);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Data berhasil disimpan',
                'data' => $historyData,
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
            $historyData = $this->historyService->deleteData($id);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Data berhasil dihapus',
                'data' => $historyData,
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
