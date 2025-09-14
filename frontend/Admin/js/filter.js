const searchInput = document.getElementById("searchInput");
const table = document.querySelector(".teachers-table");
const rows = table.getElementsByTagName("tr");
const noResult = document.getElementById("noResult");

searchInput.addEventListener("keyup", function () {
    const filter = searchInput.value.toLowerCase();
    let found = false;

    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName("td");
        let match = false;

        for (let j = 0; j < cells.length; j++) {
            if (cells[j] && cells[j].textContent.toLowerCase().includes(filter)) {
                match = true;
                break;
            }
        }

        if (match) {
            rows[i].style.display = "";
            found = true;
        } else {
            rows[i].style.display = "none";
        }
    }

    // Show "no results" if nothing matches
    noResult.style.display = found ? "none" : "block";
});