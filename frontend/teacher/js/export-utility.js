document.getElementById('downloadBtn').addEventListener('click', () => {

let fileName = document.getElementById('fileName').value;

    // Get the table element by its ID
    const table = document.getElementById('class-table');
    if (!table) {
        console.error(`Table with ID class-table not found.`);
        return;
    }

    // Get the table header and body rows
    const rows = table.querySelectorAll('tr');
    let csv = [];

    // Loop through each row and cell to build the CSV data
    for (let i = 0; i < rows.length; i++) {
        let row = [], cols = rows[i].querySelectorAll('td, th');

        for (let j = 0; j < cols.length; j++) {
            // Get the text content of each cell and handle newlines/commas
            let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/"/g, '""');
            // If the data contains a comma, wrap it in double quotes
            data = data.includes(',') ? `"${data}"` : data;
            row.push(data);
        }
        csv.push(row.join(','));
    }

    // Combine all rows into a single string with newlines
    let csvString = csv.join('\n');

    // Create a Blob from the CSV string with the correct MIME type
    const blob = new Blob([csvString], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
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
