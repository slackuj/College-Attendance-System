<?php

    require_once '../../backend/functions.php';

    if (isset($_POST['create_pass'])){
        if ($_POST['role'] == 2)
            $role = 1;
        else if ($_POST['role'] == 3)
            $role = 0;
        resetPassword($_POST['pass1'], $_POST['email'], $role);
        header("Location: /index.php?password_change=successful");
    }

    else {
        if (isset($_GET['email']))
            $email = $_GET['email'];
        if (isset($_GET['user']))
            $role = $_GET['user'];
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>New Password</title>
    <link rel="stylesheet" href="new_password.css"/>
    <script src="https://kit.fontawesome.com/8a9d95834d.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <form class="form" action="" method="post">
        <h2>Update password</h2>

        <p>You need to update your password because this is the first time you are signing in, or because your password has expired.</p>
        <input type="hidden" name="email" value="<?php echo $email; ?>" >
        <input type="hidden" name="role" value="<?php echo $role; ?>" >
        <div><i id="togglePass" onclick="togglePass()" class="fa-solid fa-eye-slash"></i></div>
        <div class="password-box">
        <input type="password" id="pass1" name="pass1" placeholder="New Password" required>
        <p id="alert1" class="required_length">password must be at least 6 characters long</p>
        </div>
        <div class="password-box">
        <input type="password" id="pass2" name="pass2" placeholder="Confirm New Password" disabled required>
        </div>
        <p id="alert2" class="not_matched"><i class="fa-solid fa-circle-info"></i>
               passwords do not match </p>
        <div class="buttons">
            <button id = "continue" type="submit" name="create_pass" class="continue" disabled>Continue</button>
        </div>
    </form>
</div>
</body>
<script>
    const pass1 = document.getElementById('pass1');
    const pass2 = document.getElementById('pass2');
    const alert1 = document.getElementById('alert1');
    const alert2 = document.getElementById('alert2');
    const updatePass = document.getElementById('continue');

    pass1.addEventListener("input", ()=>{
       let pass = pass1.value.trim();
       if (pass === '')
           alert1.style.display = 'none';
       else {
           if (pass.length >= 6){
               alert1.style.display = "none";
               pass2.removeAttribute('disabled');
           }
           else{
               alert1.style.display = "block";
               pass2.value = '';
               pass2.setAttribute('disabled', 'true');
           }
       }
    });

    pass2.addEventListener("input", ()=>{
        if (pass1.value  === '' || pass2.value === '')
            alert2.style.display = 'none';
        else {
            if (pass1.value === pass2.value){
                alert2.style.display = "none";
                updatePass.removeAttribute('disabled');
            }
            else{
                alert2.style.display = "block";
                updatePass.setAttribute('disabled', 'true');
            }

        }
    });

    function togglePass(){
       const togglePass = document.getElementById('togglePass');
       const pass1 = document.getElementById('pass1');
       const pass2 = document.getElementById('pass2');
       if (pass1.type === 'password'){
           togglePass.classList.replace('fa-eye-slash', 'fa-eye');
           pass1.type = 'text';
           pass2.type = 'text';
       }
       else {
           togglePass.classList.replace('fa-eye', 'fa-eye-slash');
           pass1.type = 'password';
           pass2.type = 'password';
       }

    }
</script>
</html>

