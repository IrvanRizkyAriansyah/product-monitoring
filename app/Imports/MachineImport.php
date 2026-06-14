<?php

namespace App\Imports;

use App\Models\Line;
use App\Models\Machine;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Throwable;

class MachineImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnError,
    SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    public function model(array $row)
    {
        // Skip jika baris kosong
        if (
            empty($row['line']) &&
            empty($row['machine_code']) &&
            empty($row['machine_name'])
        ) {
            return null;
        }

        // Cari line
        $line = Line::where(
            'name',
            trim($row['line'])
        )->first();

        // Skip jika line tidak ditemukan
        if (! $line) {
            return null;
        }

        return Machine::updateOrCreate(
            [
                'machine_code' => trim(
                    $row['machine_code']
                ),
            ],
            [
                'line_id' => $line->id,
                'machine_name' => trim(
                    $row['machine_name']
                ),
                'status' => $row['status'] ?? 'active',
            ]
        );
    }

    public function rules(): array
    {
        return [
            '*.line' => 'required',
            '*.machine_code' => 'required',
            '*.machine_name' => 'required',
            '*.status' => 'nullable',
        ];
    }

    public function onError(Throwable $e)
    {
        // Skip error
    }

    public function onFailure(...$failures)
    {
        // Skip validation failure
    }
}