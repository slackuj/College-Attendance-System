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
    var modal = document.getElementById("export-classes");

// Get the button that opens the modal
    var btn = document.getElementById("exportBtn");

// Get the <span> element that closes the modal
   var span = document.getElementsByClassName("close-button")[0];
   var btn2 = document.getElementById("downloadBtn");

// When the user clicks on the button, open the modal
    btn.onclick = function () {
        modal.style.display = "block";
    }
// When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        toastr.remove();
        modal.style.display = "none";
    }
    btn2.onclick = function () {
        modal.style.display = "none";
    }
// When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
            toastr.remove();
        }
    }