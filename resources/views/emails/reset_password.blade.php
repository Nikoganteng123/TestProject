<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset Password</h1>
    <p>Anda telah meminta untuk mereset password Anda. Klik tautan di bawah ini untuk melanjutkan:</p>
    <a href="{{ url('password/reset', $token) }}">Reset Password</a>
    <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
</body>
</html>