<div id="take-attendance" class="modal">
    <div class="attendance-modal-content">
        <div class="modal-header">
            <span class="close-button">&times;</span>
            <h1>Take Attendance</h1>
        </div>
        <br>
        <span class="info">Mark students as present, absent, or leave to take their attendance.</span>
        <div class="modal-body">
            <div class="table-wrapper">
                <?php if ($students != null){ ?>
                <table id="take-attendance-table" class="teachers-table">
                    <thead>
                    <tr>
                        <th>Class Roll Number</th>
                        <th>Name</th>
                        <th>Exam Roll Number</th>
                        <th>P</th>
                        <th>A</th>
                        <th>L</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                            $count = 0;
                            foreach ($students as $student) {
                                ++$count;?>
                        <tr>
                            <td><label><?php echo $count;?></label></td>
                            <td><label><?php echo getStudentName($student);?></label></td>
                            <td><label><?php echo $student;?></label></td>
                            <td>
                                <input type="checkbox" class="attendance-checkbox present-status" id="present-<?php echo $student;?>" name="<?php echo $student;?>" checked>
                                <label for="present-<?php echo $student;?>"></label>
                            </td>
                            <td>
                                <input type="checkbox" class="attendance-checkbox absent-status" id="absent-<?php echo $student;?>" name="<?php echo $student;?>">
                                <label for="absent-<?php echo $student;?>"></label>
                            </td>
                            <td>
                                <input type="checkbox" class="attendance-checkbox leave-status" id="leave-<?php echo $student;?>" name="<?php echo $student;?>">
                                <label for="leave-<?php echo $student;?>"></label>
                            </td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
            </div>
            <div class="buttons">
                <div class="modal-footer">
                    <button id="takeAttendance" class="positive-action-button">Save</button>
                    <button id="cancelBtn" class="negative-action-button">Cancel</button>
                </div>
            </div>
            <?php }
            else{ ?>
                <div class='info-message'><span class='fa-solid fa-circle-info'></span>
                    There are no students in this class !
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<div id="update-attendance" class="modal">
    <div class="attendance-modal-content">
        <div class="modal-header">
            <span class="close-button2">&times;</span>
            <h1>Update Attendance</h1>
        </div>
        <br>
        <span class="info">Mark students as present, absent, or leave to update their attendance.</span>
        <div class="modal-body">
            <div class="table-wrapper">
                <table id="update-attendance-table" class="teachers-table">
                    <thead>
                    <tr>
                        <th>Class Roll Number</th>
                        <th>Name</th>
                        <th>Exam Roll Number</th>
                        <th>P</th>
                        <th>A</th>
                        <th>L</th>
                    </tr>
                    </thead>
                    <tbody>
                        </tbody>
                    </table>
            </div>
            <div class="buttons">
                <div class="modal-footer">
                    <button id="updateAttendance" class="positive-action-button">Save</button>
                    <button id="cancelBtn2" class="negative-action-button">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>