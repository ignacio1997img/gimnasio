<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hour extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'name',
        'description',
        'status',
        'userRegister_id',
        'deleted_at',
        'userDelete_id'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class. 'service_id');
    }
}
