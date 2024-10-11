<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'master_photo',
        'master_name',
        'sec_name',
        'master_fam',
        'master_phone_number',
        'spec',
        'data_priema',
        'data_uvoln',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function getDataPriemaAttribute($value)
    {
        if (!empty($value)) {
            return date('d.m.Y', strtotime($value));
        } else {
            return '';
        }
    }

    public function getDataUvolnAttribute($value)
    {
        if (!empty($value)) {
            return date('d.m.Y', strtotime($value));
        } else {
            return '';
        }
    }
}
