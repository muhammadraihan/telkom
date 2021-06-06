<?php

namespace App\Helper;

use Carbon\Carbon;
use Exception;
use JWTAuth;

class Helper
{
  /**
   * Helper for greeting message
   * based on hour time
   * @return string
   */
  public static function greeting(){
    $carbon = Carbon::now('Asia/Jakarta');
    $hour = $carbon->format('H');
    if ($hour < 12){
      return 'Selamat Pagi';
    }
    elseif ($hour < 17 ){
      return 'Selamat Siang';
    }
    return 'Selamat Malam';
  }

  public static function pelapor()
  {
    try {
      if (! $user = JWTAuth::parseToken()->authenticate()) {
        return response()->json(['status' => 'ACCOUNT_NOT_FOUND'], 404);
      }
    } catch (Exception $e) {
      if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
        return response()->json(['status' => 'TOKEN_IS_INVALID'],500);
      } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
        return response()->json(['status' => 'TOKEN_IS_EXPIRED'],500);
      } else{
        return response()->json(['status' => 'TOKEN_NOT_FOUND'],500);
      }
    }
    return $user;
  }

  public static function GenerateTicketNumber($length = 20)
  {
    $characters = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
}
