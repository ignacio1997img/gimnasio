<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'presentation', 'image', 'status', 'userRegister_id', 'deleted_at'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

   
}
