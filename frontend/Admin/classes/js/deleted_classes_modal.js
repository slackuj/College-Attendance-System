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
    var modal = document.getElementById("recover-classes");
    //var modal2 = document.getElementById("delete-teachers-permanently");
    //var modal3 = document.getElementById("export-teachers");

// Get the button that opens the modal
    var btn = document.getElementById("recoverBtn");
    //var btn4 = document.getElementById("deletePermanentlyBtn");

// Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close-button")[0];
    //var span2 = document.getElementsByClassName("close-button2")[0];
    //var span3 = document.getElementsByClassName("close-button3")[0];
    var btn2 = document.getElementById("recoverBtn2");
    var btn3 = document.getElementById("cancelBtn");
    //var btn5 = document.getElementById("downloadBtn");
    //var btn5 = document.getElementById("deleteBtn");
    //var btn6 = document.getElementById("cancelBtn2");

// When the user clicks on the button, open the modal
    btn.onclick = function () {
        modal.style.display = "block";
    }
    /*btn4.onclick = function () {
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
    }/*
    span2.onclick = function () {
        toastr.remove();
        modal2.style.display = "none";
    }/*
    span3.onclick = function () {
        toastr.remove();
        modal3.style.display = "none";
    }*/
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
        if (event.target === modal ) { //} || event.target === modal2 ){// {|| event.target === modal3) {
            modal.style.display = "none";
            //modal2.style.display = "none";
            //modal3.style.display = "none";
            toastr.remove();
        }
    }
//  C O L L E C T I N G    S E L E C T E D   V A L U E S   F R O M   C H E C K B O X E S

 const selectAllCheckbox = document.getElementById('selectAllClasses');
 const classCheckboxes = document.querySelectorAll('.selectClass');
 let selectedClasses = [];

 if (selectAllCheckbox) {
     // Function to handle the "select all" functionality
     selectAllCheckbox.addEventListener('change', function() {
         classCheckboxes.forEach(checkbox => {
             checkbox.checked = selectAllCheckbox.checked;
         });
         collectSelectedClassIDs();
     });

     // Function to update the header checkbox based on individual checkboxes
     classCheckboxes.forEach(checkbox => {
         checkbox.addEventListener('change', function() {
             let allChecked;
             allChecked = Array.from(classCheckboxes).every(cb => cb.checked);
             selectAllCheckbox.checked = allChecked;
             collectSelectedClassIDs();
         });
     });
 }

 // Function to collect the roll numbers of selected rows
 function collectSelectedClassIDs() {
     const selectedClassIDs = [];
     classCheckboxes.forEach(checkbox => {
         if (checkbox.checked) {
             // Get the roll number from the data-rollno attribute
             const cID = checkbox.getAttribute('data-cID');
             selectedClassIDs.push(cID);
         }
     });

     // This is where you would pass the data. For demonstration, we'll log it.
     selectedClasses = selectedClassIDs;
     console.log('Selected Class IDs:', selectedClassIDs);

     // Example of how you might use this data:
     // You could enable a button for bulk actions here,
     // or send the data to a server via AJAX.
     // const bulkActionButton = document.getElementById('bulkActionButton');
     btn.disabled = selectedClassIDs.length <= 0;
 }

 // F E T C H    A P I   C A L L   T O    R E C O V E R   C L A S S E S

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
         const message = `Recovering Class(es)`;
         toastr.info(message);

         //console.log(cID);
         //console.log(tID);

         // make API call to backend for recovering teacher
         try {
             const tData = {
                 cID: selectedClasses
             };

             const response = await fetch('/backend/api/recover_class.php', {
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
                         title: 'Successful!',
                         message: `Class(es) recovered successfully.`
                     };

                     // 2. Store the data as a JSON string in sessionStorage
                     sessionStorage.setItem('toastMessage', JSON.stringify(toastData));

                     window.location.reload();
                 } else {
                     // Handle API-specific errors
                     btn2.classList.remove('loading');
                     btn3.disabled = false;
                     btn3.disabled = false;
                     console.error("failed recovering classes:", data.error);
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