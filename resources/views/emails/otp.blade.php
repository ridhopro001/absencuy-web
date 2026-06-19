<!DOCTYPE html>
<html>
<head>
    <title>Kode OTP Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <div style="max-width: 500px; margin: 0 auto; background: #f9f9f9; padding: 30px; border-radius: 10px;">
        <h2 style="text-align: center; color: #333;">Reset Password</h2>
        <p>Berikut adalah kode OTP untuk mereset password Anda:</p>
        <div style="text-align: center; font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #d97706; margin: 20px 0;">
            {{ $otp }}
        </div>
        <p>Kode OTP ini berlaku selama <strong>2 menit</strong>.</p>
        <p>Abaikan email ini jika Anda tidak meminta reset password.</p>
    </div>
</body>
</html>
