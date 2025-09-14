document.getElementById('download-format').addEventListener('click', () => {

    task = document.getElementById('task');

    fileName = task.textContent;

    fileName = fileName.trim();
    fileName = fileName.replaceAll(' ', '_');

    // Get the form element by its ID
    const form = document.getElementById('create-form');
    if (!form) {
        console.error(`Form with ID 'create-form' not found.`);
        return;
    }

    // Get the form labels
    const labels = form.querySelectorAll('label');
    let csv = '' ;

    // Loop through each label to build the CSV data
    labels.forEach(label =>{
        csv += label.textContent + ',';
    });

    csv = csv.replace(/,$/, '');


    // Create a Blob from the CSV string with the correct MIME type
    const blob = new Blob([csv], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    const url = URL.createObjectURL(blob);

    // Create a temporary link element to trigger the download
    const link = document.createElement('a');
    link.href = url;

    link.download = `${fileName}.xlsx`;

    // Append the link to the body and click it to start the download
    document.body.appendChild(link);
    link.click();

    // Clean up by removing the link and revoking the URL
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
});
