side1 = document.getElementById('side-1');
side2 = document.getElementById('side-2');

const urlstring = window.location.href;

if (urlstring.includes('/Admin/Subjects/subjects')){
    side1.style.backgroundColor = '#0078d4';
    side2.style.backgroundColor = '';
}