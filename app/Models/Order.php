<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'reference_no',
        'user_id',
        'billing_first_name',
        'billing_last_name',
        'billing_email',
        'billing_phone',
        'billing_country',
        'billing_address_1',
        'billing_address_2',
        'billing_city',
        'billing_state',
        'billing_zip_code',
        'shipping_method',
        'shipping_cost',
        'payment_method',
        'coupon_id',
        'payment_id',
        'discount ',
        'total',
        'currency_base_total',
        'currency_symbol',
        'order_status',
        'payment_status',
        'date',
        'tax_id ',
    ];

    protected $dates = ['deleted_at'];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function shippingDetails()
    {
        return $this->hasOne(Shipping::class);
    }

    public function coupon(){
        return $this->belongsTo(Coupon::class,'coupon_id');
    }
}
