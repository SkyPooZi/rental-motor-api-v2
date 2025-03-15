<?php

namespace App\Services\Finance;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LaravelEasyRepository\ServiceApi;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Finance\FinanceRepository;

class FinanceServiceImplement extends ServiceApi implements FinanceService
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
  protected FinanceRepository $mainRepository;

  public function __construct(FinanceRepository $mainRepository)
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
        'total_motorcycle_price' => 'bail|required|integer',
        'total_overtime_fee' => 'bail|required|integer',
        'total_delivery_fee' => 'bail|required|integer',
        'total_point_deduction' => 'bail|required|integer',
        'total_discount_fee' => 'bail|required|integer',
        'total_admin_fee' => 'bail|required|integer',
        'total_reschedule_fee' => 'bail|nullable|integer',
        'total_payment' => 'bail|required|integer',
      ], [
        'history_id' => [
          'required' => 'ID pemesanan wajib diisi.',
          'integer' => 'ID pemesanan harus berupa angka.',
        ],
        'total_motorcycle_price' => [
          'required' => 'Total harga motor wajib diisi.',
          'integer' => 'Total harga motor harus berupa angka.',
        ],
        'total_overtime_fee' => [
          'required' => 'Denda keterlambatan wajib diisi.',
          'integer' => 'Denda keterlambatan harus berupa angka.',
        ],
        'total_delivery_fee' => [
          'required' => 'Biaya pengantaran wajib diisi.',
          'integer' => 'Biaya pengantaran harus berupa angka.',
        ],
        'total_point_deduction' => [
          'required' => 'Potongan poin wajib diisi.',
          'integer' => 'Potongan poin harus berupa angka.',
        ],
        'total_discount_fee' => [
          'required' => 'Total diskon wajib diisi.',
          'integer' => 'Total diskon harus berupa angka.',
        ],
        'total_admin_fee' => [
          'required' => 'Biaya admin wajib diisi.',
          'integer' => 'Biaya admin harus berupa angka.',
        ],
        'total_reschedule_fee' => [
          'integer' => 'Biaya reschedule harus berupa angka.',
        ],
        'total_payment' => [
          'required' => 'Total pembayaran wajib diisi.',
          'integer' => 'Total pembayaran harus berupa angka.',
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
      'total_motorcycle_price' => 'bail|nullable|integer',
      'total_overtime_fee' => 'bail|nullable|integer',
      'total_delivery_fee' => 'bail|nullable|integer',
      'total_point_deduction' => 'bail|nullable|integer',
      'total_discount_fee' => 'bail|nullable|integer',
      'total_admin_fee' => 'bail|nullable|integer',
      'total_reschedule_fee' => 'bail|nullable|integer',
      'total_payment' => 'bail|nullable|integer',
    ], [
      'history_id' => [
        'integer' => 'ID pemesanan harus berupa angka.',
      ],
      'total_motorcycle_price' => [
        'integer' => 'Total harga motor harus berupa angka.',
      ],
      'total_overtime_fee' => [
        'integer' => 'Denda keterlambatan harus berupa angka.',
      ],
      'total_delivery_fee' => [
        'integer' => 'Biaya pengantaran harus berupa angka.',
      ],
      'total_point_deduction' => [
        'integer' => 'Potongan poin harus berupa angka.',
      ],
      'total_discount_fee' => [
        'integer' => 'Total diskon harus berupa angka.',
      ],
      'total_admin_fee' => [
        'integer' => 'Biaya admin harus berupa angka.',
      ],
      'total_reschedule_fee' => [
        'integer' => 'Biaya reschedule harus berupa angka.',
      ],
      'total_payment' => [
        'integer' => 'Total pembayaran harus berupa angka.',
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
