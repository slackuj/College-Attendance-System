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
    var modal = document.getElementById("edit-subject");
    //var modal2 = document.getElementById("delete_teacher");
    //var modal3 = document.getElementById("reset_password");

// Get the button that opens the modal
    var btn = document.getElementById("editBtn");
    //var btn4 = document.getElementById("deleteBtn");
    //var btn7 = document.getElementById("resetBtn");

// Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close-button")[0];
    //var span2 = document.getElementsByClassName("close-button2")[0];
    //var span3 = document.getElementsByClassName("close-button3")[0];
    var btn2 = document.getElementById("saveBtn");
    var btn3 = document.getElementById("cancelBtn");
    //var btn5 = document.getElementById("cancelBtn2");
    //var btn6 = document.getElementById("deleteBtn2");

// When the user clicks on the button, open the modal
    btn.onclick = function () {
        modal.style.display = "block";
    }
    /*btn4.onclick = function () {
        modal2.style.display = "block";
    }
    btn7.onclick = function () {
        modal3.style.display = "block";
    }*/
// When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        toastr.remove();
        modal.style.display = "none";
    }/*
    span2.onclick = function () {
        toastr.remove();
        modal2.style.display = "none";
    }
    span3.onclick = function () {
        toastr.remove();
        modal3.style.display = "none";
    }*/
    btn3.onclick = function () {
        modal.style.display = "none";
    }/*
    btn5.onclick = function () {
        modal2.style.display = "none";
    }
    btn6.onclick = function () {
        toastr.remove();
        toastr.options = {};
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.options.progressBar = true;
        toastr.options.timeOut = 2000;
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
        if (event.target === modal ){//|| event.target === modal2 || event.target === modal3) {
            modal.style.display = "none";
            //modal2.style.display = "none";
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
                    const toastData = {
                        type: 'success',
                        title: 'Teacher Deleted!',
                        message: `Teacher has been deleted successfully.`
                    };

                    // 2. Store the data as a JSON string in sessionStorage
                    sessionStorage.setItem('toastMessage', JSON.stringify(toastData));

                    window.location.href = `/frontend/Admin/teachers/teachers.php`;
                }
            })
            .catch(error => {
                console.error('Error during fetch:', error);
            });
}*/

sID = document.getElementById('sID');
const sname = document.getElementById('sname');
const courseCode = document.getElementById('ccode');
const courseCredit = document.getElementById('ccredit');

let snameOriginal = sname.value;
let courseCodeOriginal = courseCode.value;
let courseCreditOriginal = courseCredit.value;

const editSubjectElements = document.querySelectorAll('.editSubject');
editSubjectElements.forEach(element => {
  element.addEventListener('change', () =>{

      btn2.disabled = sname.value === snameOriginal && courseCodeOriginal === courseCode.value && courseCredit === courseCreditOriginal;
  });
});

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
    const message = `Updating Subject`;
    toastr.info(message);

    // make API call to backend for updating teacher

    try {
        const tData = {
            sID: sID.value,
            sname: sname.value === snameOriginal ? '' : sname.value,
            ccode: courseCode.value === courseCodeOriginal ? '' : courseCode.value,
            ccredit: courseCredit.value === courseCreditOriginal ? '' : courseCredit.value
        };

        const response = await fetch('/backend/api/edit_subject.php', {
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

                modal.style.display = 'none';

                // 1. Create a data object for the toast
                const toastData = {
                    type: 'success',
                    title: 'Successful!',
                    message: `Subject has been successfully updated.`
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
        btn2.classList.remove('loading');
        btn2.disabled = false;
        btn3.disabled = false;
        console.error("failed updating subject:", error);
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