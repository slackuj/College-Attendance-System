<div id="recover-teachers" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-button">&times;</span>
            <h1>Recovering Teacher(s)</h1>
        </div>
        <div class="modal-body">
            Do you want to recover the selected teacher(s)?
        </div>
            <div class="buttons">
                <div class="modal-footer">
                    <button id="recoverBtn2" class="positive-action-button">Recover</button>
                    <button id="cancelBtn" class="negative-action-button">Cancel</button>
                </div>
            </div>
    </div>
</div>

<!--/*  M O D A L   F O R   D E L E T I N G    T E A C H E R S    P E R M A N E N T L Y   */ -->

<div id="delete-teachers-permanently" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-button2">&times;</span>
            <h1>Permanently Deleting Teacher(s)</h1>
        </div>
        <div class="modal-body">
            All data for selected teachers will be irrevocably deleted. Are you sure you want to continue?
        </div>
        <div class="buttons">
            <div class="modal-footer">
                <button id="deleteBtn" class="positive-action-button">Delete Permanently</button>
                <button id="cancelBtn2" class="negative-action-button">Cancel</button>
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