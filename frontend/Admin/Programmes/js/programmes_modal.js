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
    var modal = document.getElementById("create-programme");
    var modal2 = document.getElementById("export-programmes");
    var modal3 = document.getElementById("bulk-programmes");

// Get the button that opens the modal
    var btn = document.getElementById("addNewPrgrmBtn");
    var btn4 = document.getElementById("exportBtn");
    var btn9 = document.getElementById("bulkBtn");


// Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close-button2")[0];
    var span2 = document.getElementsByClassName("close-button")[0];
    var span3 = document.getElementsByClassName("close-button3")[0];
    var btn2 = document.getElementById("createBtn");
    var btn3 = document.getElementById("cancelBtn");
    var btn5 = document.getElementById("downloadBtn");
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
    }
    btn4.onclick = function () {
        modal2.style.display = "block";
    }
    btn9.onclick = function () {
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
    btn3.onclick = function () {
        modal.style.display = "none";
    }
    btn5.onclick = function () {
        modal2.style.display = "none";
    }
    btn10.onclick = function () {
        downloadExcelTemplate();
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




   // H A N D L E   C R E A T E   M O D A L    F O R M
const faculty = document.getElementById('faculty');
const pname = document.getElementById('pname');

let facultyOriginal = faculty.value;
let pnameOriginal = pname.value;

faculty.addEventListener('change',() => {
    pname.disabled = faculty.value === facultyOriginal;
});

const formElements = document.querySelectorAll('.createProgramme');

formElements.forEach(formElement => {
    formElement.addEventListener('change', () =>{
       btn2.disabled = pname.value === pnameOriginal || facultyOriginal === faculty.value;
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
    const message = `Creating New Programme`;
    toastr.info(message);

    // make API call to backend for updating teacher

    try {
        const tData = {
            fID: faculty.value,
            pname: pname.value
        };

        const response = await fetch('/backend/api/create_programme.php', {
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
                    message: `Programme has been successfully created.`
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
        console.error("failed creating programme:", error);
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

function downloadExcelTemplate() {
    const data = [
        ["Faculty", "Programme"]
    ];

    const ws = XLSX.utils.aoa_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Programmes Template");

    // Define the column widths
    ws['!cols'] = [
        { wch: 50 }, // E-mail
        { wch: 50 } // E-mail
    ];

    XLSX.writeFile(wb, "programmes_template.xlsx");
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
    const message = `Creating Programmes`;
    toastr.info(message);

    // Create a FormData object to hold the file
    const formData = new FormData();
    // 'fileToUpload' is the key that the PHP backend will use to access the file
    formData.append('fileToUpload', file);

    try {
        // Send the file to the backend
        const response = await fetch('/backend/api/create_bulk_programme.php', {
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
                modal3.style.display = 'none';


                let message = `${result.message.successCount} programmes created successfully
                 ${result.message.errorCount} programmes could not be created.`;
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
                console.error("failed creating programmes:", result.error); // Use the error from the parsed result
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