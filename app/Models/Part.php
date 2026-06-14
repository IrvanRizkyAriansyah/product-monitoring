<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'part_no',
        'part_name',
        'std_run',
    ];

    protected $casts = [
        'std_run' => 'decimal:2',
    ];

    public function productionReports()
    {
        return $this->hasMany(ProductionReport::class);
    }

    public function partProcesses()
    {
        return $this->hasMany(
            PartProcess::class
        );
    }
}
