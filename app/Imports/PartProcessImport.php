<?php

namespace App\Imports;

use App\Models\Machine;
use App\Models\Part;
use App\Models\PartProcess;
use App\Models\Process;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PartProcessImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (
            empty($row['part_no']) ||
            empty($row['part_name']) ||
            empty($row['process']) ||
            empty($row['machine'])
        ) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | PART
        |--------------------------------------------------------------------------
        */

        $part = Part::updateOrCreate(

            [
                'part_no' => trim($row['part_no']),
            ],

            [
                'part_name' => trim($row['part_name']),
            ]

        );

        /*
        |--------------------------------------------------------------------------
        | PROCESS
        |--------------------------------------------------------------------------
        */

        $process = Process::firstOrCreate(

            [
                'process_name' =>
                trim($row['process']),
            ]

        );

        /*
        |--------------------------------------------------------------------------
        | MACHINE
        |--------------------------------------------------------------------------
        */

        $machine = Machine::firstOrCreate(

            [
                'machine_name' =>
                trim($row['machine']),
            ],

            [
                'status' => 'active',
            ]

        );

        /*
        |--------------------------------------------------------------------------
        | PART PROCESS
        |--------------------------------------------------------------------------
        */

        return PartProcess::updateOrCreate(

            [

                'part_id' => $part->id,

                'process_id' => $process->id,

                'machine_id' => $machine->id,

            ],

            [

                'sequence' =>
                $row['sequence'] ?? 1,

                'std_run' =>
                $row['std_run'] ?? null,

            ]

        );
    }
}