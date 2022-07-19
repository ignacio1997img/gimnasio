<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Prophecy\Doubler\Generator\Node\ReturnTypeNode;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'busine_id', 'nit', 'name', 'responsible', 'phone', 'image', 'address', 'status', 'userRegister_id', 'deleted_at'
    ];

    public function busine()
    {
        return $this->belongsTo(Busine::class, 'busine_id');
    }


}
