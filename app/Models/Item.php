<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item', 'wherehouseDetail_id', 'itemEarnings', 'amount', 'deleted_at', 'client_id', 'indice'
    ];
    
    public function wherehouseDetail()
    {
        return $this->belongsTo(WherehouseDetail::class, 'wherehouseDetail_id');
    }
}
