<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    protected $fillable = [
        'alias',
        'title',
        'description',
        'keywords',
        'robots',
        'content',
        'single_page',
        'img',
        'publish',
        'service_page',
    ];

    public function getTitleAttribute($value)
    {
        if (function_exists('my_mb_ucfirst')) {
            return my_mb_ucfirst($value);
        } else {
            return $value;
        }
    }

    public function categories()
    {
        return $this->hasMany(ServiceCategory::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
