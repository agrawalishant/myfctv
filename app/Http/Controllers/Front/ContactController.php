<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Services\MailService;
use Session;
use Validator;
use Log;

class ContactController extends Controller
{
    public function contact(Request $request)
    {
        Session::put('page', 'contact');

        if ($request->isMethod('post')) {
            $data = $request->all();

            $validator = Validator::make($data, [
                'first_name' => 'required',
                'last_name' => 'required',
                'phone' => 'required',
                'email' => 'required|email',
                'message' => 'required',
                'g-recaptcha-response' => 'required|captcha',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            }

            // You can include any data you want in your email here
            $messageData = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'message' => $data['message'],
            ];

            try {
                // Set SMTP configuration
                MailService::setSmtpConfig();

                // Use the mail function to send the email
                Mail::send('emails.contact-email', ['data' => $messageData], function ($message) {
                    $message->to('yogeshwalokar786@gmail.com')->bcc('chandrakantwk@gmail.com')->subject('Contact Form Submission');
                });

                // Log::info('Contact form email sent successfully.');

                return response()->json(['status' => 1, 'message' => 'Email sent successfully']);
            } catch (\Exception $e) {
                // Log error
                // Log::error('Error sending contact form email: ' . $e->getMessage());

                return response()->json(['status' => 0, 'error' => $e->getMessage()]);
            }
        }

        return view('front.contact');
    }

}
