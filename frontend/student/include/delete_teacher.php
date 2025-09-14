<?php
    $fname = getAttribute('aTeacher', 'fname', 'id', $tID);
    $lname = getAttribute('aTeacher', 'lname', 'id', $tID);
    ?>
<div id="delete_teacher" class="delete_modal">
    <div class="delete_modal-content">
        <div class="modal-header">
            <span id="delete_span" class="close-button">&times;</span>
            <h1>Edit <?php echo $name;?></h1>
        </div>
        <span class="info">This view contains information that can be updated.</span>
        <div class="modal-body">
            <div class="delete-user-container">
                <header class="dialog-header">
                    <h1>Deleting a user</h1>
                    <p>Deleting will remove all the user's permissions and access. The user can be reinstated up to 30 days after deletion.</p>
                </header>

                <div class="user-details">
                    <div class="details-row header-row">
                        <span class="name-label">Name</span>
                        <span class="type-label">Type</span>
                    </div>
                    <div class="details-row user-info-row">
                        <span class="name-value">Magnus Carlsen</span>
                        <span class="type-value">Member</span>
                    </div>
                </div>

                <div class="associated-assets">
                    <h2>Associated assets</h2>
                    <div class="asset-links">
                        <a href="#" class="asset-link">
                            <span class="asset-value">0 Role(s)</span>
                            <span class="asset-icon">↗</span>
                        </a>
                        <a href="#" class="asset-link">
                            <span class="asset-value">0 Group(s)</span>
                            <span class="asset-icon">↗</span>
                        </a>
                    </div>
                </div>

                <footer class="dialog-footer">
                    <button id="deleteBtn2" class="button delete-button">Delete</button>
                    <button id="cancelBtn2" class="button cancel-button">Cancel</button>
                </footer>
            </div>
<!--
      <div class="buttons">
          <div class="modal-footer">
              <button id="saveBtn" type="submit" name="edit_teacher" class="create">Save</button>
              <button id="cancelBtn" type="submit" name="cancel" class="cancel">Cancel</button>
          </div>
      </div>
    </form>-->
        </div>
    </div>
</div>