<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gaming extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function area() 
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function user() 
    {
        return $this->belongsTo(User::class, 'added_by', 'id');
    }
}
