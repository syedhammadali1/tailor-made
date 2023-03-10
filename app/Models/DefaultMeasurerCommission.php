<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultMeasurerCommission extends Model
{
    use HasFactory;
    protected $fillable = ['measurer_id', 'default_commission'];

    
}
