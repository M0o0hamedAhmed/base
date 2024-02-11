<?php

namespace App\Http\Controllers\AP1\V1\Main;

use App\Http\Controllers\AP1\V1\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(UserStoreRequest $userStoreRequest)
    {
        //
    }
    public function store(UserStoreRequest $userStoreRequest)
    {
        $seed = $userStoreRequest->validated();
        $user_id = User::query()->insertGetId($seed);
        return new UserResource(User::query()->findOrFail($user_id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($lang,$id)
    {
        $user = User::query()->find($id);
        if (!$user){
            return response()->json(['message' => trans('main.User not found')], 404);
        }
        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $userUpdateRequest, $id)
    {
        $update = $userUpdateRequest->validated();
        User::query()->update($update);
        return response()->json(['message' => trans('main.Update Success')], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
