<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'user_profiles';
    protected $guarded = ['id'];

    /**
     * Get all of the comments for the UserProfile
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function profile()
    // {
    //     return $this->hasMany(Profile::class, 'id');
    // }


    /**
     * Get the user that owns the UserProfile
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(ProfileType::class, 'profile_id');
    }

    public function profileName()
    {
        return $this->hasMany(Profile::class, 'profile_type_id', 'profile_id');
    }

}
