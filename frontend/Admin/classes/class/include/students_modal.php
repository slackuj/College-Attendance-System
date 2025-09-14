<div id="addStudents" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-button">&times;</span>
            <h1>Add Students to Class</h1>
            <?php
            $eligible_students = getEligibleStudents($pID, $semester);
            $count = 0;
            foreach ( $eligible_students as $eligible_student) {
                if ($students != null) {
                    if (in_array($eligible_student[0], $students))
                        // skip if student is already in class!
                        continue;
                }
                ++$count;
            }
            if ($count > 0) {
            ?>
        </div>
        <span class="info">Select Students to Add them to the Class.</span>
        <div class="modal-body">

            <div class="table-wrapper">
                <table id="eligibleStudents" class="teachers-table">
                    <thead>
                    <tr >
                        <!--<th>Class Roll Number</th>-->
                        <th><input type="checkbox" id="selectAllStudents"></th>
                        <th>Student Name</th>
                        <th>Exam Roll Number</th>
                    <tr>
                    </thead>
                    <tbody>
                <?php
                $eligible_students = getEligibleStudents($pID, $semester);
                foreach ( $eligible_students as $eligible_student){
                    if($students != null){
                        if (in_array($eligible_student[0], $students))
                            // skip if student is already in class!
                            continue;
                        }
                            $name = $eligible_student[1] . ' ' . $eligible_student[2];
                            ?>
                        <tr>
                            <td><input type="checkbox" class="selectStudent" data-roll="<?php echo $eligible_student[0];?>"></td>
                            <td><label><?php echo $name; ?></label></td>
                            <td><label><?php echo $eligible_student[0];?></label></td>
                        </tr>
                <?php } ?>
                    </tbody>
                </table>
            </div>
                <div class="buttons">
                    <div class="modal-footer">
                        <button id="addBtn" class="positive-action-button" disabled>Add</button>
                        <button id="cancelBtn" class="negative-action-button">Cancel</button>
                    </div>
                </div>
            <?php }
            else{ ?>
                <div class='info-message'><span class='fa-solid fa-circle-info'></span>
                    There are no eligible students to be added into this class !
                </div>
            <?php } ?>

        </div>
    </div>
</div>

<div id="removeStudents" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-button2">&times;</span>
            <h1>Removing Students</h1>
        </div>
        <div class="deleteInfo">Remove the selected students from the class ?</div>
        <div class="modal-body">
            <div class="buttons">
                <div class="modal-footer">
                    <button id="deleteBtn2" name="delete_teacher" class="positive-action-button">Remove</button>
                    <button id="cancelBtn2" name="cancel" class="negative-action-button">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>