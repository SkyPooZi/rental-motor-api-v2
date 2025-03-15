<?php

namespace App\Services\Discount;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LaravelEasyRepository\ServiceApi;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Discount\DiscountRepository;

class DiscountServiceImplement extends ServiceApi implements DiscountService
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
  protected DiscountRepository $mainRepository;

  public function __construct(DiscountRepository $mainRepository)
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
        'discount_code' => 'bail|required|string|max:255|unique:discounts,discount_code',
        'discount_name' => 'bail|required|string|max:255',
        'discount_price' => 'bail|required|integer',
        'start_date' => 'bail|required|date',
        'end_date' => 'bail|required|date',
        'is_hidden' => 'bail|required|boolean',
      ], [
        'image' => [
          'required' => 'Gambar wajib diisi.',
          'string' => 'Gambar harus berupa teks.',
          'max' => 'Gambar maksimal 255 karakter.',
        ],
        'discount_code' => [
          'required' => 'Kode diskon wajib diisi.',
          'string' => 'Kode diskon harus berupa teks.',
          'max' => 'Kode diskon maksimal 255 karakter.',
          'unique' => 'Kode diskon sudah digunakan.',
        ],
        'discount_name' => [
          'required' => 'Nama diskon wajib diisi.',
          'string' => 'Nama diskon harus berupa teks.',
          'max' => 'Nama diskon maksimal 255 karakter.',
        ],
        'discount_price' => [
          'required' => 'Harga diskon wajib diisi.',
          'integer' => 'Harga diskon harus berupa angka.',
        ],
        'start_date' => [
          'required' => 'Tanggal mulai wajib diisi.',
          'date' => 'Tanggal mulai tidak valid.',
        ],
        'end_date' => [
          'required' => 'Tanggal selesai wajib diisi.',
          'date' => 'Tanggal selesai tidak valid.',
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
      'discount_code' => 'bail|nullable|string|max:255|unique:discounts,discount_code',
      'discount_name' => 'bail|nullable|string|max:255',
      'discount_price' => 'bail|nullable|integer',
      'start_date' => 'bail|nullable|date',
      'end_date' => 'bail|nullable|date',
      'is_hidden' => 'bail|nullable|boolean',
    ], [
      'image' => [
        'string' => 'Gambar harus berupa teks.',
        'max' => 'Gambar maksimal 255 karakter.',
      ],
      'discount_code' => [
        'string' => 'Kode diskon harus berupa teks.',
        'max' => 'Kode diskon maksimal 255 karakter.',
        'unique' => 'Kode diskon sudah digunakan.',
      ],
      'discount_name' => [
        'string' => 'Nama diskon harus berupa teks.',
        'max' => 'Nama diskon maksimal 255 karakter.',
      ],
      'discount_price' => [
        'integer' => 'Harga diskon harus berupa angka.',
      ],
      'start_date' => [
        'date' => 'Tanggal mulai tidak valid.',
      ],
      'end_date' => [
        'date' => 'Tanggal selesai tidak valid.',
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
