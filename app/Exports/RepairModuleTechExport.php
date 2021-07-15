<?php

namespace App\Exports;

use App\Models\RepairJobOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RepairModuleTechExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
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

    public function query()
    {
        $year = $this->year;
        $month = $this->month;

        DB::statement(DB::raw('set @rownum=0'));
        $statement = RepairJobOrder::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'repair_item_uuid', 'item_status', 'job_status', 'assign_at', 'repair_cost', 'time_to_repair', 'repair_notes'])
            ->when($year, function ($query, $year) {
                return $query->whereYear('assign_at', $year);
            })
            ->when($month, function ($query, $month) {
                return $query->whereMonth('assign_at', $month);
            });
        return $statement;
    }

    public function map($repair_job): array
    {
        return [
            $repair_job->rownum,
            $repair_job->repair->ticket->unit->witel->name,
            Carbon::parse($repair_job->assign_at)->translatedFormat('j F Y'),
            $repair_job->repair->ModuleType->brand->moduleName->name,
            $repair_job->repair->ModuleType->brand->name,
            $repair_job->repair->ModuleType->name,
            $repair_job->repair->part_number,
            $repair_job->repair->serial_number,
            $repair_job->repair->serial_number_msc,
            $repair_job->repair->warranty_status == 1 ? 'WARRANTY' : 'NON WARRANTY',
            isset($repair_job->repair->accessories) ? implode(',', $repair_job->repair->accessories) : '',
            $repair_job->repair->complain,
            $repair_job->item_status == 1 ? 'REPAIR' : 'UNREPAIR',
            'Rp.' . number_format($repair_job->repair_cost, 2),
            isset($repair_job->repair_cost) ? floor($repair_job->time_to_repair / 60) . ' ' . 'JAM' . ' ' . ($repair_job->time_to_repair % 60) . ' ' . 'MENIT' : '',
            $repair_job->repair_notes,
        ];
    }

    public function  headings(): array
    {
        return [
            'NO',
            'ASAL',
            'TANGGAL TERIMA',
            'NAMA MODUL',
            'MERK',
            'TYPE',
            'PART NUMBER',
            'SERIAL NUMBER',
            'SERIAL NUMBER IM',
            'WARRANTY/NON WARRANTY',
            'KELENGKAPAN',
            'KERUSAKAN',
            'STATUS',
            'BIAYA PERBAIKAN',
            'LAMA PEKERJAAN',
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
