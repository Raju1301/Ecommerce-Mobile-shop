<?php
// Include the database connection and PHPMailer
include("db.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'autoload.php'; // Include PHPMailer if installed via Composer

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_otp'])) {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Generate OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        // Send OTP via email
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'mobiletrollley@gmail.com'; // Replace with your email
            $mail->Password = 'krigzadtbbvgoqxu'; // Use App Password if 2FA is enabled
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipient and sender
            $mail->setFrom('mobiletrollley@gmail.com', 'Mobile Trolley'); // Sender's email
            $mail->addAddress($email); // Recipient's email

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset OTP';
            $mail->Body    = "Dear User,<br><br>Your OTP for password reset is: <b>$otp</b><br><br>Thank you,<br>Mobile Mansion";
            $mail->AltBody = "Dear User,\n\nYour OTP for password reset is: $otp\n\nThank you,\nMobile Mansion";

            $mail->send();
            echo "<script>alert('OTP has been sent to your email. Please check your inbox.');</script>";
        } catch (Exception $e) {
            $error = "Error sending OTP. Please try again later.";
        }
    } else {
        $error = "No user found with that email!";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify_otp'])) {
    $entered_otp = $_POST['otp'];
    if ($entered_otp == $_SESSION['otp']) {
        $_SESSION['otp_verified'] = true;
    } else {
        $error = "Invalid OTP!";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password']) && isset($_SESSION['otp_verified']) && $_SESSION['otp_verified']) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_SESSION['email'];

    if ($new_password === $confirm_password) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update the password in the database
        $query = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_affected_rows($conn) > 0) {
            unset($_SESSION['otp']);
            unset($_SESSION['otp_verified']);
            unset($_SESSION['email']);
            echo "<script>alert('Password reset successfully!'); window.location='login.php';</script>";
        } else {
            $error = "Error updating password!";
        }
    } else {
        $error = "Passwords do not match!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url(bg.jpg);
            color: #fff;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background:rgba(0.9,0.3,0.1,0.2);
            padding: 30px 25px;
            border-radius: 10px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            width: 350px;
            color: #333;
        }
        input[type="email"], input[type="password"], input[type="number"], button {
            display: block;
            width: 100%;
            margin: 15px 0;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        button {
            background-color: #007BFF;
            font-family: 'papyrus';
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 1rem;
            font-weight: bold;
        }
        h1{
            text-align: center;
            font-family: 'verdana';
            color: black;
            margin-top: 50px;
            font-size: 2.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        button:hover {
            background-color: #C70039;
            transform: translateY(-3px);
        }
        .error {
            color: red;
            margin-bottom: 15px;
            font-size: 0.9rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <form method="POST" action="">
        <h1>Reset Password</h1>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (!isset($_SESSION['otp']) && !isset($_SESSION['otp_verified'])): ?>
            <!-- Request OTP Form -->
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit" name="request_otp">Request OTP</button>
        <?php elseif (!isset($_SESSION['otp_verified'])): ?>
            <!-- Verify OTP Form -->
            <input type="number" name="otp" placeholder="Enter the OTP" required>
            <button type="submit" name="verify_otp">Verify OTP</button>
        <?php else: ?>
            <!-- Reset Password Form -->
            <input type="password" name="new_password" placeholder="Enter new password" required>
            <input type="password" name="confirm_password" placeholder="Confirm new password" required>
            <button type="submit" name="reset_password">Reset Password</button>
        <?php endif; ?>
    </form>
</body>
</html>