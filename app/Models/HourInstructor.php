<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HourInstructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'hour_id',
        'instructor_id',
        'description',
        'status',
        'userRegister_id',
        'deleted_at',
        'userDelete_id'
    ];


    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }
    public function hour()
    {
        return $this->belongsTo(Hour::class, 'hour_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userRegister_id');
    }
}
