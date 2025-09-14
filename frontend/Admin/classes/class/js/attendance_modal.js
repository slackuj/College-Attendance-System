// 1. Get the stored toast data
const storedToastData = sessionStorage.getItem('toastMessage');

if (storedToastData) {
    try {
        // 2. Parse the JSON data back into a JavaScript object
        const toastData = JSON.parse(storedToastData);

        // 3. Display the toast based on the stored data
        toastr.options = {
            "positionClass": "toast-bottom-right",
            "closeButton": true,
            "timeOut": "3000"
        };
        toastr[toastData.type](toastData.message, toastData.title);

    } catch (e) {
        console.error("Error parsing toast data:", e);
    }

    // 4. IMPORTANT: Remove the data from sessionStorage so it doesn't show again
    sessionStorage.removeItem('toastMessage');
}

//const cID = document.getElementById('cID').value;


// Get the modal
    var modal = document.getElementById("take-attendance");
    var modal2 = document.getElementById("update-attendance");

// Get the button that opens the modal
    var btn = document.getElementById("takeAttendanceBtn");
    var btn4 = document.getElementById("updateAttendanceBtn");

// Get the <span> element that closes the modal
   var span = document.getElementsByClassName("close-button")[0];
   var span2 = document.getElementsByClassName("close-button2")[0];
   var btn2 = document.getElementById("takeAttendance");
   var btn5 = document.getElementById("cancelBtn");
   var btn3 = document.getElementById("updateAttendance");
   var btn6 = document.getElementById("cancelBtn2");

// When the user clicks on the button, open the modal
    if (btn) {
        btn.onclick = function () {
            modal.style.display = "block";
        }
    }
    if (btn4) {
        btn4.onclick = function () {
            modal2.style.display = "block";
        }
    }
// When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        toastr.remove();
        modal.style.display = "none";
    }
    span2.onclick = function () {
        toastr.remove();
        modal2.style.display = "none";
    }
    if(btn6) {
        btn6.onclick = function () {
        modal2.style.display = "none";
        }
    }
    btn5.onclick = function () {
        modal.style.display = "none";
    }

   /* btn6.onclick = function () {
        toastr.remove();
        toastr.options = {};
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.options.progressBar = true;
        const title = `Deleting Teacher`;
        const message = `Deleting ${tName}`;
        toastr.info(message, title);

        // fetc api to delete Teacher
        deleteTeacher();
        //modal2.style.display = "none";
    }
    */

// When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target === modal || event.target === modal2) {
            modal.style.display = "none";
            modal2.style.display = "none";
            toastr.remove();
        }
    }


// F E T C H    A P I   C A L L   T O  T A K E    A T T E N D A N C E

btn2.addEventListener('click', async () => {

// 1. Show loading state
    btn2.classList.add('loading');
    btn2.disabled = true;
    btn5.disabled = true;

    // display toast message
    toastr.remove();
    toastr.options = {};
    toastr.options.positionClass = 'toast-bottom-right';
    toastr.options.progressBar = true;
    toastr.options.timeOut = 2000;
    const message = `Saving Attendance`;
    toastr.info(message);

    //console.log(cID);
    //console.log(tID);

    // make API call to backend for updating teacher
    try {
        const tData = {
            cID: cID, // from calendar attendance.js
            attendanceData: collectAttendanceData('take-attendance-table'),
            date: DAY // from calendar attendance.js
        };

        const response = await fetch('/backend/api/take_attendance.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(tData)
        });

        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                // 3. Add an artificial delay to show a loading state for longer
                await new Promise(resolve => setTimeout(resolve, 1000));

                // 4. Hide loading state and display success message
                btn2.classList.remove('loading');
                btn2.disabled = false;
                btn5.disabled = false;

                toastr.remove();
                modal.style.display = 'none';

                // 1. Create a data object for the toast
                const toastData = {
                    type: 'success',
                    title: 'Attendance Taken!',
                    message: `Attendance saved successfully.`
                };

                // 2. Store the data as a JSON string in sessionStorage
                sessionStorage.setItem('toastMessage', JSON.stringify(toastData));

                window.location.href = `/frontend/Admin/classes/class/attendance.php?id=${cID}&day=${DAY}`;
            } else {
                // Handle API-specific errors
                btn2.classList.remove('loading');
                btn2.disabled = false;
                btn5.disabled = false;
                console.error("failed taking attendance:", data.error);
                toastr.remove();
                toastr.options = {
                    "positionClass": "toast-bottom-right",
                    "closeButton": true,
                    "timeOut": "5000"
                };
                toastr.error(`${data.error}!`, 'Failed');
            }
        } else {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

    } catch (error) {
        // Handle any errors
        console.error('Error during fetch:', error);
    }
});

