<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionReport extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'line_id',
        'part_id',
        'shift_id',
        'leader_id',
        'report_date',
        'total_target',
        'total_actual',
        'achievement',
        'status',
        'notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'report_date' => 'date',
        'approved_at' => 'datetime',
        'achievement' => 'decimal:2',
    ];

    public function line()
    {
        return $this->belongsTo(Line::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function details()
    {
        return $this->hasMany(ProductionReportDetail::class, 'report_id');
    }

    public function downtimes()
    {
        return $this->hasMany(Downtime::class, 'report_id');
    }

    public function rejects()
    {
        return $this->hasMany(Reject::class, 'report_id');
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class, 'report_id');
    }

}
