<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

use function implode, json_decode;

use const null, true;

/**
 * Class InstagramService
 *
 * @package App\Services
 */
class InstagramService
{
    protected const API_BASE_URL = 'https://api.instagram.com';
    protected const GRAPH_BASE_URL = 'https://graph.instagram.com';

    /**
     * @var string
     */
    protected string $clientId;

    /**
     * @var string
     */
    protected string $clientSecret;

    /**
     * @var string
     */
    protected string $redirectUrl;

    /**
     * InstagramService constructor.
     */
    public function __construct()
    {
        $this->clientId= Config::get('services.instagram.client_id');
        $this->clientSecret = Config::get('services.instagram.client_secret');
        $this->redirectUrl = Config::get('services.instagram.redirect_url');
    }

    /**
     * @return string
     */
    public function generateAuthorizationLink(): string
    {
        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUrl,
            'scope' => 'user_profile,user_media',
            'response_type' => 'code',
        ];

        return self::API_BASE_URL."/oauth/authorize?{$this->buildQuery($params)}";
    }

    /**
     * @param string $code
     *
     * @return array|null
     */
    public function getAccessToken(string $code): ?array
    {
        $params = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirectUrl,
        ];

        $response = Http::asForm()->post(self::API_BASE_URL.'/oauth/access_token', $params);

        return $this->parseResponse($response);
    }

    /**
     * @param string $accessToken
     *
     * @return array|null
     */
    public function getLongLiveToken(string $accessToken): ?array
    {
        $params = [
            'grant_type' => 'ig_exchange_token',
            'client_secret' => $this->clientSecret,
            'access_token' => $accessToken,
        ];

        $response = Http::get(self::GRAPH_BASE_URL."/access_token?{$this->buildQuery($params)}");

        return $this->parseResponse($response);
    }

    /**
     * @param string $accessToken
     *
     * @return array|null
     */
    public function refreshLongLiveToken(string $accessToken): ?array
    {
        $params = [
            'grant_type' => 'ig_refresh_token',
            'access_token' => $accessToken,
        ];

        $response = Http::get(self::GRAPH_BASE_URL."/refresh_access_token?{$this->buildQuery($params)}");

        return $this->parseResponse($response);
    }

    /**
     * @param string $accessToken
     * @param string $userId
     *
     * @return array|null
     */
    public function getUserProfile(string $accessToken, string $userId): ?array
    {
        $params = [
            'fields' => 'id,media_count,username,account_type,media',
            'access_token' => $accessToken,
        ];

        $response = Http::get(self::GRAPH_BASE_URL."/{$userId}?{$this->buildQuery($params)}");

        return $this->parseResponse($response);
    }

    /**
     * @param string $accessToken
     * @param string $mediaId
     *
     * @return array|null
     */
    public function getMediaData(string $accessToken, string $mediaId): ?array
    {
        $params = [
            'fields' => 'id,caption,media_type,media_url,permalink,thumbnail_url,timestamp,children',
            'access_token' => $accessToken,
        ];

        $response = Http::get(self::GRAPH_BASE_URL."/{$mediaId}?{$this->buildQuery($params)}");

        return $this->parseResponse($response);
    }

    /**
     * @param \Illuminate\Http\Client\Response $response
     *
     * @return null|array
     */
    protected function parseResponse(Response $response): ?array
    {
        if ($response->status() !== 200) {
            return null;
        }

        return json_decode($response->body(), true);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    protected function buildQuery(array $params): string
    {
        $query = [];

        foreach ($params as $param => $value) {
            $query[] = "{$param}={$value}";
        }

        return implode('&', $query);
    }
}
