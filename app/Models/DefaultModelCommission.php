<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultModelCommission extends Model
{
    use HasFactory;
    protected $fillable = ['model_id', 'model_commission'];
}
