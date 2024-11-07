<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $ipAddress = $request->ip();
        $attemptsKey = 'login_attempts_' . $ipAddress;
        $blockedKey = 'blocked_' . $ipAddress;
    
        if (Cache::has($blockedKey)) {
            return response()->json(['message' => 'Too many login attempts. Try again later'], 429);
        }
    
        $user = User::where('email', $request->email)->first();
    
        if (! $user || ! Hash::check($request->password, $user->password)) {

            $attempts = Cache::get($attemptsKey, 0) + 1;
            Cache::put($attemptsKey, $attempts, now()->addMinutes(60));
    
            if ($attempts >= 5) {
                Cache::put($blockedKey, true, now()->addMinutes(1)); // 1 minute for testing otherwise 15 minutes
                Cache::forget($attemptsKey); 
            }
    
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }
    
        Cache::forget($attemptsKey);
    
        $user->tokens()->delete();
        $token = $user->createToken('API Token', ['read'])->plainTextToken;
    
        return response()->json(['access_token' => $token], 200);
    }

    public function register(Request $request) {

        $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        $device = substr($request->userAgent() ?? '', 0, 255);

        return response()->json([
            'access_token' => $user->createToken($device, ['read'])->plainTextToken,
        ], Response::HTTP_CREATED);
    }
}
