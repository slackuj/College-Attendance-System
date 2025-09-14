const urlParams = new URLSearchParams(window.location.search);

//check if parameter exists
if (urlParams.has('id')) {
    const cID = urlParams.get('id');

    let classData = [];
    const userData = {
        cID: cID
    };

    fetch('/backend/api/get_class_attendance.php', {
        method: 'POST', // or 'PUT'
        headers: {
            'Content-Type': 'application/json' // Crucial: Tells the server we're sending JSON
        },
        body: JSON.stringify(userData) // Convert JavaScript object to JSON string
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json(); // Assuming server responds with JSON
        })
        .then(data => {
            classData = data.data;
            renderCalendar();
        })
        .catch(error => {
            console.error('Error during fetch:', error);
        });
    const daysTag = document.querySelector(".days"),
        currentDate = document.querySelector(".current-date"),
        prevNextIcon = document.querySelectorAll(".icons span");
        // get <select> element for attendance-options

// getting new date, current year and month
    let date = new Date(),
        currYear = date.getFullYear(),
        currMonth = date.getMonth();

// storing full name of all months in array
    const months = ["January", "February", "March", "April", "May", "June", "July",
        "August", "September", "October", "November", "December"];

    const renderCalendar = () => {
        let firstDayofMonth = new Date(currYear, currMonth, 1).getDay(), // getting first day of month
            lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(), // getting last date of month
            lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay(), // getting last day of month
            lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate(); // getting last date of previous month
        let liTag = "";

        let last_dayOfClass;

        for (let i = firstDayofMonth; i > 0; i--) { // creating li of previous month last days
            liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
        }

        for (let i = 1; i <= lastDateofMonth; i++) { // creating li of all days of current month

            let attendanceClass = '';

            // Find if a class was taken for this specific day, month, and year
            const classTaken = classData.find(item => {
                const itemDate = new Date(item.day);
                return itemDate.getFullYear() === currYear &&
                    itemDate.getMonth() === currMonth &&
                    itemDate.getDate() === i;
            });

            if (classTaken) {
                attendanceClass = 'taken';
                last_dayOfClass = i;
            }
            /* check if day i has attendance ===> this also implies class was taken on day i
            const classes = classData.find(item => new Date(item.day).getDate() === i);
            if (classes && new Date(classes.day).getMonth() === currMonth) {
               attendanceClass = 'taken';
               last_dayOfClass = i;
            }
             */
            // adding active class to li if the current day, month, and year matched
            let isToday = i === date.getDate() && currMonth === new Date().getMonth()
            && currYear === new Date().getFullYear() ? "active" : "";
            liTag += `<li class="${isToday} ${attendanceClass}">${i}</li>`;
        }

        for (let i = lastDayofMonth; i < 6; i++) { // creating li of next month first days
            liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
        }
        currentDate.innerText = `${months[currMonth]} ${currYear}`; // passing current mon and yr as currentDate text
        daysTag.innerHTML = liTag;

        // render daily table for last day on which there was class

        const takenDays = document.querySelectorAll('.taken');

        // Check if any elements were found
        if (takenDays.length > 0) {
            // Get the last element in the list
            const lastTakenDay = takenDays[takenDays.length - 1];

            // Add the 'selected' class to the last element
            lastTakenDay.classList.add('selected');
        }


        attendanceTableHeader.textContent = `Update Attendance for ${months[currMonth]} ${last_dayOfClass}, ${currYear}`

        const formattedMonth = (currMonth + 1).toString().padStart(2, '0');

        // To get a two-digit day number
        const formattedDay = last_dayOfClass.toString().padStart(2, '0');

        // Create the formatted date string
        const dat = `${currYear}-${formattedMonth}-${formattedDay}`;
        DAY = dat;

        renderDailyCalendar(dat);
    }

    /*
        renders daily calendar dynamically
     */


    let dailyAttendanceData = [];
    const dailyAttendanceTableBody = document.querySelector("#daily-attendance-table tbody");
    function renderDailyCalendar(date) {

        let namesData = [];
        const attendanceData = {
            subject: klass,
            date: date
        };

        fetch('/backend/api/get_daily_attendance.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json' // Crucial: Tells the server we're sending JSON
            },
            body: JSON.stringify(attendanceData) // Convert JavaScript object to JSON string
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json(); // Assuming server responds with JSON
            })
            .then(data => {
                dailyAttendanceData = data.attendance;
                namesData = data.names;

                dailyAttendanceTableBody.innerHTML = ''; // Clear existing table rows

                let innerTag = '';
                let count = 0;
                // Loop through the attendance data and populate the table
                dailyAttendanceData.forEach((record, index) => {
                    ++count;
                    innerTag += `<tr>
                <td>${count}</td>
                <td>${namesData[index]}</td>
                <td>${record.roll_number}</td>
                <td><span class="attendance-status ${record.attendance}">${record.attendance}</span></td>
                <td><button class="reject-btn" data-roll-number="${record.roll_number}">
                        Reject
                    </button></td>
                </tr>`;
                });

                dailyAttendanceTableBody.innerHTML = innerTag;
            })
            .catch(error => {
                console.error('Error during fetch:', error);
            });
    }


    attendanceTableHeader = document.getElementById('attendance-table-header');

    // Add this new event listener to handle clicks on the days
    daysTag.addEventListener("click", (event) => {
            const clickedDay = event.target;
            // logic for view_attendance.php

        // Check if the clicked element is an LI and has the 'taken' class
        if (clickedDay.tagName === "LI" && clickedDay.classList.contains("taken")) {

            // Find the currently selected day (if any)
            const currentlySelected = daysTag.querySelector(".selected");

            // If there's a selected day and it's not the one we just clicked, un-highlight it
            if (currentlySelected && currentlySelected !== clickedDay) {
                currentlySelected.classList.remove("selected");
            }

            // add the 'selected' class on the clicked day
            clickedDay.classList.add("selected");
            attendanceDate = document.querySelector('.selected');
            day = attendanceDate.textContent;
            attendanceTableHeader.textContent = `Update Attendance for ${months[currMonth]} ${day}, ${currYear}`;


            // To get the correct month number (1-12)



            const formattedMonth = (currMonth + 1).toString().padStart(2, '0');

            // To get a two-digit day number
            const formattedDay = day.toString().padStart(2, '0');

            // Create the formatted date string
            const date = `${currYear}-${formattedMonth}-${formattedDay}`;

            DAY = date;
            renderDailyCalendar(date);
        }
    });


    /* Add an event listener for the 'change' event
    attendanceChoice.addEventListener('change', () => {
        if (attendanceChoice.value === 'Daily'){

            // Get all elements with the class 'taken'
            const takenDays = document.querySelectorAll('.taken');

            // Check if any elements were found
            if (takenDays.length > 0) {
                // Get the last element in the list
                const lastTakenDay = takenDays[takenDays.length - 1];

                // Add the 'selected' class to the last element
                lastTakenDay.classList.add('selected');
            }

            dailyAttendance.style.display = 'table';
            totalAttendance.style.display = 'none';
            attendanceDate = document.querySelector('.selected');
            day = attendanceDate.textContent;
            attendanceTableHeader.textContent = `Attendance for ${months[currMonth]} ${day}, ${currYear}`

            // To get the correct month number (1-12)
            const formattedMonth = (currMonth + 1).toString().padStart(2, '0');

            // To get a two-digit day number
            const formattedDay = day.toString().padStart(2, '0');

            // Create the formatted date string
            const date = `${currYear}-${formattedMonth}-${formattedDay}`;

            renderDailyCalendar(date);
        }

        else if(attendanceChoice.value === 'Monthly'){

            selectedDate = document.querySelector('.selected');
            if (selectedDate != null) selectedDate.classList.remove('selected');
            dailyAttendance.style.display = 'none';
            totalAttendance.style.display = 'table';
            attendanceTableHeader.textContent = `Total Attendance for ${months[currMonth]}, ${currYear}`;
            // To get the correct month number (1-12)
            const formattedMonth = (currMonth + 1);

            renderMonthlyCalendar(formattedMonth);
        }

        else{
            selectedDate = document.querySelector('.selected');
            if (selectedDate != null) selectedDate.classList.remove('selected');
            dailyAttendance.style.display = 'none';
            totalAttendance.style.display = 'table';
            attendanceTableHeader.textContent = "Total Net Attendance"
        }
    });

    // logic-ends for view_attendance.php
    /* -----------------------------------*/

    prevNextIcon.forEach(icon => { // getting prev and next icons
        icon.addEventListener("click", () => { // adding click event on both icons
            // if clicked icon is previous icon then decrement current month by 1 else increment it by 1
            currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;

            if (currMonth < 0 || currMonth > 11) { // if current month is less than 0 or greater than 11
                // creating a new date of current year & month and pass it as date value
                date = new Date(currYear, currMonth, new Date().getDate());
                currYear = date.getFullYear(); // updating current year with new date year
                currMonth = date.getMonth(); // updating current month with new date month
            } else {
                date = new Date(); // pass the current date as date value
            }
            renderCalendar(); // calling renderCalendar function
        });
    });

