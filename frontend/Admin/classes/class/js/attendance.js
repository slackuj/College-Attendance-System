refresh = document.getElementById('refresh');
refresh.addEventListener('click', () => {
    window.location.href = `/frontend/Admin/classes/class/attendance.php?id=${cID}&day=${DAY}`;
});