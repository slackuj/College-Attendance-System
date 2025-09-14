document.addEventListener('DOMContentLoaded', () => {
    const classIdElement = document.getElementById('cID');
    const cID = classIdElement ? classIdElement.value : null;

    if (!cID) {
        console.error("Class ID not found. Cannot fetch attendance data.");
        return;
    }

    const reportTypeSelect = document.getElementById('attendance-report-options');
    const dailyTableBody = document.querySelector('#daily-attendance-table tbody');
    const monthlyTableBody = document.querySelector('#monthly-attendance-table tbody');
    const totalTableBody = document.querySelector('#total-attendance-table tbody');
    const headerElement = document.getElementById('attendance-table-header');
    const noStudentsMessage = document.getElementById('no-students');
    const dateInput = document.getElementById('date');
    const tables = {
        'daily': document.getElementById('daily-attendance-table'),
        'monthly': document.getElementById('monthly-attendance-table'),
        'total': document.getElementById('total-attendance-table')
    };

    let allStudentsData = [];

    // Fetches all attendance data for the class
    function fetchAttendanceData() {
        const userData = {
            cID: cID
        };

        fetch('/backend/api/getAttendance.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(userData)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.data) {
                    allStudentsData = data.data;
                    handleReportChange(); // Populate tables on initial load
                } else {
                    console.error("API returned no data:", data.message);
                    showNoStudentsMessage();
                }
            })
            .catch(error => {
                console.error('Error during fetch:', error);
                showNoStudentsMessage();
            });
    }

    // Displays the appropriate table and message based on the data
    function handleReportChange() {
        const selectedReport = reportTypeSelect.value;
        hideAllTables();

        if (allStudentsData.length === 0) {
            showNoStudentsMessage();
            return;
        }

        headerElement.innerText = `Attendance Report for ${selectedReport.charAt(0).toUpperCase() + selectedReport.slice(1)}`;
        tables[selectedReport].style.display = 'table';
        noStudentsMessage.style.display = 'none';

        if (selectedReport === 'daily') {
            populateDailyTable(allStudentsData);
        } else if (selectedReport === 'monthly') {
            populateMonthlyTable(allStudentsData);
        } else if (selectedReport === 'total') {
            populateTotalTable(allStudentsData);
        }
    }

    function hideAllTables() {
        Object.values(tables).forEach(table => {
            table.style.display = 'none';
        });
    }

    function showNoStudentsMessage() {
        hideAllTables();
        noStudentsMessage.style.display = 'block';
        noStudentsMessage.innerHTML = `<span class='fa-solid fa-circle-info'></span> There were no students found for this class.`;
    }

    function populateDailyTable(students) {
        dailyTableBody.innerHTML = '';
        const selectedDate = dateInput.value;
        let html = '';

        students.forEach(student => {
            const dailyAttendance = student.attendance_data.find(att => att.day === selectedDate);
            const status = dailyAttendance ? (dailyAttendance.status === '1' ? 'Present' : (dailyAttendance.status === '0' ? 'Absent' : 'Leave')) : 'N/A';
            const avgAttendance = calculateAverage(student.attendance_data);

            html += `
                <tr>
                    <td>${student.class_roll_number || 'N/A'}</td>
                    <td>${student.name}</td>
                    <td>${student.exam_roll_number || 'N/A'}</td>
                    <td>${status}</td>
                    <td>${avgAttendance}%</td>
                </tr>
            `;
        });
        dailyTableBody.innerHTML = html;
    }

    function populateMonthlyTable(students) {
        monthlyTableBody.innerHTML = '';
        let html = '';
        const currentDate = new Date();
        const currYear = currentDate.getFullYear();
        const currMonth = currentDate.getMonth();

        students.forEach(student => {
            const monthlyData = student.attendance_data.filter(att => {
                const itemDate = new Date(att.day);
                return itemDate.getFullYear() === currYear && itemDate.getMonth() === currMonth;
            });

            const totalClasses = monthlyData.length;
            const presentDays = monthlyData.filter(item => item.status === '1').length;
            const absentDays = monthlyData.filter(item => item.status === '0').length;
            const leaveDays = monthlyData.filter(item => item.status === 'L').length;
            const avgAttendance = totalClasses > 0 ? ((presentDays + leaveDays) / totalClasses * 100).toFixed(2) : 0;

            html += `
                <tr>
                    <td>${student.class_roll_number || 'N/A'}</td>
                    <td>${student.name}</td>
                    <td>${student.exam_roll_number || 'N/A'}</td>
                    <td>${totalClasses}</td>
                    <td>${presentDays}</td>
                    <td>${absentDays}</td>
                    <td>${leaveDays}</td>
                    <td>${avgAttendance}%</td>
                </tr>
            `;
        });
        monthlyTableBody.innerHTML = html;
    }

    function populateTotalTable(students) {
        totalTableBody.innerHTML = '';
        let html = '';

        students.forEach(student => {
            const totalClasses = student.attendance_data.length;
            const presentDays = student.attendance_data.filter(item => item.status === '1').length;
            const absentDays = student.attendance_data.filter(item => item.status === '0').length;
            const leaveDays = student.attendance_data.filter(item => item.status === 'L').length;
            const avgAttendance = totalClasses > 0 ? ((presentDays + leaveDays) / totalClasses * 100).toFixed(2) : 0;

            html += `
                <tr>
                    <td>${student.class_roll_number || 'N/A'}</td>
                    <td>${student.name}</td>
                    <td>${student.exam_roll_number || 'N/A'}</td>
                    <td>${totalClasses}</td>
                    <td>${presentDays}</td>
                    <td>${absentDays}</td>
                    <td>${leaveDays}</td>
                    <td>${avgAttendance}%</td>
                </tr>
            `;
        });
        totalTableBody.innerHTML = html;
    }

    function calculateAverage(attendanceRecords) {
        if (attendanceRecords.length === 0) return 0;
        const presentAndLeave = attendanceRecords.filter(att => att.status === '1' || att.status === 'L').length;
        return ((presentAndLeave / attendanceRecords.length) * 100).toFixed(2);
    }

    // Event listeners
    reportTypeSelect.addEventListener('change', handleReportChange);
    dateInput.addEventListener('change', () => {
        if (reportTypeSelect.value === 'daily') {
            populateDailyTable(allStudentsData);
        }
    });

    // Initial data fetch and population
    fetchAttendanceData();
});