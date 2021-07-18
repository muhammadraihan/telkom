<?php

namespace App\Exports;

use App\Models\RepairJobVendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RepairModuleVendorExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles

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
        $statement = RepairJobVendor::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'repair_item_uuid', 'vendor_name', 'repair_notes', 'created_at'])
            ->when($year, function ($query, $year) {
                return $query->whereYear('created_at', $year);
            })
            ->when($month, function ($query, $month) {
                return $query->whereMonth('created_at', $month);
            });

        return $statement;
    }

    public function map($repair_vendor): array
    {
        return [
            $repair_vendor->rownum,
            $repair_vendor->repair->ticket->unit->witel->name,
            $repair_vendor->repair->ModuleType->brand->moduleName->name,
            $repair_vendor->repair->ModuleType->brand->name,
            $repair_vendor->repair->ModuleType->name,
            $repair_vendor->repair->part_number,
            $repair_vendor->repair->serial_number,
            $repair_vendor->repair->serial_number_msc,
            isset($repair_vendor->repair->accessories) ? implode(',', $repair_vendor->repair->accessories) : '',
            $repair_vendor->repair->complain,
            $repair_vendor->vendor_name,
            Carbon::parse($repair_vendor->created_at)->translatedFormat('j F Y'),
            $repair_vendor->repair_notes,
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'ASAL',
            'NAMA MODUL',
            'MERK',
            'TYPE',
            'PART NUMBER',
            'SERIAL NUMBER',
            'SERIAL NUMBER IM',
            'KELENGKAPAN',
            'KERUSAKAN',
            'VENDOR',
            'TANGGAL KEMBALI',
            'KETERANGAN',
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
