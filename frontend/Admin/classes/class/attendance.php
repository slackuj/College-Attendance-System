<?php
require_once "../../../../backend/auth_check.php";
require "../../../../backend/functions.php";

if (isset($_GET['id'])){
    $cID = $_GET['id'];
    $row = getRow('Classes', 'id', $cID);
    $sname = getAttribute('aSubject', 'name', 'id', $row[1]);
    $pID = getAttribute('aSubject', 'programme', 'id', $row[1]);
    $pname = getAttribute('aProgramme', 'name', 'id', $pID);
    $semester = getAttribute('aSubject', 'semester', 'id', $row[1]);
    if ($row[4] == 0){
        $tname = getTeachersName($row[2]);
        $dateHeader = "Created Date";
    }
    else if ($row[4] == -1){
        $tname = "not assigned";
        $dateHeader = "Unassigned Date";
    }
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
    <link rel="stylesheet" href="../../css/teacherProfile.css">
    <link rel="stylesheet" href="../../css/edit_teacher.css">
    <link rel="stylesheet" href="../../css/modal.css">
    <link rel="stylesheet" href="../../css/attendance.css">
    <script src="/frontend/calendar/attendance.js" defer></script>
    <script src="js/attendance.js" defer></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="/frontend/Admin/css/view_attendance.css">
    <link rel="stylesheet" href="/frontend/calendar/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/sidebar.js" defer></script>
    <script src="js/attendance_modal.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>
<?php include "../../include/topbar.php";?>
<body>
<div class="wrapper">
    <?php include "include/class_sidebar.php";?>
    <div class="main-content">
        <div class="header-bar">
            <button id="takeAttendanceBtn" class="action-button" disabled><i class="fas fa-edit"></i> Take Attendance</button>
            <button id="updateAttendanceBtn" class="action-button" disabled><i class="fas fa-sign-in"></i> Update Attendance</button>
            <button id="refresh" class="action-button"><i class="fas fa-sync-alt"></i> Refresh</button>
        </div>
        <input type="hidden" id="cID" value="<?php echo $cID;?>">
        <div class="profile-container">
            <main class="profile-content">
                <div class="date">
                    <label for="Date">Date</label>
                    <input type="date" id="date" name="day" required value="<?php echo date('Y-m-d'); ?>" disabled>
                </div>
            <div class="calendar_details">
        <div class="calndr"><?php include '../../../calendar/index.html';?></div>
        <!--<div class="class_details">
            <span><label for="S-date">Start Date:</label>
                <input id="s-date" type="date" required>
            </span>
            <span><label for="E-date">End Date:</label>
                <input id="e-date" type="date" required>
            </span>
           <div>
               <span><b>Total Estimated Classes :</b></span>
           </div>
            <div>
               <span><b>Total Taken Classes :</b></span>
            </div>
            <div>
               <span><b>Estimated Remaining Classes :</b></span>
           </div>
        </div>
        -->
    </div>
                <br>
    <h3 class="attendance-table-header" id ="attendance-table-header"></h3>
                <br>

    <?php include 'include/daily_attendance.php';?>
                <div class='info-message' id="no-attendance"><span class='fa-solid fa-circle-info'></span>
                </div>
            </main>
        </div>
    </div>
</div>
<?php include "include/attendance_modal.php";?>
</body>
</html>