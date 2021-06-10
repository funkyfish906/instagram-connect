<?php

declare(strict_types=1);

namespace App\Console\Commands\InstagramProfile;

use App\Facades\InstagramServiceFacade;
use App\Models\InstagramProfile;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Class RefreshTokens
 *
 * @package App\Console\Commands\InstagramProfile
 */
class RefreshTokens extends Command
{
    /**
     * @var string
     */
    protected $signature = 'instagram-profiles:refresh-tokens';

    /**
     * @return void
     */
    public function handle(): void
    {
        $expiresInDate = Carbon::now()->addDays(2)->format('Y-m-d H:i:s');

        $profiles = InstagramProfile::query()
            ->whereDate('token_expires_in', '<=', $expiresInDate)
            ->get();

        try {
            $profiles->each(static function (InstagramProfile $profile) {
                $newToken = InstagramServiceFacade::refreshLongLiveToken($profile->getAttribute('access_token'));
                $profile->update([
                    'access_token' => $newToken['access_token'],
                    'token_expires_in' => Carbon::now()->addSeconds($newToken['expires_in'])->format('Y-m-d H:i:s'),
                ]);
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage());
        }
    }
}
