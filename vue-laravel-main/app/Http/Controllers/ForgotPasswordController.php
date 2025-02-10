<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\ForgotPassword;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $token = 'your-generated-token'; // Generate a token for password reset

        Mail::to($request->email)->send(new ForgotPassword($token));

        return response()->json(['message' => 'Reset link sent to your email.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($user->password_reset_token !== $request->token) {
            return response()->json(['message' => 'Invalid token.'], 400);
        }

        $user->password = Hash::make($request->password);
        $user->password_reset_token = null;
        $user->save();

        return response()->json(['message' => 'Password has been reset!']);
    }
}
