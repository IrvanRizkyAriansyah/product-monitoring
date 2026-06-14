<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    //
    protected $fillable = [
        'process_code',
        'process_name',
    ];

    public function partProcesses()
    {
        return $this->hasMany(
            PartProcess::class
        );
    }
}
