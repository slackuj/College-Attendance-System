<?php
require_once "../../../backend/auth_check.php";
require "../../../backend/functions.php";

$classes = getUnassignedClasses();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classes</title>
    <link rel="icon" href="/resources/images/favicon.svg">
    <link rel="stylesheet" href="../css/teacherProfile.css">
    <link rel="stylesheet" href="../css/edit_teacher.css">
    <link rel="stylesheet" href="../css/modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/sidebar.js" defer></script>
    <script src="js/classes.js" defer></script>
    <script src="/frontend/Admin/js/filter.js" defer></script>
    <script src="js/unassigned_classes.js" defer></script>
    <script src="../teachers/teacher/js/export-utility.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>
<?php include "../include/topbar.php";?>
<body>
<div class="wrapper">
    <?php include "include/classes_sidebar.php";?>
    <div class="main-content">
        <div class="header-bar">
            <button id="exportBtn"><i class="fas fa-download"></i> Download</button>
            <button id="refresh"><i class="fas fa-sync-alt"></i> Refresh</button>
        </div>

        <div class="profile-container">
            <div class="searchBar">
                <input id="searchInput" type="text" placeholder="type to search" name="searchText">
    </div>

            <main class="profile-content">
                <?php include "include/unassigned-classes-modal.php"; ?>
                <h2><?php echo count($classes); ?> classes found</h2>
                <table id="class-table" class="teachers-table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Programme</th>
        <th>Semester</th>
        <th>Students</th>
        <th>Unassigned Date</th>
      </tr>
    </thead>
    <tbody>
       <?php
          $count = 0;
          foreach ($classes as $class){
              ++$count;
              $row = getRow('aSubject', 'id', $class[1]);
              $pname = getAttribute('aProgramme', 'name', 'id', $row[2]);
              $students = count(getStudentsRollNumbersOfClass($class[0]));
            ?>
            <tr id="<?php echo $count ; ?>">
                <td><a href="/frontend/Admin/classes/class/class.php?id=<?php echo $class[0];?>"><?php echo $row[1];?></a></td>
                <td><?php echo $pname;?></td>
                <td><?php echo $row[3];?></td>
                <td><?php echo $students;?></td>
                <td><?php echo $class[3];?></td>
            </tr>
          <?php } ?>
     </tbody>
  </table>
            </main>
        </div>
    </div>
</div>
<p id="noResult" class="no-result" style="display: none;">No matching results found</p>
</body>
</html>