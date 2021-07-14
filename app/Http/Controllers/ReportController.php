<?php

namespace App\Http\Controllers;

use App\Exports\RepairModuleTechExport;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function RepairModuleTech()
    {
        return view('reports.repair-module-tech-export');
    }

    public function RepairModuleTechExport(Request $request)
    {
        return (new RepairModuleTechExport)
            ->forYear(isset($request->year))
            ->forMonth(isset($request->month))
            ->download('repair-module-by-tech.xlsx');
    }
}
