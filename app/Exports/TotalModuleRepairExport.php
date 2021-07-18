<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TotalModuleRepairExport implements FromArray, WithMapping, WithHeadings, ShouldAutoSize, WithStrictNullComparison, WithStyles
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

    public function array(): array
    {
        $year = $this->year;
        $month = $this->month;
        $result = [];
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
        for ($i = 0; $i < count($witels); $i++) {
            $witel_uuid = $witels[$i]->witel_uuid;
            $count_module = DB::table('repair_job_orders as repair_job')
                ->leftJoin('repair_items as repair', 'repair_job.repair_item_uuid', '=', 'repair.uuid')
                ->leftJoin('ticketings as ticket', 'repair.ticket_uuid', '=', 'ticket.uuid')
                ->leftJoin('units as unit', 'ticket.uuid_unit', '=', 'unit.uuid')
                ->leftJoin('witels as witel', 'unit.witel_uuid', '=', 'witel.uuid')
                ->leftJoin('module_types as type', 'repair.module_type_uuid', '=', 'type.uuid')
                ->leftJoin('module_brands as brand', 'type.module_brand_uuid', '=', 'brand.uuid')
                ->leftJoin('module_names as module', 'brand.module_name_uuid', '=', 'module.uuid')
                ->where('witel.uuid', '=', $witel_uuid)
                ->when($year, function ($query, $year) {
                    return $query->whereYear('repair.created_at', $year);
                })
                ->when($month, function ($query, $month) {
                    return $query->whereMonth('repair.created_at', $month);
                });
            $result[$i]['witel_uuid'] = $witels[$i]->witel_uuid;
            $result[$i]['witel_name'] = $witels[$i]->witel_name;
            $result[$i]['module_in'] = $count_module->count();
            $result[$i]['module_repaired'] = $count_module->where('repair_job.item_status', '=', 1)->count();
        }
        return $result;
    }

    public function map($total): array
    {
        return [
            $total['witel_name'],
            $total['module_in'],
            $total['module_repaired'],
        ];
    }

    public function headings(): array
    {
        return [
            'WITEL',
            'JUMLAH MODULE MASUK',
            'JUMLAH SELESAI REPAIR'
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
