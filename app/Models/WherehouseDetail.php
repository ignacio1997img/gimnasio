<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WherehouseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'wherehouse_id', 'article_id', 'amount', 'items', 'item', 'unitPrice', 'itemEarnings', 'expiration',
        'status', 'userRegister_id', 'delleted_at', 'userDetele_id'
    ];

    public function wherehouse()
    {
        return $this->belongsTo(Wherehouse::class, 'wherhouse_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
