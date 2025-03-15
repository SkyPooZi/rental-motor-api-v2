<?php

namespace App\Services\PaymentNotification;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LaravelEasyRepository\ServiceApi;
use Illuminate\Support\Facades\Validator;
use App\Repositories\PaymentNotification\PaymentNotificationRepository;

class PaymentNotificationServiceImplement extends ServiceApi implements PaymentNotificationService
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
  protected PaymentNotificationRepository $mainRepository;

  public function __construct(PaymentNotificationRepository $mainRepository)
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
        'user_id' => 'bail|nullable|integer',
        'history_id' => 'bail|nullable|integer',
        'message' => 'bail|required|string|max:255',
        'total_amount' => 'bail|required|integer',
        'due_date' => 'bail|required|date',
        'is_hidden' => 'bail|required|boolean',
      ], [
        'user_id' => [
          'integer' => 'ID pengguna harus berupa angka.',
        ],
        'history_id' => [
          'integer' => 'ID pemesanan harus berupa angka.',
        ],
        'message' => [
          'required' => 'Pesan wajib diisi.',
          'string' => 'Pesan harus berupa teks.',
          'max' => 'Pesan maksimal 255 karakter.',
        ],
        'total_amount' => [
          'required' => 'Jumlah total wajib diisi.',
          'integer' => 'Jumlah total harus berupa angka.',
        ],
        'due_date' => [
          'required' => 'Tanggal batas waktu wajib diisi.',
          'date' => 'Tanggal batas waktu tidak valid.',
        ],
        'is_hidden' => [
          'required' => 'Status visibilitas wajib diisi.',
          'boolean' => 'Status visibilitas tidak valid.',
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
      'user_id' => 'bail|nullable|integer',
      'history_id' => 'bail|nullable|integer',
      'message' => 'bail|nullable|string|max:255',
      'total_amount' => 'bail|nullable|integer',
      'due_date' => 'bail|nullable|date',
      'is_hidden' => 'bail|nullable|boolean',
    ], [
      'user_id' => [
        'integer' => 'ID pengguna harus berupa angka.',
      ],
      'history_id' => [
        'integer' => 'ID pemesanan harus berupa angka.',
      ],
      'message' => [
        'string' => 'Pesan harus berupa teks.',
        'max' => 'Pesan maksimal 255 karakter.',
      ],
      'total_amount' => [
        'integer' => 'Jumlah total harus berupa angka.',
      ],
      'due_date' => [
        'date' => 'Tanggal batas waktu tidak valid.',
      ],
      'is_hidden' => [
        'boolean' => 'Status visibilitas tidak valid.',
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
