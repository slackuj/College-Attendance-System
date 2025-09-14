<!--/*  M O D A L   F O R    C H A N G I N G   P A S S W O R D   */ -->

<div id="change-password" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-button">&times;</span>
            <h1>Change Password</h1>
        </div>
        <div class="modal-body">
            <form class="form" action="" method="post">
                <p>Create a new password that is at least 6 characters long.
                    Your password must be at least 6 characters and should include a combination of numbers, letters and special characters (!$@%).</p>
                <label for="confirmPass">Current Password</label>
                <div class="password-input-container">
                    <input type="password" id="currentPass" class="changePassword" placeholder="Current password" required>
                    <button type="button" id="toggleCurrentPassBtn" class="password-toggle-btn"><span class="fa-solid fa-eye-slash"></span></button>
                </div>
                <div id="current-password-validation-message" class="validation-message">Current password is incorrect. Please try again.</div>

                <label for="confirmPass">New Password</label>
                <div class="password-input-container">
                    <input type="password" id="newPass" class="changePassword" placeholder="New password" required>
                    <button type="button" id="toggleNewPassBtn" class="password-toggle-btn"><span class="fa-solid fa-eye-slash"></span></button>
                </div>
                <div id="new-password-validation-message" class="validation-message"></div>

                <label for="confirmPass">Confirm Password</label>
                <div class="password-input-container">
                    <input type="password" id="confirmPass" class="changePassword" placeholder="Confirm Password" required>
                    <button type="button" id="toggleConfirmPassBtn" class="password-toggle-btn"><span class="fa-solid fa-eye-slash"></span></button>
                </div>
                <div id="confirm-password-validation-message" class="validation-message"></div>
            <div class="buttons">
                <div class="modal-footer">
                    <button id="changePassBtn" class="positive-action-button" disabled>Change password</button>
                </div>
            </div>
            </form>
            </div>
    </div>
</div>