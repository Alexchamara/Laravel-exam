<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    //login method
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            //create token with user email as name
            $token = $user->createToken($user->email);

            if ($request->hasSession()) {
                $request->session()->put('auth_token', $token->plainTextToken);
                $request->session()->regenerate();
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Successfully logged in',
                    'user' => $user,
                    'token' => $token->plainTextToken,
                    'token_type' => 'Bearer'
                ], 200);
            }

            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    //register method
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        // Create token for API
        $token = $user->createToken($request->email);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'User registered successfully',
                'token' => $token->plainTextToken,
                'user' => $user
            ], 200);
        }

        return redirect()->route('dashboard');
    }

    //logout method
    public function logout(Request $request)
    {
        //delete all tokens for current user
        Auth::user()->tokens()->delete();

        if ($request->hasSession()) {
            $request->session()->forget('auth_token');
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // Logout user
        Auth::guard('web')->logout();

        $response = [
            'status' => true,
            'message' => 'Logged out successfully'
        ];

        if ($request->expectsJson()) {
            return new JsonResponse($response, 200);
        }

        return redirect('/')->with('status', 'Logged out successfully');
    }
}
