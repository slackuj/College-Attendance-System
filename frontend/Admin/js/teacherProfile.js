side1 = document.getElementById('side-1');
side3 = document.getElementById('side-3');

const urlstring = window.location.href;

if (urlstring.includes('/Admin/teacher/profile')){
    side1.style.backgroundColor = '#0078d4';
    side3.style.backgroundColor = '';
}

if (urlstring.includes('/Admin/teacher/subjects')){
    side1.style.backgroundColor = '';
    side3.style.backgroundColor = '';
}

if (urlstring.includes('/Admin/teacher/classes')){
    side3.style.backgroundColor = '#0078d4';
    side1.style.backgroundColor = '';
}


