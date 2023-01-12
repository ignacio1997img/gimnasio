<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'busine_id',
        'cashier_id',
        'type',
        'service_id', 
        'people_id',
        'plan_id',
        'hour_id',
        'hourInstructor_id',
        'day',
        'start',
        'finish',
        'subAmount',
        'amount',
        'credit',
        'status',
        'userRegister_id',
        'deleted_at',
        'userDelete_id'
    ];


    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'userRegister_id');
    // }

    public function cashier()
    {
        return $this->belongsTo(Cashier::class, 'cashier_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function plan()
    
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function day()
    {
        return $this->belongsTo(Day::class, 'day_id');
    }

    public function people()
    {
        return $this->belongsTo(People::class, 'people_id');
    }
    
    public function item()
    {
        return $this->hasMany(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userRegister_id');
    }
    public function userDelete()
    {
        return $this->belongsTo(User::class, 'userDelete_id');
    }
    public function adition()
    {
        return $this->hasMany(Adition::class, 'client_id');
    }


}
