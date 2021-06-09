<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class InstagramProfile
 *
 * @package App\Models
 */
class InstagramProfile extends Model
{
    /**
     * @var string
     */
    protected $table = 'instagram_profiles';

    /**
     * @var array
     */
    protected $fillable = [
        'username',
        'user_id',
        'account_type',
        'access_token',
        'token_expires_in',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'username' => 'string',
        'user_id' => 'string',
        'account_type' => 'string',
        'access_token' => 'string',
        'token_expires_in' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(InstagramImage::class, 'profile_id', 'id');
    }
}
