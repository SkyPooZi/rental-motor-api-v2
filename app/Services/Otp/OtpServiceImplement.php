<?php

namespace App\Services\Otp;

use App\Models\User;
use App\Mail\OtpMail;
use InvalidArgumentException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\Otp\OtpRepository;
use Illuminate\Support\Facades\Validator;

class OtpServiceImplement extends ServiceApi implements OtpService
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
  protected OtpRepository $mainRepository;

  public function __construct(OtpRepository $mainRepository)
  {
    $this->mainRepository = $mainRepository;
  }

  public function sendOtp($email)
  {
    $this->mainRepository->expiredOtps();

    if (empty($email)) {
      return [
        'code' => 400,
        'error' => 'Tolong masukkan email.',
      ];
    }

    $validator = Validator::make($email, [
      'email' => 'required|email|exists:users,email',
    ], [
      'email' => [
        'required' => 'Email wajib diisi.',
        'email' => 'Email tidak valid.',
        'exists' => 'Email tidak ditemukan.',
      ],
    ]);

    $user = User::where('email', $email)->first();
    if (!$user) {
      return [
        'code' => 404,
        'error' => '404 Pengguna tidak ditemukan.',
      ];
    }

    if ($validator->fails()) {
      throw new InvalidArgumentException($validator->errors()->first());
    }

    $existingOtp = $this->mainRepository->findByEmail($email);
    if ($existingOtp && Carbon::now()->lessThan($existingOtp->expires_at)) {
      return [
        'code' => 429,
        'error' => 'OTP sudah dikirim. Harap tunggu 10 menit sebelum meminta yang baru.',
      ];
    }

    $otp = rand(100000, 999999);
    $expires_at = Carbon::now()->addMinutes(10);

    $otpData = [
      'email' => $email['email'],
      'otp' => $otp,
      'expires_at' => $expires_at,
    ];

    $this->mainRepository->create($otpData);

    $mailData = [
      'otp' => $otp,
      'expires_at' => $expires_at->format('d-m-Y H:i:s'),
    ];

    Mail::to($email['email'])->send(new OtpMail($mailData));

    return [
      'code' => 200,
      'message' => 'OTP telah dikirim ke email Anda.',
      'otp' => $otp,
      'expires_at' => $expires_at->format('d-m-Y H:i:s'),
    ];
  }

  public function sendOtpNewEmail($email)
  {
    $this->mainRepository->expiredOtps();

    if (empty($email)) {
      return [
        'code' => 400,
        'error' => 'Email tidak boleh kosong. Mohon masukkan alamat email yang valid.',
      ];
    }

    $validator = Validator::make($email, [
      'email' => 'required|email|unique:users,email',
    ], [
      'email' => [
        'required' => 'Email wajib diisi.',
        'email' => 'Email tidak valid.',
        'unique' => 'Alamat email ini sudah terdaftar di sistem kami. Silakan gunakan alamat email lain.',
      ],
    ]);

    if ($validator->fails()) {
      throw new InvalidArgumentException($validator->errors()->first());
    }

    $existingOtp = $this->mainRepository->findByEmail($email);
    if ($existingOtp && Carbon::now()->lessThan($existingOtp->expires_at)) {
      return [
        'code' => 429,
        'error' => 'OTP sudah dikirim. Harap tunggu 10 menit sebelum meminta yang baru.',
      ];
    }

    $otp = rand(100000, 999999);
    $expires_at = Carbon::now()->addMinutes(10);

    $otpData = [
      'email' => $email['email'],
      'otp' => $otp,
      'expires_at' => $expires_at,
    ];

    $this->mainRepository->create($otpData);

    $mailData = [
      'otp' => $otp,
      'expires_at' => $expires_at->format('d-m-Y H:i:s'),
    ];

    Mail::to($email['email'])->send(new OtpMail($mailData));

    return [
      'code' => 200,
      'message' => 'OTP telah dikirim ke email Anda.',
      'otp' => $otp,
      'expires_at' => $expires_at->format('d-m-Y H:i:s'),
    ];
  }

  public function verifyOtp($email, $otp)
  {
    if (empty($email)) {
      return [
        'code' => 400,
        'error' => 'Email tidak boleh kosong. Mohon masukkan alamat email yang valid.',
      ];
    }

    $otpRecord = $this->mainRepository->findValidOtp($email, $otp);

    if ($otpRecord) {
      $otpRecord->update(['is_used' => true]);

      return [
        'code' => 200,
        'message' => 'OTP berhasil diverifikasi. Anda sekarang dapat melanjutkan ke langkah berikutnya.',
      ];
    }

    return [
      'code' => 400,
      'error' => 'OTP tidak valid atau sudah kedaluwarsa.',
    ];
  }

  public function resetPassword($user, $dataPasswordUser)
  {
    $validator = Validator::make($dataPasswordUser, [
      'password' => 'required|string|min:8|confirmed',
    ], [
      'password' => [
        'required' => 'Password wajib diisi.',
        'string' => 'Password harus berupa teks.',
        'min' => 'Password minimal 8 karakter.',
        'confirmed' => 'Konfirmasi password tidak cocok.',
      ],
    ]);

    if ($validator->fails()) {
      throw new InvalidArgumentException($validator->errors()->first());
    }

    $result = $this->mainRepository->updatePassword($user, Hash::make($dataPasswordUser['password']));

    return [
      'code' => 200,
      'data' => $result,
    ];
  }
}
