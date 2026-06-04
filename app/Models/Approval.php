<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'report_id',
        'approved_by',
        'status',
        'notes',
    ];

    public function report()
    {
        return $this->belongsTo(ProductionReport::class, 'report_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

}
