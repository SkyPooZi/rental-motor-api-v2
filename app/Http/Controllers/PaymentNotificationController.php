<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\PaymentNotification\PaymentNotificationService;

class PaymentNotificationController extends Controller
{
    // Payment Notification Services
    private $paymentNotificationService;

    public function __construct(
        PaymentNotificationService $paymentNotificationService,
    ) {
        $this->paymentNotificationService = $paymentNotificationService;
    }

    public function getAllWithSearch(Request $request)
    {
        $paymentNotificationData = $this->paymentNotificationService->getAllWithSearch($request);

        if (!$paymentNotificationData) {
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
            'data' => $paymentNotificationData,
        ], 200);
    }

    public function findById($id)
    {
        $paymentNotificationData = $this->paymentNotificationService->findById($id);

        if (!$paymentNotificationData) {
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
            'data' => $paymentNotificationData,
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
            $paymentNotificationData = $this->paymentNotificationService->insertData($data);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Data berhasil dibuat',
                'data' => $paymentNotificationData,
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
            $this->paymentNotificationService->updateData($id, $data);

            $paymentNotificationData = $this->paymentNotificationService->findByid($id);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Data berhasil disimpan',
                'data' => $paymentNotificationData,
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
            $paymentNotificationData = $this->paymentNotificationService->deleteData($id);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Data berhasil dihapus',
                'data' => $paymentNotificationData,
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
