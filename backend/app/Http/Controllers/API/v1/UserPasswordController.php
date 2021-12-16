<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPassword\UpdateUserPasswordRequest;
use App\Models\User;
use Hash;

class UserPasswordController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserPasswordRequest $request, User $user)
    {
        $currentPassword = $request->password;
        $newPassword = $request->new;

        if (!Hash::check($currentPassword, $user->password)) {
            return response()->json(['errors' => [
                'error' => 'The old password was incorrect.'
            ]], 422);
        }

        $user->password = bcrypt($newPassword);
        $user->save();

        return response()->json([], 204);
    }
}
