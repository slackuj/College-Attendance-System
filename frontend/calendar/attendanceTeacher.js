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

        if (urlParams.has('day')) {

            const toBeSelectedDAY = new Date(urlParams.get('day')).getDate();
            console.log(toBeSelectedDAY);
            const pastDays = document.querySelectorAll('.pastDay');
            pastDays.forEach(passedDay => {

                console.log(toBeSelectedDAY);
                console.log(passedDay.textContent);
                if (parseInt(passedDay.textContent) === toBeSelectedDAY) {
                    passedDay.classList.add('selected');
                    selectedDay = toBeSelectedDAY;
                }
            });
        } else {

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
}