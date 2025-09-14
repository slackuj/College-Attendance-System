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


//tName = document.getElementById('tName').value;
//tID = document.getElementById('tID');
// Get the modal
    var modal = document.getElementById("recover-teachers");
    var modal2 = document.getElementById("delete-teachers-permanently");
    //var modal3 = document.getElementById("export-teachers");

// Get the button that opens the modal
    var btn = document.getElementById("recoverBtn");
    var btn4 = document.getElementById("deletePermanentlyBtn");

// Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close-button")[0];
    var span2 = document.getElementsByClassName("close-button2")[0];
    //var span3 = document.getElementsByClassName("close-button3")[0];
    var btn2 = document.getElementById("recoverBtn2");
    var btn3 = document.getElementById("cancelBtn");
    //var btn5 = document.getElementById("downloadBtn");
    var btn5 = document.getElementById("deleteBtn");
    var btn6 = document.getElementById("cancelBtn2");

// When the user clicks on the button, open the modal
    btn.onclick = function () {
        modal.style.display = "block";
    }
    btn4.onclick = function () {
        modal2.style.display = "block";
    }/*
    btn7.onclick = function () {
     modal2.style.display = "block";
    }
    btn5.onclick = function () {
        modal3.style.display = "none";
    }
*/
// When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        toastr.remove();
        modal.style.display = "none";
    }
    span2.onclick = function () {
        toastr.remove();
        modal2.style.display = "none";
    }/*
    span3.onclick = function () {
        toastr.remove();
        modal3.style.display = "none";
    }*/
    btn6.onclick = function () {
        toastr.remove();
        modal2.style.display = "none";
    }
    btn3.onclick = function () {
        modal.style.display = "none";
    }
    /*btn6.onclick = function () {
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
    }*/

// When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target === modal  || event.target === modal2 ){// {|| event.target === modal3) {
            modal.style.display = "none";
            modal2.style.display = "none";
            //modal3.style.display = "none";
            toastr.remove();
        }
    }
