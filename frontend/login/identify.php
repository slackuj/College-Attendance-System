<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Reset Password</title>
    <link rel="stylesheet" href="identify.css"/>
</head>
<body>
<div class="container">
    <h2>Reset your password</h2>
    <form class="form" action="/frontend/account/reset_password.php" method="post">
        <label for="email">Please enter email for your account.</label>
        <input type="email" id="email" name="email" placeholder="Email" required>
        <div class="buttons">
            <button type="button" class="cancel" onclick="window.location.href = '../../index.php';">Cancel</button>
            <button type="submit" name="send_code" class="continue">Continue</button>
        </div>
    </form>
</div>
</body>
</html>