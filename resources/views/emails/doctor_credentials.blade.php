<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Health Nest</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 16px;">
        <h2 style="color: #2563eb;">Welcome to Health Nest, {{ $name }}!</h2>
        <p>Your professional account has been successfully created by the administrator. You can now log in to the portal using the following credentials:</p>
        
        <div style="background-color: #f9f9f9; padding: 15px; border-left: 4px solid #2563eb; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Login URL:</strong> <a href="{{ url('/login') }}">{{ url('/login') }}</a></p>
            <p style="margin: 5px 0;"><strong>Email ID:</strong> {{ $email }}</p>
            <p style="margin: 5px 0;"><strong>Password:</strong> {{ $password }}</p>
        </div>

        <p><strong>Note:</strong> For security reasons, we recommend that you reset your password immediately after your first login.</p>
        
        <p>If you have any questions or need assistance, please contact the hospital administration.</p>
        
        <p>Best regards,<br>
        <strong>Health Nest Team</strong></p>
    </div>
</body>
</html>
