refresh = document.getElementById('refresh');
refresh.addEventListener('click', () => {
    window.location.href = `/frontend/teacher/class/attendance.php?id=${cID}&day=${DAY}`;
});