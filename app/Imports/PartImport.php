<?php

namespace App\Imports;

use App\Models\Part;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PartImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip jika semua kolom kosong
        if (
            empty($row['part_no']) &&
            empty($row['part_name']) &&
            empty($row['std_run'])
        ) {
            return null;
        }

        // Skip jika part_no kosong
        if (empty($row['part_no'])) {
            return null;
        }

        // Skip jika part_name kosong
        if (empty($row['part_name'])) {
            return null;
        }

        return Part::updateOrCreate(
            [
                'part_no' => $row['part_no'],
            ],
            [
                'part_name' => $row['part_name'],
                'std_run' => $row['std_run'],
            ]
        );
    }
}
