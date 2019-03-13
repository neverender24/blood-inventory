<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = ['blood_type_id', 'blood_component_id', 'qty', 'remarks', 'order_id'];

    public function blood_type() {
        return $this->belongsTo('App\BloodType');
    }

    public function blood_component() {
        return $this->belongsTo('App\BloodComponent');
    }

    public function order() {
        return $this->belongsTo('App\Order');
    }

    public function dispositions() {
        return $this->belongsToMany('App\Disposition', 'disposition_order_detail');
    }

}
