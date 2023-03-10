<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryMeasurerCommission extends Model
{
    use HasFactory;

    protected $fillable = ['consumer_id','measurer_id','commission'];
}
