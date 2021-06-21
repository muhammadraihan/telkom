<?php

namespace App\Helper;

use App\Models\Ticketing;
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
  public static function greeting()
  {
    $carbon = Carbon::now('Asia/Jakarta');
    $hour = $carbon->format('H');
    if ($hour < 12) {
      return 'Selamat Pagi';
    } elseif ($hour < 17) {
      return 'Selamat Siang';
    }
    return 'Selamat Malam';
  }

  public static function pelapor()
  {
    try {
      if (!$user = JWTAuth::parseToken()->authenticate()) {
        return response()->json(['status' => 'ACCOUNT_NOT_FOUND'], 404);
      }
    } catch (Exception $e) {
      if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
        return response()->json(['status' => 'TOKEN_IS_INVALID'], 500);
      } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
        return response()->json(['status' => 'TOKEN_IS_EXPIRED'], 500);
      } else {
        return response()->json(['status' => 'TOKEN_NOT_FOUND'], 500);
      }
    }
    return $user;
  }

  /**
   * Generate Ticket Number
   * Ticket format : IMR1_YYXXXXXX
   * When Y is two digits current year and X is gerated ticket amoumt
   * Asuming max ticket created is 999999 per year
   * @return void
   */
  public static function GenerateTicketNumber()
  {
    // count ticket with current year prefix first.
    $ticket_start = Ticketing::where('ticket_number', 'LIKE', '%_' . date('y') . '%')->count();
    $ticket_start++; // increment count result
    $ticket_format = 'IMR1' . '_' . date('y') . str_pad(($ticket_start), 6, '0', STR_PAD_LEFT);
    return $ticket_format;
  }
}
