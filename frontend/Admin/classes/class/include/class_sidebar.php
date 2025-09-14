<div class="sidebar">
    <div class="user-info-section">
        <div class="user-avatar">
            <div>
                <img src="/resources/images/favicon.svg" alt="appIcon">
            </div>
        </div>
        <div class="user-details">
            <h1 class="user-name"><?php echo $sname;?></h1>
            <p class="email"><?php echo $pname;?></p>
            <p class="email"><?php echo 'Semester: ' . $semester;?></p>
        </div>
    </div>
    <hr>
    <ul>
        <li id="side-1"><a href="class.php?id=<?php echo $cID;?>"><img src="../../../../../view/resources/Dashboard.svg" alt="Dashboard Icon">Overview</a> </li>
        <li id="side-2"><a href="students.php?id=<?php echo $cID;?>"><img src="../../../../../view/resources/Dashboard.svg" alt="Dashboard Icon">Students</a> </li>
        <li id="side-3"><a href="attendance.php?id=<?php echo $cID;?>"><img src="../../../../../view/resources/Take attendance.svg" alt="Take Attendance Icon">Attendance</a></li>
        <li id="side-4"><a href="attendance_report.php?id=<?php echo $cID;?>"><img src="../../../../../view/resources/Take attendance.svg" alt="Take Attendance Icon">Attendance Report</a></li>
    </ul>
</div>