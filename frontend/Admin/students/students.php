<?php
require_once "../../../backend/auth_check.php";
require "../../../backend/functions.php";

$students = getStudents();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students</title>
    <link rel="icon" href="/resources/images/favicon.svg">
    <link rel="icon" href="/resources/images/favicon.svg">
    <link rel="stylesheet" href="../css/teacherProfile.css">
    <link rel="stylesheet" href="../css/edit_teacher.css">
    <link rel="stylesheet" href="../css/modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/sidebar.js" defer></script>
    <script src="js/teachers.js" defer></script>
    <script src="js/students_modal.js" defer></script>
    <script src="/frontend/Admin/js/filter.js" defer></script>
    <script src="student/js/export-utility.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
</head>
<?php include "../include/topbar.php";?>
<body>
<div class="wrapper">
    <?php include "include/student_sidebar.php";?>
    <div class="main-content">
        <div class="header-bar">
            <button id="addNewStudent"><i class="fas fa-plus"></i> New Student</button>
            <button id="bulkBtn"><i class="fas fa-file-arrow-up"></i> Bulk Create</button>
            <button id="exportBtn"><i class="fas fa-download"></i> Download</button>
            <button id="refresh"><i class="fas fa-sync-alt"></i> Refresh</button>
        </div>

        <div class="profile-container">
            <div class="searchBar">
                <input id="searchInput" type="text" placeholder="type to search" name="searchText">
            </div>

            <main class="profile-content">
                <h2><?php echo count($students);?> students found</h2>
                <table id="class-table" class="teachers-table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Roll Number</th>
                        <th>Email</th>
                        <th>Programme</th>
                        <th>Semester</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($students as $student){
                        $sname = $student[2] . ' ' . $student[3];
                        $sID = $student[0];
                        $roll_number = $student[1];
                        $email = getAttribute('aUsers', 'email', 'id', $sID);
                        $pname = getAttribute('aProgramme', 'name', 'id', $student[5]);
                        $semester = $student[4];
                        ?>
                        <tr>
                            <td><a href="student/profile.php?id=<?php echo $sID;?>"><?php echo $sname;?></td>
                            <td><?php echo $roll_number;?></td>
                            <td><?php echo $email;?></td>
                            <td><?php echo $pname;?></td>
                            <td><?php echo $semester;?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
</div>
<?php include "include/students_modal.php"; ?>
<p id="noResult" class="no-result" style="display: none;">No matching results found</p>
</body>
</html>