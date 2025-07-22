<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginUser(Request $request)
    {
        return $this->loginWithModel($request, User::class, 'user');
    }

    public function loginCustomer(Request $request)
    {
        return $this->loginWithModel($request, Customer::class, 'customer');
    }


    private function loginWithModel(Request $request, $modelClass, string $tokenPrefix)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = $modelClass::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $accessToken = $user->createToken("{$tokenPrefix}-token")->plainTextToken;


        return response()->json([
            'status' => 200,
            'message' => 'Authorized',
            'token' => $accessToken,
            'data' => [
                'user' => $user,
            ]
        ]);
    }


    public function checkTokenValidity(Request $request)
    {
        return response()->json([
            'authenticated' => true,
            'user' => $request->user()
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso.'
        ]);
    }
}
