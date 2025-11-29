<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify Your Account</title>
    <style>
        /* Email clients often strip external CSS, so we put styles here */
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { background-color: #343a40; color: #ffffff; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px 20px; color: #333333; line-height: 1.6; }
        .btn-container { text-align: center; margin: 30px 0; }
        .btn { background-color: #ffc107; color: #000000; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px; display: inline-block; }
        .footer { text-align: center; font-size: 12px; color: #888888; margin-top: 20px; border-top: 1px solid #eeeeee; padding-top: 20px; }
    </style>
</head>
<body>

    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>ITSO Inventory System</h1>
        </div>

        <!-- Body -->
        <div class="content">
            <p><strong>Hello, <?= esc($name) ?>!</strong></p>
            
            <p>Thank you for registering with the ITSO Equipment Management System. To activate your account and gain access to the dashboard, please verify your email address.</p>
            
            <p>Your Role: <strong><?= esc($role) ?></strong></p>

            <div class="btn-container">
                <a href="<?= $link ?>" class="btn">Verify My Account</a>
            </div>

            <p>If the button above does not work, you can also click on the link below (or copy and paste it into your browser):</p>
            <p><a href="<?= $link ?>" style="color: #007bff; word-break: break-all;"><?= $link ?></a></p>
            
            <p>If you did not request this account, please ignore this email.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; <?= date('Y') ?> ITSO Inventory System. All rights reserved.</p>
            <p>This is an automated message, please do not reply.</p>
        </div>
    </div>

</body>
</html>