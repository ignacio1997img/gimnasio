<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attention extends Model
{
    use HasFactory;
    protected $fillable = [
        'cashier_id', 'service_id', 'plan_id', 'day_id', 'people_id', 'beforeImage', 'laterImage', 'beforeWeight',
        'laterWeight', 'start', 'finish', 'status', 'ip', 'userRegister_id', 'userDelete_id', 'deleted_at'
    ];
}
