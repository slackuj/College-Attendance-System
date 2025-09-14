<?php
    $fname = getAttribute('aTeacher', 'fname', 'id', $tID);
    $lname = getAttribute('aTeacher', 'lname', 'id', $tID);

    ?>


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
