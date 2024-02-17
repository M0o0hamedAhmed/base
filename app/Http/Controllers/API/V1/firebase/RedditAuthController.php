<?php

namespace App\Http\Controllers\API\V1\firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Kreait\Firebase\Factory;

class RedditAuthController extends Controller
{
    public function index(){
        $this->store_in_firebase();
        $firebase = (new Factory)
            ->withServiceAccount(base_path('/firebase_credentials.json'))
            ->withDatabaseUri('https://reddit-d1b99-default-rtdb.firebaseio.com');

        $database = $firebase->createDatabase();
        $data['new'] =  $database->getReference('new')->getValue();
        $data['hot'] =  $database->getReference('hot')->getValue();
        $data['rising'] =  $database->getReference('rising')->getValue();
       return response()->json($data);
    }
    private function get_token()
    {
        if (!Cache::has('access_token')) {
            $response = Http::withBasicAuth('9ldpvBz9yKDZ9TIm9mcVPA', 'PP5aGVQ8mIysFOamsh2zmvpXAV7S0Q')
                ->asForm()
                ->post('https://www.reddit.com/api/v1/access_token',
                    [
                        'grant_type' => 'password',
                        'username' => 'Quirky_Till_2969',
                        'password' => 'M0o0hamedAhmed',
                    ]);
            $token = $response['access_token'];
            Cache::put('access_token', $response['access_token'], 15);
        } else {
            $token = Cache::get('access_token');
        }
        return $token;
    }

    private function store_in_firebase()
    {
        $types = ['new', 'hot', 'rising'];
        $limit = 100;

        foreach ($types as $type) {
            $result = $this->get_data($type, $limit);
            $data = $result['data']['children'];
            $this->store($data, $type);
        }

    }

    private function store(array $data, string $reference): void
    {
        $firebase = (new Factory)
            ->withServiceAccount(base_path('/firebase_credentials.json'))
            ->withDatabaseUri('https://reddit-d1b99-default-rtdb.firebaseio.com');

        $database = $firebase->createDatabase();
        $blog = $database->getReference($reference)->set($data);
    }

    private function get_data($type, int $limit)
    {
        $token = $this->get_token();
        $result = Http::withHeaders([
            "Authorization" => "bearer " . $token,
            "User-Agent" => "ChangeMeClient/0.1 by M0o0hamedAhmed"
        ])->get('https://oauth.reddit.com/r/FlutterDev/' . $type, [
            'limit' => $limit ?? 100
        ]);
        return $result;
    }
}
