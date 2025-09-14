side1 = document.getElementById('side-1');
side2 = document.getElementById('side-2');
side3 = document.getElementById('side-3');

const urlstring = window.location.href;

if (urlstring.includes('/Admin/classes/classes')){
    side1.style.backgroundColor = '#0078d4';
    side3.style.backgroundColor = '';
    side2.style.backgroundColor = '';
}

if (urlstring.includes('/Admin/classes/unassigned-classes')){
    side1.style.backgroundColor = '';
    side3.style.backgroundColor = '';
    side2.style.backgroundColor = '#0078d4';
}

if (urlstring.includes('/Admin/classes/deleted-classes')){
    side3.style.backgroundColor = '#0078d4';
    side1.style.backgroundColor = '';
    side2.style.backgroundColor = '';
}


