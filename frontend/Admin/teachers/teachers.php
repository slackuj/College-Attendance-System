<?php
require_once "../../../backend/auth_check.php";
require "../../../backend/functions.php";

$teachers = getTeachers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers</title>
    <link rel="icon" href="/resources/images/favicon.svg">
    <link rel="stylesheet" href="../css/teacherProfile.css">
    <link rel="stylesheet" href="../css/edit_teacher.css">
    <link rel="stylesheet" href="../css/modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/sidebar.js" defer></script>
    <script src="js/teachers.js" defer></script>
    <script src="/frontend/Admin/js/filter.js" defer></script>
    <script src="js/teachers_modal.js" defer></script>
    <script src="teacher/js/export-utility.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
</head>
<?php include "../include/topbar.php";?>
<body>
<div class="wrapper">
    <?php include "include/teacher_sidebar.php";?>
    <div class="main-content">
        <div class="header-bar">
            <button id="addNewTeacher"><i class="fas fa-plus"></i> New Teacher</button>
            <button id="bulkBtn"><i class="fas fa-file-arrow-up"></i> Bulk Create</button>
            <button id="deleteBtn" class="action-button" disabled><i class="fas fa-trash-alt"></i> Delete</button>
            <button id="exportBtn"><i class="fas fa-download"></i> Download</button>
            <button id="refresh"><i class="fas fa-sync-alt"></i> Refresh</button>
        </div>

        <div class="profile-container">
            <div class="searchBar">
                <input id="searchInput" type="text" placeholder="type to search" name="searchText">
            </div>

            <main class="profile-content">
                <h2><?php echo count($teachers);?> teachers found</h2>
                <table id="class-table" class="teachers-table">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAllTeachers"></th>
                        <th>Name</th>
                        <th>Title</th>
                        <th>Email</th>
                        <th>Classes</th>
                        <th>Created Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($teachers as $teacher){
                        $tname = $teacher[1] . ' ' . $teacher[2];
                        $title = $teacher[3];
                        $tID = $teacher[0];
                        $email = getAttribute('aUsers', 'email', 'id', $tID);
                        $cdate = getAttribute('aUsers', 'created_date', 'id', $tID);
                        $classes = totalClassesOf($tID, '1');
                        ?>
                        <tr>
                            <td><input type="checkbox" class="selectTeacher" data-tID="<?php echo $tID;?>"></td>
                            <td><a href="teacher/profile.php?id=<?php echo $tID;?>"><?php echo $tname;?></td>
                            <td><?php echo $title;?></td>
                            <td><?php echo $email;?></td>
                            <td><?php echo $classes;?></td>
                            <td><?php echo $cdate;?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
</div>
<?php include "include/teachers_modal.php"; ?>
<p id="noResult" class="no-result" style="display: none;">No matching results found</p>
</body>
</html>