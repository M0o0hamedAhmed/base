<?php

namespace App\Http\Controllers\API\V1\firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class RedditAuthController extends Controller
{

    public function login(Request $request)
    {
        $response = Http::withBasicAuth('9ldpvBz9yKDZ9TIm9mcVPA', 'PP5aGVQ8mIysFOamsh2zmvpXAV7S0Q')
            ->asForm()
            ->post('https://www.reddit.com/api/v1/access_token',
                [
                    'grant_type' => 'password',
                    'username' => 'Quirky_Till_2969',
                    'password' => 'M0o0hamedAhmed',
                ]);
        $token =$response['access_token'];

        $result = Http::withToken($token)
            ->post('https://oauth.reddit.com/r/FlutterDev/new');
//        Cache::put('access_token',$response['access_token'],$response['expires_in']);
        return [$result->json(),$response->json(),$token];
    }
}
