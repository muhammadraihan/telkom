<?php

namespace App\Exports;

use App\Models\Material;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryMaterialStockExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{

    use Exportable;

    public function query()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $statement = Material::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'module_name_uuid', 'material_type', 'material_description', 'available', 'volume', 'unit_price']);
        return $statement;
    }

    public function map($material): array
    {
        return [
            $material->rownum,
            $material->moduleName->category->name,
            $material->moduleName->name,
            $material->material_type,
            $material->material_description,
            $material->available,
            $material->volume,
            'Rp.' . ' ' . number_format($material->unit_price, 2),
        ];
    }

    public function  headings(): array
    {
        return [
            'NO',
            'MODULE CATEGORY',
            'MODULE NAME',
            'MATERIAL',
            'DESKRIPSI',
            'AVAILABLE',
            'VOLUME',
            'UNIT PRICE',
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
