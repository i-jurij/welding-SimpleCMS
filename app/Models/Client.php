<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'phone', 'email'];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // ///////////////////////////////////////
    // не для Postgresql
    public function latestOrder()
    {
        return $this->hasOne(Order::class)->latestOfMany();
    }

    public function oldestOrder()
    {
        return $this->hasOne(Order::class)->oldestOfMany();
    }

    /**
     * Получить самый дорогой заказ пользователя.
     */
    public function largestOrder()
    {
        return $this->hasOne(Order::class)->ofMany('price', 'max');
    }

    // end не для Postgresql
    // ////////////////////////////////////////
    public function callbacks(): HasMany
    {
        return $this->hasMany(Callback::class);
    }
}
