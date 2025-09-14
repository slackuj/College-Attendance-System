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

const cID = document.getElementById('cID').value;


// Get the modal
    var modal = document.getElementById("addStudents");
    var modal2 = document.getElementById("removeStudents");

// Get the button that opens the modal
    var btn = document.getElementById("addStudentBtn");
    var btn4 = document.getElementById("removeStudentBtn");

// Get the <span> element that closes the modal
   var span = document.getElementsByClassName("close-button")[0];
    var span2 = document.getElementsByClassName("close-button2")[0];
   var btn2 = document.getElementById("addBtn");
    var btn5 = document.getElementById("deleteBtn2");
    var btn3 = document.getElementById("cancelBtn");
    var btn6 = document.getElementById("cancelBtn2");

// When the user clicks on the button, open the modal
    btn.onclick = function () {
        modal.style.display = "block";
    }
    btn4.onclick = function () {
        modal2.style.display = "block";
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
    if(btn3) {
        btn3.onclick = function () {
        modal.style.display = "none";
        }
    }
    btn6.onclick = function () {
        modal2.style.display = "none";
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

    //  P R O C E S S   C H E C K B O X    S E L E C T I O N

    const selectAllCheckbox = document.getElementById('selectAllStudents');
    const studentCheckboxes = document.querySelectorAll('.selectStudent');
    let selectedStudents = [];

    if (selectAllCheckbox) {
        // Function to handle the "select all" functionality
        selectAllCheckbox.addEventListener('change', function() {
            studentCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            collectSelectedRollNumbers();
        });

        // Function to update the header checkbox based on individual checkboxes
        studentCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                let allChecked;
                allChecked = Array.from(studentCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
                collectSelectedRollNumbers();
            });
        });
    }

    // Function to collect the roll numbers of selected rows
    function collectSelectedRollNumbers() {
        const selectedRollNumbers = [];
        studentCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                // Get the roll number from the data-rollno attribute
                const rollNo = checkbox.getAttribute('data-roll');
                selectedRollNumbers.push(rollNo);
            }
        });

        // This is where you would pass the data. For demonstration, we'll log it.
        selectedStudents = selectedRollNumbers;
        console.log('Selected Roll Numbers:', selectedRollNumbers);

        // Example of how you might use this data:
        // You could enable a button for bulk actions here,
        // or send the data to a server via AJAX.
        // const bulkActionButton = document.getElementById('bulkActionButton');
        btn2.disabled = selectedRollNumbers.length <= 0;
    }
/*

const teacherOptions = document.querySelectorAll('.teacherOptions');

teacherOptions.forEach(option => {

    option.addEventListener('change', () =>{

        btn2.disabled = false;
        tID = option.value;
        //console.log(teacher2.options[teacher2.selectedIndex].text);
    });
});

// F E T C H    A P I   C A L L   T O  A D D   S T U D E N T S

*/
if (btn2) {
    btn2.addEventListener('click', async () => {

// 1. Show loading state
        btn2.classList.add('loading');
        btn2.disabled = true;
        btn3.disabled = true;

        // display toast message
        toastr.remove();
        toastr.options = {};
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.options.progressBar = true;
        toastr.options.timeOut = 2000;
        const message = `Adding Students to the Class`;
        toastr.info(message);

        //console.log(cID);
        //console.log(tID);

        // make API call to backend for updating teacher
        try {
            const tData = {
                cID: cID,
                selectedStudents: selectedStudents
            };

            const response = await fetch('/backend/api/add_students.php', {
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
                    btn3.disabled = false;

                    toastr.remove();
                    modal.style.display = 'none';

                    // 1. Create a data object for the toast
                    const toastData = {
                        type: 'success',
                        title: 'Students Added!',
                        message: `Students have been added successfully to the Class.`
                    };

                    // 2. Store the data as a JSON string in sessionStorage
                    sessionStorage.setItem('toastMessage', JSON.stringify(toastData));

                    window.location.reload();
                } else {
                    // Handle API-specific errors
                    btn2.classList.remove('loading');
                    btn2.disabled = false;
                    btn3.disabled = false;
                    console.error("failed adding students:", data.error);
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
}


// F E T C H    A P I   C A L L   T O   R E M O V E   S T U D E N T S

btn5.addEventListener('click', async () => {

// 1. Show loading state
    btn5.classList.add('loading');
    btn5.disabled = true;
    btn6.disabled = true;

    // display toast message
    toastr.remove();
    toastr.options = {};
    toastr.options.positionClass = 'toast-bottom-right';
    toastr.options.progressBar = true;
    toastr.options.timeOut = 2000;
    const message = `Removing Students from the Class`;
    toastr.info(message);

    //console.log(cID);
    //console.log(tID);

    // make API call to backend for updating teacher
    try {
        const tData = {
            cID: cID,
            selectedStudents: collectSelectedStudents()
        };

        const response = await fetch('/backend/api/remove_students.php', {
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
                btn5.classList.remove('loading');
                btn5.disabled = false;
                btn6.disabled = false;

                toastr.remove();
                modal2.style.display = 'none';

                // 1. Create a data object for the toast
                const toastData = {
                    type: 'success',
                    title: 'Students Removed!',
                    message: `Students have been removed successfully from the Class.`
                };

                // 2. Store the data as a JSON string in sessionStorage
                sessionStorage.setItem('toastMessage', JSON.stringify(toastData));

                window.location.reload();
            } else {
                // Handle API-specific errors
                btn5.classList.remove('loading');
                btn5.disabled = false;
                btn6.disabled = false;
                console.error("failed removing students:", data.error);
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