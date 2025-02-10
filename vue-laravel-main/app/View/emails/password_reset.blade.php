<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body>
    <p>Hello {{ $user->first_name }},</p>
    <p>Click the link below to reset your password:</p>
    <a href="{{ url('/reset-password?token='.$token) }}">Reset Password</a>
    <p>If you didn't request a password reset, no further action is required.</p>
</body>
</html>
