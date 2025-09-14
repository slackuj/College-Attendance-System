document.addEventListener('DOMContentLoaded', () => {
faculty = document.getElementById('programme');
programme = document.getElementById('programme');
semester = document.getElementById('semester');
teacher = document.getElementById('teacher');

//programme.addEventListener('change', () => {
//    console.log('hi');
//})

//console.log('hi');
filters = document.querySelectorAll('.filters');
filters.forEach(filter => {
// 1. Select all elements with the class 'subject-box'
const allSubjects = document.querySelectorAll('.subject-box');

// The `visibleSubjects` array now contains only the subjects that are not hidden.

filter.addEventListener('change', () => {
    allSubjects.forEach(subject => {
        if (faculty.value === 'faculty' || faculty.value === 'All' || subject.id.includes(faculty.value))
            subject.style.display = 'block';
        else {
            subject.style.display = 'none';
            return;
        }
        if (programme.value === 'programme' || programme.value === 'All' || subject.id.includes(programme.value))
            subject.style.display = 'block';
        else {
            subject.style.display = 'none';
            return;
        }
        if (semester.value === 'semester' || semester.value === 'All' || subject.id.includes(semester.value))
            subject.style.display = 'block';
        else {
            subject.style.display = 'none';
            return;
        }
        if (teacher.value === 'lecturer' || teacher.value === 'All' || subject.id.includes(teacher.value))
            subject.style.display = 'block';
        else {
            subject.style.display = 'none';
        }
    });
});
});
});
