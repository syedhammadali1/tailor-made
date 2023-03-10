<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RequestAppointment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function measurer() {
        return $this->belongsTo(User::class, 'measurer_id');
    }
    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function request() {
        return $this->belongsTo(RequestPersonaliseProduct::class, 'request_id');
    }
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function measurement() {
        return $this->hasOne(RequestMeasurement::class, 'appointment_id');
    }

    public static function boot()
    {
        parent::boot();

        self::created(function ($request_appointment) {
            $request_appointment->update([
                'uuid' => Str::uuid()
            ]);
        });

    }

}
