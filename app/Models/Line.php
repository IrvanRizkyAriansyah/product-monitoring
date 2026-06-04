<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function machines()
    {
        return $this->hasMany(Machine::class);
    }

    public function productionReports()
    {
        return $this->hasMany(ProductionReport::class);
    }

}
