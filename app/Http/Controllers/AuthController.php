<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth')->only(['verify', 'resend', 'notice']);
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

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
            return redirect()->route('posts');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    //register method
    // public function register(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    //         'password' => ['required', 'string', 'min:8', 'confirmed'],
    //     ]);

    //     $user = User::create([
    //         'name' => $validated['name'],
    //         'email' => $validated['email'],
    //         'password' => Hash::make($validated['password']),
    //     ]);

    //     Auth::login($user);

    //     // Create token for API
    //     $token = $user->createToken($request->email);

    //     if ($request->expectsJson()) {
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'User registered successfully',
    //             'token' => $token->plainTextToken,
    //             'user' => $user
    //         ], 200);
    //     }

    //     return redirect()->route('posts');
    // }

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

        event(new Registered($user));

        Auth::login($user);

        // Create token for API
        $token = $user->createToken($request->email);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'User registered successfully. Please check your email for verification.',
                'token' => $token->plainTextToken,
                'user' => $user
            ], 201);
        }

        return redirect()->route('verification.notice');
    }

    // Email verification notice
    public function notice()
    {
        return view('auth.verify-email');
    }

    // Handle the email verification
    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));
    
        if (!hash_equals(
            (string) $request->route('hash'),
            sha1($user->getEmailForVerification())
        )) {
            throw new AuthorizationException('Invalid verification link');
        }
    
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('posts');
        }
    
        $user->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($user));
    
        return redirect()->route('posts')->with('status', 'Email verified successfully!');
    }

    // Resend verification email
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('posts');
        }
    
        $request->user()->sendEmailVerificationNotification();
    
        return back()->with('status', 'verification-link-sent');
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
