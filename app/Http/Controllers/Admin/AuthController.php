<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        if (Auth::attempt($credentials)) {
            $accessToken = $request->user()->createToken('authToken')->plainTextToken;

            return response()->json([
                'status' => 200,
                'message' => 'Authorized',
                'token' => $accessToken,
                'data' => [
                    'user' => $request->user(),
                ]
            ], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }



    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json(['message' => 'Logged out'], 200);
    }


    public function checkTokenValidity(Request $request)
    {
        try {
            $user = Auth::user();

            if ($user) {
                return response()->json([
                    'valid' => true,
                    'message' => 'Token is still valid.'
                ]);
            } else {
                return response()->json([
                    'valid' => false,
                    'message' => 'Token is not valid or expired.'
                ], 401); // Unauthorized status code
            }
        } catch (\Exception $e) {
            // Captura exceções e retorna uma resposta adequada
            return response()->json([
                'valid' => false,
                'message' => 'An error occurred while checking token validity.',
                'error' => $e->getMessage()
            ], 500); // Internal Server Error status code
        }
    }
}
