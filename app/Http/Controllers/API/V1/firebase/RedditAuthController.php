<?php

namespace App\Http\Controllers\API\V1\firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Kreait\Firebase\Factory;

class RedditAuthController extends Controller
{
    private $firebase;

    public function __construct()
    {
        $this->firebase = (new Factory)
            ->withServiceAccount(base_path('/firebase_credentials.json'))
            ->withDatabaseUri('https://reddit-d1b99-default-rtdb.firebaseio.com');
    }

    public function index()
    {
        $this->storeInFirebase();
        $data['new'] = $this->getDataFromFirebase('new');
        $data['hot'] = $this->getDataFromFirebase('hot');
        $data['rising'] = $this->getDataFromFirebase('rising');

        return response()->json($data);
    }

    private function getAccessToken()
    {
        return Cache::remember('access_token', 86400, function () {
            $response = Http::withBasicAuth(config('services.reddit.client_id'), config('services.reddit.secret'))
                ->asForm()
                ->post('https://www.reddit.com/api/v1/access_token', [
                    'grant_type' => 'password',
                    'username' => config('services.reddit.username'),
                    'password' => config('services.reddit.password'),
                ]);

            return $response['access_token'];
        });
    }

    private function getDataFromReddit($type, $limit = 100)
    {
        $token = $this->getAccessToken();

        $result = Http::withHeaders([
            "Authorization" => "Bearer $token",
            "User-Agent" => "ChangeMeClient/0.1 by M0o0hamedAhmed"
        ])->get("https://oauth.reddit.com/r/FlutterDev/$type", [
            'limit' => $limit,
        ]);

        return $result->json();
    }

    private function storeInFirebase()
    {
        $types = ['new', 'hot', 'rising'];
        $limit = 100;

        foreach ($types as $type) {
            $result = $this->getDataFromReddit($type, $limit);
            $data = $result['data']['children'];
            $this->storeDataInFirebase($data, $type);
        }
    }

    private function storeDataInFirebase(array $data, string $reference): void
    {
        $database = $this->firebase->createDatabase();
        $database->getReference($reference)->set($data);
    }

    private function getDataFromFirebase(string $reference)
    {
        $database = $this->firebase->createDatabase();
        return $database->getReference($reference)->getValue();
    }
}
