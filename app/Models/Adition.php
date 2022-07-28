<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adition extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'cant', 'status', 'deleted_at', 'cashier_id', 'observation', 'type', 'userRegister_id'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'userRegister_id');
    }


}
