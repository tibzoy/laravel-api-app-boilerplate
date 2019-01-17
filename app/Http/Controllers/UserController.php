<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\User;

class UserController extends Controller
{
    public function activate(Request $request, User $user)
    {
        if($user)
        {
            $user->is_active = true;

            $user->save();

            return new UserResource($user);
        }

        return response()->json([
            'message' => 'User not found.'
        ], 404);
    }

    public function deactivate(Request $request, User $user)
    {
        if($user)
        {
            $user->is_active = false;

            $user->save();

            return new UserResource($user);
        }

        return response()->json([
            'message' => 'User not found.'
        ], 404);
    }

    //
    public function update(Request $request, User $user)
    {
        if($user)
        {
            $request->validate([
                'name' => 'required'
            ]);

            $user->name = $request->name;

            $user->save();

            return new UserResource($user);
        }

        return response()->json([
            'message' => 'User not found.'
        ], 404);
    }

    public function getUsers(Request $request)
    {
        return new UserCollection(User::paginate());
    }

    public function getUser(Request $request, User $user)
    {
        return new UserResource($user);
    }
}
