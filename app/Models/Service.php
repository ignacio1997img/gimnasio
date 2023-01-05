<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'busine_id',
        'name',
        'image',
        'description',
        'status',
        'ip',
        'mac',
        'register_user',
        'deleted_at',
        'userDelete_id'
    ];

    public function busine()
    {
        return $this->belongsTo(Busine::class, 'busine_id');
    }

    public function plan()
    {
        return $this->hasMany(Plan::class, 'service_id');
    }
}
