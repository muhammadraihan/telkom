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

  /**
   * Count Time to repair
   * This method will be count repair time per job
   * Weekend days will not included in this calculation
   * This calculation not cover holidays and leave day
   * @todo Need calculate holidays and staff leave
   * @param String $job_created
   * @param String $job_finish
   * @return void
   */
  public static function CountRepairTime($job_created, $job_finish)
  {
    // set working time per day
    $work_hour_start = '08:00:00';
    $work_hour_end = '17:00:00';
    // calculate working hours per day
    $working_hours = Carbon::parse($work_hour_start)->diffInHours($work_hour_end);
    // get total days between task created and task is finish
    $total_days = Carbon::parse($job_created)->diffInDays($job_finish);
    // filter weekend days
    $total_weekend_days = Carbon::parse($job_created)->diffInDaysFiltered(function (Carbon $date) {
      return $date->isWeekend();
    }, $job_finish);
    // calculate working days per task
    $working_days = $total_days - $total_weekend_days;
    // flag is finish in same day
    $same_day = Carbon::parse($job_created)->isSameDay($job_finish);

    if ($same_day == true) {
      // count time to repair
      $time_to_repair =  Carbon::parse($job_created)->floatDiffInHours($job_finish);
      return $time_to_repair;
    } elseif ($working_days >= 1) {
      // convert job in time format
      $job_time_start = Carbon::parse($job_created)->toTimeString();
      $job_time_finish = Carbon::parse($job_finish)->toTimeString();
      // count difference hours if job finished next day to past the midnight gap 
      $actual_job_start = Carbon::parse($work_hour_end)->floatDiffInHours($job_time_start);
      $actual_job_finish = Carbon::parse($work_hour_start)->floatDiffInHours($job_time_finish);
      // count addition working hours
      $addition_working_hours = $working_days * $working_hours;
      // count time to repair
      $time_to_repair =  $actual_job_start + $actual_job_finish + $addition_working_hours;
      return $time_to_repair;
    } else {
      // convert job in time format
      $job_time_start = Carbon::parse($job_created)->toTimeString();
      $job_time_finish = Carbon::parse($job_finish)->toTimeString();
      // count difference hours if job finished next day to past the midnight gap 
      $actual_job_start = Carbon::parse($work_hour_end)->floatDiffInHours($job_time_start);
      $actual_job_finish = Carbon::parse($work_hour_start)->floatDiffInHours($job_time_finish);
      // count time to repair
      $time_to_repair =  $actual_job_start + $actual_job_finish;
      return $time_to_repair;
    }
  }
}
