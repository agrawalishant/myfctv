<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use DB;

class ForgotPasswordController extends Controller
{
    public function forgotPassword()
    {
        return view('dashboard.forgot-password');
    }

    public function checkPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;

        $admin = Admin::where('email', $email)->first();

        if (!$admin) {
            return response()->json(['status' => '0', 'error' => ['message' => 'Email does not exist in the database.']]);
        }

        // Check if a record with the same email already exists in the password_reset_tokens table
        $existingResetToken = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if ($existingResetToken) {
            $createdAt = strtotime($existingResetToken->created_at);
            $currentTime = time();
            $timeDiff = $currentTime - $createdAt;
            $waitingPeriod = 120; // 2 minutes

            if ($timeDiff < $waitingPeriod) {
                $remainingTime = $waitingPeriod - $timeDiff;
                return response()->json(['status' => '0', 'error' => ['message' => 'Please wait for ' . $remainingTime . ' seconds before making another reset request.']]);
            }

            // Update the existing record with a new token and timestamp
            $token = Str::random(32);
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->update([
                    'token' => $token,
                    'created_at' => now()
                ]);
        } else {
            // Insert a new record
            $token = Str::random(32);
            DB::table('password_reset_tokens')->insert([
                'email' => $admin->email,
                'token' => $token,
                'created_at' => now()
            ]);
        }

        // Send the reset link to the user's email
        $resetLink = url('reset-password/' . $token);
        Mail::to($admin->email)->send(new ResetPasswordMail($admin->email, $resetLink));

        return response()->json(['status' => 1]);
    }

    public function resetForm(Request $request, $token)
    {
        // Retrieve the email from the token
        $resetToken = DB::table('password_reset_tokens')->where('token', $token)->first();

        if (!$resetToken) {
            return redirect()->back()->with('error', 'Invalid password reset token.');
        }

        $email = $resetToken->email;

        // Store the email in the session
        $request->session()->put('email', $email);

        return view('dashboard.reset-password')->with(compact('email', 'token'));
    }

    public function reset(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
        ]);

        // Retrieve the email and token from the request
        $email = $request->email;
        $token = $request->token;

        // Find the user based on the email
        $admin = Admin::where('email', $email)->first();

        // Check if the user exists
        if (!$admin) {
            return response()->json(['status' => 'error', 'message' => 'Invalid email'], 422);
        }

        // Find the password reset token
        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->first();

        // Check if the token exists and is valid
        if (!$resetToken) {
            return response()->json(['status' => 'error', 'message' => 'Invalid token'], 422);
        }

        // Update the user's password
        $admin->password = Hash::make($request->password);
        $admin->save();

        // Delete the password reset token
        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->delete();

        // Return a success response
        return response()->json(['status' => 'success', 'message' => 'Password reset successfully']);
    }

    public function confirm(){
        return view('dashboard.confirm-mail');
    }
}
