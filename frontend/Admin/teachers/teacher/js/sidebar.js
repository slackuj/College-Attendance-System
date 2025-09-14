side1 = document.getElementById('side-1');
side2 = document.getElementById('side-2');

const urlstring = window.location.href;

if (urlstring.includes('/Admin/teachers/teacher/profile')){
    side1.style.backgroundColor = '#0078d4';
    side2.style.backgroundColor = '';
}

if (urlstring.includes('/Admin/teachers/teacher/classes')){
    side1.style.backgroundColor = '';
    side2.style.backgroundColor = '#0078d4';
}