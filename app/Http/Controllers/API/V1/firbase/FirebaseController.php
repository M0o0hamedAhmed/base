<?php

namespace App\Http\Controllers\API\V1\firbase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

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
        return response()->json($response['access_token']);
    }

    public function store(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer YOUR_REDDIT_ACCESS_TOKEN',
        ])->post('https://oauth.reddit.com/r/FlutterDev/new', [
        ]);
        return response()->json("x");
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
