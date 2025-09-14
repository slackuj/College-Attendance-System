const urlParams = new URLSearchParams(window.location.search);
console.log('hi');

//check if parameter exists
if (urlParams.has('class') && urlParams.has('student')) {
    const klass = urlParams.get('class');
    const roll_number = urlParams.get('student');
    console.log(klass);
    console.log(roll_number);

    let attendanceData = [];
    const userData = {
        subject: klass,
        roll_number: roll_number
    };

    fetch('/backend/api/get_student_attendance.php', {
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
            attendanceData = data.data;
            renderCalendar();
        })
        .catch(error => {
            console.error('Error during fetch:', error);
        });

    const daysTag = document.querySelector(".days"),
        currentDate = document.querySelector(".current-date"),
        prevNextIcon = document.querySelectorAll(".icons span");

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

        for (let i = firstDayofMonth; i > 0; i--) { // creating li of previous month last days
            liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
        }

        for (let i = 1; i <= lastDateofMonth; i++) { // creating li of all days of current month

            let subscript = "";
            let attendanceClass = '';
            // check if day i has attendance ===> this also implies class was taken on day i
            const Attendance = attendanceData.find(item => new Date(item.day).getDate() === i);
            if (Attendance && new Date(Attendance.day).getMonth() === currMonth) {
                if (Attendance.attendance === '1') {
                    attendanceClass = 'present';
                    subscript = 'P';
                } else if (Attendance.attendance === '0') {
                    attendanceClass = 'absent';
                    subscript = 'A';
                } else {
                    attendanceClass = 'leave';
                    subscript = 'L';
                }
            }
            // adding active class to li if the current day, month, and year matched
            let isToday = i === date.getDate() && currMonth === new Date().getMonth()
            && currYear === new Date().getFullYear() ? "active" : "";
            liTag += `<li class="${isToday} ${attendanceClass}">${i}<sub class="attendance-subscript">${subscript}</sub></li>`;
        }

        for (let i = lastDayofMonth; i < 6; i++) { // creating li of next month first days
            liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`
        }
        currentDate.innerText = `${months[currMonth]} ${currYear}`; // passing current mon and yr as currentDate text
        daysTag.innerHTML = liTag;
    }

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