<?php

namespace App\Http\Controllers;

use App\Exports\InventoryModuleStockExport;
use App\Exports\ModuleHandleExport;
use App\Exports\RepairModuleReplaceExport;
use App\Exports\RepairModuleTechExport;
use App\Exports\RepairModuleVendorExport;
use App\Exports\TotalModuleByWitel;
use App\Exports\TotalModuleHandleExport;
use App\Exports\TotalModulePercentageExport;
use App\Exports\TotalModulePerWitel;
use App\Exports\TotalModuleRepairExport;
use App\Models\Witel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function RepairModuleTech()
    {
        return view('reports.repair-module-tech-export');
    }

    public function RepairModuleTechExport(Request $request)
    {
        return (new RepairModuleTechExport)
            ->forYear(isset($request->year) ? $request->year : '')
            ->forMonth(isset($request->month) ? $request->month : '')
            ->download('repair-module-by-tech.xlsx');
    }

    public function RepairModuleVendor()
    {
        return view('reports.repair-module-vendor-export');
    }

    public function RepairModuleVendorExport(Request $request)
    {
        return (new RepairModuleVendorExport)
            ->forYear(isset($request->year) ? $request->year : '')
            ->forMonth(isset($request->month) ? $request->month : '')
            ->download('repair-module-by-vendor.xlsx');
    }

    public function ReplaceModule()
    {
        return view('reports.replace-module');
    }

    public function ReplaceModuleExport(Request $request)
    {
        return (new RepairModuleReplaceExport)
            ->forYear(isset($request->year) ? $request->year : '')
            ->forMonth(isset($request->month) ? $request->month : '')
            ->download('replace-module.xlsx');
    }

    public function ModuleHandle()
    {
        return view('reports.module-handle-export');
    }

    public function ModuleHandleExport(Request $request)
    {
        return (new ModuleHandleExport)
            ->forYear(isset($request->year) ? $request->year : '')
            ->forMonth(isset($request->month) ? $request->month : '')
            ->download('module-handle.xlsx');
    }

    public function TotalModulePerWitel()
    {
        return view('reports.total-module-per-witel');
    }

    public function TotalModulePerWitelExport(Request $request)
    {
        return (new TotalModulePerWitel)
            ->forYear(isset($request->year) ? $request->year : '')
            ->forMonth(isset($request->month) ? $request->month : '')
            ->download('total-module-per-witel.xlsx');
    }

    public function TotalModuleByWitel()
    {
        $witel = Witel::all()->pluck('name', 'uuid');
        return view('reports.total-module-by-witel', compact('witel'));
    }

    public function TotalModuleByWitelExport(Request $request)
    {
        return (new TotalModuleByWitel)
            ->forWitel($request->witel_uuid)
            ->forYear(isset($request->year) ? $request->year : '')
            ->forMonth(isset($request->month) ? $request->month : '')
            ->download('total-module-by-witel.xlsx');
    }

    public function TotalModuleHandle()
    {
        return view('reports.total-module-handle');
    }

    public function TotalModuleHandleExport(Request $request)
    {
        return (new TotalModuleHandleExport)
            ->forYear(isset($request->year) ? $request->year : '')
            ->forMonth(isset($request->month) ? $request->month : '')
            ->download('total-module-handle.xlsx');
    }

    public function TotalModulePercentage()
    {
        return view('reports.total-module-percentage');
    }

    public function TotalModulePercentageExport(Request $request)
    {
        return (new TotalModulePercentageExport)
            ->forYear(isset($request->year) ? $request->year : '')
            ->forMonth(isset($request->month) ? $request->month : '')
            ->download('total-module-percentage.xlsx');
    }

    public function TotalModuleRepairComparison()
    {
        return view('reports.total-module-repair-comparison');
    }

    public function TotalModuleRepairComparisonExport(Request $request)
    {
        return (new TotalModuleRepairExport)
            ->forYear(isset($request->year) ? $request->year : '')
            ->forMonth(isset($request->month) ? $request->month : '')
            ->download('total-module-repair-comparison.xlsx');
    }

    public function ModuleInventory()
    {
        return view('reports.inventory-module-export');
    }

    public function ModuleInventoryExport(Request $request)
    {
        return (new InventoryModuleStockExport)->download('inventory-module.xlsx');
    }
}
