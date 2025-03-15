<?php

namespace App\Services\Notification;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LaravelEasyRepository\ServiceApi;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Notification\NotificationRepository;

class NotificationServiceImplement extends ServiceApi implements NotificationService
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
  protected NotificationRepository $mainRepository;

  public function __construct(NotificationRepository $mainRepository)
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
        'discount_id' => 'bail|nullable|integer',
        'history_id' => 'bail|nullable|integer',
        'history_status' => 'bail|nullable|string|max:255',
        'message' => 'bail|required|string|max:255',
      ], [
        'user_id' => [
          'integer' => 'ID pengguna harus berupa angka.',
        ],
        'discount_id' => [
          'integer' => 'ID diskon harus berupa angka.',
        ],
        'history_id' => [
          'integer' => 'ID pemesanan harus berupa angka.',
        ],
        'history_status' => [
          'string' => 'Status pemesanan harus berupa teks.',
          'max' => 'Status pemesanan maksimal 255 karakter.',
        ],
        'message' => [
          'required' => 'Nama diskon wajib diisi.',
          'string' => 'Nama diskon harus berupa teks.',
          'max' => 'Nama diskon maksimal 255 karakter.',
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
      'discount_id' => 'bail|nullable|integer',
      'history_id' => 'bail|nullable|integer',
      'history_status' => 'bail|nullable|string|max:255',
      'message' => 'bail|nullable|string|max:255',
    ], [
      'user_id' => [
        'integer' => 'ID pengguna harus berupa angka.',
      ],
      'discount_id' => [
        'integer' => 'ID diskon harus berupa angka.',
      ],
      'history_id' => [
        'integer' => 'ID pemesanan harus berupa angka.',
      ],
      'history_status' => [
        'string' => 'Status pemesanan harus berupa teks.',
        'max' => 'Status pemesanan maksimal 255 karakter.',
      ],
      'message' => [
        'string' => 'Nama diskon harus berupa teks.',
        'max' => 'Nama diskon maksimal 255 karakter.',
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
