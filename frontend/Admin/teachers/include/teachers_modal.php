<?php

?>
<div id="add-teacher" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-button">&times;</span>
            <h1>Add New Teacher</h1>
        </div>
        <span class="info">Fill up first-name, last-name, email, and password to create a new teacher.</span>
        <div class="modal-body">
            <form action="" method="post" id="addTeacherForm">
                <label for="fname">First Name</label>
                <input type="text" id="fname" name="fname" class="editTeacher" placeholder="First Name" required />

                <label for="lname">Last Name</label>
                <input type="text" id="lname" name="lname" class="editTeacher" placeholder="Last Name"  required />

                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" class="editTeacher" placeholder="E-mail" required>
                <div id="email-validation-message" class="validation-message"></div>

                <label for="pass">Password <button type="button" id="generatePassBtn" class="generate-password-btn">Generate Password</button></label>
                <div class="password-input-container">
                    <input type="password" id="pass"  name="pass" class="editTeacher" placeholder="password" required>
                    <button type="button" id="togglePassBtn" class="password-toggle-btn"><span class="fa-solid fa-eye-slash"></span></button>
                </div>
                <div id="password-validation-message" class="validation-message"></div>

                <label for="confirmPass">Confirm Password</label>
                <div class="password-input-container">
                    <input type="password" id="confirmPass"  name="confirmPass" class="editTeacher" placeholder="Confirm Password" required>
                    <button type="button" id="toggleConfirmPassBtn" class="password-toggle-btn"><span class="fa-solid fa-eye-slash"></span></button>
                </div>
                <div id="confirm-password-validation-message" class="validation-message"></div>

               <!--<div class="generate-password-section">
                    <button type="button" id="generatePassBtn" class="generate-password-btn">Generate Password</button>
                </div>-->
            </form>
            <div class="buttons">
                <div class="modal-footer">
                    <button id="createBtn" class="positive-action-button" disabled>create new teacher</button>
                    <button id="cancelBtn" class="cancel negative-action-button">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="delete-teachers" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-button2">&times;</span>
            <h1>Deleting Teacher(s)</h1>
        </div>
        <div class="modal-body">
            Do you want to delete the selected teacher(s)?
        </div>
            <div class="buttons">
                <div class="modal-footer">
                    <button id="deleteBtn2" name="delete_teacher" class="create">Delete</button>
                    <button id="cancelBtn2" name="cancel" class="cancel">Cancel</button>
                </div>
            </div>
    </div>
</div>

<!--/*  M O D A L   F O R   E X P O R T I N G  C L A S S E S   */ -->
<div id="export-teachers" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-button3">&times;</span>
            <h1>Download Teachers</h1>
        </div>
        <div id="modelInfo" class="deleteInfo">Your download will be based on the filter selections you have made.</div>
        <div class="modal-body">
            <div class="exportInfo">Format</div>
            <input checked type="radio" class="exportFormat" id="format" name="file_format" value="xlsx">
            <label id="formatOption1" for="xlsx">XLSX</label>
            <br></br>
            <div class="fileHeader">File Name</div>
            <input id="fileName" value="<?php echo 'Teachers' . '_' . date('Y-m-d')?>">
                <div class="modal-footer">
                    <button id="downloadBtn" class="create" type="button">
                        Download
                    </button>
                </div>
    </div>
</div>
</div>


<div id="bulk-teachers" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-button4">&times;</span>
            <h1>Bulk Create Teachers</h1>
        </div>
        <div id="modelInfo" class="deleteInfo">
            <div>1.Download the .xlsx template (optional)</div>
            <br>
        <button id="templateBtn" class="positive-action-button" type="button">Download</button>
            <br>
            <div>2. Edit your file</div>
            <br>
            <div>3. Upload your file</div>
        </div>
        <div class="modal-body">
            <input type="file" name="fileToUpload" id="fileToUpload">
            <br><br>
            <button class="positive-action-button" id="create_bulk_teacher" disabled>Upload & Create</button>
        </div>
    </div>
</div>