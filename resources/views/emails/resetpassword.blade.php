@component('mail::message')
# Password Reset

Dear user,

We have received a request to reset the password for your account associated with the email: **{{ $email }}**

To proceed with resetting your password, please click on the following link:

@component('mail::button', ['url' => $resetLink])
Reset Password
@endcomponent

If you did not request this password reset, please ignore this email.

Thank you,<br>
Your Website Team
@endcomponent