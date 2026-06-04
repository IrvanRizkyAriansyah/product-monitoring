<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reject_Type extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    public function rejects()
   {
        return $this->hasMany(Reject::class);
    }

}
