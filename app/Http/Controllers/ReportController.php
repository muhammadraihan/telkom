<?php

namespace App\Http\Controllers;

use App\Exports\RepairModuleExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function RepairModule()
    {
        return view('reports.repair-module-export');
    }

    public function RepairModuleExport()
    {
        return Excel::download(new RepairModuleExport, 'repair-module.xlsx');
    }
}
