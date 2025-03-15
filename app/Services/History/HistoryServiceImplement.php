<?php

namespace App\Services\History;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LaravelEasyRepository\ServiceApi;
use Illuminate\Support\Facades\Validator;
use App\Repositories\History\HistoryRepository;

class HistoryServiceImplement extends ServiceApi implements HistoryService
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
  protected HistoryRepository $mainRepository;

  public function __construct(HistoryRepository $mainRepository)
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
        'user_id' => 'bail|required|integer',
        'full_name' => 'bail|required|string|max:255',
        'email' => 'bail|required|email|max:255',
        'phone_number' => 'bail|required|string|max:15',
        'social_media_account' => 'bail|required|string|max:255',
        'address' => 'bail|required|string|max:255',
        'renter' => 'bail|required|string|max:255',
        'motorcycle_id' => 'bail|required|integer',
        'start_date' => 'bail|required|date',
        'duration' => 'bail|required|integer|min:1',
        'end_date' => 'bail|required|date',
        'renter_purpose' => 'bail|required|string|max:255',
        'motorcycle_receipt' => 'bail|required|string|max:255',
        'emergency_contact_name' => 'bail|required|string|max:255',
        'emergency_contact_number' => 'bail|required|string|max:15',
        'emergency_contact_relationship' => 'bail|required|string|max:255',
        'point' => 'bail|required|integer|min:0',
        'discount_id' => 'bail|nullable|integer',
        'payment_method' => 'bail|required|string|max:255',
        'total_payment' => 'bail|required|integer|min:0',
        'history_status' => 'bail|required|string|max:255',
        'review_id' => 'bail|nullable|integer',
        'cancellation_date' => 'bail|nullable|date',
        'cancellation_reason' => 'bail|nullable|string|max:255',
      ], [
        'user_id' => [
          'required' => 'ID pengguna wajib diisi.',
          'integer' => 'ID pengguna harus berupa angka.',
        ],
        'full_name' => [
          'required' => 'Nama lengkap wajib diisi.',
          'string' => 'Nama lengkap harus berupa teks.',
          'max' => 'Nama lengkap maksimal 255 karakter.',
        ],
        'email' => [
          'required' => 'Email wajib diisi.',
          'email' => 'Format email tidak valid.',
          'max' => 'Email maksimal 255 karakter.',
        ],
        'phone_number' => [
          'required' => 'Nomor telepon wajib diisi.',
          'string' => 'Nomor telepon harus berupa teks.',
          'max' => 'Nomor telepon maksimal 15 karakter.',
        ],
        'social_media_account' => [
          'required' => 'Akun media sosial wajib diisi.',
          'string' => 'Akun media sosial harus berupa teks.',
          'max' => 'Akun media sosial maksimal 255 karakter.',
        ],
        'address' => [
          'required' => 'Alamat wajib diisi.',
          'string' => 'Alamat harus berupa teks.',
          'max' => 'Alamat maksimal 255 karakter.',
        ],
        'renter' => [
          'required' => 'Nama penyewa wajib diisi.',
          'string' => 'Nama penyewa harus berupa teks.',
          'max' => 'Nama penyewa maksimal 255 karakter.',
        ],
        'motorcycle_id' => [
          'required' => 'ID motor wajib diisi.',
          'integer' => 'ID motor harus berupa angka.',
        ],
        'start_date' => [
          'required' => 'Tanggal mulai wajib diisi.',
          'date' => 'Format tanggal mulai tidak valid.',
        ],
        'duration' => [
          'required' => 'Durasi sewa wajib diisi.',
          'integer' => 'Durasi harus berupa angka.',
          'min' => 'Durasi minimal 1 hari.',
        ],
        'end_date' => [
          'required' => 'Tanggal selesai wajib diisi.',
          'date' => 'Format tanggal selesai tidak valid.',
        ],
        'renter_purpose' => [
          'required' => 'Tujuan penyewaan wajib diisi.',
          'string' => 'Tujuan penyewaan harus berupa teks.',
          'max' => 'Tujuan penyewaan maksimal 255 karakter.',
        ],
        'motorcycle_receipt' => [
          'required' => 'Bukti sewa motor wajib diisi.',
          'string' => 'Bukti sewa motor harus berupa teks.',
          'max' => 'Bukti sewa motor maksimal 255 karakter.',
        ],
        'emergency_contact_name' => [
          'required' => 'Nama kontak darurat wajib diisi.',
          'string' => 'Nama kontak darurat harus berupa teks.',
          'max' => 'Nama kontak darurat maksimal 255 karakter.',
        ],
        'emergency_contact_number' => [
          'required' => 'Nomor kontak darurat wajib diisi.',
          'string' => 'Nomor kontak darurat harus berupa teks.',
          'max' => 'Nomor kontak darurat maksimal 15 karakter.',
        ],
        'emergency_contact_relationship' => [
          'required' => 'Hubungan dengan kontak darurat wajib diisi.',
          'string' => 'Hubungan dengan kontak darurat harus berupa teks.',
          'max' => 'Hubungan dengan kontak darurat maksimal 255 karakter.',
        ],
        'point' => [
          'required' => 'Poin wajib diisi.',
          'integer' => 'Poin harus berupa angka.',
          'min' => 'Poin tidak boleh negatif.',
        ],
        'discount_id' => [
          'integer' => 'ID diskon harus berupa angka.',
        ],
        'payment_method' => [
          'required' => 'Metode pembayaran wajib diisi.',
          'string' => 'Metode pembayaran harus berupa teks.',
          'max' => 'Metode pembayaran maksimal 255 karakter.',
        ],
        'total_payment' => [
          'required' => 'Total pembayaran wajib diisi.',
          'integer' => 'Total pembayaran harus berupa angka.',
          'min' => 'Total pembayaran tidak boleh negatif.',
        ],
        'history_status' => [
          'required' => 'Status riwayat wajib diisi.',
          'string' => 'Status riwayat harus berupa teks.',
          'max' => 'Status riwayat maksimal 255 karakter.',
        ],
        'review_id' => [
          'integer' => 'ID ulasan harus berupa angka.',
        ],
        'cancellation_date' => [
          'date' => 'Format tanggal pembatalan tidak valid.',
        ],
        'cancellation_reason' => [
          'string' => 'Alasan pembatalan harus berupa teks.',
          'max' => 'Alasan pembatalan maksimal 255 karakter.',
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
      'full_name' => 'bail|nullable|string|max:255',
      'email' => 'bail|nullable|email|max:255',
      'phone_number' => 'bail|nullable|string|max:15',
      'social_media_account' => 'bail|nullable|string|max:255',
      'address' => 'bail|nullable|string|max:255',
      'renter' => 'bail|nullable|string|max:255',
      'motorcycle_id' => 'bail|nullable|integer',
      'start_date' => 'bail|nullable|date',
      'duration' => 'bail|nullable|integer|min:1',
      'end_date' => 'bail|nullable|date',
      'renter_purpose' => 'bail|nullable|string|max:255',
      'motorcycle_receipt' => 'bail|nullable|string|max:255',
      'emergency_contact_name' => 'bail|nullable|string|max:255',
      'emergency_contact_number' => 'bail|nullable|string|max:15',
      'emergency_contact_relationship' => 'bail|nullable|string|max:255',
      'point' => 'bail|nullable|integer|min:0',
      'discount_id' => 'bail|nullable|integer',
      'payment_method' => 'bail|nullable|string|max:255',
      'total_payment' => 'bail|nullable|integer|min:0',
      'history_status' => 'bail|nullable|string|max:255',
      'review_id' => 'bail|nullable|integer',
      'cancellation_date' => 'bail|nullable|date',
      'cancellation_reason' => 'bail|nullable|string|max:255',
    ], [
      'user_id' => [
        'integer' => 'ID pengguna harus berupa angka.',
      ],
      'full_name' => [
        'string' => 'Nama lengkap harus berupa teks.',
        'max' => 'Nama lengkap maksimal 255 karakter.',
      ],
      'email' => [
        'email' => 'Format email tidak valid.',
        'max' => 'Email maksimal 255 karakter.',
      ],
      'phone_number' => [
        'string' => 'Nomor telepon harus berupa teks.',
        'max' => 'Nomor telepon maksimal 15 karakter.',
      ],
      'social_media_account' => [
        'string' => 'Akun media sosial harus berupa teks.',
        'max' => 'Akun media sosial maksimal 255 karakter.',
      ],
      'address' => [
        'string' => 'Alamat harus berupa teks.',
        'max' => 'Alamat maksimal 255 karakter.',
      ],
      'renter' => [
        'string' => 'Nama penyewa harus berupa teks.',
        'max' => 'Nama penyewa maksimal 255 karakter.',
      ],
      'motorcycle_id' => [
        'integer' => 'ID motor harus berupa angka.',
      ],
      'start_date' => [
        'date' => 'Format tanggal mulai tidak valid.',
      ],
      'duration' => [
        'integer' => 'Durasi harus berupa angka.',
        'min' => 'Durasi minimal 1 hari.',
      ],
      'end_date' => [
        'date' => 'Format tanggal selesai tidak valid.',
      ],
      'renter_purpose' => [
        'string' => 'Tujuan penyewaan harus berupa teks.',
        'max' => 'Tujuan penyewaan maksimal 255 karakter.',
      ],
      'motorcycle_receipt' => [
        'string' => 'Bukti sewa motor harus berupa teks.',
        'max' => 'Bukti sewa motor maksimal 255 karakter.',
      ],
      'emergency_contact_name' => [
        'string' => 'Nama kontak darurat harus berupa teks.',
        'max' => 'Nama kontak darurat maksimal 255 karakter.',
      ],
      'emergency_contact_number' => [
        'string' => 'Nomor kontak darurat harus berupa teks.',
        'max' => 'Nomor kontak darurat maksimal 15 karakter.',
      ],
      'emergency_contact_relationship' => [
        'string' => 'Hubungan dengan kontak darurat harus berupa teks.',
        'max' => 'Hubungan dengan kontak darurat maksimal 255 karakter.',
      ],
      'point' => [
        'integer' => 'Poin harus berupa angka.',
        'min' => 'Poin tidak boleh negatif.',
      ],
      'discount_id' => [
        'integer' => 'ID diskon harus berupa angka.',
      ],
      'payment_method' => [
        'string' => 'Metode pembayaran harus berupa teks.',
        'max' => 'Metode pembayaran maksimal 255 karakter.',
      ],
      'total_payment' => [
        'integer' => 'Total pembayaran harus berupa angka.',
        'min' => 'Total pembayaran tidak boleh negatif.',
      ],
      'history_status' => [
        'string' => 'Status riwayat harus berupa teks.',
        'max' => 'Status riwayat maksimal 255 karakter.',
      ],
      'review_id' => [
        'integer' => 'ID ulasan harus berupa angka.',
      ],
      'cancellation_date' => [
        'date' => 'Format tanggal pembatalan tidak valid.',
      ],
      'cancellation_reason' => [
        'string' => 'Alasan pembatalan harus berupa teks.',
        'max' => 'Alasan pembatalan maksimal 255 karakter.',
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
