<?php

namespace App\Exports;

use App\Models\RepairItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TotalModulePercentageExport implements FromArray, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
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

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function array(): array
    {
        $year = $this->year;
        $month = $this->month;
        $result = [];
        $total_module = RepairItem::select([DB::raw('count(*) as total')])
            ->when($year, function ($query, $year) {
                return $query->whereYear('created_at', $year);
            })
            ->when($month, function ($query, $month) {
                return $query->whereMonth('created_at', $month);
            })->first();

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

        for ($i = 0; $i < count($modules); $i++) {
            $module_uuid = $modules[$i]->module_uuid;
            $count_module = DB::table('repair_items as repair')
                ->leftJoin('ticketings as ticket', 'repair.ticket_uuid', '=', 'ticket.uuid')
                ->leftJoin('units as unit', 'ticket.uuid_unit', '=', 'unit.uuid')
                ->leftJoin('witels as witel', 'unit.witel_uuid', '=', 'witel.uuid')
                ->leftJoin('module_types as type', 'repair.module_type_uuid', '=', 'type.uuid')
                ->leftJoin('module_brands as brand', 'type.module_brand_uuid', '=', 'brand.uuid')
                ->leftJoin('module_names as module', 'brand.module_name_uuid', '=', 'module.uuid')
                ->where('module.uuid', '=', $module_uuid)
                ->when($year, function ($query, $year) {
                    return $query->whereYear('repair.created_at', $year);
                })
                ->when($month, function ($query, $month) {
                    return $query->whereMonth('repair.created_at', $month);
                })
                ->count();

            $result[$i]['module_uuid'] = $modules[$i]->module_uuid;
            $result[$i]['module_name'] = $modules[$i]->module_name;
            $result[$i]['module_count'] = $count_module;
            $result[$i]['module_percentage'] = number_format(($count_module / $total_module->total) * 100, 2) . '%';
        }
        return $result;
    }

    public function map($total): array
    {
        return [
            $total['module_name'],
            $total['module_count'],
            $total['module_percentage'],
        ];
    }

    public function headings(): array
    {
        return [
            'NAMA MODULE',
            'JUMLAH',
            'PERSENTASE',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
