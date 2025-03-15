<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\ChangeLog\ChangeLogService;

class ChangeLogController extends Controller
{
    // ChangeLog Services
    private $changeLogService;

    public function __construct(
        ChangeLogService $changeLogService,
    ) {
        $this->changeLogService = $changeLogService;
    }

    public function getAllWithSearch(Request $request)
    {
        $changeLogData = $this->changeLogService->getAllWithSearch($request);

        if (!$changeLogData) {
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
            'data' => $changeLogData,
        ], 200);
    }

    public function findById($id)
    {
        $changeLogData = $this->changeLogService->findById($id);

        if (!$changeLogData) {
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
            'data' => $changeLogData,
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
            $changeLogData = $this->changeLogService->insertData($data);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Data berhasil dibuat',
                'data' => $changeLogData,
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
            $this->changeLogService->updateData($id, $data);

            $changeLogData = $this->changeLogService->findByid($id);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Data berhasil disimpan',
                'data' => $changeLogData,
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
            $changeLogData = $this->changeLogService->deleteData($id);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Data berhasil dihapus',
                'data' => $changeLogData,
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
