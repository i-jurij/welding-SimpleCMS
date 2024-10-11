<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'page_id',
        'image',
        'name',
        'description',
    ];

    public function getNameAttribute($value)
    {
        if (function_exists('my_mb_ucfirst')) {
            return my_mb_ucfirst($value);
        } else {
            return $value;
        }
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
