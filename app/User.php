<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use App\Model\Order;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'role', 'phone', 'address',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'role' => 'integer',
        'active' => 'integer',
        // 'email_verified_at' => 'datetime',
    ];

     /**
     * @return role
     */
    public function role()
    {
        return $this->role;
    }

    public function photo()
    {
       return Storage::disk('s3')->url($this->image);
    }

    public function active()
    {
        return $this->active;
    }

    public function waiterOrders()
    {
        return $this->hasMany(Order::class, 'served_by');
    }

    public function waiterOrdersToday()
    {
        return $this->hasMany(Order::class, 'served_by')
            ->where('created_at', 'like',
                \Carbon\Carbon::today()->format('Y-m-d') . '%');
    }

    public function kitchenOrders()
    {
        return $this->hasMany(Order::class, 'kitchen_id');
    }

    public function kitchenOrderToday()
    {
        return $this->hasMany(Order::class, 'kitchen_id')
            ->where('created_at', 'like',
                \Carbon\Carbon::today()->format('Y-m-d') . '%');
    }
}
