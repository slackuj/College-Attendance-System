<?php
session_start();

require 'backend/functions.php';
$data = null;
if (isset($_SESSION['logged-in'])){
    switch ($_SESSION['logged-in']){
        case -1:
            header('Location:  /frontend/Admin/profile.php');
            break;
        case 0:
            header('Location:  /frontend/student/profile.php');
            break;
        case 1:
            header('Location:  /frontend/teacher/profile.php');
            break;
    }
    exit();
}
else if (isset($_POST['login'])){
    $data = login($_POST['email'], $_POST['password']);
    if ($data){
        $_SESSION['id'] = $data['id'];
        if ($data['role'] == -1){
            $_SESSION['logged-in'] = -1;
            header('Location:  /frontend/Admin/profile.php');
        }
        else if ($data['role'] == 0) {
            $_SESSION['logged-in'] = 0;
            header('Location:  /frontend/student/profile.php');
        }
        else if($data['role'] == 1){
            $_SESSION['logged-in'] = 1;
            header('Location:  /frontend/teacher/profile.php');
        }
        else if($data['role'] == 2 || $data['role'] == 3){/* password has been reset by admin and user needs to update the password */
            //$_SESSION['logged-in'] = $data[2];
            header('Location:  /frontend/account/update_password.php?email=' . $_POST['email'] . '&user=' . $data[2]);
        }
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="index_register.css">
    <script src="https://kit.fontawesome.com/8a9d95834d.js" crossorigin="anonymous"></script>
</head>
<body>
<?php
if (isset($_GET['error'])){
    echo "<div class='error-message'><span class='fa-solid fa-circle-info'></span> You must log in first !</div>";
}
if (isset($_GET['password_change'])){
    if ($_GET['password_change'] === 'successful'){
        echo "<div class='error-message'><span class='fa-solid fa-circle-check'></span>
       <b>Password changed successfully! </b> Please login to your account again. </div>";
    }
}
if (!$data && isset($_POST['login'])){
    unset($_POST['login']);
    echo "<div class='error-message'><span class='fa-solid fa-circle-info'></span> Wrong Credentials! Please Try Again</div>";
}
?>
<div class="container">
    <img class="app_icon" src="view/resources/Take%20attendance.svg" alt="app_icon">
    <form action="" method="post">
        <div class="input-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>
        <div class="checkbox-group">
            <a href="/frontend/login/identify.php" class="forgot-password">Forgot Password?</a>
        </div>
        <button type="submit" name="login" class="login-button">Log In</button>
    </form>
</div>
</body>
</html>