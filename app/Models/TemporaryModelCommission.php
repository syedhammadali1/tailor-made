<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryModelCommission extends Model
{
    use HasFactory;
    protected $fillable = ['seller_id','model_id','commission'];

}
