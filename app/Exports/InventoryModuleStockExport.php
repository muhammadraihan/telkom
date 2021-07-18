<?php

namespace App\Exports;

use App\Models\ModuleStock;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryModuleStockExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function query()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $statement = ModuleStock::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'module_type_uuid', 'available', 'created_at']);
        return $statement;
    }

    public function map($repair_job): array
    {
        return [
            $repair_job->rownum,
            $repair_job->type->brand->moduleName->category->name,
            $repair_job->type->brand->moduleName->name,
            $repair_job->type->brand->name,
            $repair_job->type->name,
            $repair_job->available,
        ];
    }

    public function  headings(): array
    {
        return [
            'NO',
            'MODULE CATEGORY',
            'MODULE NAME',
            'MODULE BRAND',
            'MODULE TYPE',
            'AVAILABLE',
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
