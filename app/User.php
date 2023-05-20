<?php

namespace App;

use App\Models\Order;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasRoles, SoftDeletes;


    protected $fillable = [
        'username', 'first_name', 'last_name','phone','email','password','role_id','image','last_login_at','last_login_ip','user_type'
    ];
    
    protected $dates = ['deleted_at'];


    protected $hidden = [
        'password', 'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roleName()
    {
        return $this->belongsTo('Spatie\Permission\Models\Role','role','id');
    }

    public function orders()
    {
    	return $this->hasMany(Order::class,'user_id');
    }
}
