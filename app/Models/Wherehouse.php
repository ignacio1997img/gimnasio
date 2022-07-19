<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wherehouse extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'busine_id', 'provider_id', 'number', 'status', 'userRegister_id', 'userDetele_id', 'deleted_at'
    ];

    public function busine()
    {
        return $this->belongsTo(Busine::class, 'busine_id');
    }
}
