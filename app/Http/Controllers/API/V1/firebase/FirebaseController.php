<?php

namespace App\Http\Controllers\API\V1\firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Kreait\Firebase\Factory;

class FirebaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $firebase = (new Factory)
            ->withServiceAccount(base_path('/firebase_credentials.json'))
            ->withDatabaseUri('https://reddit-d1b99-default-rtdb.firebaseio.com');

        $database = $firebase->createDatabase();
        $blog = $database->getReference('reddit')->set([[
            'name' => 'My Application',
            'emails' => [
                'support' => 'support@domain.example',
                'sales' => 'sales@domain.example',
            ],
            'website' => 'https://app.domain.example',
        ], [
            'name' => 'My Application2',
            'emails' => [
                'support' => 'support@domain.example',
                'sales' => 'sales@domain.example',
            ],
            'website' => 'https://app.domain.example',
        ]]);
        return response()->json($blog->getValue());


    }
}
