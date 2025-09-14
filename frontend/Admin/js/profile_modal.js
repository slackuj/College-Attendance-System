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


// Get the modal
    var modal = document.getElementById("change-password");

// Get the button that opens the modal
    var btn = document.getElementById("changeBtn");

// Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close-button")[0];
    var btn2 = document.getElementById("changePassBtn");

// When the user clicks on the button, open the modal
    btn.onclick = function () {
        modal.style.display = "block";
    }

// When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        toastr.remove();
        modal.style.display = "none";
    }


// When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target === modal ) {
            modal.style.display = "none";
            toastr.remove();
        }
    }


const currentPass = document.getElementById('currentPass');
const newPass = document.getElementById('newPass');
const confirmPass = document.getElementById('confirmPass');
const currentPassValidationMsg = document.getElementById('current-password-validation-message');
const newPassValidationMsg = document.getElementById('new-password-validation-message');
const confirmPassValidationMsg = document.getElementById('confirm-password-validation-message');

let currentOriginal = currentPass.value;
let newOriginal = newPass.value;
let confirmOriginal = confirmPass.value;

const changePasswordElements = document.querySelectorAll('.changePassword');

changePasswordElements.forEach(element => {

    element.addEventListener('input', () =>{

        validatePasswords();
    });
});

function togglePasswordVisibility(input, button) {
    if (input.type === 'password') {
        input.type = 'text';
        button.innerHTML = `<span class="fa-solid fa-eye"></span>`;
    } else {
        input.type = 'password';
        button.innerHTML = `<span class="fa-solid fa-eye-slash"></span>`;
    }
}


function validatePasswords() {
    const isCurrentPassValid = currentPass.value.length >= 6;
    const isNewPassValid = newPass.value.length >= 6;
    const isConfirmPassValid = confirmPass.value.length >= 6;
    const doPasswordsMatch = newPass.value === confirmPass.value;


    // Check for password length
    if (currentPass.value.length > 0 && !isCurrentPassValid) {
        currentPassValidationMsg.textContent = 'Password must be at least 6 characters long.';
    } else {
        currentPassValidationMsg.textContent = '';
    }

    if (newPass.value.length > 0 && !isNewPassValid) {
        newPassValidationMsg.textContent = 'Password must be at least 6 characters long.';
    } else {
        newPassValidationMsg.textContent = '';
    }
    if (confirmPass.value.length > 0 && !isConfirmPassValid) {
        confirmPassValidationMsg.textContent = 'Password must be at least 6 characters long.';
    } else {
        confirmPassValidationMsg.textContent = '';
    }
    // Check if passwords match
    if (confirmPass.value.length > 0 && !doPasswordsMatch) {
        confirmPassValidationMsg.textContent = 'Passwords do not match.';
    } else {
        confirmPassValidationMsg.textContent = '';
    }

    btn2.disabled = currentPass.value === currentOriginal || newPass.value === newOriginal || confirmPass.value === confirmOriginal || !isCurrentPassValid || !isConfirmPassValid || !isNewPassValid || !doPasswordsMatch;

}



const toggleCurrentPassBtn = document.getElementById('toggleCurrentPassBtn');
const toggleNewPassBtn = document.getElementById('toggleNewPassBtn');
const toggleConfirmPassBtn = document.getElementById('toggleConfirmPassBtn');

toggleCurrentPassBtn.addEventListener('click', () => togglePasswordVisibility(currentPass, toggleCurrentPassBtn));
toggleNewPassBtn.addEventListener('click', () => togglePasswordVisibility(newPass, toggleNewPassBtn));
toggleConfirmPassBtn.addEventListener('click', () => togglePasswordVisibility(confirmPass, toggleConfirmPassBtn));

/*btn2.addEventListener('click', async () => {

// 1. Show loading state
    btn3.classList.add('loading');
    btn3.disabled = true;
    btn2.disabled = true;

    // display toast message
    toastr.remove();
    toastr.options = {};
    toastr.options.positionClass = 'toast-bottom-right';
    toastr.options.progressBar = true;
    const title = `Updating Teacher`;
    const message = `Updating ${tName}'s information`;
    toastr.info(message, title);

    // make API call to backend for updating teacher

    try {
        const tData = {
            tID: tID.value,
            fname: fnameOriginal === fname.value ? '' : fname.value,
            lname: lnameOriginal === lname.value ? '' : lname.value,
            email: emailOriginal === email.value ? '' : email.value,
            title: titleOriginal === teacher_title.value ? '' : teacher_title.value
        };

        const response = await fetch('/backend/api/edit_teacher.php', {
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
                    title: 'Updated Successfully!',
                    message: `${tName} has been successfully updated.`
                };

                // 2. Store the data as a JSON string in sessionStorage
                sessionStorage.setItem('toastMessage', JSON.stringify(toastData));

                window.location.reload();
            } else {
                // Handle API-specific errors
                throw new Error(data.error);
            }
        } else {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

    } catch (error) {
        // Handle any errors
        btn3.classList.remove('loading');
        btn3.disabled = false;
        btn2.disabled = false;
        console.error("failed updating teacher:", error);
        toastr.remove();
        toastr.options = {
            "positionClass": "toast-bottom-right",
            "closeButton": true,
            "progressBar": true,
            "timeOut": "5000"
        };
        toastr.error(error, 'Update Failed');
    }
});
 */