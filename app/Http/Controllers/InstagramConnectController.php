<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Facades\InstagramServiceFacade;
use App\Jobs\ParseInstagramImages;
use App\Models\InstagramProfile;
use Carbon\Carbon;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Throwable;

/**
 * Class InstagramConnectController
 *
 * @package App\Http\Controllers
 */
class InstagramConnectController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): ViewContract
    {
        $link = InstagramServiceFacade::generateAuthorizationLink();

        return View::make('instagram-connect', ['link' => $link]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function connect(Request $request): RedirectResponse
    {
        try {
            $code = $request->get('code');

            $accessData = InstagramServiceFacade::getAccessToken($code);
            $profileData = InstagramServiceFacade::getUserProfile($accessData['access_token'], $accessData['user_id']);

            $longLiveToken = InstagramServiceFacade::getLongLiveToken($accessData['access_token']);

            $profile = InstagramProfile::query()->updateOrCreate(
                [
                    'user_id' => $accessData['user_id'],
                ],
                [
                    'access_token' => $longLiveToken['access_token'],
                    'token_expires_in' => Carbon::now()->addSeconds($longLiveToken['expires_in'])->format('Y-m-d H:i:s'),
                    'username' => $profileData['username'],
                    'account_type' => $profileData['account_type'],
                ]
            );

            ParseInstagramImages::dispatch($profile);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return Redirect::route('instagram-connect.index')
                ->with('connection_failed', 'Connection failed');
        }

        return Redirect::route('instagram-connect.index')
            ->with('connection_success', 'Connection success');
    }
}
