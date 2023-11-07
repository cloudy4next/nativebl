<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
</head>

<body>
    <p>Click the following link to reset your password:</p>
    <p><a href="{{ route('password.reset', $resetToken) }}">Reset Password</a></p>
</body>

</html>
