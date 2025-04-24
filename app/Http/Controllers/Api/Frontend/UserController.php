<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function saveToken(Request $request)
    {
        $request->validate([
            'device_token' => 'required|string',
        ]);

        auth()->user()->deviceTokens()->updateOrCreate(
            ['device_token' => $request->device_token]
        );

        return response()->json([
            'success' => true,
            'message' => 'Token saved'
        ]);

    }

}
