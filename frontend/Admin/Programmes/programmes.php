<?php
require_once "../../../backend/auth_check.php";
require "../../../backend/functions.php";

$programmes = getProgrammes();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programmes</title>
    <link rel="icon" href="/resources/images/favicon.svg">
    <link rel="stylesheet" href="../css/teacherProfile.css">
    <link rel="stylesheet" href="../css/edit_teacher.css">
    <link rel="stylesheet" href="../css/modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/sidebar.js" defer></script>
    <script src="/frontend/Admin/js/filter.js" defer></script>
    <script src="js/programmes_modal.js" defer></script>
    <script src="js/programmes.js" defer></script>
    <script src="../teachers/teacher/js/export-utility.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
</head>
<?php include "../include/topbar.php";?>
<body>
<div class="wrapper">
    <?php include "include/programmes_sidebar.php";?>
    <div class="main-content">
        <div class="header-bar">
            <button id="addNewPrgrmBtn"><i class="fas fa-plus"></i> New Programme</button>
            <button id="exportBtn"><i class="fas fa-download"></i> Download</button>
            <button id="bulkBtn"><i class="fas fa-file-arrow-up"></i> Bulk Create</button>
            <button id="refresh"><i class="fas fa-sync-alt"></i> Refresh</button>
        </div>

        <div class="profile-container">
            <div class="searchBar">
                <input id="searchInput" type="text" placeholder="type to search" name="searchText">
            </div>

            <main class="profile-content">
                <h2><?php echo count($programmes);?> programmes found</h2>
                <table id="class-table" class="teachers-table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Faculty</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($programmes as $programme){
                        $pID = $programme[0];
                        $fname = getAttribute('aFaculty', 'name', 'id', $programme[2]);
                        ?>
                        <tr>
                            <td><a href="programme/profile.php?id=<?php echo $pID;?>"><?php echo $programme[1];?></a></td>
                            <td><?php echo $fname;?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
</div>
<?php include "include/programmes_modal.php"; ?>
</body>
</html>