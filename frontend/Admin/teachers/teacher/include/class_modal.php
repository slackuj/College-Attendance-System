<?php
    $fname = getAttribute('aTeacher', 'fname', 'id', $tID);
    $lname = getAttribute('aTeacher', 'lname', 'id', $tID);

    ?>

<!--/*  M O D A L   F O R   E X P O R T I N G  C L A S S E S   */ -->
<div id="export-classes" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-button">&times;</span>
            <h1>Download Classes</h1>
        </div>
        <div id="modelInfo" class="deleteInfo">Your download will be based on the filter selections you have made.</div>
        <div class="modal-body">
            <div class="exportInfo">Format</div>
            <input checked type="radio" class="exportFormat" id="format" name="file_format" value="xlsx">
            <label id="formatOption1" for="xlsx">XLSX</label>
            <br></br>
            <div class="fileHeader">File Name</div>
            <input id="fileName" value="<?php echo $name . '_' . 'Classes' . '_' . date('Y-m-d')?>">
                <div class="modal-footer">
                    <button id="downloadBtn" class="create" type="button">
                        Download
                    </button>
                </div>
    </div>
</div>
</div>

<!--/*  M O D A L   F O R   C R E A T I N G   N E W  C L A S S E S   */ -->
<div id="create-class" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-button2">&times;</span>
            <h1>Add New Class</h1>
        </div>
        <span class="info">Choose programme, semester, and subject to create new class for the teacher.</span>
        <div class="modal-body">
            <form action="" method="post">

      <label for="programme">Programme</label>
<select id="programme" class="createCLass">
        <option disabled selected>Select Programme</option>
                <?php $programmes = getProgrammes();
                foreach ($programmes as $programme) {
                  ?>
              <option value="<?php echo $programme[0];?>"><?php echo $programme[1]; ?></option>
                <?php } ?>
           </select>

      <label for="semester">Semester</label>
      <select id="semester" disabled class="createCLass">
          <option disabled selected>Select Semester</option>
      </select>

      <label for="subject">Subject Name</label>
      <select id="subject" name="sname" disabled class="createCLass">
          <option disabled selected>Select Subject</option>
          </select>
                <label for="teacher">Teacher Name</label>
                <input id="teacher" type="text" value="<?php echo $name;?>" disabled>
                <input  type="hidden" id="tID" value="<?php echo $tID;?>">
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
