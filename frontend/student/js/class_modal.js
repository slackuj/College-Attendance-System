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


let tID = document.getElementById('tID').value;
tName = document.getElementById('teacher').value;
// Get the modal
    var modal = document.getElementById("export-classes");
    var modal2 = document.getElementById("create-class");

// Get the button that opens the modal
    var btn = document.getElementById("exportBtn");
    var btn3 = document.getElementById("newClass");

// Get the <span> element that closes the modal
   var span = document.getElementsByClassName("close-button")[0];
   var span2 = document.getElementsByClassName("close-button2")[0];
   var btn2 = document.getElementById("downloadBtn");
    var btn4 = document.getElementById("createBtn");
    var btn5 = document.getElementById("cancelBtn");

// When the user clicks on the button, open the modal
    btn.onclick = function () {
        modal.style.display = "block";
    }
    btn3.onclick = function () {
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
    btn2.onclick = function () {
        modal.style.display = "none";
    }
    btn5.onclick = function () {
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

    //  K E E P   C R E A T E   B U T T O N   D I S A B L E D

programme2 = document.getElementById('programme');
semester2 = document.getElementById('semester');  // already defined in add_class.js
subject2 = document.getElementById('subject');

let programmeOriginal = programme2.value;
let semesterOriginal = semester2.value;
let subjectOriginal = subject2.value;

const createClassElements = document.querySelectorAll('.createCLass');

createClassElements.forEach(classElement => {

    classElement.addEventListener('change', () =>{

        btn4.disabled = programme2.value === programmeOriginal || semester2.value === semesterOriginal || subject2.value === subjectOriginal;
    });
});

// F E T C H    A P I   C A L L   T O   C R E A T E   C L A S S

btn4.addEventListener('click', async () => {

// 1. Show loading state
    btn4.classList.add('loading');
    btn4.disabled = true;
    btn5.disabled = true;

    // display toast message
    toastr.remove();
    toastr.options = {};
    toastr.options.positionClass = 'toast-bottom-right';
    toastr.options.progressBar = true;
    const title = `Creating Class`;
    const message = `Creating new class for  ${tName}`;
    toastr.info(message, title);

    // make API call to backend for updating teacher

    try {
        const tData = {
            subjectID: subject2.value,
            tID: tID
        };

        const response = await fetch('/backend/api/create_class.php', {
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
                await new Promise(resolve => setTimeout(resolve, 2000));

                // 4. Hide loading state and display success message
                btn4.classList.remove('loading');
                btn4.disabled = false;
                btn5.disabled = false;

                toastr.remove();
                modal2.style.display = 'none';

                // 1. Create a data object for the toast
                const toastData = {
                    type: 'success',
                    title: 'New Class Created!',
                    message: `New class for ${tName} has been created successfully.`
                };

                // 2. Store the data as a JSON string in sessionStorage
                sessionStorage.setItem('toastMessage', JSON.stringify(toastData));

                window.location.reload();
            } else {
                // Handle API-specific errors
                btn4.classList.remove('loading');
                btn4.disabled = false;
                btn5.disabled = false;
                console.error("failed creating new class:", data.error);
                toastr.remove();
                toastr.options = {
                    "positionClass": "toast-bottom-right",
                    "closeButton": true,
                    "timeOut": "5000"
                };
                toastr.error(`${data.error}!`, 'Creating Class Failed');
            }
        } else {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

    } catch (error) {
        // Handle any errors
        console.error('Error during fetch:', error);
    }
});