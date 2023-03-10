<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;
    public $fillable = [
        'model_type',
        'model_id',
        'attachment_type',
        'attachment_path'
    ];
}
