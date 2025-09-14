<?php
require_once "../../../../backend/auth_check.php";
require "../../../../backend/functions.php";

if (isset($_GET['id'])){
    $sID = $_GET['id'];
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance | Student</title>
    <link rel="icon" href="/resources/images/favicon.svg">
    <link rel="stylesheet" href="../../css/teacherProfile.css">
    <link rel="stylesheet" href="../../css/edit_teacher.css">
    <link rel="stylesheet" href="../../css/modal.css">
    <link rel="stylesheet" href="../../css/attendance.css">
    <script src="/frontend/calendar/adminStudent.js" defer></script>
    <script src="js/attendance.js" defer></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="/frontend/Admin/css/view_attendance.css">
    <link rel="stylesheet" href="/frontend/calendar/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/sidebar.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>
<?php include "../../include/topbar.php";?>
<body>
<div class="wrapper">
    <?php include "include/student_sidebar.php";?>
    <div class="main-content">
        <div class="header-bar">
            <button id="refresh" class="action-button"><i class="fas fa-sync-alt"></i> Refresh</button>
        </div>
        <div class="profile-container">
            <main class="profile-content">
                <input id="roll" type="hidden" value="<?php echo $roll_number;?>">
                <div class="date">
                    <label for="Date">Date</label>
                    <input type="date" id="date" name="day" value="<?php echo date('Y-m-d'); ?>" disabled>
                </div>
                <label for="classes"><b>Class </b></label>
                <select id="classes" >
                    <?php foreach ($classes as $class){
                        $subjecID = getAttribute('aClasses','subject', 'id', $class);
                        $className = getAttribute('aSubject', 'name', 'id', $subjecID);
                        ?>
                        <option value="<?php echo $class;?>" data-cname="<?php echo $className;?>"><?php echo $className;?></option>
                    <?php } ?>
                </select>
                <div><h3 class="attendance-class-header" id ="attendance-class-header"></h3></div>
            <div class="calendar_details">
        <div class="calndr"><?php include '../../../calendar/index.html';?></div>
    </div>
                <br>
    <h3 class="monthly-attendance-table-header" id ="monthly-attendance-table-header"></h3>
                <br>

    <?php include 'include/monthly_attendance.php';?>
                <div class='info-message' id="no-attendance"><span class='fa-solid fa-circle-info'></span>
                </div>
            </main>
        </div>
    </div>
</div>
</body>
</html>