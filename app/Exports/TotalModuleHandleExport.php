<?php

namespace App\Exports;

use App\Models\RepairItem;
use App\Models\Witel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TotalModuleHandleExport implements FromView, ShouldAutoSize, WithStrictNullComparison, WithStyles
{
    use Exportable;

    public function forYear(string $year)
    {
        $this->year = $year;

        return $this;
    }

    public function forMonth(string $month)
    {
        $this->month = $month;

        return $this;
    }

    public function view(): View
    {
        $year = $this->year;
        $month = $this->month;
        $result = [];

        $modules = DB::table('module_names as module')
            ->rightJoin('module_brands as brand', 'module.uuid', '=', 'brand.module_name_uuid')
            ->rightJoin('module_types as type', 'brand.uuid', '=', 'type.module_brand_uuid')
            ->rightJoin('repair_items as repair', 'type.uuid', '=', 'repair.module_type_uuid')
            ->select('module.name as module_name', 'module.uuid as module_uuid')
            ->when($year, function ($query, $year) {
                return $query->whereYear('repair.created_at', $year);
            })
            ->when($month, function ($query, $month) {
                return $query->whereMonth('repair.created_at', $month);
            })
            ->groupBy(['module.name'])
            ->orderBy('module_name')
            ->get();
        $witels = DB::table('witels as witel')
            ->leftJoin('units as unit', 'witel.uuid', '=', 'unit.witel_uuid')
            ->leftJoin('ticketings as ticket', 'unit.uuid', '=', 'ticket.uuid_unit')
            ->rightJoin('repair_items as repair', 'ticket.uuid', '=', 'repair.ticket_uuid')
            ->select(['witel.name as witel_name', 'witel.uuid as witel_uuid'])
            ->when($year, function ($query, $year) {
                return $query->whereYear('repair.created_at', $year);
            })
            ->when($month, function ($query, $month) {
                return $query->whereMonth('repair.created_at', $month);
            })
            ->groupBy(['witel_name'])
            ->orderBy('witel_name')
            ->get();
        for ($i = 0; $i < count($modules); $i++) {
            $result['module'][$i]['module_name'] = $modules[$i]->module_name;
            $result['module'][$i]['module_uuid'] = $modules[$i]->module_uuid;
            for ($j = 0; $j < count($witels); $j++) {
                $module_uuid = $modules[$i]->module_uuid;
                $witel_uuid = $witels[$j]->witel_uuid;
                $count = DB::table('repair_items as repair')
                    ->leftJoin('ticketings as ticket', 'repair.ticket_uuid', '=', 'ticket.uuid')
                    ->leftJoin('units as unit', 'ticket.uuid_unit', '=', 'unit.uuid')
                    ->leftJoin('witels as witel', 'unit.witel_uuid', '=', 'witel.uuid')
                    ->leftJoin('module_types as type', 'repair.module_type_uuid', '=', 'type.uuid')
                    ->leftJoin('module_brands as brand', 'type.module_brand_uuid', '=', 'brand.uuid')
                    ->leftJoin('module_names as module', 'brand.module_name_uuid', '=', 'module.uuid')
                    ->where('module.uuid', '=', $module_uuid)
                    ->where('witel.uuid', '=', $witel_uuid)
                    ->when($year, function ($query, $year) {
                        return $query->whereYear('repair.created_at', $year);
                    })
                    ->when($month, function ($query, $month) {
                        return $query->whereMonth('repair.created_at', $month);
                    })
                    ->count();

                $result['module'][$i]['witel'][$j]['witel_name'] = $witels[$j]->witel_name;
                $result['module'][$i]['witel'][$j]['witel_uuid'] = $witels[$j]->witel_uuid;
                $result['module'][$i]['witel'][$j]['module_count'] = $count;
            }
        }
        return view('reports.template.total-module-handle-template', compact('result'));
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
