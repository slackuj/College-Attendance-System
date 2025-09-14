<?php
require_once "../../../../backend/auth_check.php";
require "../../../../backend/functions.php";

if (isset($_GET['id'])){
    $tID = $_GET['id'];
    $name = getTeachersName($tID);
    $email = getAttribute('aUsers', 'email', 'id', $tID);
    $title = getAttribute('aTeacher', 'title', 'id', $tID);
    $cdate = getAttribute('aUsers', 'created_date', 'id', $tID);
    $classes = totalClassesOf($tID, '1');
}

// display success toast
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Profile</title>
    <link rel="icon" href="/resources/images/favicon.svg">
    <link rel="stylesheet" href="../../css/teacherProfile.css">
    <link rel="stylesheet" href="../../css/edit_teacher.css">
    <link rel="stylesheet" href="../../css/resetPassword.css">
    <link rel="stylesheet" href="../../css/modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/profile.js" defer></script>
    <script src="js/sidebar.js" defer></script>
    <script src="js/profile_modal.js" defer></script>
    <script src="js/resetPassword.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>
<?php include "../../include/topbar.php";?>
<body>
<div class="wrapper">
    <?php include "include/teacher_sidebar.php";?>
    <div class="main-content">
        <div class="header-bar">
            <button id="editBtn"><i class="fas fa-edit"></i> Edit Teacher</button>
            <button id="deleteBtn"><i class="fas fa-trash-alt"></i> Delete</button>
            <button id="resetBtn"><i class="fas fa-key"></i> Reset password</button>
            <!--<button><i class="fas fa-comment-dots"></i> Requests</button>-->
            <button id="refresh"><i class="fas fa-sync-alt"></i> Refresh</button>
        </div>

        <div class="profile-container">
            <nav class="profile-nav">
                <a href="#" class="active">Overview</a>
            </nav>

            <main class="profile-content">
                <?php include "include/profile_modal.php"; ?>
                <h2>Basic info</h2>
                <div class="user-info-section">
                    <div class="user-avatar">
                        <div class="avatar-circle"><?php echo substr($name, 0, 1); ?></div>
                        <i class="fas fa-camera avatar-icon"></i>
                    </div>
                    <div class="user-details">
                        <input id="tName" type="hidden" value="<?php echo $name;?>">
                        <h1><?php echo $name; ?></h1>
                        <p class="email"><?php echo $email; ?></p>
                        <p class="user-type"><?php echo $title; ?></p>
                    </div>
                </div>

                <hr>

                <div class="user-details-grid">
                    <div class="grid-item">
                        <div class="label">email</div>
                        <div class="value-with-icon">
                            <span id="teacher-email" class="value"><?php echo $email; ?></span>
                            <i id="copyEmail" class="fas fa-copy copy-icon"></i>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="label">Classes</div>
                        <div class="value-link"><a href="classes.php?id=<?php echo $tID?>"><?php echo $classes;?></a></div>
                    </div>
                    <div class="grid-item">
                        <div class="label">Job Title</div>
                        <div class="value-with-icon">
                            <span class="value"><?php echo $title; ?></span>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="label">Created date</div>
                        <div class="value"><?php echo $cdate;?></div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
</body>
</html>