btn3.addEventListener('click', async () => {

// 1. Show loading state
    btn3.classList.add('loading');
    btn3.disabled = true;
    btn6.disabled = true;

    // display toast message
    toastr.remove();
    toastr.options = {};
    toastr.options.positionClass = 'toast-bottom-right';
    toastr.options.progressBar = true;
    toastr.options.timeOut = 2000;
    const message = `Updating Attendance`;
    toastr.info(message);

    //console.log(cID);
    //console.log(tID);

    // make API call to backend for updating teacher
    try {
        const tData = {
            cID: cID,
            attendanceData: collectAttendanceData('update-attendance-table'),
            date: DAY // from attendance.js
        };

        const response = await fetch('/backend/api/update_attendance.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(tData)
        });

        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                // 3. Add an artificial delay to show a loading state for longer
                await new Promise(resolve => setTimeout(resolve, 1000));

                // 4. Hide loading state and display success message
                btn3.classList.remove('loading');
                btn3.disabled = false;
                btn6.disabled = false;

                toastr.remove();
                modal.style.display = 'none';

                // 1. Create a data object for the toast
                const toastData = {
                    type: 'success',
                    title: 'Attendance Updated!',
                    message: `Attendance has been updated successfully.`
                };

                // 2. Store the data as a JSON string in sessionStorage
                sessionStorage.setItem('toastMessage', JSON.stringify(toastData));

                window.location.href = `/frontend/Admin/classes/class/attendance.php?id=${cID}&day=${DAY}`;
            } else {
                // Handle API-specific errors
                btn3.classList.remove('loading');
                btn3.disabled = false;
                btn6.disabled = false;
                console.error("failed updating attendance:", data.error);
                toastr.remove();
                toastr.options = {
                    "positionClass": "toast-bottom-right",
                    "closeButton": true,
                    "timeOut": "5000"
                };
                toastr.error(`${data.error}!`, 'Failed');
            }
        } else {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

    } catch (error) {
        // Handle any errors
        console.error('Error during fetch:', error);
    }
});


    //  H A N D L I N G    C H E C K B O X E S
// ... (Your existing toast, cID, and modal-opening code) ...

//  H A N D L I N G    C H E C K B O X E S
//  ----------------------------------------

// We use event delegation on the document body to handle clicks
// on any checkbox with the class 'attendance-checkbox', even
// if it's added to the DOM dynamically.
document.body.addEventListener('change', function(event) {
    // Check if the element that triggered the event is a checkbox
    // with the specific class we care about.
    if (event.target.classList.contains('attendance-checkbox')) {
        const checkbox = event.target;
        const groupName = checkbox.name;

        // If the checkbox is checked, uncheck all others with the same name.
        if (checkbox.checked) {
            document.querySelectorAll(`input[name="${groupName}"]`).forEach(otherCheckbox => {
                if (otherCheckbox !== checkbox) {
                    otherCheckbox.checked = false;
                }
            });
        }
    }
});

/*
    // 2. Add a click listener to the submit button
    if (submitButton) {
        submitButton.addEventListener('click', function() {
            const attendanceData = collectAttendanceData();
            console.log("Collected Data:", attendanceData);

            // Call the function to send the data via fetch
            sendAttendanceData(attendanceData);
        });
    }

    // 3. Define the function to collect all attendance data
    */
    function collectAttendanceData(tableID) {
        const attendanceList = [];
        const studentRows = document.querySelectorAll(`#${tableID} tbody tr`);

        studentRows.forEach(row => {
            let status = 'Not Marked';
            let rollNumber;

            // Find the checked checkbox in the current row
            const presentCheckbox = row.querySelector('.present-status');
            const absentCheckbox = row.querySelector('.absent-status');
            const leaveCheckbox = row.querySelector('.leave-status');

            if (presentCheckbox && presentCheckbox.checked) {
                status = 1;
                rollNumber = presentCheckbox.name.replace('a', '');
            } else if (absentCheckbox && absentCheckbox.checked) {
                status = 0;
                rollNumber = presentCheckbox.name.replace('a', '');
            } else if (leaveCheckbox && leaveCheckbox.checked) {
                status = -1;
                rollNumber = presentCheckbox.name.replace('a', '');
            }

            // Create an object for the current student's attendance
            attendanceList.push({
                roll_number: rollNumber,
                attendance: status
            });
        });

        //console.log(attendanceList);
        return attendanceList;
    }

    // E V E N T    H A N D L I N G   F O R   U P D A T E    A T T E N D A N C E   B U T T O N