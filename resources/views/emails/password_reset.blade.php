<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f4f4f4;">
        <h2 style="color: #333;">Password Reset</h2>
        <p>Hello {{ $user->name }},</p>
        <p>We received a request to reset your password. Please use the following OTP to reset your password:</p>
        <p style="font-size: 24px; font-weight: bold; color: #007bff;">{{ $otp }}</p>
        <p>If you did not request a password reset, please ignore this email.</p>
        <p>Thank you!</p>
    </div>
</body>
</html>