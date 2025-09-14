<?php
require_once "../../backend/auth_check.php";
require "../../backend/functions.php";

if (isset($_SESSION['id'])){
    $sID = $_SESSION['id'];
    $row = getRow('aStudent', 'id', $sID);
    $sname = $row[2] . ' ' . $row[3];
    $roll_number = $row[1];
    $semester = $row[4];
    $pname = getAttribute('aProgramme', 'name', 'id', $row[5]);
    $title = $row[6];
    $fID = getAttribute('aProgramme', 'faculty', 'id', $row[5]);
    $fname = getAttribute('aFaculty', 'name', 'id', $fID);
    $email = getAttribute('aUsers', 'email', 'id', $sID);
    $classes = getKlassesOf($roll_number, 0);
}
else{
    echo "Error: No valid Session found";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="icon" href="/resources/images/favicon.svg">
    <link rel="stylesheet" href="/frontend/Admin/css/teacherProfile.css">
    <link rel="stylesheet" href="/frontend/Admin/css/edit_teacher.css">
    <!--<link rel="stylesheet" href="/frontend/Admin/css/resetPassword.css">-->
    <link rel="stylesheet" href="/frontend/Admin/css/modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/profile.js" defer></script>
    <script src="js/sidebar.js" defer></script>
    <!--<script src="js/profile_modal.js" defer></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>
<?php include "include/topbar.php";?>
<body>
<div class="wrapper">
    <?php include "include/student_sidebar.php";?>
    <div class="main-content">
        <div class="header-bar">
            <!--<button id="resetBtn"><i class="fas fa-key"></i> Reset password</button>-->
            <button id="refresh"><i class="fas fa-sync-alt"></i> Refresh</button>
        </div>

        <div class="profile-container">
            <nav class="profile-nav">
                <a href="#" class="active">Overview</a>
            </nav>

            <main class="profile-content">
                <h2>Basic info</h2>
                <div class="user-info-section">
                    <div class="user-avatar">
                        <div class="avatar-circle"><?php echo substr($sname, 0, 1); ?></div>
                    </div>
                    <div class="user-details">
                        <input id="sID" type="hidden" value="<?php echo $sID;?>">
                        <h1><?php echo $sname; ?></h1>
                        <p class="email"><?php echo $email; ?></p>
                    </div>
                </div>

                <hr>

                <div class="user-details-grid">
                    <div class="grid-item">
                        <div class="label">Faculty</div>
                        <div class="value"><?php echo $fname;?></div>
                    </div>
                    <div class="grid-item">
                        <div class="label">Programme</div>
                        <div class="value"><?php echo $pname;?></div>
                    </div>
                    <div class="grid-item">
                        <div class="label">Roll Number</div>
                        <div class="value-with-icon">
                            <span class="value"><?php echo $roll_number;?></span>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="label">Semester</div>
                        <div class="value"><?php echo $semester;?></div>
                    </div>
                    <div class="grid-item">
                        <div class="label">Email</div>
                        <span id="teacher-email" class="value"><?php echo $email;?></span>
                        <i id="copyEmail" class="fas fa-copy copy-icon"></i>
                    </div>
                    <div class="grid-item">
                        <div class="label">Title</div>
                        <div class="value"><?php echo $title;?></div>
                    </div>
                </div>

                <br>
                <table id="class-table" class="teachers-table">
                    <thead>
                    <tr>
                        <th>Classes</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($classes as $class){
                        $subjecID = getAttribute('aClasses','subject', 'id', $class);
                        $className = getAttribute('aSubject', 'name', 'id', $subjecID);
                        ?>
                        <tr><td><?php echo $className;?></td></tr>
                    <?php } ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
</div>
<?php include "include/profile_modal.php"; ?>
</body>
</html>