/*function deleteTeacher() {
        const tID = document.getElementById('tID').value;

        const tData = {
            tID: tID
        };

        fetch('/backend/api/delete_teacher.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json' // Crucial: Tells the server we're sending JSON
            },
            body: JSON.stringify(tData) // Convert JavaScript object to JSON string
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json(); // Assuming server responds with JSON
            })
            .then(data => {
                if (data.success){
                    //history.back();
                    window.location.href = `../../teachers.php`;
                }
            })
            .catch(error => {
                console.error('Error during fetch:', error);
            });
}

const fname = document.getElementById('fname');
const lname = document.getElementById('lname');
const email = document.getElementById('email');
const teacher_title = document.getElementById('title');

let fnameOriginal = fname.value;
let lnameOriginal = lname.value;
let emailOriginal = email.value;
let titleOriginal = teacher_title.value;

const editTeacherElements = document.querySelectorAll('.editTeacher');

editTeacherElements.forEach(teacherElement => {

    teacherElement.addEventListener('change', () =>{

        btn3.disabled = teacher_title.value === titleOriginal && email.value === emailOriginal && lname.value === lnameOriginal && fname.value === fnameOriginal;
    });
});
*/
/*btn3.addEventListener('click', async () => {

// 1. Show loading state
    btn3.classList.add('loading');
    btn3.disabled = true;
    btn2.disabled = true;

    // display toast message
    toastr.remove();
    toastr.options = {};
    toastr.options.positionClass = 'toast-bottom-right';
    toastr.options.progressBar = true;
    toastr.options.timeOut = 2000;
    const message = `Creating New Teacher`;
    toastr.info(message);

    // make API call to backend for updating teacher

    try {
        const tData = {
            fname: fnameInput.value,
            lname: lnameInput.value,
            email: emailInput.value,
            pass: passInput.value
        };

        const response = await fetch('/backend/api/create_teacher.php', {
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
                btn2.disabled = false;

                modal.style.display = 'none';

                // 1. Create a data object for the toast
                const toastData = {
                    type: 'success',
                    title: 'Created Teacher',
                    message: `New teacher has been created successfully.`
                };

                // 2. Store the data as a JSON string in sessionStorage
                sessionStorage.setItem('toastMessage', JSON.stringify(toastData));

                window.location.reload();
            } else {
                // Handle API-specific errors
                throw new Error(data.error || 'Failed creating new teacher.');
            }
        } else {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

    } catch (error) {
        // Handle any errors
        btn3.classList.remove('loading');
        btn3.disabled = false;
        btn2.disabled = false;
        console.error("failed creating teacher:", error);
        toastr.remove();
        toastr.options = {
            "positionClass": "toast-bottom-right",
            "closeButton": true,
            "progressBar": true,
            "timeOut": "5000"
        };
        toastr.error(error, 'Failed');
    }
});
*/
//  C O L L E C T I N G    S E L E C T E D   V A L U E S   F R O M   C H E C K B O X E S

 const selectAllCheckbox = document.getElementById('selectAllTeachers');
 const teacherCheckboxes = document.querySelectorAll('.selectTeacher');
 let selectedTeachers = [];

 if (selectAllCheckbox) {
     // Function to handle the "select all" functionality
     selectAllCheckbox.addEventListener('change', function() {
         teacherCheckboxes.forEach(checkbox => {
             checkbox.checked = selectAllCheckbox.checked;
         });
         collectSelectedTeacherIDs();
     });

     // Function to update the header checkbox based on individual checkboxes
     teacherCheckboxes.forEach(checkbox => {
         checkbox.addEventListener('change', function() {
             let allChecked;
             allChecked = Array.from(teacherCheckboxes).every(cb => cb.checked);
             selectAllCheckbox.checked = allChecked;
             collectSelectedTeacherIDs();
         });
     });
 }

 // Function to collect the roll numbers of selected rows
 function collectSelectedTeacherIDs() {
     const selectedTeacherIDs = [];
     teacherCheckboxes.forEach(checkbox => {
         if (checkbox.checked) {
             // Get the roll number from the data-rollno attribute
             const rollNo = checkbox.getAttribute('data-tID');
             selectedTeacherIDs.push(rollNo);
         }
     });

     // This is where you would pass the data. For demonstration, we'll log it.
     selectedTeachers = selectedTeacherIDs;
     console.log('Selected Teacher IDs:', selectedTeacherIDs);

     // Example of how you might use this data:
     // You could enable a button for bulk actions here,
     // or send the data to a server via AJAX.
     // const bulkActionButton = document.getElementById('bulkActionButton');
     btn.disabled = selectedTeacherIDs.length <= 0;
     btn4.disabled = selectedTeacherIDs.length <= 0;
 }

 // F E T C H    A P I   C A L L   T O    R E C O V E R    T E A C H E R S

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
         const message = `Recovering Teacher(s)`;
         toastr.info(message);

         //console.log(cID);
         //console.log(tID);

         // make API call to backend for recovering teacher
         try {
             const tData = {
                 tID: selectedTeachers
             };

             const response = await fetch('/backend/api/recover_teacher.php', {
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
                         title: 'Teacher(s) Recovered!',
                         message: `Teacher(s) recovered successfully.`
                     };

                     // 2. Store the data as a JSON string in sessionStorage
                     sessionStorage.setItem('toastMessage', JSON.stringify(toastData));

                     window.location.reload();
                 } else {
                     // Handle API-specific errors
                     btn2.classList.remove('loading');
                     btn3.disabled = false;
                     btn3.disabled = false;
                     console.error("failed recovering teachers:", data.error);
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

// F E T C H    A P I   C A L L   T O    R E C O V E R    T E A C H E R S

if (btn5) {
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
        const message = `Permanently Deleting Teacher(s)`;
        toastr.info(message);

        //console.log(cID);
        //console.log(tID);

        // make API call to backend for recovering teacher
        try {
            const tData = {
                tID: selectedTeachers
            };

            const response = await fetch('/backend/api/permanently_delete_teacher.php', {
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
                        title: 'Successful!',
                        message: `Teacher(s) permanently deleted.`
                    };

                    // 2. Store the data as a JSON string in sessionStorage
                    sessionStorage.setItem('toastMessage', JSON.stringify(toastData));

                    window.location.reload();
                } else {
                    // Handle API-specific errors
                    btn5.classList.remove('loading');
                    btn6.disabled = false;
                    btn6.disabled = false;
                    console.error("failed permanently deleting teachers:", data.error);
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