<?php

namespace App\Http\Controllers;

use App\Events\PasswordResetRequested;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            event(new PasswordResetRequested($user, $status));
            return response()->json(['message' => 'Reset link sent to your email.']);
        }

        return response()->json(['error' => 'Unable to send reset link.'], 500);
    }
}
