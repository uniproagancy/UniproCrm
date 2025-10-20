<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    //
    use SoftDeletes;

    protected $table = 'db_orders';

    protected $fillable = ['agent_id', 'created_by', 'type_id', 'city_id', 'district_id', 'subdistrict_id', 'amount', 'currency_id', 'custom_id', 'customer_id', 'status_id', 'url', 'comment',
    ];

    public function agent()
    {
        return $this->hasOne(Admin::class, 'id', 'agent_id');
    }

    public function createdBy()
    {
        return $this->hasOne(Admin::class, 'id', 'created_by');
    }

    public function status()
    {
        return $this->hasOne(OrderStatus::class, 'id', 'status_id');
    }

    public function type()
    {
        return $this->hasOne(OrderType::class, 'id', 'type_id');
    }

    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    public function district()
    {
        return $this->hasOne(District::class, 'id', 'district_id');
    }

    public function subdistrict()
    {
        return $this->hasOne(SubDistrict::class, 'id', 'subdistrict_id');
    }

    public function currency()
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function comments()
    {
        return $this->hasMany(OrderComment::class, 'order_id', 'id');
    }

    public function logs()
    {
        return $this->hasMany(OrderLog::class, 'order_id', 'id');
    }
}
