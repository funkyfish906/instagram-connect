<?php

namespace App\Jobs;

use App\Facades\InstagramServiceFacade;
use App\Models\InstagramImage;
use App\Models\InstagramProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

/**
 * Class ParseInstagramImages
 *
 * @package App\Jobs
 */
class ParseInstagramImages implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var \App\Models\InstagramProfile
     */
    protected InstagramProfile $profile;

    /**
     * @param \App\Models\InstagramProfile $client
     */
    public function __construct(InstagramProfile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $data = InstagramServiceFacade::getUserProfile($this->profile['access_token'], $this->profile['user_id']);
        $medias = $data['media']['data'] ?? [];

        $images = [];

        $count = Config::get('services.instagram.image_loading_count');

        foreach ($medias as $media) {
            $mediaData = InstagramServiceFacade::getMediaData($this->profile['access_token'], $media['id']);

            if ($mediaData['media_type'] === InstagramImage::MEDIA_TYPE_IMAGE) {
                $images[] = [
                    'id' => $media['id'],
                    'image_url' => $mediaData['media_url'],
                    'caption' => $mediaData['caption'] ?? null,
                ];
            }

            if (count($images) >= $count) {
                break;
            }
        }

        foreach ($images as $image) {
            $this->profile->images()->updateOrCreate(
                [
                    'media_id' => $image['id'],
                ],
                [
                    'image_url' => $image['image_url'],
                    'caption' => $image['caption'],
                ],
            );
        }
    }
}
