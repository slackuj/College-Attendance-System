document.addEventListener("DOMContentLoaded", function() {
// Get the modal
    var modal2 = document.getElementById("delete_teacher");

// Get the button that opens the modal
    var btn4 = document.getElementById("deleteBtn");

// Get the <span> element that closes the modal
    var span2 = document.getElementById("delete_span");
    var btn5 = document.getElementById("cancelBtn2");
    var btn6 = document.getElementById("deleteBtn2");

// When the user clicks on the button, open the modal
    btn4.onclick = function () {
        modal2.style.display = "block";
    }

// When the user clicks on <span> (x), close the modal
    span2.onclick = function () {
        modal2.style.display = "none";
    }
    btn5.onclick = function () {
        modal2.style.display = "none";
    }
    btn6.onclick = function () {
        modal2.style.display = "none";
    }

// When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target === modal2) {
            modal2.style.display = "none";
        }
    }
});