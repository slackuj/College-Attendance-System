let dailyAttendanceData = [];
const dailyAttendanceTable = document.getElementById('daily-attendance-table');
const attendanceTable = document.getElementById('attendance-table');
const dailyAttendanceTableBody = document.querySelector("#daily-attendance-table tbody");
const attendanceTableBody = document.querySelector("#attendance-table tbody");
const noAttendanceInfo = document.getElementById('no-attendance');
attendanceTableHeader = document.getElementById('attendance-table-header');

dailyAttendanceTable.style.display = 'none';
attendanceTable.style.display = 'none';



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

    let selectedDay;// last takenDay

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

            liTag += `<li class="${isToday} ${attendanceClass}">${i}</li>`;
        }

        for (let i = lastDayofMonth; i < 6; i++) { // creating li of next month first days
            liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
        }
        currentDate.innerText = `${months[currMonth]} ${currYear}`; // passing current mon and yr as currentDate text
        daysTag.innerHTML = liTag;

        // render daily table for last day on which there was class

        const takenDays = document.querySelectorAll('.taken');
        if (attendanceChoice.value === 'Total')
            renderTotalTable();
        else if (takenDays.length > 0) {
            noAttendanceInfo.style.display = 'none';
            const lastTakenDay = takenDays[takenDays.length - 1];
            lastTakenDay.classList.add('selected');

            selectedDay = lastTakenDay.textContent;
            attendanceTableHeader.textContent = `Attendance for ${months[currMonth]} ${selectedDay}, ${currYear}`

            const formattedMonth = (currMonth + 1).toString().padStart(2, '0');

            // To get a two-digit day number
            const formattedDay = selectedDay.toString().padStart(2, '0');

            // Create the formatted date string
            const dat = `${currYear}-${formattedMonth}-${formattedDay}`;
            DAY = dat;

            if (attendanceChoice.value === 'Daily') renderDailyCalendar(dat);
            else if(attendanceChoice.value === 'Monthly') renderMonthlyTable(formattedMonth);
        }
            else {
            dailyAttendanceTable.style.display = 'none';
            attendanceTable.style.display = 'none';
            attendanceTableHeader.style.display = 'none';
            noAttendanceInfo.innerHTML = '';

            noAttendanceInfo.innerHTML = `<span class='fa-solid fa-circle-info'></span>
                    No attendance taken for  <b>${months[currMonth]}</b> `;

            noAttendanceInfo.style.display = 'block';
        }
    }

    /*
        renders daily calendar dynamically
     */


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
                    attendanceTableHeader.style.display = 'block';
                    noAttendanceInfo.style.display = 'none';

                    dailyAttendanceTableBody.innerHTML = ''; // Clear existing table rows

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

                } else {
                    dailyAttendanceTable.style.display = 'none';
                    attendanceTableHeader.style.display = 'none';
                    noAttendanceInfo.innerHTML = '';

                    noAttendanceInfo.innerHTML = `<span class='fa-solid fa-circle-info'></span>
                    No attendance taken for  <b>${months[currMonth]}</b> `;

                    noAttendanceInfo.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error during fetch:', error);
            });
    }


    // Add this new event listener to handle clicks on the days
    daysTag.addEventListener("click", (event) => {
        if ( attendanceChoice.value === 'Daily') {
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
        }
    });


    const attendanceChoice = document.getElementById('attendance-options');
    attendanceChoice.addEventListener('change', () => {
        if (attendanceChoice.value === 'Daily') {

            attendanceTable.style.display = 'none';
            // Get all elements with the class 'taken'
            const takenDays = document.querySelectorAll('.taken');

            // Check if any elements were found
            if (takenDays.length > 0) {
                // Get the last element in the list
                const lastTakenDay = takenDays[takenDays.length - 1];

                // Add the 'selected' class to the last element
                lastTakenDay.classList.add('selected');


            dailyAttendanceTable.style.display = 'table';
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
        } else if (attendanceChoice.value === 'Monthly') {

            dailyAttendanceTable.style.display = 'none';
            selectedDate = document.querySelector('.selected');
            if (selectedDate != null) selectedDate.classList.remove('selected');
            attendanceTableHeader.textContent = `Total Attendance for ${months[currMonth]}, ${currYear}`;
            // To get the correct month number (1-12)
            const formattedMonth = (currMonth + 1);

            renderMonthlyTable(formattedMonth);
        } else {
            selectedDate = document.querySelector('.selected');
            if (selectedDate != null) selectedDate.classList.remove('selected');
            dailyAttendanceTable.style.display = 'none';
            attendanceTableHeader.textContent = "Total Net Attendance"
            renderTotalTable();
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

    function renderMonthlyTable(month) {

        let monthlyAttendanceData = [];
        let namesData = [];
        const attendanceData = {
            subject: cID,
            month: month,
            year: currYear
        };

        fetch('/backend/api/get_monthly_attendance.php', {
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
                monthlyAttendanceData = data.attendance;
                namesData = data.names;

                attendanceTable.style.display = 'block';
                attendanceTableBody.innerHTML = ''; // Clear existing table rows

                let innerTag = '';
                let count = 0;
                // Loop through the attendance data and populate the table
                monthlyAttendanceData.forEach((record, index) => {
                    ++count;
                    innerTag += `<tr>
                <td>${count}</td>
                <td>${namesData[index]}</td>
                <td>${record.roll_number}</td>
                <td>${record.total_classes}</td>
                <td>${record.total_present_days}</td>
                <td>${record.total_absent_days}</td>
                <td>${record.total_leave_days}</td>
                <td>${record.attendance_percentage}</td>
                </tr>`;
                });

                attendanceTableBody.innerHTML = innerTag;
            })
            .catch(error => {
                console.error('Error during fetch:', error);
            });
    }

    function renderTotalTable() {
        noAttendanceInfo.style.display = 'none';

        let totalAttendanceData = [];
        let namesData = [];
        const attendanceData = {
            subject: cID
        };

        fetch('/backend/api/get_total_attendance.php', {
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
                totalAttendanceData = data.attendance;
                namesData = data.names;

                attendanceTable.style.display = 'block';
                attendanceTableBody.innerHTML = ''; // Clear existing table rows

                let innerTag = '';
                let count = 0;
                // Loop through the attendance data and populate the table
                totalAttendanceData.forEach((record, index) => {
                    ++count;
                    innerTag += `<tr>
                <td>${count}</td>
                <td>${namesData[index]}</td>
                <td>${record.roll_number}</td>
                <td>${record.total_class_days}</td>
                <td>${record.present_days}</td>
                <td>${record.absent_days}</td>
                <td>${record.leave_days}</td>
                <td>${record.attendance_percentage}</td>
                </tr>`;
                });

                attendanceTableBody.innerHTML = innerTag;
            })
            .catch(error => {
                console.error('Error during fetch:', error);
            });
    }
}