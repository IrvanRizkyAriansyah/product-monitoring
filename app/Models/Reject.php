<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reject extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'report_id',
        'reject_type_id',
        'qty',
        'notes',
    ];

    public function report()
    {
        return $this->belongsTo(ProductionReport::class, 'report_id');
    }

    public function rejectType()
    {
        return $this->belongsTo(RejectType::class);
    }

}
