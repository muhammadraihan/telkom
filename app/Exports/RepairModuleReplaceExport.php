<?php

namespace App\Exports;

use App\Models\ItemReplaceDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RepairModuleReplaceExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
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
        $statement = ItemReplaceDetail::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'replace_status', 'vendor_name', 'item_repair_uuid', 'module_type_uuid', 'part_number', 'serial_number', 'serial_number_msc', 'accessories', 'created_at'])
            ->when($year, function ($query, $year) {
                return $query->whereYear('created_at', $year);
            })
            ->when($month, function ($query, $month) {
                return $query->whereMonth('created_at', $month);
            });

        return $statement;
    }

    public function map($item_replace): array
    {
        return [
            $item_replace->rownum,
            $item_replace->ModuleType->brand->moduleName->name,
            $item_replace->ModuleType->brand->name,
            $item_replace->ModuleType->name,
            $item_replace->part_number,
            $item_replace->serial_number,
            $item_replace->serial_number_msc,
            isset($item_replace->accessories) ? implode(',', $item_replace->accessories) : '',
            $item_replace->replace_status == 3 ? 'STOCK' : 'VENDOR',
            isset($item_replace->vendor_name) ? $item_replace->vendor_name : '',
            Carbon::parse($item_replace->created_at)->translatedFormat('j F Y'),
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
            'STATUS GANTI',
            'NAMA VENDOR',
            'TANGGAL PENGGANTIAN',
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
