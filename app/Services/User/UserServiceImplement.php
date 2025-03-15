<?php

namespace App\Services\User;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Validator;

class UserServiceImplement extends ServiceApi implements UserService
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
  protected UserRepository $mainRepository;

  public function __construct(UserRepository $mainRepository)
  {
    $this->mainRepository = $mainRepository;
  }

  public function createUserWithRole($dataUser, $role)
  {
    $validator = Validator::make($dataUser, [
      'username' => 'required|string|unique:users,username|max:255',
      'email' => 'required|string|email|unique:users,email',
      'password' => 'required|string|min:8',
    ], [
      'username' => [
        'required' => 'Nama Pengguna wajib diisi.',
        'string' => 'Nama Pengguna harus berupa teks.',
        'unique' => 'Nama Pengguna sudah digunakan.',
        'max' => 'Nama Pengguna maksimal 255 karakter.',
      ],
      'email' => [
        'required' => 'Email wajib diisi.',
        'string' => 'Email harus berupa teks.',
        'email' => 'Email tidak valid.',
        'unique' => 'Email sudah digunakan.',
      ],
      'password' => [
        'required' => 'Password wajib diisi.',
        'string' => 'Password harus berupa teks.',
        'min' => 'Password minimal 8 karakter.',
      ],
    ]);

    if ($validator->fails()) {
      throw new InvalidArgumentException($validator->errors()->first());
    }

    DB::beginTransaction();

    try {
      $result = $this->mainRepository->createUserWithRole($dataUser, $role);
    } catch (Exception $e) {
      DB::rollBack();
      Log::info($e->getMessage());

      throw new InvalidArgumentException('Unable to Create User');
    }

    DB::commit();

    return $result;
  }

  public function updateUserData($id, $data)
  {
    DB::beginTransaction();

    try {
      $result = $this->mainRepository->update($id, $data);
    } catch (Exception $e) {
      DB::rollBack();
      Log::info($e->getMessage());

      throw new InvalidArgumentException('Unable to Update Data');
    }

    DB::commit();
    return $result;
  }

  public function changePassword($user, $data)
  {
    $validator = Validator::make($data, [
      'old_password' => 'required_with:password|string',
      'password' => 'required|string|min:8|confirmed',
    ], [
      'old_password' => [
        'required_with' => 'Password lama wajib diisi jika ingin mengubah password baru.',
      ],
      'password' => [
        'required' => 'Password baru wajib diisi.',
        'string' => 'Password baru harus berupa teks.',
        'min' => 'Password baru minimal 8 karakter.',
        'confirmed' => 'Konfirmasi password tidak cocok.',
      ],
    ]);

    if ($validator->fails()) {
      throw new InvalidArgumentException($validator->errors()->first());
    }

    if (!Hash::check($data['old_password'], $user->password)) {
      return [
        'code' => 401,
        'error' => 'Kata sandi lama tidak sesuai',
      ];
    }

    $result = $this->mainRepository->update($user->id, ['password' => Hash::make($data['password'])]);

    return [
      'code' => 200,
      'data' => $result,
    ];
  }

  public function deleteUserData($id)
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

  public function banUser($id, $reason)
  {
    $validator = Validator::make($reason, [
      'ban_reason' => 'string|max:255'
    ], [
      'ban_reason' => [
        'string' => 'Alasan pemblokiran harus berupa teks.',
        'max' => 'Alasan pemblokiran maksimal 255 karakter.',
      ],
    ]);

    if ($validator->fails()) {
      throw new InvalidArgumentException($validator->errors()->first());
    }

    DB::beginTransaction();

    try {
      $result = $this->mainRepository->banUser($id, $reason);
    } catch (Exception $e) {
      DB::rollBack();
      Log::info($e->getMessage());

      throw new InvalidArgumentException('Unable to Ban User');
    }

    DB::commit();

    return $result;
  }

  public function unBanUser($id)
  {
    DB::beginTransaction();

    try {
      $result = $this->mainRepository->unBanUser($id);
    } catch (Exception $e) {
      DB::rollBack();
      Log::info($e->getMessage());

      throw new InvalidArgumentException('Unable to unBan User');
    }

    DB::commit();
    return $result;
  }
}
