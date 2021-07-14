<?php

namespace App\Exports;

use App\Models\RepairJobVendor;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RepairModuleVendorExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize

{
    use Exportable;

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $query = RepairJobVendor::selct([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'repair_item_uuid', 'vendor_name', 'repair_notes', 'created_at']);
        return $query;
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
            'STATUS',
            'VENDOR',
            'KETERANGAN',
        ];
    }
}
