<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;



class BillofMaterialExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return new Collection($this->flattenRecursive($this->data));
    }

    public function headings(): array
    {
        return ['id', 'ItemDescription', 'parent', 'BOMType'];
    }

    public function map($row): array
    {
        return [
            'id' => $row['id'],
            'ItemDescription' => $row['ItemDescription'],
            'parent' => $row['parent'],
            'BOMType' => $row['BOMType'],
        ];
    }

    private function flattenRecursive($data, $parent = '')
    {
        $result = [];

        foreach ($data as $item) {
            $row = [
                'id' => $item['id'],
                'ItemDescription' => $item['ItemDescription'],
                'parent' => $parent,
                'BOMType' => $item['BOMType'],
            ];

            $result[] = $row;

            if (isset($item['nodes'])) {
                $result = array_merge($result, $this->flattenRecursive($item['nodes'], $item['id']));
            }
        }

        return $result;
    }
}
