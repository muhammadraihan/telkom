<?php

namespace App\Http\Controllers;

use App\Exports\ModuleHandleExport;
use App\Exports\RepairModuleReplaceExport;
use App\Exports\RepairModuleTechExport;
use App\Exports\RepairModuleVendorExport;
use App\Exports\TotalModulePerWitel;
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

    public function RepairModuleVendor()
    {
        return view('reports.repair-module-vendor-export');
    }

    public function RepairModuleVendorExport(Request $request)
    {
        return (new RepairModuleVendorExport)
            ->forYear(isset($request->year))
            ->forMonth(isset($request->month))
            ->download('repair-module-by-vendor.xlsx');
    }

    public function ReplaceModule()
    {
        return view('reports.replace-module');
    }

    public function ReplaceModuleExport(Request $request)
    {
        return (new RepairModuleReplaceExport)
            ->forYear(isset($request->year))
            ->forMonth(isset($request->month))
            ->download('replace-module.xlsx');
    }

    public function ModuleHandle()
    {
        return view('reports.module-handle-export');
    }

    public function ModuleHandleExport(Request $request)
    {
        return (new ModuleHandleExport)
            ->forYear(isset($request->year))
            ->forMonth(isset($request->month))
            ->download('module-handle.xlsx');
    }

    public function TotalModulePerWitel()
    {
        return view('reports.total-module-per-witel');
    }

    public function TotalModulePerWitelExport(Request $request)
    {
        // dd($request->all());
        return (new TotalModulePerWitel)
            ->forYear(isset($request->year))
            ->forMonth(isset($request->month))
            ->download('total-module-per-witel.xlsx');
    }
}
