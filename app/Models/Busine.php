<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Busine extends Model
{
    use HasFactory;
    protected $fillable = ['nit', 'name', 'responsible', 'phone', 'email', 'address', 'image', 'status', 'deleted_at'];
    
}
