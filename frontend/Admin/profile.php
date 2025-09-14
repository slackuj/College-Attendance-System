<?php
require_once "../../backend/auth_check.php";
require "../../backend/functions.php";

if (isset($_SESSION['id'])){
    $faculties = count(getFaculties());
    $programmes = count(getProgrammes());
    $teachers = count(getTeachers());
    $classes = count(getClasses());
    $subjects = count(getSubjects());
    $students = count(getStudents());
}
else{
    echo "Error: No valid Session found";
    exit();
}

// display success toast
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="icon" href="/resources/images/favicon.svg">
    <link rel="stylesheet" href="css/teacherProfile.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/edit_teacher.css">
    <!--<link rel="stylesheet" href="css/new_password.css">-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!--<script src="js/profile_modal.js" defer></script>-->
    <script src="teachers/teacher/js/classes.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>
<?php include "include/topbar.php";?>
<body>
    <div class="main-content">
        <!--<div class="header-bar">
            <button id="changeBtn"><i class="fas fa-key"></i> Change password</button>
            <button id="refresh"><i class="fas fa-sync-alt"></i> Refresh</button>
        </div>-->
        <div class="profile-container">
            <nav class="profile-nav">
                <a href="#" class="active">Overview</a>
            </nav>

            <main class="profile-content">

                <div class="user-details-grid">
                    <div class="grid-item">
                        <div class="label">Faculties</div>
                        <div class="value-with-icon">
                            <span id="teacher-email" class="value-link"><?php echo $faculties; ?></span>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="label">Programmes</div>
                        <div class="value-link"><?php echo $programmes;?></div>
                    </div>
                    <div class="grid-item">
                        <div class="label">Subjects</div>
                        <div class="value-with-icon">
                            <span class="value-link"><?php echo $subjects;?></span>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="label">Classes</div>
                        <div class="value-with-icon">
                            <span class="value-link"><?php echo $classes;?></span>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="label">Teachers</div>
                        <div class="value-link"><?php echo $teachers;?></div>
                    </div>
                    <div class="grid-item">
                        <div class="label">Students</div>
                        <div class="value-link"><?php echo $students;?></div>
                    </div>
                </div>
            </main>
        </div>

                <div class="actions">
                        <div class="action-box" onclick="window.location.href = 'teachers/teachers.php';">
                            <div class="action-icon"><img src="../../resources/images/current_semester.svg" alt="Edit Teacher"></div>
                            <div class="action-label">Teachers</div>
                        </div>
                        <div class="action-box" onclick="window.location.href = 'classes/classes.php';">
                            <div class="action-icon"><img src="../../resources/images/add.svg" alt="Edit class"></div>
                            <div class="action-label">Classes</div>
                        </div>
                        <div class="action-box" onclick="window.location.href = 'http://localhost:8000/frontend/Admin/Subjects/subjects.php';">
                            <div class="action-icon"><img src="../../resources/images/subject.svg" alt="Edit Subject "></div>
                            <div class="action-label">Subjects</div>
                        </div>
                </div>
        <div class="actions">
                        <div class="action-box" onclick="window.location.href = 'http://localhost:8000/frontend/Admin/students/students.php';">
                            <div class="action-icon"><img src="../../resources/images/student.svg" alt="Add Student"></div>
                            <div class="action-label">Students</div>
                        </div>
                        <div class="action-box" onclick="window.location.href = 'http://localhost:8000/frontend/Admin/Faculties/faculties.php';">
                            <div class="action-icon"><img src="../../resources/images/previous_semester.svg" alt="Add Faculty"></div>
                            <div class="action-label">Faculties</div>
                        </div>
                        <div class="action-box" onclick="window.location.href = 'http://localhost:8000/frontend/Admin/Programmes/programmes.php';">
                            <div class="action-icon"><img src="../../resources/images/programme.svg" alt="Add Program"></div>
                            <div class="action-label">Programmes</div>
                        </div>
                </div>
        </div>
    <?php //include "include/profile_modal.php";?>
</body>
</html>