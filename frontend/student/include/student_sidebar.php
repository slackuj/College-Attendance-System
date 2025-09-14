<div class="sidebar">
    <div class="user-info-section">
        <div class="user-avatar">
            <div class="avatar-circle">
                <?php echo substr($sname, 0, 1); ?>
            </div>
        </div>
        <div class="user-details">
            <h1 class="user-name"><?php echo $sname; ?></h1>
            <p class="email"><?php echo $email; ?></p>
        </div>
    </div>
    <hr>
    <ul>
        <li id="side-1"><a href="profile.php"><img src="../../../../../view/resources/Dashboard.svg" alt="Dashboard Icon">Overview</a> </li>
        <li id="side-2"><a href="attendance.php"><img src="../../../../../view/resources/Take%20attendance.svg" alt="Take Attendance Icon">Attendance</a></li>
    </ul>
</div>