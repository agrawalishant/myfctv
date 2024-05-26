<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use App\Services\MailService;
use DB;
use Illuminate\Support\Str;
use Validator;
use Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if the user is activated
            if ($user->status == 0) {
                // Optionally, you can still provide access to certain features for unverified users.
                // Consider your application's requirements and security considerations.
                $message = "Your account is not fully activated yet! Please consider verifying your email for full access.";
                // return $this->getErrorResponse(['error' => $message], $request);
                return $this->isApiRequest($request)
                    ? response()->json(['message' => $message, 'user' => $user])
                    : redirect('/')->with('info', $message);
            }

            $accessToken = $user->createToken('MyApp')->accessToken;
            $responseData = ['message' => 'Login successful', 'user' => $user, 'access_token' => $accessToken,];

            if ($this->isApiRequest($request)) {
                return response()->json($responseData);
            } else {
                return redirect('/');
            }
        } else {
            $message = "Invalid Username or Password";
            return $this->getErrorResponse(['error' => $message], $request);
        }
    }

    public function logout(Request $request)
    {
        // Get the authenticated user's access token
        $request->user()->token()->revoke();

        $responseData = ['message' => 'Logout successful'];

        return $this->isApiRequest($request)
            ? new JsonResponse($responseData, 200)
            : redirect('/')->with('success', $responseData['message']);
    }

    public function register(Request $request)
    {
        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'mobile' => 'required|string|unique:users',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return $this->isApiRequest($request)
                ? new JsonResponse(['errors' => $errors], 422)
                : redirect()->back()->withErrors($errors)->withInput();
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

        $responseData = [
            'message' => 'Registration successful. Please check your email for account activation.',
            'user' => $user,
        ];

        return $this->isApiRequest($request)
            ? new JsonResponse($responseData, 201)
            : redirect('/')->with('success', $responseData['message']);
    }

    public function profile(Request $request)
    {
        $user = auth()->user();

        if ($request->wantsJson() || $request->is('api/*')) {
            // API request
            return response()->json(['data' => ['user' => $user]], 200);
        } else {
            // Web request
            return view('profile', ['user' => $user]);
        }
    }

    public function editProfile(Request $request)
    {
        $user = Auth::user();

        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'required|string|unique:users,mobile,' . $user->id,
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->isApiRequest($request)
                ? new JsonResponse(['errors' => $validator->errors()], 422)
                : redirect()->back()->withErrors($validator->errors())->withInput();
        }

        // Check if email is updated
        if ($user->email !== $request->input('email')) {
            // Update user profile with new email
            $user->email = $request->input('email');
            $user->status = 0; // Mark the account as unverified
            $user->save();

            // Send verification email to the new email address using the global function
            $this->sendVerificationEmail($user);
        }

        // Update other profile information
        $user->name = $request->input('name');
        $user->mobile = $request->input('mobile');
        $user->gender = $request->input('gender');
        $user->date_of_birth = $request->input('date_of_birth');
        $user->save();

        $responseData = [
            'message' => 'Profile updated successfully',
            'user' => $user,
        ];

        return $this->isApiRequest($request)
            ? new JsonResponse($responseData, 200)
            : redirect()->back()->with('success', $responseData['message']);
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

    public function resendVerificationEmail(Request $request)
    {
        $validator = validator($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->input('email'))->first();

        // Check if the user is already verified
        if ($user->status === 1) {
            return response()->json(['message' => 'Your account is already verified.'], 200);
        }

        // Send a new verification email
        $this->sendVerificationEmail($user);

        return response()->json(['message' => 'A new verification email has been sent.'], 200);
    }

    private function isApiRequest(Request $request)
    {
        return $request->wantsJson() || $request->is('api/*');
    }


    private function getErrorResponse($errors, Request $request)
    {
        if ($this->isApiRequest($request)) {
            return response()->json(['errors' => $errors], 422);
        } else {
            return redirect()->back()->withErrors($errors)->withInput();
        }
    }

    public function verify(Request $request, $code)
    {
        $email = base64_decode($code);
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid verification code'], 404);
        }

        // Check if the user is already verified
        if ($user->status === 1) {
            return response()->json(['message' => 'Your account is already verified.'], 200);
        }

        // Update user status to verified
        $user->status = 1;
        $user->save();

        return response()->json(['message' => 'Your account has been successfully verified.'], 200);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'user_type' => 'required|in:admin,user',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->input('email'))->first();

        // Generate a unique token
        $token = Str::random(60);

        // Store the token in the password reset tokens table
        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => $token,
            'user_type' => $request->input('user_type'),
            'created_at' => now(),
        ]);

        // Send an email with the password reset link
        $this->sendPasswordResetEmail($user, $token);

        return response()->json(['message' => 'Password reset email sent.'], 200);
    }

    public function sendPasswordResetEmail($user, $token)
    {
        // Assuming you have a custom Mailable class for the password reset email
        MailService::setSmtpConfig();

        Mail::send('emails.password_reset', ['user' => $user, 'token' => $token], function ($message) use ($user) {
            $message->to($user->email)->subject('Reset Your Password');
        });
    }

    public function resetPassword(Request $request)
    {
        // dd('Reset Password Request Data: ', $request->all());
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|min:8|confirmed',
            'user_type' => 'required|in:admin,user',
        ]);

        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->input('email'))
            ->where('token', $request->input('token'))
            ->where('user_type', $request->input('user_type'))
            ->first();

            if (!$tokenData) {
                \Log::error('Invalid or expired token for email: ' . $request->input('email'));
                return response()->json(['error' => 'Invalid or expired token.'], 422);
            }

        // Update the user's password
        $user = User::where('email', $request->input('email'))->first();
        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Delete the used token
        DB::table('password_reset_tokens')
            ->where('email', $request->input('email'))
            ->where('token', $request->input('token'))
            ->where('user_type', $request->input('user_type'))
            ->delete();

        return response()->json(['message' => 'Password reset successful.'], 200);
    }

}
