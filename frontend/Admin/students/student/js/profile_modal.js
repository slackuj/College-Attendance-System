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


sID = document.getElementById('sID');
// Get the modal
    var modal = document.getElementById("edit-student");
    var modal2 = document.getElementById("delete-student");
    var modal3 = document.getElementById("reset_password");

// Get the button that opens the modal
    var btn = document.getElementById("editBtn");
    var btn4 = document.getElementById("deleteBtn");
    var btn7 = document.getElementById("resetBtn");

// Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close-button")[0];
    var span2 = document.getElementsByClassName("close-button2")[0];
    var span3 = document.getElementsByClassName("close-button3")[0];
    var btn3 = document.getElementById("saveBtn");
    var btn2 = document.getElementById("cancelBtn");
    var btn5 = document.getElementById("cancelBtn2");
    var btn6 = document.getElementById("deleteBtn2");

// When the user clicks on the button, open the modal
    btn.onclick = function () {
        modal.style.display = "block";
    }
    btn4.onclick = function () {
        modal2.style.display = "block";
    }
    btn7.onclick = function () {
        modal3.style.display = "block";
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
    btn2.onclick = function () {
        modal.style.display = "none";
    }
    if(btn5) {
        btn5.onclick = function () {
            modal2.style.display = "none";
        }
    }


// When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target === modal || event.target === modal2 || event.target === modal3) {
            modal.style.display = "none";
            modal2.style.display = "none";
            modal3.style.display = "none";
            toastr.remove();
        }
    }
const fname = document.getElementById('fname');
const lname = document.getElementById('lname');
const email = document.getElementById('email');
const title = document.getElementById('title');

let fnameOriginal = fname.value;
let lnameOriginal = lname.value;
let emailOriginal = email.value;
let titleOriginal = title.value;

const editFormElements = document.querySelectorAll('.editStudent');

editFormElements.forEach(element => {

    element.addEventListener('change', () =>{

        btn3.disabled = title.value === titleOriginal && email.value === emailOriginal && lname.value === lnameOriginal && fname.value === fnameOriginal;
    });
});

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
    const message = `Updating Student`;
    toastr.info(message);

    // make API call to backend for updating teacher

    try {
        const tData = {
            sID: sID.value,
            fname: fnameOriginal === fname.value ? '' : fname.value,
            lname: lnameOriginal === lname.value ? '' : lname.value,
            email: emailOriginal === email.value ? '' : email.value,
            title: titleOriginal === title.value ? '' : title.value
        };

        const response = await fetch('/backend/api/edit_student.php', {
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
                    title: 'Successful!',
                    message: `Student has been successfully updated.`
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
        console.error("failed updating student:", error);
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

if (btn6) {
    btn6.addEventListener('click', async () => {

// 1. Show loading state
        btn6.classList.add('loading');
        btn6.disabled = true;
        btn5.disabled = true;

        // display toast message
        toastr.remove();
        toastr.options = {};
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.options.progressBar = true;
        toastr.options.timeOut = 2000;
        const message = `Deleting Student`;
        toastr.info(message);

        // make API call to backend for updating teacher

        try {
            const tData = {
                sID: sID.value,
            };

            const response = await fetch('/backend/api/delete_student.php', {
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
                    btn5.disabled = false;

                    modal2.style.display = 'none';

                    // 1. Create a data object for the toast
                    const toastData = {
                        type: 'success',
                        title: 'Successful!',
                        message: `Student has been successfully deleted.`
                    };

                    // 2. Store the data as a JSON string in sessionStorage
                    sessionStorage.setItem('toastMessage', JSON.stringify(toastData));

                    window.location.href = "/frontend/Admin/students/students.php";
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
            console.error("failed deleting student:", error);
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
}