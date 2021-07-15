<?php

namespace App\Exports;

use App\Models\RepairItem;
use App\Models\Witel;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TotalModulePerWitel implements FromArray, WithMapping, WithHeadings, ShouldAutoSize, WithStrictNullComparison, WithStyles
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
        $witel = Witel::select('uuid', 'name')->get();
        for ($i = 0; $i < count($witel); $i++) {
            $witel_uuid = $witel[$i]['uuid'];
            $repair_item = RepairItem::select(['uuid', 'ticket_uuid', 'created_at'])
                ->whereHas('ticket.unit', function ($q) use ($witel_uuid) {
                    $q->where('witel_uuid', $witel_uuid);
                })
                ->when($year, function ($query, $year) {
                    return $query->whereYear('created_at', $year);
                })
                ->when($month, function ($query, $month) {
                    return $query->whereMonth('created_at', $month);
                });

            $result[$i]['no'] = $i + 1;
            $result[$i]['witel'] = $witel[$i]['name'];
            $result[$i]['count'] = $repair_item->count();
        }
        return $result;
    }

    public function map($total): array
    {
        return [
            $total['no'],
            $total['witel'],
            $total['count'],
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'WITEL',
            'JUMLAH',
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
