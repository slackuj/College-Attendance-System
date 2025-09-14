<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';
require_once 'functions.php';


if (isset($_POST['send_code'])){
        $email = $_POST['email'];
    }
else if (isset($_GET['resend_code'])){
        $email=$_GET['resend_code'];
    }


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$mail = new PHPMailer();
$mail->isSMTP();
$mail->SMTPDebug = SMTP::DEBUG_OFF;
$mail->Host = $_ENV['SMTP_HOST'];
$mail->Port = $_ENV['SMTP_PORT'];
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->SMTPAuth = true;
$mail->Username = $_ENV['SMTP_USER'];
$mail->Password = $_ENV['SMTP_PASS'];

try {
    $mail->setFrom($_ENV['SMTP_USER'], 'College Attendance Security');
} catch (\PHPMailer\PHPMailer\Exception $e) {
    echo $e->getTraceAsString();
}

/*try {
    $mail->addReplyTo($_ENV['SMTP_USER'], $email);
} catch (\PHPMailer\PHPMailer\Exception $e) {
    echo $e->getTraceAsString();
}
*/

try {
    $mail->addAddress($email);
} catch (\PHPMailer\PHPMailer\Exception $e) {
    echo $e->getTraceAsString();
}
$mail->Subject = 'Reset your password';
$mail->isHTML(true);
$mail->Body = '
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset your password</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;700&family=IBM+Plex+Mono&display=swap" rel="stylesheet">
    <style>
        /* Import IBM Plex Sans font from Google Fonts */
        @import url("https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;700&family=IBM+Plex+Mono&display=swap");

        /* Basic reset and body styles */
        body {
            font-family: "IBM Plex Sans", Arial, sans-serif;
            margin: 0;
            background-color: #f7f7f7; /* Light grey background */
            color: #2e2e2e; /* Darker text for better contrast */
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .container {
            background-color: #fff;
            max-width: 550px;
            margin: 40px auto; /* Adjust top/bottom margin */
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); /* Softer shadow */
            border: 1px solid #e0e0e0; /* Subtle container border */
        }

        /* Header Section */
        .header {
            padding: 20px;
            border-bottom: 1px solid #d9d9d9; /* Light border */
            margin-bottom: 25px;
            background-color: #000000;
            font-size: 1.5em;
        }

        .logo a{
            font-family: "IBM Plex Sans", Arial, sans-serif;
            font-size: 1em; /* Adjusted font size */
            font-weight: 900; /* Medium-bold weight */
            color: #ffffff; /* Pure black color */
            letter-spacing: -0.05em; /* Adjust letter spacing */
            text-decoration: none;
        }

        /* Content Section */
        .content {
            padding: 10px 15px 0; /* Top and horizontal padding */
        }

        .content h1 {
            font-family: "IBM Plex Sans", Arial, sans-serif;
            font-size: 1.5em; /* Adjusted font size */
            font-weight: 500; /* Medium font weight */
            color: #2e2e2e;
            margin-top: 0;
            margin-bottom: 4px; /* Reduced bottom margin */
        }

        .content hr {
            border: none;
            background-color: #d3d3d3; /* Light grey line */
            height: 0.7px; /* Thin line */
            margin: 8px 0; /* Adjust vertical spacing */
        }

        .content .instruction {
            font-size: 1em; /* Adjusted font size */
            line-height: 1.5;
            color: #494949;
            margin-bottom: 9px; /* Adjusted bottom margin */
            word-spacing: 0.02em;
        }

        .content .instruction strong {
            font-weight: bold;
        }

        .content .code {
            font-family: "Courier New", monospace; /* Monospace font for the code */
            font-size: 1.9em; /* Adjusted font size for monospace */
            font-weight: normal; /* Normal weight */
            color: #222; /* Darker color for code */
            letter-spacing: 1px; /* Adjust letter spacing for monospace */
            padding: 10px 0;
            margin-bottom: 12px; /* Adjusted bottom margin */
        }

        .content .security-note {
            font-size: 1em; /* Adjusted font size */
            line-height: 1.5;
            color: #666;
            word-spacing: 0.01em;
        }

        .content .security-note a {
            color: #0062be; /* IBM Blue for links */
            text-decoration: none;
            font-size: inherit; /* Ensure link font size matches parent */
        }

        .content .security-note a:hover {
            text-decoration: underline;
        }

        /* Footer Section */
        .footer {
            padding-top: 8px; /* Adjusted top padding */
            margin-top: 16px; /* Adjusted top margin */
            font-size: 0.6em; /* Adjusted font size */
            color: #696969;
            text-align: left; /* Align footer text to the left */
        }

        .footer p {
            margin: 0; /* Remove default paragraph margin */
            padding-top: 4px;
            padding-bottom: 4px;
            padding-left: 20px;
            word-spacing: 0.01em;
            color: #ffffff;
            background-color: #000000;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="logo"><a href="http://localhost:8000" >College Attendance</div>
    </div>
    <div class="content">
        <h1>Reset your password</h1>
        <hr>
        <p class="instruction">Please return to your browser window and enter this <strong>6-digit code</strong> to reset your password.</p>
        <p class="code">' . getCode($email) . '</p>
        <p class="security-note">If you did not make this change, please disregard this email and contact <a href="#">admin</a>. Do not reply to this automated email.</p>
    </div>
    <div class="footer">
        <p>© 2025 College Attendance All rights reserved</p>
    </div>
</div>
</body>
</html>
';

try {
    if (!$mail->send())
        echo 'mail error' . $mail->ErrorInfo;
}
catch (\PHPMailer\PHPMailer\Exception $e) {
    echo $e->getTraceAsString();
}
