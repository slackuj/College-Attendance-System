side1 = document.getElementById('side-1');
side2 = document.getElementById('side-2');
side3 = document.getElementById('side-3');
side4 = document.getElementById('side-4');

const urlstring = window.location.href;

if (urlstring.includes('/Admin/classes/class/class')){
    side1.style.backgroundColor = '#0078d4';
    side3.style.backgroundColor = '';
    side2.style.backgroundColor = '';
    side4.style.backgroundColor = '';
}

if (urlstring.includes('/Admin/classes/class/students')){
    side1.style.backgroundColor = '';
    side3.style.backgroundColor = '';
    side4.style.backgroundColor = '';
    side2.style.backgroundColor = '#0078d4';
}

if (urlstring.includes('/Admin/classes/class/attendance')){
    side3.style.backgroundColor = '#0078d4';
    side1.style.backgroundColor = '';
    side2.style.backgroundColor = '';
    side4.style.backgroundColor = '';
}
if (urlstring.includes('/Admin/classes/class/attendance_report')){
    side4.style.backgroundColor = '#0078d4';
    side3.style.backgroundColor = '';
    side1.style.backgroundColor = '';
    side2.style.backgroundColor = '';
}