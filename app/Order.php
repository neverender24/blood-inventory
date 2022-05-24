<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    // use SoftDeletes;
    
    protected $fillable = [
        'transaction_code',
        'order_date',
        'order_time',
        'delivery_date',
        'delivery_time',
        'routine', 
        'user_id'
    ];

    protected $dates = ['deleted_at'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function order_details() {
        return $this->hasMany('App\OrderDetail');
    }

    public function scopeIsOwner($query) {
        return $query->where('user_id', auth()->user()->id);
    }
}
