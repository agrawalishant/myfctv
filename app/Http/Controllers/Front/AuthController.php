<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserPayment;
use Illuminate\Support\Facades\Mail;
use App\Services\MailService;
use Session;
use Validator;
use Auth;
use DB;
use Illuminate\Support\Str;
use Hash;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        Session::put('page', 'login');
        if ($request->isMethod('post')) {
            $data = $request->all();
            /*echo "<pre>"; print_r($data); die;*/
            $validator = validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (!$validator->passes()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toarray()]);
            } else {
                if (Auth::guard('user')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
                    return response()->json(['status' => 1]);
                } else {
                    return response()->json(['status' => 2, 'error' => ['message' => 'Invalid email or password']]);
                }
            }
        }
        return view('front.login');
    }

    public function register(Request $request)
    {
        Session::put('page', 'register');
        if ($request->isMethod('post')) {
            $validator = validator($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'mobile' => 'required|string|unique:users',
                'gender' => 'required|in:male,female,other',
                'date_of_birth' => 'required|date',
                'password' => 'required|min:6|same:confirm_password',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            }

            // Registration logic...

            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->mobile = $request->input('mobile');
            $user->gender = $request->input('gender');
            $user->date_of_birth = $request->input('date_of_birth');
            $user->password = bcrypt($request->input('password'));
            $user->status = 0; // Assuming 0 means inactive, update it as needed
            $user->save();

            // Send verification email
            $this->sendVerificationEmail($user);

            return response()->json(['status' => 1, 'message' => 'Registration successfully']);
        }
        return view('front.register');
    }

    public function sendVerificationEmail($user)
    {
        $code = base64_encode($user->email);

        $messageData = [
            'email' => $user->email,
            'name' => $user->name,
            'code' => $code,
        ];

        MailService::setSmtpConfig();

        Mail::send('emails.verification', $messageData, function ($message) use ($user) {
            $message->to($user->email)->subject('Verify your Account');
        });
    }


    public function logout()
    {
        Auth::guard('user')->logout();

        return redirect('/');
    }

    public function account()
    {
        Session::put('page', 'account');
        $user = auth('user')->user();
        $subscription = UserPayment::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('front.account', compact('user', 'subscription'));
    }

    public function updateProfile(Request $request)
    {
        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'mobile' => 'required|string',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $user = auth()->user();

        if ($user) {
            // Update user details
            $user->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'gender' => $request->input('gender'),
                'date_of_birth' => $request->input('date_of_birth'),
                // Add other fields as needed
            ]);
            return response()->json(['status' => 1, 'message' => 'Profile updated successfully']);
        }

        return redirect()->back()->with('error', 'User not found');
    }

    public function forgotPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = validator($request->all(), [
                'email' => 'required|email',
                'user_type' => 'required|in:admin,user',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            }

            $user = User::where('email', $request->input('email'))->first();

            // Check if there's an existing OTP record for the user's email
            // Check if there's an existing OTP record for the user's email
            $existingOtpData = DB::table('password_reset_tokens')
                ->where('email', $request->input('email'))
                ->where('user_type', $request->input('user_type'))
                ->first();

            // Generate a unique OTP
            $otp = Str::random(6); // Adjust the length of the OTP as needed

            if ($existingOtpData) {
                // If an OTP record exists, update it with the new OTP
                DB::table('password_reset_tokens')
                    ->where('email', $request->input('email'))
                    ->where('user_type', $request->input('user_type'))
                    ->update([
                        'otp' => $otp,
                        'created_at' => now(),
                    ]);
            } else {
                // If no existing OTP record, insert a new record
                DB::table('password_reset_tokens')->insert([
                    'email' => $user->email,
                    'otp' => $otp,
                    'user_type' => $request->input('user_type'),
                    'created_at' => now(),
                ]);
            }

            // Send an email with the OTP for password reset
            $this->sendPasswordResetEmail($user, $otp);

            return redirect('/change-password')->with('email', $user->email);
        }

        return view('front.forgot-password');
    }


    public function sendPasswordResetEmail($user, $otp)
    {
        // Assuming you have a custom Mailable class for the password reset email
        MailService::setSmtpConfig();

        Mail::send('emails.password_reset', ['user' => $user, 'otp' => $otp], function ($message) use ($user) {
            $message->to($user->email)->subject('Reset Your Password');
        });
    }

    public function changePassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'otp' => 'required|string',
                'password' => 'required|min:4|confirmed',
                'user_type' => 'required|in:admin,user',
            ]);

            $otpData = DB::table('password_reset_tokens')
                ->where('email', $request->input('email'))
                ->where('otp', $request->input('otp'))
                ->where('user_type', $request->input('user_type'))
                ->first();

            if (!$otpData) {
                \Log::error('Invalid or expired OTP for email: ' . $request->input('email'));
                return response()->json(['error' => 'Invalid or expired OTP.'], 422);
            }

            // Update the user's password
            $user = User::where('email', $request->input('email'))->first();
            $user->password = Hash::make($request->input('password'));
            $user->save();

            // Delete the used OTP
            DB::table('password_reset_tokens')
                ->where('email', $request->input('email'))
                ->where('otp', $request->input('otp'))
                ->where('user_type', $request->input('user_type'))
                ->delete();

            // Display a SweetAlert and redirect to the login page
            Alert::success('Password Changed', 'Your password has been changed successfully.');
            return redirect('/login');
        }

        return view('front.change-password');
    }

}
