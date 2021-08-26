<?php

namespace App\Http\Controllers;

use App\Models\Ticketing;

class DashboardController extends Controller
{
  /**
   * Redirect to dashboard
   * @return [type] [description]
   */
  public function index()
  {
    return redirect()->route('backoffice.dashboard');
  }

  public function dashboard()
  {
    $all_ticket = Ticketing::count();
    $repair_ticket = Ticketing::where('ticket_status', 1)->count();
    $warehouse_ticket = Ticketing::where('ticket_status', 2)->count();
    $closed_ticket = Ticketing::where('ticket_status', 3)->count();
    return view('backoffice.dashboard', compact('all_ticket', 'repair_ticket', 'warehouse_ticket', 'closed_ticket'));
  }
}
