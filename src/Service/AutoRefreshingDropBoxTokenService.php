<?php

namespace Thotam\ThotamHr\Service;

use Exception;
use Spatie\Dropbox\TokenProvider;
use Illuminate\Support\Facades\Http;


class AutoRefreshingDropBoxTokenService implements TokenProvider
{
    private string $key;

    private string $secret;

    private string $refreshToken;

    public function __construct()
    {
        $this->key = env('DropboxAppkey');
        $this->secret = env('DropboxAppsecret');
        $this->refreshToken = env('DropboxRefreshToken');
    }

    public function getToken(): string
    {
        return $this->refreshToken();
        //        return Cache::remember('access_token', 14000, function () {
        //            return $this->refreshToken();
        //        });
    }

    public function refreshToken(): string|bool
    {
        try {
            $res = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://api.dropboxapi.com/oauth2/token?client_id=' . $this->key . '&client_secret=' . $this->secret . '&refresh_token=' . $this->refreshToken . '&grant_type=refresh_token');

            if ($res->getStatusCode() == 200) {
                $response = json_decode($res->getBody(), true);
                return trim(json_encode($response['access_token']), '"');
            } else {
                return false;
            }
        } catch (Exception $e) {
            //            ray("[{$e->getCode()}] {$e->getMessage()}");

            return false;
        }
    }
}
