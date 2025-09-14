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
           header("Location: change_password.php?email=". urlencode($_POST['email']) . "&error=wrongCode");
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
    <title>Change Password</title>
    <link rel="stylesheet" href="/frontend/Admin/css/addNew.css" />
    <script src="https://kit.fontawesome.com/8a9d95834d.js" crossorigin="anonymous"></script>
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
        <p>
            you will receive an email with a 6-digit temporary code.
            This code will expire in 30 minutes</p>
        <hr>

        <input type="hidden" name="email" value="<?php echo $email; ?>" >
        <label for="code">Code</label>
        <input type="text" id="code" name="code" required/>

        <div class="buttons">
            <button type="button" onclick="window.location.href = '../../frontend/Teacher/dashboard.php';">Cancel</button>
            <button type="submit" name="confirm_code" class="continue">Continue</button>
        </div>
        <p>
            Didn’t receive the email?<br>
            Check your spam filter for an email from<br>
            <b>college.attendance@gmail.com</b>
        </p>
        <a href="change_password.php?resend_code=<?php echo $email; ?>">Resend Code</a>
    </form>
</div>
</body>
</html>