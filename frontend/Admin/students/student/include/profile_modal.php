<div id="edit-student" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-button">&times;</span>
            <h1>Edit Student</h1>
            <h2><b>Editing <?php echo $sname;?></b></h2>
        </div>
        <span class="info">This view contains information that can be updated.</span>
        <div class="modal-body">
<form action="" method="post">
      <label for="fname">First Name</label>
      <input type="text" id="fname" name="fname" class="editStudent" placeholder="First Name" value="<?php echo $row[2]; ?>" />

      <label for="lname">Last Name</label>
      <input type="text" id="lname" name="lname" class="editStudent" placeholder="Last Name"  value="<?php echo $row[3]; ?>"/>

      <label for="email">E-mail</label>
      <input type="email" id="email" name="email" class="editStudent" placeholder="E-mail" value="<?php echo $email; ?>">

      <label for="title">Title</label>
      <input type="text" id="title"  name="title" class="editStudent" placeholder="title" value="<?php echo $title; ?>">
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

<div id="delete-student" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-button2">&times;</span>
            <h1>Deleting Class</h1>
        </div>
        <?php if (!$classes) { ?>
        <div class="deleteInfo">Do you want to delete this Student?</div>
        <div class="modal-body">
            <div class="user-details-grid">
                <div class="grid-item">
                    <div class="label">Student</div>
                    <div class="delete-info">
                        <span class="value"><?php echo $sname;?></span>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="label">Faculty</div>
                    <div><?php echo $fname?></div>
                </div>
                <div class="grid-item">
                    <div class="label">Programme</div>
                    <div><?php echo $pname;?></div>
                </div>
                <div class="grid-item">
                    <div class="label">Semester</div>
                    <div><?php echo $semester;?></div>
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
                    This student has active classes and hence cannot be deleted.
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
        <div id="modelInfo" class="deleteInfo">The student '<?php echo $email;?>' will be assigned a temporary password that must be changed on the next sign in.<br><br>Click 'Reset password'.</div>
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
