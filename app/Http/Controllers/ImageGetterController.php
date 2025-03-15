<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageGetterController extends Controller
{
    public function __invoke(Request $request, $context)
    {
        if ($context) {
            switch ($context) {
                case 'profile':
                    $imagePath = $request->profile ? $request->profile : 'img/profile/default-profile.jpg';
                    if (!Storage::exists($imagePath)) {
                        $imagePath = 'img/profile/default-profile.jpg';
                    }
                    break;
                case 'discount':
                    $imagePath = $request->discount ? $request->discount : 'img/discount/diskon_awal_tahun.jpg';
                    if (!Storage::exists($imagePath)) {
                        $imagePath = 'img/discount/diskon_awal_tahun.jpg';
                    }
                    break;
                case 'motorcycle':
                    $imagePath = $request->motorcycle ? $request->motorcycle : 'img/motorcycle/xsr.png';
                    if (!Storage::exists($imagePath)) {
                        $imagePath = 'img/motorcycle/xsr.png';
                    }
                    break;
                case 'review':
                    $imagePath = $request->review ? $request->review : 'img/review/review1.jpg';
                    if (!Storage::exists($imagePath)) {
                        $imagePath = 'img/review/review1.jpg';
                    }
                    break;
                default:
                    $imagePath = 'img/profile/default-profile.jpg';
                    break;
            }
            return Storage::response($imagePath);
        }
    }
}
