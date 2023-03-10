<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestPersonaliseProduct extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function customer() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function appointment() {
        return $this->hasOne(RequestAppointment::class, 'request_id', 'id');
    }

    public function addresses() {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }
    
}
