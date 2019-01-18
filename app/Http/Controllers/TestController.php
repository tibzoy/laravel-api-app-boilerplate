<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class TestController extends Controller
{
    public function isAdmin(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    public function isHead(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    public function isAny(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }
}
