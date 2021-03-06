<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    use HasFactory;

    protected $fillable = [
        'busine_id', 'ci', 'first_name', 'last_name', 'birthdate', 'email', 'phone','address', 'photo', 'gender',
        'weight', 'height', 'status', 'deleted_at'
    ];

    public function busine()
    {
        return $this->belongsTo(Busine::class, 'busine_id');
    }
}
