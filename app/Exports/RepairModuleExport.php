<?php

namespace App\Exports;

use App\Models\RepairItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RepairModuleExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{

    use Exportable;

    public function query()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $query = RepairItem::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'ticket_uuid', 'module_type_uuid', 'part_number', 'serial_number', 'serial_number_msc', 'complain', 'warranty_status', 'accessories', 'repair_status', 'created_at'])->whereHas('ticket', function ($statement) {
            return $statement->where('job_status', '=', 9);
        });
        return $query;
    }

    public function map($repair): array
    {
        return [
            $repair->rownum,
            $repair->ModuleType->brand->moduleName->name,
            $repair->ModuleType->brand->name,
            $repair->ticket->unit->witel->name,
            Carbon::parse($repair->created_at)->translatedFormat('j F'),
            Carbon::parse($repair->created_at)->translatedFormat('Y'),
            $repair->ModuleType->name,
            $repair->part_number,
            $repair->serial_number,
            $repair->serial_number_msc,
            $repair->warranty_status == 1 ? 'WARRANTY' : 'NON WARRANTY',
            $repair->accessories ? implode(',', $repair->accessories) : '',
            $repair->complain,
            switch ($repair->status) {
                case 0:
                    'UNREPAIRED';
                    break;
                case 1:
                    'REPAIR BY TECHNICIAN';
                case 2:
                    'REPAIRED BY VENDOR';
                default:
                    'UNKNONW';
                    break;
            }

        ];
    }

    public function  headings(): array
    {
        return [
            'NO',
            'NAMA MODUL',
            'MERK',
            'ASAL',
            'TANGGAL TERIMA',
            'TAHUN',
            'TYPE',
            'PART NUMBER',
            'SERIAL NUMBER',
            'SERIAL NUMBER IM',
            'WARRANTY/NON WARRANTY',
            'KELENGKAPAN',
            'KERUSAKAN',
            // 'STATUS',
            // 'TANGGAL KIRIM',
            // 'KETERANGAN',
        ];
    }
}
