<?php

namespace App\Services\Review;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LaravelEasyRepository\ServiceApi;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Review\ReviewRepository;

class ReviewServiceImplement extends ServiceApi implements ReviewService
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
  protected ReviewRepository $mainRepository;

  public function __construct(ReviewRepository $mainRepository)
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
        'image' => 'bail|nullable|string|max:255',
        'user_id' => 'bail|required|integer',
        'rating' => 'bail|required|integer',
        'comment' => 'bail|required|string|max:255',
      ], [
        'image' => [
          'string' => 'Gambar harus berupa teks.',
          'max' => 'Gambar maksimal 255 karakter.',
        ],
        'user_id' => [
          'required' => 'ID pengguna wajib diisi.',
          'integer' => 'ID pengguna harus berupa angka.',
        ],
        'rating' => [
          'required' => 'Penilaian wajib diisi.',
          'integer' => 'Penilaian harus berupa angka.',
        ],
        'comment' => [
          'required' => 'Ulasan wajib diisi.',
          'string' => 'Ulasan harus berupa teks.',
          'max' => 'Ulasan maksimal 255 karakter.',
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
      'image' => 'bail|nullable|string|max:255',
      'user_id' => 'bail|nullable|integer',
      'rating' => 'bail|nullable|integer',
      'comment' => 'bail|nullable|string|max:255',
    ], [
      'image' => [
        'string' => 'Gambar harus berupa teks.',
        'max' => 'Gambar maksimal 255 karakter.',
      ],
      'user_id' => [
        'integer' => 'ID pengguna harus berupa angka.',
      ],
      'rating' => [
        'integer' => 'Penilaian harus berupa angka.',
      ],
      'comment' => [
        'string' => 'Ulasan harus berupa teks.',
        'max' => 'Ulasan maksimal 255 karakter.',
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
