<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;
    // public $timestamps = false;
    protected $fillable = [
        'client_id',
        'service_id',
        'master_id',
        'status',
        'start_dt',
        'end_dt',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function master()
    {
        return $this->belongsTo(Master::class)->withDefault([
            'master_photo' => '',
            'master_name' => 'Noname',
            'sec_name' => 'NoSecondName',
            'master_fam' => 'NoLastName',
            'master_phone_number' => 'No phone',
            'spec' => 'All specialities in the world',
            'data_priema' => date('Y-m-d H:i:s', time()),
            'data_uvoln' => '',
        ]);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Диапазон запроса, включающий записи на две недели с сегодняшнего дня.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLengthcalendar($query)
    {
        // $length = DB::table('orgworktimesets')->select('length')->first();
        return $query->where('start_dt', '>', Carbon::today()->toDateTimeString());
    }
}
