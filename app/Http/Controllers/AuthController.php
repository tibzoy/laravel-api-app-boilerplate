<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Notifications\RegistrationSetPassword;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\PasswordReset;

class AuthController extends Controller
{

    public function resetPassword(Request $request, $token)
    {
        $rules = [
            'password' => User::PASSWORD_COMPLEXITY['validation']
        ];

        $errorMsg = [
            'regex' =>  User::PASSWORD_COMPLEXITY['errorMessage']
        ];

        $this->validate($request, $rules, $errorMsg);

        $passwordReset = PasswordReset::where('token', $token)->first();

        if($passwordReset)
        {
            if (Carbon::parse($passwordReset->updated_at)->addMinutes(60)->isPast())
            {
                $passwordReset->delete();
                return response()->json([
                    'message' => 'This password reset token is invalid.'
                ], 404);
            }

            $user = User::where('email', $passwordReset->email)->first();

            $user->password = bcrypt($request->password);

            $user->save();

            $user->notify(new PasswordResetSuccess());

            return response()->json([
                'message' => 'Password reset succeeded.'
            ]);
        }

        return response()->json([
            'message' => "Token not found: $token"
        ],404);
    }

    public function recoverPasswordStep2($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();

        if($passwordReset)
        {
            return response()->json([
                'message' => 'You can now reset your password. ' . User::PASSWORD_COMPLEXITY['errorMessage']
            ]);
        }

        return response()->json([
            'message' => "Token not found: $token"
        ],404);
    }

    public function recoverPasswordStep1(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email',$request->email)->first();

        if($user)
        {
            $token = str_random(60);

            PasswordReset::updateOrCreate(
                ['email' => $user->email],
                [
                    'email' => $user->email,
                    'token' => $token
                 ]
            );

            $user->notify(new PasswordResetRequest($token));

            return response()->json([
                'message' => "A password reset link has been sent to $request->email"
            ],200);
        }

        return response()->json([
            'message' => 'Email is not registered.'
        ],401);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function getUser(Request $request)
    {
        return response()->json($request->user());
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'is_active' => true,
            'deleted_at' => null,
        ];

        if(Auth::attempt($credentials))
        {
            $user = $request->user();

            $tokenResult = $user->createToken('Personal Access Token');

            $token = $tokenResult->token;

            $token->expires_at = Carbon::now()->addMinutes(1);

            $token->save();

            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                'message' => 'Login successful'
            ], 200);
        }

        return response()->json([
            'message' => 'Login failed.'
        ], 401);
    }

    public function activate(Request $request, $token)
    {
        $rules = [
            'password' => User::PASSWORD_COMPLEXITY['validation']
        ];

        $customErrorMsg = [
            'regex' => User::PASSWORD_COMPLEXITY['errorMessage']
        ];


        $this->validate($request, $rules, $customErrorMsg);

        $user = User::where('activation_token',$token)->first();

        if($user)
        {
            $user->password = bcrypt($request->password);

            $user->is_active = true;

            $user->activation_token = '';

            $user->save();

            return response()->json([
                'message' => 'Account is active. Please login now.'
            ]);
        }

        return response()->json([
            'message' => 'Token not found.'
        ],404);
    }

    public function preActivate($token)
    {
        $user = User::where('activation_token',$token)->first();

        if($user)
        {
            return response()->json([
                'user_id' => $user->id,
                'message' => 'Set your own password now.'
            ]);
        }

        return response()->json([
            'message' => 'Invalid token',
            'token' => $token
        ], 400);
    }

    //
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users'
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt(str_random(16)),
            'activation_token' => str_random(60)
        ]);

        $user->save();

        $user->notify(new RegistrationSetPassword($user));

        return response()->json([
            'message' => 'Created user successfully!'
        ], 200);
    }
}
