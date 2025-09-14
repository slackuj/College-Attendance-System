<?php
    if ($row[4] == 0){ ?>
<div id="reassign_class" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-button">&times;</span>
            <h1>Reassigning Class</h1>
        </div>
        <span class="info">Choose a teacher that you want to assign to this class. This will remove the access of current teacher to this class. All permissions and access to this class shall be assigned to the new teacher.</span>
        <div class="modal-body">
                <div class="table-wrapper">
                    <table class="teachers-table">
                        <thead><tr>
                            <th></th>
                            <th>Name</th>
                            <th>Title</th>
                        </tr></thead>
                        <tbody>
                            <?php $count = 0;
                                $teachers = getContentsOf("aTeacher");
                                $i = count($teachers);// count for iteration
                                for ($j = 0; $j < $i; ++$j){
                                    if ($row[2] === $teachers[$j][0]) continue;
                                    ++$count;
                                    $name = $teachers[$j][1] . ' ' . $teachers[$j][2];
                            ?>
                            <tr>
                                <td><input type="radio" name="teacherOptions" class="teacherOptions" value="<?php echo $teachers[$j][0];?>" </td>
                                <td><?php echo $name;?></a></td>
                                <td><?php echo getAttribute('aTeacher', 'title', 'id', $teachers[$j][0]);?></td>
                            </tr>
                        <?php } ?>
                        <input type="hidden" id="cID" value="<?php echo $cID;?>">
                        </tbody>
                    </table>
                </div>
      <div class="buttons">
          <div class="modal-footer">
              <button id="assignBtn" class="positive-action-button" disabled>Assign</button>
              <button id="cancelBtn" class="cancel negative-action-button">Cancel</button>
          </div>
      </div>
        </div>
    </div>
</div>
<?php } else if ($row[4] == -1){ ?>
    <div id="reassign_class" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close-button">&times;</span>
                <h1>Assigning Class</h1>
            </div>
            <span class="info">Choose a teacher that you want to assign to this class.</span>
            <div class="modal-body">
                <div class="table-wrapper">
                    <table class="teachers-table">
                        <thead><tr>
                            <th></th>
                            <th>Name</th>
                            <th>Title</th>
                        </tr></thead>
                        <tbody>
                        <?php $count = 0;
                        $teachers = getContentsOf("aTeacher");
                        $i = count($teachers);// count for iteration
                        for ($j = 0; $j < $i; ++$j){
                            ++$count;
                            $name = $teachers[$j][1] . ' ' . $teachers[$j][2];
                            ?>
                            <tr>
                                <td><input type="radio" name="teacherOptions" class="teacherOptions" value="<?php echo $teachers[$j][0];?>" </td>
                                <td><?php echo $name;?></a></td>
                                <td><?php echo getAttribute('aTeacher', 'title', 'id', $teachers[$j][0]);?></td>
                            </tr>
                        <?php } ?>
                        <input type="hidden" id="cID" value="<?php echo $cID;?>">
                        </tbody>
                    </table>
                </div>
                <div class="buttons">
                    <div class="modal-footer">
                        <button id="assignBtn" class="positive-action-button" disabled>Assign</button>
                        <button id="cancelBtn" class="cancel negative-action-button">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<!--/*  M O D A L   F O R   D E L E T I N G   T E A C H E R  */ -->

<div id="delete-class" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-button2">&times;</span>
            <h1>Deleting Class</h1>
        </div>
        <?php if (!$students) { ?>
        <div class="deleteInfo">Do you want to delete this Class?<br>Deleted classes become unassigned when recovered.</div>
        <div class="modal-body">
            <div class="user-details-grid">
                <div class="grid-item">
                    <div class="label">Class</div>
                    <div class="delete-info">
                        <span class="value"><?php echo $sname;?></span>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="label">Lecturer</div>
                    <div><?php echo $tname?></div>
                </div>
                <div class="grid-item">
                    <div class="label">Programme</div>
                    <div><?php echo $pname;?></div>
                </div>
                <div class="grid-item">
                    <div class="label"><?php echo $dateHeader;?></div>
                    <div><?php echo $cdate;?></div>
                </div>
            </div>
            <div class="buttons">
                <div class="modal-footer">
                    <button id="deleteBtn2" class="positive-action-button">Delete</button>
                    <button id="cancelBtn2" class="negative-action-button">Cancel</button>
                </div>
            </div>
            <?php } else { ?>
                <div class='error-message'><span class='fa-solid fa-circle-exclamation'></span>
                    This class has active students and therefore it cannot be deleted.
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<!--/*  M O D A L   F O R   R E S E T T I N G   P A S S W O R D   */ -->

<div id="reset_password" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-button3">&times;</span>
            <h1>Reset Password</h1>
            <h3><?php echo $name;?></h3>
        </div>
        <div id="modelInfo" class="deleteInfo">The teacher '<?php echo $email;?>' will be assigned a temporary password that must be changed on the next sign in.<br><br>Click 'Reset password'.</div>
        <div class="modal-body">
            <div class="password-reset-section">
                <div class="modal-footer">
                    <button id="resetPasswordBtn" class="reset-password-btn" type="button">
                        Reset Password
                    </button>
                </div>
                <div id="password-display-container" class="password-display-container">
                    <div class="new-password">
                        <span id="new-password-text"></span>
                        <i id="passwordCopy" class="fas fa-copy copy-icon"></i>
                    </div>
                </div>
            </div>
    </div>
</div>
</div>
