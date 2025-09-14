<?php
require_once "../../../backend/auth_check.php";
require "../../../backend/functions.php";

$students = getDeletedStudents();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deleted | Students</title>
    <link rel="icon" href="/resources/images/favicon.svg">
    <link rel="icon" href="/resources/images/favicon.svg">
    <link rel="stylesheet" href="../css/teacherProfile.css">
    <link rel="stylesheet" href="../css/edit_teacher.css">
    <link rel="stylesheet" href="../css/modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/sidebar.js" defer></script>
    <script src="js/teachers.js" defer></script>
    <script src="/frontend/Admin/js/filter.js" defer></script>
    <script src="js/deleted_students_modal.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>
<?php include "../include/topbar.php";?>
<body>
<div class="wrapper">
    <?php include "include/student_sidebar.php";?>
    <div class="main-content">
        <div class="header-bar">
            <button id="recoverBtn" class="action-button" disabled><i class="fas fa-trash-can-arrow-up"></i> Recover Student</button>
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
                        <th><input type="checkbox" id="selectAllStudents"></th>
                        <th>Name</th>
                        <th>Roll Number</th>
                        <th>Email</th>
                        <th>Programme</th>
                        <th>Deleted Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($students as $student){
                        $sname = $student[1] . ' ' . $student[2];
                        $sID = $student[6];
                        $roll_number = $student[0];
                        $email = getAttribute('Users', 'email', 'id', $sID);
                        $pname = getAttribute('Programme', 'name', 'id', $student[4]);
                        $ddate = getAttribute('Users', 'created_date', 'id', $sID);
                        ?>
                        <tr>
                            <td><input type="checkbox" class="selectStudent" data-sID="<?php echo $sID;?>"></td>
                            <td><?php echo $sname;?></td>
                            <td><?php echo $roll_number;?></td>
                            <td><?php echo $email;?></td>
                            <td><?php echo $pname;?></td>
                            <td><?php echo $ddate;?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
</div>
<?php include "include/deleted_students_modal.php"; ?>
<p id="noResult" class="no-result" style="display: none;">No matching results found</p>
</body>
</html>