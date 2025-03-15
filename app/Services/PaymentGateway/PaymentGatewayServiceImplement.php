<?php

namespace App\Services\PaymentGateway;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LaravelEasyRepository\ServiceApi;
use Illuminate\Support\Facades\Validator;
use App\Repositories\PaymentGateway\PaymentGatewayRepository;

class PaymentGatewayServiceImplement extends ServiceApi implements PaymentGatewayService
{

  /**
   * set title message api for CRUD
   * @param string $title
   */
  protected string $title = "";
  /**
   * uncomment this to override the default message
   * protected string $create_message = "";
   * protected string $update_message = "";
   * protected string $delete_message = "";
   */

  /**
   * don't change $this->mainRepository variable name
   * because used in extends service class
   */
  protected PaymentGatewayRepository $mainRepository;

  public function __construct(PaymentGatewayRepository $mainRepository)
  {
    $this->mainRepository = $mainRepository;
  }

  public function getAllWithSearch($request)
  {
    return $this->mainRepository->getAllWithSearch($request);
  }

  public function findById($id)
  {
    return $this->mainRepository->findById($id);
  }

  public function insertData(array $data)
  {
    try {
      $validator = Validator::make($data, [
        'history_id' => 'bail|required|integer',
        'order_number' => 'bail|required|integer',
        'order_date' => 'bail|required|date',
        'payment_date' => 'bail|required|date',
        'payment_method' => 'bail|required|string|max:255',
        'payment_status' => 'bail|required|string|max:255',
        'total_order' => 'bail|required|integer',
      ], [
        'history_id' => [
          'required' => 'ID pemesanan wajib diisi.',
          'integer' => 'ID pemesanan harus berupa angka.',
        ],
        'order_number' => [
          'required' => 'Nomor pesanan wajib diisi.',
          'integer' => 'Nomor pesanan harus berupa angka.',
        ],
        'order_date' => [
          'required' => 'Tanggal pesanan wajib diisi.',
          'date' => 'Tanggal pesanan tidak valid.',
        ],
        'payment_date' => [
          'required' => 'Tanggal pembayaran wajib diisi.',
          'date' => 'Tanggal pembayaran tidak valid.',
        ],
        'payment_method' => [
          'required' => 'Metode pembayaran wajib diisi.',
          'string' => 'Metode pembayaran harus berupa teks.',
          'max' => 'Metode pembayaran maksimal 255 karakter.',
        ],
        'payment_status' => [
          'required' => 'Status pembayaran wajib diisi.',
          'string' => 'Status pembayaran harus berupa teks.',
          'max' => 'Status pembayaran maksimal 255 karakter.',
        ],
        'total_order' => [
          'required' => 'Total pesanan wajib diisi.',
          'integer' => 'Total pesanan harus berupa angka.',
        ],
      ]);

      if ($validator->fails()) {
        throw new InvalidArgumentException($validator->errors()->first());
      }

      return $this->mainRepository->insertData($data);
    } catch (InvalidArgumentException $e) {
      return response()->json([
        'message' => $e->getMessage()
      ], 400);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Something went wrong. Please try again.',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function updateData($id, array $data)
  {
    if (empty($id)) {
      throw new InvalidArgumentException('ID tidak boleh kosong');
    }

    $validator = Validator::make($data, [
      'history_id' => 'bail|nullable|integer',
      'order_number' => 'bail|nullable|integer',
      'order_date' => 'bail|nullable|date',
      'payment_date' => 'bail|nullable|date',
      'payment_method' => 'bail|nullable|string|max:255',
      'payment_status' => 'bail|nullable|string|max:255',
      'total_order' => 'bail|nullable|integer',
    ], [
      'history_id' => [
        'integer' => 'ID pemesanan harus berupa angka.',
      ],
      'order_number' => [
        'integer' => 'Nomor pesanan harus berupa angka.',
      ],
      'order_date' => [
        'date' => 'Tanggal pesanan tidak valid.',
      ],
      'payment_date' => [
        'date' => 'Tanggal pembayaran tidak valid.',
      ],
      'payment_method' => [
        'string' => 'Metode pembayaran harus berupa teks.',
        'max' => 'Metode pembayaran maksimal 255 karakter.',
      ],
      'payment_status' => [
        'string' => 'Status pembayaran harus berupa teks.',
        'max' => 'Status pembayaran maksimal 255 karakter.',
      ],
      'total_order' => [
        'integer' => 'Total pesanan harus berupa angka.',
      ],
    ]);

    if ($validator->fails()) {
      throw new InvalidArgumentException($validator->errors()->first());
    }

    DB::beginTransaction();

    try {
      $result = $this->mainRepository->update($id, $data);

      if (!$result) {
        throw new InvalidArgumentException('Gagal mengupdate data');
      }

      DB::commit();
      return $result;
    } catch (Exception $e) {
      DB::rollBack();
      Log::info($e->getMessage());
      throw new InvalidArgumentException('Gagal mengupdate data: ' . $e->getMessage());
    }
  }

  public function deleteData($id)
  {
    DB::beginTransaction();

    try {
      $result = $this->mainRepository->delete($id);
    } catch (Exception $e) {
      DB::rollBack();
      Log::info($e->getMessage());

      throw new InvalidArgumentException('Unable to Delete Data');
    }

    DB::commit();
    return $result;
  }
}
