<?php

namespace App;

use App\Models\ClubPointType;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ClubPoint extends Model
{
    public function user(){
    	return $this->belongsTo(user::class);
    }

    public function order(){
    	return $this->belongsTo(Order::class);
    }
    public function pointType(){
        return $this->belongsTo(ClubPointType::class, 'club_point_type_id', 'id');
    }
}
