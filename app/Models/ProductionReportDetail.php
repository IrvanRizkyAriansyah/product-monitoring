<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionReportDetail extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'report_id',
        'part_process_id',
        'report_date',
        'target_qty',
        'actual_qty',
    ];

    public function report()
    {
        return $this->belongsTo(ProductionReport::class, 'report_id');
    }

    public function partProcess()
{
    return $this->belongsTo(
        PartProcess::class
    );
}
}
