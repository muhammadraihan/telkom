<?php

namespace App\Exports;

use App\Helper\Helper;
use App\Models\RepairItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ModuleHandleExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
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
    public function query()
    {
        $year = $this->year;
        $month = $this->month;

        DB::statement(DB::raw('set @rownum=0'));
        $statement = RepairItem::select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'module_type_uuid', 'part_number', 'serial_number', 'serial_number_msc', 'accessories', 'complain', 'warranty_status', 'status', 'created_at',
            DB::raw('(CASE WHEN status = 1 THEN "DIPERBAIKI TEKNISI" WHEN status = 2 THEN "DIPERBAIKI VENDOR" WHEN status = 3 THEN "DIGANTI DARI STOCK" ELSE "DIGANTI DARI VENDOR" END) AS item_status')
        ])
            ->when($year, function ($query, $year) {
                return $query->whereYear('created_at', $year);
            })
            ->when($month, function ($query, $month) {
                return $query->whereMonth('created_at', $month);
            });
        return $statement;
    }

    public function map($module): array
    {
        return [
            $module->rownum,
            $module->ModuleType->brand->moduleName->name,
            $module->ModuleType->brand->name,
            $module->ModuleType->name,
            $module->part_number,
            $module->serial_number,
            $module->serial_number_msc,
            isset($module->accessories) ? implode(',', $module->accessories) : '',
            $module->complain,
            $module->warranty_status == 1 ? 'WARRANTY' : 'NON WARRANTY',
            $module->item_status,
            Carbon::parse($module->created_at)->translatedFormat('j F Y'),
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA MODUL',
            'MERK',
            'TYPE',
            'PART NUMBER',
            'SERIAL NUMBER',
            'SERIAL NUMBER IM',
            'KELENGKAPAN',
            'KERUSAKAN',
            'WARRANTY/NON WARRANTY',
            'STATUS',
            'TANGGAL TERIMA',
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
