programme = document.getElementById('programme');
semester = document.getElementById('semester');
subject = document.getElementById('subject');

let subjects = [];

fetch('/backend/api/get_programmes&subjects.php', {
        method: 'GET'
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json(); // Assuming server responds with JSON
        })
        .then(data => {
            subjects = data.subjects;
        })
        .catch(error => {
            console.error('Error during fetch:', error);
        });

programme.addEventListener('change', () => {
    semester.disabled = false;
    let tag = `
        <option disabled selected>Select Semester</option>
        <option >1</option>
        <option >2</option>
        <option >3</option>
        <option >4</option>
        <option >5</option>
        <option >6</option>
        <option >7</option>
        <option >8</option>
        `;
    semester.innerHTML = '';
    semester.innerHTML = tag;

    subject.disabled = true;
    tag = `
        <option disabled selected>Select Subject</option>
        `;

    subject.innerHTML = '';
    subject.innerHTML = tag;
});

semester.addEventListener('change', () => {
    subject.disabled = false;
    let tag = `
        <option disabled selected>Select Subject</option>
        `;
    subjects.forEach((record) =>{
       if (record.programme === programme.value && record.semester === semester.value){
           tag += `
                    <option value="${record.id}">${record.name}</option>
          `;
       }
    });

   subject.innerHTML = '';
   subject.innerHTML = tag;
});
