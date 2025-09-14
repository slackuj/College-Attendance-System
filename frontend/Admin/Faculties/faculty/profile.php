<?php
require_once "../../../../backend/auth_check.php";
require "../../../../backend/functions.php";

if (isset($_GET['id'])){
    $fID = $_GET['id'];
    $fname = getAttribute('aFaculty', 'name', 'id', $fID);
    $programmes = getProgrammesOf($fID);
}

// display success toast
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty</title>
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
    <?php include "include/faculty_sidebar.php";?>
    <div class="main-content">
        <div class="header-bar">
            <button id="editBtn"><i class="fas fa-edit"></i> Edit Faculty</button>
            <button id="deleteBtn"><i class="fas fa-trash-alt"></i> Delete</button>
            <!--<button><i class="fas fa-comment-dots"></i> Requests</button>-->
            <button id="refresh"><i class="fas fa-sync-alt"></i> Refresh</button>
        </div>

        <div class="profile-container">
            <nav class="profile-nav">
                <a href="#" class="active">Overview</a>
            </nav>

            <main class="profile-content">
                <?php include "include/profile_modal.php"; ?>
                <h2>Basic info</h2>
                <div class="user-info-section">
                    <div class="user-avatar">
                        <div class="avatar-circle"><?php echo substr($fname, 0, 1); ?></div>
                    </div>
                    <div class="user-details">
                        <h1><?php echo $fname; ?></h1>
                        <p>Faculty</p>
                    </div>
                </div>

                <hr>

                <div class="user-details-grid">
                    <table id="class-table" class="teachers-table">
                        <thead>
                        <tr>
                            <th>Programmes</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach ($programmes as $programme){
                            ?>
                            <tr><td><?php echo $programme[1];?></td></tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</div>
<input type="hidden" id="fID" value="<?php echo $fID;?>">
</body>
</html>