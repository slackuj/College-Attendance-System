refresh = document.getElementById('refresh');
refresh.addEventListener('click', () => {
    window.location.reload();
});

//  P R O C E S S   C H E C K B O X    S E L E C T I O N

const selectAllStudents = document.getElementById('students');
const selectStudent = document.querySelectorAll('.student');

if (selectAllStudents) {
    // Function to handle the "select all" functionality
    selectAllStudents.addEventListener('change', function() {
        selectStudent.forEach(checkbox => {
            checkbox.checked = selectAllStudents.checked;
        });
        collectSelectedStudents();
    });

    // Function to update the header checkbox based on individual checkboxes
    selectStudent.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            let allChecked;
            allChecked = Array.from(selectStudent).every(cb => cb.checked);
            selectAllStudents.checked = allChecked;
            collectSelectedStudents();
        });
    });
}

// Function to collect the roll numbers of selected rows
function collectSelectedStudents() {
    const selectedRollNumbers = [];
    selectStudent.forEach(checkbox => {
        if (checkbox.checked) {
            // Get the roll number from the data-rollno attribute
            const rollNo = checkbox.getAttribute('data-roll');
            selectedRollNumbers.push(rollNo);
        }
    });

    // This is where you would pass the data. For demonstration, we'll log it.
    //console.log('Selected Roll Numbers:', selectedRollNumbers);

    // Example of how you might use this data:
    // You could enable a button for bulk actions here,
    // or send the data to a server via AJAX.
    const removeBtn = document.getElementById('removeStudentBtn');
    removeBtn.disabled = selectedRollNumbers.length <= 0;
    return selectedRollNumbers;
}
