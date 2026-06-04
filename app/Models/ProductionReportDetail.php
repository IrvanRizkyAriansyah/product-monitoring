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
        'production_hour',
        'target_qty',
        'actual_qty',
    ];

    public function report()
    {
        return $this->belongsTo(ProductionReport::class, 'report_id');
    }

}
