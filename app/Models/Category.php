<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'busine_id', 'status', 'userRegister_id', 'deleted_at'
    ];

    public function busine()
    {
        return $this->belongsTo(Busine::class, 'busine_id');
    }

}
