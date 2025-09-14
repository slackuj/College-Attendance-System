<?php

    ?>
<div id="edit-faculty" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-button">&times;</span>
            <h1>Edit Faculty</h1>
            <h2><b>Editing <?php echo $fname;?></b></h2>
        </div>
        <span class="info">This view contains information that can be updated.</span>
        <div class="modal-body">
<form action="" method="post">
      <label for="fname">Faculty</label>
      <input type="text" id="fname" value="<?php echo $fname; ?>" />
</form>
      <div class="buttons">
          <div class="modal-footer">
              <button id="saveBtn" class="positive-action-button" disabled>Save</button>
              <button id="cancelBtn" class="cancel negative-action-button">Cancel</button>
          </div>
      </div>
        </div>
    </div>
</div>

<!--/*  M O D A L   F O R   D E L E T I N G   T E A C H E R  */ -->

<div id="delete_teacher" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-button2">&times;</span>
            <h1>Deleting <?php echo $name;?></h1>
            <h2><b>Deleting a teacher</b></h2>
        </div>
        <div class="deleteInfo">Deleting will remove all the teacher's permissions and access. The
            teacher's data is kept safe and teacher can be reinstated later after deletion.</div>
        <div class="modal-body">
            <div class="user-details-grid">
                <div class="grid-item">
                    <div class="label">Teacher</div>
                    <div class="delete-info">
                        <span class="value"><?php echo $name;?></span>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="label">Title</div>
                    <div><?php echo $title?></div>
                </div>
                <div class="grid-item">
                    <div class="label">Assigned Classes</div>
                    <div id="tClasses" class="value-link2"><?php echo $classes;?></div>
                </div>
            </div>
            <div class="buttons">
                <div class="modal-footer">
                    <button id="deleteBtn2" name="delete_teacher" class="create">Delete</button>
                    <button id="cancelBtn2" name="cancel" class="cancel">Cancel</button>
                </div>
            </div>
            <?php if ($classes) { ?>
                <div class='warning-message'><span class='fa-solid fa-triangle-exclamation'></span>
                    This teacher has assigned classes. Please reassign the classes before deleting the teacher, or they will be unassigned.
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
