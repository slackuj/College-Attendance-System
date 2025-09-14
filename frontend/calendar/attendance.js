const urlParams = new URLSearchParams(window.location.search);
let DAY;
let cID;
//check if parameter exists
if (urlParams.has('id')) {
    cID = urlParams.get('id');

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

    //const currentDate = date;

    let selectedDay = date.getDate();// current day selected on the calendar
    // by default selectedDay === present day

// storing full name of all months in array
    const months = ["January", "February", "March", "April", "May", "June", "July",
        "August", "September", "October", "November", "December"];

    const renderCalendar = () => {
        let firstDayofMonth = new Date(currYear, currMonth, 1).getDay(), // getting first day of month
            lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(), // getting last date of month
            lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay(), // getting last day of month
            lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate(); // getting last date of previous month
        let liTag = "";


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
            }
            /* check if day i has attendance ===> this also implies class was taken on day i
            const classes = classData.find(item => new Date(item.day).getDate() === i);
            if (classes && new Date(classes.day).getMonth() === currMonth) {
               attendanceClass = 'taken';
               selectedDay = i;
            }
             */
            // adding active class to li if the current day, month, and year matched
            let isToday = i === date.getDate() && currMonth === new Date().getMonth()
            && currYear === new Date().getFullYear() ? "active" : "";

            // adding pastDay class to li if the current day, month, and year is less than currentDate
            let isPastDay = i <= date.getDate() && currMonth <= new Date().getMonth()
            && currYear <= new Date().getFullYear() ? "pastDay" : "";

            liTag += `<li class="${isPastDay} ${isToday} ${attendanceClass}">${i}</li>`;
        }

        for (let i = lastDayofMonth; i < 6; i++) { // creating li of next month first days
            liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
        }
        currentDate.innerText = `${months[currMonth]} ${currYear}`; // passing current mon and yr as currentDate text
        daysTag.innerHTML = liTag;

        // render daily table for last day on which there was class

        if (urlParams.has('day')){

            const toBeSelectedDAY = new Date(urlParams.get('day')).getDate();
            console.log(toBeSelectedDAY);
            const pastDays = document.querySelectorAll('.pastDay');
            pastDays.forEach(passedDay => {

                console.log(toBeSelectedDAY);
                console.log(passedDay.textContent);
                if (parseInt(passedDay.textContent) === toBeSelectedDAY){
                    passedDay.classList.add('selected');
                    selectedDay = toBeSelectedDAY;
                }
            });
        }
        else {

        const currentDay = document.querySelector('.active');
        currentDay.classList.add('selected');
        }

        attendanceTableHeader.textContent = `Attendance for ${months[currMonth]} ${selectedDay}, ${currYear}`

        const formattedMonth = (currMonth + 1).toString().padStart(2, '0');

        // To get a two-digit day number
        const formattedDay = selectedDay.toString().padStart(2, '0');

        // Create the formatted date string
        const dat = `${currYear}-${formattedMonth}-${formattedDay}`;
        DAY = dat;

        renderDailyCalendar(dat);
    }

    /*
        renders daily calendar dynamically
     */


    let dailyAttendanceData = [];
    const dailyAttendanceTable = document.getElementById('daily-attendance-table');
    const dailyAttendanceTableBody = document.querySelector("#daily-attendance-table tbody");
    const updateAttendanceTable = document.getElementById('update-attendance-table');
    const updateAttendanceTableBody = document.querySelector("#update-attendance-table tbody");
    const noAttendanceInfo = document.getElementById('no-attendance');
    attendanceTableHeader = document.getElementById('attendance-table-header');

    function renderDailyCalendar(date) {

        let namesData = [];
        const attendanceData = {
            subject: cID,
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

                if (dailyAttendanceData.length > 0) {

                    dailyAttendanceTable.style.display = 'block';
                    updateAttendanceTable.style.display = 'block';
                    attendanceTableHeader.style.display = 'block';
                    noAttendanceInfo.style.display = 'none';
                    btn.disabled = true;
                    btn4.disabled = false;

                    dailyAttendanceTableBody.innerHTML = ''; // Clear existing table rows
                    updateAttendanceTableBody.innerHTML = ''; // Clear existing table rows

                    let innerTag = '';
                    let count = 0;
                    // Loop through the attendance data and populate the table
                    dailyAttendanceData.forEach((record, index) => {
                        ++count;
                        innerTag += `<tr>
                <td><label>${count}</label></td>
                <td><label>${namesData[index]}</label></td>
                <td><label>${record.roll_number}</label></td>
                <td><label><span class="attendance-status ${record.attendance}">${record.attendance}</span></label></td>
                </tr>`;
                    });

                    dailyAttendanceTableBody.innerHTML = innerTag;

                    innerTag = '';
                    count = 0;

                    let P = '';
                    let A = '';
                    let L = '';
                    let idName = '';
                    // Loop through the attendance data and populate the table
                    dailyAttendanceData.forEach((record, index) => {
                        ++count;
                        P = record.attendance === 'present' ? 'checked' : '';
                        A = record.attendance === 'absent' ? 'checked' : '';
                        L = record.attendance === 'leave' ? 'checked' : '';
                        idName = record.roll_number + 'a';
                        innerTag += `<tr>
                <td><label>${count}</label></td>
                <td><label>${namesData[index]}</label></td>
                <td><label>${record.roll_number}</label></td>
                <td>
                    <input type="checkbox" class="attendance-checkbox present-status" id="present-${idName}" name="${idName}" ${P}>
                    <label for="present-${idName}"></label>
                </td>
                <td>
                     <input type="checkbox" class="attendance-checkbox absent-status" id="absent-${idName}" name="${idName}" ${A}>
                     <label for="absent-${idName}"></label>
                </td>
                <td>
                     <input type="checkbox" class="attendance-checkbox leave-status" id="leave-${idName}" name="${idName}" ${L}>
                     <label for="leave-${idName}"></label>
                </td>
                </tr>`;
                    });

                    updateAttendanceTableBody.innerHTML = innerTag;
                } else {
                    dailyAttendanceTable.style.display = 'none';
                    updateAttendanceTable.style.display = 'none';
                    attendanceTableHeader.style.display = 'none';
                    noAttendanceInfo.innerHTML = '';
                    btn.disabled = false;
                    btn4.disabled = true;

                    noAttendanceInfo.innerHTML = `<span class='fa-solid fa-circle-info'></span>
                    No attendance taken for  <b>${months[currMonth]} ${selectedDay}</b> `;

                    noAttendanceInfo.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error during fetch:', error);
            });
    }


    // Add this new event listener to handle clicks on the days
    daysTag.addEventListener("click", (event) => {
        const clickedDay = event.target;
        // logic for view_attendance.php

        // Check if the clicked element is an LI and has the 'taken' class
        if (clickedDay.tagName === "LI" && clickedDay.classList.contains("pastDay")) {

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
            selectedDay = day;
            attendanceTableHeader.textContent = `Attendance for ${months[currMonth]} ${day}, ${currYear}`;


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
}
/*else {
    // generate error if you need
}
 */

/*
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

/*updateBtn = document.getElementById('updateBtn');
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
 */