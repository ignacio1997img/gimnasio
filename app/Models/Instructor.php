<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'people_id',
        'description',
        'status',
        'userRegister_id',
        'deleted_at',
        'userDelete_id'
    ];

    public function people()
    {
        return $this->belongsTo(People::class, 'people_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userRegister_id');
    }
}
