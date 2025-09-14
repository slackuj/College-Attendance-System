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
    <title>Students | Class</title>
    <link rel="icon" href="/resources/images/favicon.svg">
    <link rel="stylesheet" href="../../css/teacherProfile.css">
    <link rel="stylesheet" href="../../css/edit_teacher.css">
    <link rel="stylesheet" href="../../css/modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/students.js" defer></script>
    <script src="js/sidebar.js" defer></script>
    <script src="js/students_modal.js" defer></script>
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
            <button id="addStudentBtn"><i class="fas fa-plus"></i> New Student</button>
            <button id="removeStudentBtn" class="action-button" disabled><i class="fas fa-xmark"></i> Remove Student</button>
            <button id="refresh"><i class="fas fa-sync-alt"></i> Refresh</button>
        </div>

        <input type="hidden" id="cID" value="<?php echo $cID;?>">
        <div class="profile-container">
            <nav class="profile-nav">
                <a href="#" class="active">Overview</a>
            </nav>

            <main class="profile-content">
               <?php include "include/students_modal.php"; ?>
                <h2><?php if ($students) echo count($students);?> students found</h2>
                <table id="class-table" class="teachers-table">
    <thead>
      <tr>
        <th><input type="checkbox" id="students"></th>
        <th>Name</th>
        <th>Exam Roll Number</th>
      </tr>
    </thead>
    <tbody>
       <?php
          foreach ($students as $student){
            ?>
            <tr>
                <td><input type="checkbox" class="student" data-roll="<?php echo $student;?>"></td>
                <td><a href="#"><?php echo getStudentName($student);?></a></td>
                <td><?php echo $student;?></td>
            </tr>
          <?php } ?>
     </tbody>
  </table>
            </main>
        </div>
    </div>
</div>
</body>
</html>