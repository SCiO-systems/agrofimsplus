<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Requests\UserAvatars\DeleteUserAvatarRequest;
use App\Http\Requests\UserAvatars\ShowUserAvatarRequest;
use App\Http\Requests\UserAvatars\UpdateUserAvatarRequest;
use App\Http\Resources\v1\UserAvatarResource;
use App\Models\User;
use App\Http\Controllers\Controller;
use Storage;

class UserAvatarController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowUserAvatarRequest $request, User $user)
    {
        return new UserAvatarResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserAvatarRequest $request, User $user)
    {
        $user->avatar_url = $request->file('avatar')->store('avatars', 'public');
        $user->save();
        return new UserAvatarResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteUserAvatarRequest $request, User $user)
    {
        Storage::disk('public')->delete($user->avatar_url);
        $user->avatar_url = null;
        $user->save();

        return new UserAvatarResource($user);
    }
}