/*else {
    // generate error if you need
}
 */


let updatedRecords = {};
// Event delegation for the Reject button

if(dailyAttendanceTableBody) {
    dailyAttendanceTableBody.addEventListener('click', (event) => {
        if (event.target.classList.contains('reject-btn')) {
            const button = event.target;
            const row = button.closest('tr');
            const rollNumber = button.getAttribute('data-roll-number');
            const attendanceStatusEl = row.querySelector('.attendance-status');

            // Find the original record in our data array
            const originalRecord = dailyAttendanceData.find(rec => rec.roll_number === rollNumber);

            if (originalRecord) {
                // Toggle the attendance status
                let newAttendance = '';
                if (attendanceStatusEl.textContent.trim() === 'present') {
                    newAttendance = 'absent';
                } else if (attendanceStatusEl.textContent.trim() === 'absent') {
                    newAttendance = 'present';
                } else {
                    // For 'leave' or other states, we'll toggle to 'absent'
                    newAttendance = 'absent';
                }

                // Update the UI
                attendanceStatusEl.textContent = newAttendance;
                attendanceStatusEl.className = 'attendance-status ' + newAttendance;

                // Add or update the record in our modified list
                updatedRecords[rollNumber] = {
                    ...originalRecord,
                    attendance: newAttendance
                };
            }
        }
    });
}

updateBtn = document.getElementById('updateBtn');
// Event listener for the main Update button
    if (updateBtn) {
        updateBtn.addEventListener('click', () => {
            const attendancesToUpdate = Object.values(updatedRecords);
            const attendancesData = {
                attendancesToUpdate: attendancesToUpdate,
                date: DAY
            }

            if (attendancesToUpdate.length === 0) {
                alert('No attendance attendances have been updated.');
                return;
            }
            else{
                attendancesToUpdate.forEach(record => {
                    if(record.attendance === 'absent')
                        record.attendance = '0';
                    if(record.attendance === 'present')
                        record.attendance = '1';
                });
            }

            // Send the new array of only updated attendances to the server
            fetch('/backend/api/update_daily_attendance.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(attendancesData)
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Attendance updated successfully!');
                        // Clear the updated attendances list after a successful update
                        updatedRecords = {};
                        //renderDailyCalendar(DAY);
                    } else {
                        alert('Failed to update attendance: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error during update:', error);
                    alert('An error occurred while updating attendance.');
                });
        });
    }

}
