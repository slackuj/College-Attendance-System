<table class="total-attendance" id="total-attendance">
            <thead>
                <tr>
                    <th rowspan="2">Class Roll Number</th>
                    <th rowspan="2">Name</th>
                    <th rowspan="2">Exam Roll Number</th>
                    <th rowspan="2">Total Classes</th>
                    <th rowspan="2">Total Attendance</th>
                    <th rowspan="2">Total Absent Days</th>
                    <th rowspan="2">Total Leave Days</th>
                    <th rowspan="2">Attendance %</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $totalAttendance = getTotalAttendance($class);
            $count = 0;
            foreach ( $totalAttendance as $attendance){
                ++$count;
                ?>
                <tr id="<?php echo $count; ?>">
                    <td><?php echo $count;?></td>
                    <td><a href="/frontend/Admin/view_student_attendance.php?class=<?php echo $class; ?>&student=<?php echo $attendance[0]; ?>">
                            <?php echo getStudentName($attendance[0]); ?>
                        </a>
                    </td>
                    <td><?php echo $attendance[0]; ?></td>
                    <td><?php echo $attendance[1]; ?></td>
                    <td><?php echo $attendance[2]; ?></td>
                    <td><?php echo $attendance[3]; ?></td>
                    <td><?php echo $attendance[4]; ?></td>
                    <td><?php echo $attendance[5]; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
