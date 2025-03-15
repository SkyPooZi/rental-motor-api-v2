<?php

namespace App\Services\MotorcycleList;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LaravelEasyRepository\ServiceApi;
use Illuminate\Support\Facades\Validator;
use App\Repositories\MotorcycleList\MotorcycleListRepository;

class MotorcycleListServiceImplement extends ServiceApi implements MotorcycleListService
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
  protected MotorcycleListRepository $mainRepository;

  public function __construct(MotorcycleListRepository $mainRepository)
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
        'image' => 'bail|required|string|max:255',
        'name' => 'bail|required|string|max:255',
        'type' => 'bail|required|string|max:255',
        'brand' => 'bail|required|string|max:255',
        'stock' => 'bail|required|integer',
        'price_per_day' => 'bail|required|integer',
        'price_per_week' => 'bail|required|integer',
        'delivery_price' => 'bail|required|integer',
        'status' => 'bail|required|string|max:255',
        'unavailable_start_date' => 'bail|nullable|date',
        'unavailable_end_date' => 'bail|nullable|date',
        'is_hidden' => 'bail|required|boolean',
      ], [
        'image' => [
          'required' => 'Gambar wajib diisi.',
          'string' => 'Gambar harus berupa teks.',
          'max' => 'Gambar maksimal 255 karakter.',
        ],
        'name' => [
          'required' => 'Name motor wajib diisi.',
          'string' => 'Name motor harus berupa teks.',
          'max' => 'Name motor maksimal 255 karakter.',
        ],
        'type' => [
          'required' => 'Tipe motor wajib diisi.',
          'string' => 'Tipe motor harus berupa teks.',
          'max' => 'Tipe motor maksimal 255 karakter.',
        ],
        'brand' => [
          'required' => 'Merek motor wajib diisi.',
          'string' => 'Merek motor harus berupa teks.',
          'max' => 'Merek motor maksimal 255 karakter.',
        ],
        'stock' => [
          'required' => 'Stok motor wajib diisi.',
          'integer' => 'Stok motor harus berupa angka.',
        ],
        'price_per_day' => [
          'required' => 'Harga motor per hari wajib diisi.',
          'integer' => 'Harga motor per hari harus berupa angka.',
        ],
        'price_per_week' => [
          'required' => 'Harga motor per minggu wajib diisi.',
          'integer' => 'Harga motor per minggu harus berupa angka.',
        ],
        'delivery_price' => [
          'required' => 'Harga pengantaran wajib diisi.',
          'integer' => 'Harga pengantaran harus berupa angka.',
        ],
        'status' => [
          'required' => 'Status motor wajib diisi.',
          'string' => 'Status motor harus berupa teks.',
          'max' => 'Status motor maksimal 255 karakter.',
        ],
        'unavailable_start_date' => [
          'date' => 'Tanggal mulai motor tidak tersedia tidak valid.',
        ],
        'unavailable_end_date' => [
          'date' => 'Tanggal selesai motor tidak tersedia tidak valid.',
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
      'image' => 'bail|nullable|string|max:255',
      'name' => 'bail|nullable|string|max:255',
      'type' => 'bail|nullable|string|max:255',
      'brand' => 'bail|nullable|string|max:255',
      'stock' => 'bail|nullable|integer',
      'price_per_day' => 'bail|nullable|integer',
      'price_per_week' => 'bail|nullable|integer',
      'delivery_price' => 'bail|nullable|integer',
      'status' => 'bail|nullable|string|max:255',
      'unavailable_start_date' => 'bail|nullable|date',
      'unavailable_end_date' => 'bail|nullable|date',
      'is_hidden' => 'bail|nullable|boolean',
    ], [
      'image' => [
        'string' => 'Gambar harus berupa teks.',
        'max' => 'Gambar maksimal 255 karakter.',
      ],
      'name' => [
        'string' => 'Name motor harus berupa teks.',
        'max' => 'Name motor maksimal 255 karakter.',
      ],
      'type' => [
        'string' => 'Tipe motor harus berupa teks.',
        'max' => 'Tipe motor maksimal 255 karakter.',
      ],
      'brand' => [
        'string' => 'Merek motor harus berupa teks.',
        'max' => 'Merek motor maksimal 255 karakter.',
      ],
      'stock' => [
        'integer' => 'Stok motor harus berupa angka.',
      ],
      'price_per_day' => [
        'integer' => 'Harga motor per hari harus berupa angka.',
      ],
      'price_per_week' => [
        'integer' => 'Harga motor per minggu harus berupa angka.',
      ],
      'delivery_price' => [
        'integer' => 'Harga pengantaran harus berupa angka.',
      ],
      'status' => [
        'string' => 'Status motor harus berupa teks.',
        'max' => 'Status motor maksimal 255 karakter.',
      ],
      'unavailable_start_date' => [
        'date' => 'Tanggal mulai motor tidak tersedia tidak valid.',
      ],
      'unavailable_end_date' => [
        'date' => 'Tanggal selesai motor tidak tersedia tidak valid.',
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
