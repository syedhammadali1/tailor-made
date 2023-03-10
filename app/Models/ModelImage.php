<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelImage extends Model
{
    use HasFactory;

    protected $fillable = ['model_id', 'uploaded_image_id'];
}
