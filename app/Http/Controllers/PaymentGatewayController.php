<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\PaymentGateway\PaymentGatewayService;

class PaymentGatewayController extends Controller
{
    // Payment Gateway Services
    private $paymentGatewayService;

    public function __construct(
        PaymentGatewayService $paymentGatewayService,
    ) {
        $this->paymentGatewayService = $paymentGatewayService;
    }

    public function getAllWithSearch(Request $request)
    {
        $paymentGatewayData = $this->paymentGatewayService->getAllWithSearch($request);

        if (!$paymentGatewayData) {
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
            'data' => $paymentGatewayData,
        ], 200);
    }

    public function findById($id)
    {
        $paymentGatewayData = $this->paymentGatewayService->findById($id);

        if (!$paymentGatewayData) {
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
            'data' => $paymentGatewayData,
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
            $paymentGatewayData = $this->paymentGatewayService->insertData($data);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Data berhasil dibuat',
                'data' => $paymentGatewayData,
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
            $this->paymentGatewayService->updateData($id, $data);

            $paymentGatewayData = $this->paymentGatewayService->findByid($id);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Data berhasil disimpan',
                'data' => $paymentGatewayData,
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
            $paymentGatewayData = $this->paymentGatewayService->deleteData($id);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Data berhasil dihapus',
                'data' => $paymentGatewayData,
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
