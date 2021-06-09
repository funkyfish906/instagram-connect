<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class InstagramImage
 *
 * @package App\Models
 */
class InstagramImage extends Model
{
    public const MEDIA_TYPE_IMAGE = 'IMAGE';

    /**
     * @var string
     */
    protected $table = 'instagram_images';

    /**
     * @var array
     */
    protected $fillable = [
        'media_id',
        'image_url',
        'filename',
        'caption',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'profile_id' => 'int',
        'media_id' => 'string',
        'image_url' => 'string',
        'filename' => 'string',
        'caption' => 'string',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(InstagramProfile::class, 'profile_id', 'id', 'profile');
    }
}
