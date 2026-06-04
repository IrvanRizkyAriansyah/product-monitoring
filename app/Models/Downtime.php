<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Downtime extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'report_id',
        'machine_id',
        'start_time',
        'end_time',
        'duration_minutes',
        'reason',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function report()
    {
        return $this->belongsTo(ProductionReport::class, 'report_id');
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

}
