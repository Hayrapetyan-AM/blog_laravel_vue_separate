<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): JsonResponse
    {
        // Custom validation rules
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6', // Ensure password is at least 6 characters
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors.',
                'errors' => $validator->errors(),
            ], 422); // Unprocessable Entity
        }

        // Attempt to authenticate the user
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            // Regenerate session token for security
            //$request->session()->regenerate();
            if(!Auth::user()->email_verified_at){
                // Return error response if authentication fails
                return response()->json([
                    'message' => 'You have to verify your email address.',
                ], 401);
            }else{
                // Return a success response with user details and token
                return response()->json([
                    'message' => 'Login successful',
                    'user' => Auth::user(),
                    'token' => Auth::user()->createToken('auth_token')->plainTextToken,
                ], 200);
            }

        }

        // Return error response if authentication fails
        return response()->json([
            'message' => 'Invalid credentials.',
        ], 401);
    }

    public function create(Request $request)
    {

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        // Custom validation for session destruction
        $validator = Validator::make($request->all(), [
            'token' => 'required|string', // Ensure a valid token is provided
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Revoke the user's token and log them out
        Auth::user()->tokens->each(function ($token) {
            $token->delete();
        });

        Auth::guard('web')->logout();

        // Invalidate the session and regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out successfully.',
        ], 200);
    }
}
