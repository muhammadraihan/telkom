<?php

namespace App\Exports;

use App\Models\RepairItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TotalModuleByWitel implements FromArray, WithMapping, WithHeadings, ShouldAutoSize, WithStrictNullComparison, WithStyles
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

    public function forWitel(string $witel)
    {
        $this->witel = $witel;

        return $this;
    }

    public function array(): array
    {
        $year = $this->year;
        $month = $this->month;
        $witel = $this->witel;
        $result = [];
        $module = RepairItem::select([DB::raw('count(*) as total'), 'module_type_uuid', 'ticket_uuid', 'created_at'])
            ->whereHas('ticket.unit.witel', function (Builder $q) use ($witel) {
                $q->where('uuid', $witel);
            })
            ->when($year, function ($query, $year) {
                return $query->whereYear('created_at', $year);
            })
            ->when($month, function ($query, $month) {
                return $query->whereMonth('created_at', $month);
            })->GroupBy('module_type_uuid')->get();

        foreach ($module as $key => $value) {
            $result[$key]['witel'] = $value->ticket->unit->witel->name;
            $result[$key]['module'] = $value->ModuleType->brand->moduleName->name;
            $result[$key]['count'] = $value->total;
        }
        return $result;
    }

    public function map($total): array
    {
        return [
            $total['module'],
            $total['count'],
            $total['witel'],
        ];
    }

    public function headings(): array
    {
        return [
            'NAMA MODULE',
            'JUMLAH',
            'WITEL'
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
