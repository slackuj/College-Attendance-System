<?php
    require_once '../../backend/functions.php';
    if (isset($_POST['send_code'])){
        $email = $_POST['email'];
        if (send_code($email))
            require_once '../../backend/send_code.php';
    }
    else if (isset($_GET['resend_code'])){
        $email=$_GET['resend_code'];
        if (send_code($email))
            require_once '../../backend/send_code.php';
    }
    else if (isset($_POST['confirm_code'])) {
        $email = $_POST['email'];
        $confirmed = confirmCode($_POST['code'], $_POST['email']);
        if ($confirmed){
           header("Location: new_password.php?email=" . urlencode($_POST['email']));
           exit();
        }
        else {
           header("Location: reset_password.php?email=". urlencode($_POST['email']) . "&error=wrongCode");
        }
    }

    else if (isset($_GET['error'])){
        $email = $_GET['email'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Reset Password</title>
    <link rel="stylesheet" href="reset_password.css"/>
</head>
<body>
<?php
if (isset($_GET['error'])){
    echo "<div class='error-message'><span class='fa-solid fa-circle-info'></span> The code is invalid
    .Please check your email and try again.</div>";
}
?>
<div class="container">
    <form class="form" action="" method="post">
        <h2>Check your email</h2>
        <p class="reset-info"><br>If there is an account associated with <?php echo $email; ?>,
            you will receive an email with a 6-digit temporary code.
            This code will expire in 30 minutes</p>
        <hr>

        <input type="hidden" name="email" value="<?php echo $email; ?>" >
        <label class="codeLabel" for="code"><b>Code</b></label>
        <input type="text" id="code" name="code" required/>

        <div class="buttons">
            <button type="button" class="cancel" onclick="window.location.href = '../../index.php';">Cancel</button>
            <button type="submit" name="confirm_code" class="continue">Continue</button>
        </div>
        <p>
            <br>
            Didn’t receive the email?<br>
            Check your spam filter for an email from<br>
            <b>college.attendance@gmail.com</b>
        </p>
        <a href="reset_password.php?resend_code=<?php echo $email; ?>">Resend Code</a>
    </form>
</div>
</body>
</html>