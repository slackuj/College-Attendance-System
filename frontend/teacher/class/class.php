<?php
require_once "../../../backend/auth_check.php";
require "../../../backend/functions.php";

if (isset($_GET['id'])){
    $cID = $_GET['id'];
    $row = getRow('Classes', 'id', $cID);
    $sname = getAttribute('aSubject', 'name', 'id', $row[1]);
    $pID = getAttribute('aSubject', 'programme', 'id', $row[1]);
    $semester = getAttribute('aSubject', 'semester', 'id', $row[1]);
    $pname = getAttribute('aProgramme', 'name', 'id', $pID);
    $name = getTeachersName($row[2]);
    $dateHeader = "Created Date";
    $cdate = $row[3];
    $students = getStudentsRollNumbersOfClass($cID);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class</title>
    <link rel="icon" href="/resources/images/favicon.svg">
    <link rel="stylesheet" href="/frontend/Admin/css/teacherProfile.css">
    <link rel="stylesheet" href="/frontend/Admin/css/edit_teacher.css">
    <link rel="stylesheet" href="/frontend/Admin/css/modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/class.js" defer></script>
    <script src="js/sidebar.js" defer></script>
    <script src="js/class_modal.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>
<?php include "../include/topbar.php";?>
<body>
<div class="wrapper">
    <?php include "include/class_sidebar.php";?>
    <div class="main-content">
        <div class="header-bar">
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
                        <i class="fas fa-camera avatar-icon"></i>
                    </div>
                    <div class="user-details">
                        <input id="sName" type="hidden" value="<?php echo $sname;?>">
                        <h1><?php echo $sname; ?></h1>
                        <p class="email"><?php echo $pname; ?></p>
                    </div>
                </div>

                <hr>

                <div class="user-details-grid">
                    <div class="grid-item">
                        <div class="label">programme</div>
                        <div class="value-with-icon">
                            <span id="teacher-email" class="value"><?php echo $pname; ?></span>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="label">Semester</div>
                        <div class="value-link"><?php echo $semester;?></div>
                    </div>
                    <div class="grid-item">
                        <div class="label">Students</div>
                        <div class="value-link"><a href="students.php?id=<?php echo $cID?>"><?php echo count($students);?></a></div>
                    </div>
                    <div class="grid-item">
                        <div class="label"><?php echo $dateHeader;?></div>
                        <div class="value"><?php echo $cdate;?></div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
</body>
</html>