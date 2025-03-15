<?php

namespace App\Services\Facebook;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LaravelEasyRepository\ServiceApi;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Facebook\FacebookRepository;

class FacebookServiceImplement extends ServiceApi implements FacebookService
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
  protected FacebookRepository $mainRepository;

  public function __construct(FacebookRepository $mainRepository)
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
        'access_token' => 'bail|required|string|max:255',
        'user_id' => 'bail|required|integer',
        'login_date' => 'bail|required|date',
      ], [
        'access_token' => [
          'required' => 'Akses token wajib diisi.',
          'string' => 'Akses token harus berupa teks.',
          'max' => 'Akses token maksimal 255 karakter.',
        ],
        'user_id' => [
          'required' => 'ID pengguna wajib diisi.',
          'integer' => 'ID pengguna harus berupa angka.',
        ],
        'login_date' => [
          'required' => 'Tanggal masuk wajib diisi.',
          'date' => 'Tanggal masuk tidak valid.',
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
      'access_token' => 'bail|nullable|string|max:255',
      'user_id' => 'bail|nullable|integer',
      'login_date' => 'bail|nullable|date',
    ], [
      'access_token' => [
        'string' => 'Akses token harus berupa teks.',
        'max' => 'Akses token maksimal 255 karakter.',
      ],
      'user_id' => [
        'integer' => 'ID pengguna harus berupa angka.',
      ],
      'login_date' => [
        'date' => 'Tanggal masuk tidak valid.',
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
