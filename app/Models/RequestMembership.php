<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestMembership extends Model
{
    use HasFactory;


    /**
     * Get the user that owns the RequestMembership
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }

    public function profile_type()
    {
        return $this->belongsTo(ProfileType::class, 'profile_type_id', 'id');
    }
}
