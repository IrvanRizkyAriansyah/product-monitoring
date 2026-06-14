<?php

namespace App\Imports;

use App\Models\Process;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProcessImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Skip jika data kosong
        if (
            empty($row['process_code']) ||
            empty($row['process_name'])
        ) {
            return null;
        }

        return Process::updateOrCreate(
            [
                'process_code' => trim($row['process_code']),
            ],
            [
                'process_name' => trim($row['process_name']),
            ]
        );
    }
}