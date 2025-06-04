<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil masuk',
            'data' => [
                'token' => $user->createToken('auth-token')->plainTextToken,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->roles->pluck('name')->first(),
                ]
            ]
        ], 200);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil keluar'
        ], 200);
    }

    public function me(Request $request){
        
        try {
            $user = $request->user();
        
            return response() -> json([
                'status' => 'success',
                'message' => 'Berhasil mendapatkan data pengguna.',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->roles->pluck('name')->first(),
                ]
                
                ], 200);
        } catch (\Exception $e){
            return response() -> json([
                'status' => 'failed',
                'message' => 'Gagal mendapatkan data pengguna.',
            ], 500);
        }
        
    }
}
