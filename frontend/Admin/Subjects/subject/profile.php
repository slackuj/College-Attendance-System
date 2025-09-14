<?php
require_once "../../../../backend/auth_check.php";
require "../../../../backend/functions.php";

if (isset($_GET['id'])){
    $sID = $_GET['id'];
    $row = getRow('aSubject', 'id', $sID);
    $pname = getAttribute('aProgramme', 'name', 'id', $row[2]);
    $fID = getAttribute('aProgramme', 'faculty', 'id', $row[2]);
    $fname = getAttribute('aFaculty', 'name', 'id', $fID);
    $sname = $row[1];
    $semester = $row[3];
    $course_code = $row[4];
    $course_credit = $row[5];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programme</title>
    <link rel="icon" href="/resources/images/favicon.svg">
    <link rel="stylesheet" href="../../css/teacherProfile.css">
    <link rel="stylesheet" href="../../css/edit_teacher.css">
    <link rel="stylesheet" href="../../css/resetPassword.css">
    <link rel="stylesheet" href="../../css/modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/profile.js" defer></script>
    <script src="js/sidebar.js" defer></script>
    <script src="js/profile_modal.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>
<?php include "../../include/topbar.php";?>
<body>
<div class="wrapper">
    <?php include "include/subject_sidebar.php";?>
    <div class="main-content">
        <div class="header-bar">
            <button id="editBtn"><i class="fas fa-edit"></i> Edit Subject</button>
            <button id="deleteBtn"><i class="fas fa-trash-alt"></i> Delete</button>
            <!--<button><i class="fas fa-comment-dots"></i> Requests</button>-->
            <button id="refresh"><i class="fas fa-sync-alt"></i> Refresh</button>
        </div>

        <div class="profile-container">
            <nav class="profile-nav">
                <a href="profile.php?id=<?php echo $sID;?>" class="active">Overview</a>
            </nav>

            <main class="profile-content">
                <?php include "include/profile_modal.php"; ?>
                <h2>Basic info</h2>
                <div class="user-info-section">
                    <div class="user-avatar">
                        <div class="avatar-circle"><?php echo substr($sname, 0, 1); ?></div>
                    </div>
                    <div class="user-details">
                        <h1><?php echo $sname; ?></h1>
                        <p><?php echo $pname;?></p>
                    </div>
                </div>

                <hr>

                <div class="user-details-grid">
                    <div class="grid-item">
                        <div class="label">Programme</div>
                        <div class="value-with-icon">
                            <span id="teacher-email" class="value"><?php echo $pname;?></span>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="label">Faculty</div>
                        <div class="value"><?php echo $fname;?></a></div>
                    </div>
                    <div class="grid-item">
                        <div class="label">Semester</div>
                        <div class="value-with-icon">
                            <span class="value-link"><?php echo $semester; ?></span>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="label">Course Code</div>
                        <div class="value"><?php echo $course_code;?></div>
                    </div>
                    <div class="grid-item">
                        <div class="label">Course Credit</div>
                        <div class="value-link"><?php echo $course_credit;?></div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
<input type="hidden" id="sID" value="<?php echo $sID;?>">
</body>
</html>