<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id')->select('id', 'name');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'added_by', 'id');
    }
}
