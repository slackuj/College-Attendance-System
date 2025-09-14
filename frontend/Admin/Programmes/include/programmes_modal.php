<!--/*  M O D A L   F O R   E X P O R T I N G  C L A S S E S   */ -->
<div id="export-programmes" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-button">&times;</span>
            <h1>Download Programmes</h1>
        </div>
        <div id="modelInfo" class="deleteInfo">Your download will be based on the filter selections you have made.</div>
        <div class="modal-body">
            <div class="exportInfo">Format</div>
            <input checked type="radio" class="exportFormat" id="format" name="file_format" value="xlsx">
            <label id="formatOption1" for="xlsx">XLSX</label>
            <br></br>
            <div class="fileHeader">File Name</div>
            <input id="fileName" value="<?php echo 'Programmes' . '_' . date('Y-m-d')?>">
                <div class="modal-footer">
                    <button id="downloadBtn" class="create" type="button">
                        Download
                    </button>
                </div>
    </div>
</div>
</div>

<!--/*  M O D A L   F O R   C R E A T I N G   N E W  F A C U L T I E S   */ -->
<div id="create-programme" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-button2">&times;</span>
            <h1>Create New Programme</h1>
        </div>
        <div class="modal-body">
            <form action="" method="post">
                <label for="faculty">Faculty</label>
                <select id="faculty" class="createProgramme">
                    <option disabled selected>Select Faculty</option>
                    <?php $faculties = getFaculties();
                    foreach ($faculties as $faculty) { ?>
                        <option value="<?php echo $faculty[0];?>"><?php echo $faculty[1] ?></option>
                    <?php } ?>
                </select>
                <label for="pname">Programme</label>
                <input id="pname" type="text" class="createProgramme" placeholder="Programme Name" disabled>
            </form>
      <div class="buttons">
          <div class="modal-footer">
              <button id="createBtn" class="positive-action-button" disabled>Create</button>
              <button id="cancelBtn" class="negative-action-button">Cancel</button>
          </div>
      </div>
        </div>
    </div>
</div>

<!--   M O D A L    F O R    B U L K    C R E A T I O N    --->
<div id="bulk-programmes" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-button3">&times;</span>
            <h1>Bulk Create Programmes</h1>
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