<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PartProcess extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_id',
        'process_id',
        'machine_id',
        'sequence',
        'std_run',
    ];

    protected $casts = [
        'std_run' => 'decimal:2',
    ];

    public function part()
    {
        return $this->belongsTo(
            Part::class
        );
    }

    public function process()
    {
        return $this->belongsTo(
            Process::class
        );
    }

    public function machine()
    {
        return $this->belongsTo(
            Machine::class
        );
    }
}