<?php
require_once "../../../backend/auth_check.php";
require "../../../backend/functions.php";

$subjects = getSubjects();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects</title>
    <link rel="icon" href="/resources/images/favicon.svg">
    <link rel="stylesheet" href="../css/teacherProfile.css">
    <link rel="stylesheet" href="../css/edit_teacher.css">
    <link rel="stylesheet" href="../css/modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/sidebar.js" defer></script>
    <script src="js/subjects_modal.js" defer></script>
    <script src="/frontend/Admin/js/filter.js" defer></script>
    <script src="js/subjects.js" defer></script>
    <script src="../teachers/teacher/js/export-utility.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
</head>
<?php include "../include/topbar.php";?>
<body>
<div class="wrapper">
    <?php include "include/subjects_sidebar.php";?>
    <div class="main-content">
        <div class="header-bar">
            <button id="addNewSubjectBtn"><i class="fas fa-plus"></i> New Subject</button>
            <button id="bulkBtn"><i class="fas fa-file-arrow-up"></i> Bulk Create</button>
            <button id="exportBtn"><i class="fas fa-download"></i> Download</button>
            <button id="refresh"><i class="fas fa-sync-alt"></i> Refresh</button>
        </div>

        <div class="profile-container">
            <div class="searchBar">
                <input id="searchInput" type="text" placeholder="type to search" name="searchText">
            </div>

            <main class="profile-content">
                <h2><?php echo count($subjects);?> subjects found</h2>
                <table id="class-table" class="teachers-table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Programme</th>
                        <th>Faculty</th>
                        <th>Semester</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($subjects as $subject){
                        $sID = $subject[0];
                        $pID = $subject[2];
                        $fID = getAttribute('aProgramme', 'faculty', 'id', $pID);
                        $pname = getAttribute('aProgramme', 'name', 'id', $pID);
                        $fname = getAttribute('aFaculty', 'name', 'id', $fID);
                        ?>
                        <tr>
                            <td><a href="subject/profile.php?id=<?php echo $sID;?>"><?php echo $subject[1];?></a></td>
                            <td><?php echo $pname;?></td>
                            <td><?php echo $fname;?></td>
                            <td><?php echo $subject[3];?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
</div>
<?php include "include/subjects_modal.php"; ?>
</body>
</html>