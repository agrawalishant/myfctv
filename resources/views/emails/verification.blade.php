<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" width="100%">
        <tr>
            <td>
                <p>Hello {{ $name }},</p>
                <p>Thank you for registering with us. Please click the following link to verify your email address:</p>
                <p><a href="{{ url('/api/verify/' . $code) }}">Verify Email</a></p>
                <p>If you did not create an account, no further action is required.</p>
            </td>
        </tr>
    </table>
</body>
</html>
