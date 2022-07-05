<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VaultsClosure extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'vault_id', 'user_id', 'observations'
    ];

    public function details(){
        return $this->hasMany(VaultsClosuresDetail::class);
    }

    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }
}
