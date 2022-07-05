<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vault extends Model
{
    use HasFactory;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id', 'name', 'description', 'status', 'deleted_at', 'busine_id'
    ];

    public function details(){
        return $this->hasMany(VaultsDetail::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function busine()
    {
        return $this->belongsTo(Busine::class, 'busine_id');
    }
}
