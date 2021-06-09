<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\InstagramService;
use Illuminate\Support\Facades\Facade;

/**
 * Class InstagramServiceFacade
 *
 * @package App\Facades
 *
 * @method static string generateAuthorizationLink()
 * @method static mixed getAccessToken(string $code)
 * @method static mixed getUserProfile(string $accessToken, string $userId)
 * @method static mixed refreshLongLiveToken(string $accessToken)
 * @method static mixed getMediaData(string $accessToken, string $mediaId)
 */
class InstagramServiceFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return InstagramService::class;
    }
}
