<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'line_id',
        'machine_code',
        'machine_name',
        'status',
    ];

    public function line()
    {
        return $this->belongsTo(Line::class);
    }

    public function downtimes()
    {
        return $this->hasMany(Downtime::class);
    }

    public function partProcesses()
    {
        return $this->hasMany(
            PartProcess::class
        );
    }
}
