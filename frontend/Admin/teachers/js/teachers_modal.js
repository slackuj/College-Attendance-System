 //  H A N D L I N G    C R E A T E    N E W    T E A C H E R    F O R M

 const fnameInput = document.getElementById('fname');
 const lnameInput = document.getElementById('lname');
 const emailInput = document.getElementById('email');
 const passInput = document.getElementById('pass');
 const confirmPassInput = document.getElementById('confirmPass');
 const createBtn = document.getElementById('createBtn');
 const generatePassBtn = document.getElementById('generatePassBtn');
 const togglePassBtn = document.getElementById('togglePassBtn');
 const toggleConfirmPassBtn = document.getElementById('toggleConfirmPassBtn');
 const emailValidationMsg = document.getElementById('email-validation-message');
 const passValidationMsg = document.getElementById('password-validation-message');
 const confirmPassValidationMsg = document.getElementById('confirm-password-validation-message');

 function initializeForm(){
     fnameInput.value = lnameInput.value = emailInput.value = passInput.value = confirmPassInput. value = '';
 }
 function validateForm() {
     const isFnameFilled = fnameInput.value.trim() !== '';
     const isLnameFilled = lnameInput.value.trim() !== '';
     const isEmailValid = validateEmail(emailInput.value);
     const isPassValid = passInput.value.length >= 6;
     const doPasswordsMatch = passInput.value === confirmPassInput.value;

     // Check for email validation
     if (!isEmailValid && emailInput.value.length > 0){
         emailValidationMsg.textContent = 'Invalid email address.';
     } else {
         emailValidationMsg.textContent = '';
     }

     // Check for password length
     if (passInput.value.length > 0 && !isPassValid) {
         passValidationMsg.textContent = 'Password must be at least 6 characters long.';
     } else {
         passValidationMsg.textContent = '';
     }

     // Check if passwords match
     if (confirmPassInput.value.length > 0 && !doPasswordsMatch) {
         confirmPassValidationMsg.textContent = 'Passwords do not match.';
     } else {
         confirmPassValidationMsg.textContent = '';
     }

     // Enable/disable the create button
     if (isFnameFilled && isLnameFilled && isEmailValid && isPassValid && doPasswordsMatch) {
         createBtn.disabled = false;
     } else {
         createBtn.disabled = true;
     }
 }

 function validateEmail(email) {
     const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
     return re.test(String(email).toLowerCase());
 }

 function generateRandomPassword() {
     const length = 12;
     const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+~`|}{[]:;?><,./-=";
     let password = "";
     for (let i = 0; i < length; i++) {
         password += charset.charAt(Math.floor(Math.random() * charset.length));
     }
     return password;
 }

 // Toggle password visibility
 function togglePasswordVisibility(input, button) {
     if (input.type === 'password') {
         input.type = 'text';
         button.innerHTML = `<span class="fa-solid fa-eye"></span>`;
     } else {
         input.type = 'password';
         button.innerHTML = `<span class="fa-solid fa-eye-slash"></span>`;
     }
 }

 // Attach event listeners to all input fields
 fnameInput.addEventListener('input', validateForm);
 lnameInput.addEventListener('input', validateForm);
 emailInput.addEventListener('input', validateForm);
 passInput.addEventListener('input', validateForm);
 confirmPassInput.addEventListener('input', validateForm);

 // Attach event listener for the generate password button
 generatePassBtn.addEventListener('click', function() {
     const newPassword = generateRandomPassword();
     passInput.value = newPassword;
     confirmPassInput.value = newPassword;
     validateForm(); // Re-validate the form after filling the passwords

     // Copy password to clipboard
     navigator.clipboard.writeText(newPassword).then(() => {
         toastr.options = {
             "positionClass": "toast-bottom-right",
             "closeButton": true,
             "progressBar": true,
             "timeOut": "3000"
         };
         toastr.success('Password copied to clipboard!', 'Success');
     }).catch(err => {
         console.error('Could not copy text: ', err);
         toastr.options = {
             "positionClass": "toast-bottom-right",
             "closeButton": true,
             "progressBar": true,
             "timeOut": "3000"
         };
         toastr.error('Failed to copy password.', 'Error');
     });
 });

 // Attach event listeners for the toggle password buttons
 togglePassBtn.addEventListener('click', () => togglePasswordVisibility(passInput, togglePassBtn));
 toggleConfirmPassBtn.addEventListener('click', () => togglePasswordVisibility(confirmPassInput, toggleConfirmPassBtn));





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
    var modal = document.getElementById("add-teacher");
    var modal2 = document.getElementById("delete-teachers");
    var modal3 = document.getElementById("export-teachers");
    var modal4 = document.getElementById("bulk-teachers");

// Get the button that opens the modal
    var btn = document.getElementById("addNewTeacher");
    var btn4 = document.getElementById("exportBtn");
    var btn7 = document.getElementById("deleteBtn");
    var btn9 = document.getElementById("bulkBtn");

// Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close-button")[0];
    var span2 = document.getElementsByClassName("close-button2")[0];
    var span3 = document.getElementsByClassName("close-button3")[0];
    var span4 = document.getElementsByClassName("close-button4")[0];
    var btn2 = document.getElementById("cancelBtn");
    var btn3 = document.getElementById("createBtn");
    var btn5 = document.getElementById("downloadBtn");
    var btn6 = document.getElementById("deleteBtn2");
    var btn8 = document.getElementById("cancelBtn2");
    var btn10 = document.getElementById("templateBtn");
    var btn11 = document.getElementById("create_bulk_teacher");

 const fileInput = document.getElementById('fileToUpload');

 // 1. Disable/Enable the button based on file selection
 fileInput.addEventListener('change', () => {
     btn11.disabled = fileInput.files.length <= 0;
 });

// When the user clicks on the button, open the modal
    btn.onclick = function () {
        modal.style.display = "block";
        // Initial validation check on page load
        initializeForm();
        validateForm();
    }
    btn4.onclick = function () {
        modal3.style.display = "block";
    }
    btn7.onclick = function () {
     modal2.style.display = "block";
    }
    btn5.onclick = function () {
        modal3.style.display = "none";
    }
    btn9.onclick = function () {
        modal4.style.display = "block";
    }
    btn10.onclick = function () {
        downloadExcelTemplate();
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
    span3.onclick = function () {
        toastr.remove();
        modal3.style.display = "none";
    }
    span4.onclick = function () {
        toastr.remove();
        modal4.style.display = "none";
    }
    btn2.onclick = function () {
        toastr.remove();
        modal.style.display = "none";
    }
    btn8.onclick = function () {
        modal2.style.display = "none";
    }


// When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target === modal  || event.target === modal4 || event.target === modal2 || event.target === modal3) {
            modal.style.display = "none";
            modal2.style.display = "none";
            modal3.style.display = "none";
            modal4.style.display = "none";
            toastr.remove();
        }
    }

btn3.addEventListener('click', async () => {

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
     btn7.disabled = selectedTeacherIDs.length <= 0;
 }

 // F E T C H    A P I   C A L L   T O   D E L E T E    T E A C H E R S

 if (btn6) {
     btn6.addEventListener('click', async () => {

// 1. Show loading state
         btn6.classList.add('loading');
         btn6.disabled = true;
         btn8.disabled = true;

         // display toast message
         toastr.remove();
         toastr.options = {};
         toastr.options.positionClass = 'toast-bottom-right';
         toastr.options.progressBar = true;
         toastr.options.timeOut = 2000;
         const message = `Deleting Teachers`;
         toastr.info(message);

         //console.log(cID);
         //console.log(tID);

         // make API call to backend for updating teacher
         try {
             const tData = {
                 tID: selectedTeachers
             };

             const response = await fetch('/backend/api/delete_teacher.php', {
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
                     btn6.classList.remove('loading');
                     btn6.disabled = false;
                     btn8.disabled = false;

                     toastr.remove();
                     modal2.style.display = 'none';

                     // 1. Create a data object for the toast
                     const toastData = {
                         type: 'success',
                         title: 'Teachers Deleted!',
                         message: `Teachers have been deleted successfully.`
                     };

                     // 2. Store the data as a JSON string in sessionStorage
                     sessionStorage.setItem('toastMessage', JSON.stringify(toastData));

                     window.location.reload();
                 } else {
                     // Handle API-specific errors
                     btn6.classList.remove('loading');
                     btn6.disabled = false;
                     btn8.disabled = false;
                     console.error("failed deleting teachers:", data.error);
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

 function downloadExcelTemplate() {
     const data = [
         ["First Name", "Last Name", "E-mail", "Password"]
     ];

     const ws = XLSX.utils.aoa_to_sheet(data);
     const wb = XLSX.utils.book_new();
     XLSX.utils.book_append_sheet(wb, ws, "Teachers Template");

     // Define the column widths
     ws['!cols'] = [
         { wch: 15 }, // First Name
         { wch: 15 }, // Last Name
         { wch: 25 }, // E-mail
         { wch: 15 }  // Password
     ];

     XLSX.writeFile(wb, "teachers_template.xlsx");
 }

    // 2. Fetch API call to send the file
 // 2. Fetch API call to send the file
 btn11.addEventListener('click', async () => {
     const file = fileInput.files[0];

     // Check if a file is actually selected
     if (!file) {
         alert('Please select a file to upload.');
         return;
     }

     btn11.classList.add('loading');
     btn11.disabled = true;
     btn10.disabled = true;

     // display toast message
     toastr.remove();
     toastr.options = {};
     toastr.options.positionClass = 'toast-bottom-right';
     toastr.options.progressBar = true;
     toastr.options.timeOut = 5000;
     const message = `Creating Teachers`;
     toastr.info(message);

     // Create a FormData object to hold the file
     const formData = new FormData();
     // 'fileToUpload' is the key that the PHP backend will use to access the file
     formData.append('fileToUpload', file);

     try {
         // Send the file to the backend
         const response = await fetch('/backend/api/create_bulk_teacher.php', {
             method: 'POST',
             body: formData
         });

         // Parse the JSON response from the server
         const result = await response.json(); // Correctly parse it once here

         // Handle the server response
         if (response.ok) {
             // Check the 'success' property from the parsed result
             if (result.success === "true") {
                 // 3. Add an artificial delay to show a loading state for longer
                 await new Promise(resolve => setTimeout(resolve, 1000));

                 // 4. Hide loading state and display success message
                 btn11.classList.remove('loading');
                 btn11.disabled = false;
                 btn10.disabled = false;

                 toastr.remove();
                 modal4.style.display = 'none';


                 let message = `${result.message.successCount} teachers created successfully
                 ${result.message.errorCount} teachers could not be created.`;
                 // 1. Create a data object for the toast
                 const toastData = {
                     type: 'success',
                     title: 'Bulk Creation Completed!',
                     message: message // Use the message from the parsed result
                 };

                 // 2. Store the data as a JSON string in sessionStorage
                 sessionStorage.setItem('toastMessage', JSON.stringify(toastData));

                 window.location.reload();
             } else {
                 // Handle API-specific errors
                 btn11.classList.remove('loading');
                 btn11.disabled = false;
                 btn10.disabled = false;
                 console.error("failed creating teachers:", result.error); // Use the error from the parsed result
                 toastr.remove();
                 toastr.options = {
                     "positionClass": "toast-bottom-right",
                     "closeButton": true,
                     "timeOut": "5000"
                 };
                 toastr.error(`${result.error}`, 'Failed');
             }
         } else {
             // Handle HTTP errors
             throw new Error(`HTTP error! Status: ${response.status}`);
         }
     } catch (error) {
         // Handle any errors
         console.error('Error during fetch:', error);
         btn11.classList.remove('loading');
         btn11.disabled = false;
         btn10.disabled = false;
         toastr.remove();
         toastr.options = {
             "positionClass": "toast-bottom-right",
             "closeButton": true,
             "progressBar": true,
             "timeOut": "5000"
         };
         toastr.error('An error occurred. Please try again later.', 'Failed');
     }
 });
