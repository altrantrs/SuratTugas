document.addEventListener("DOMContentLoaded", () => {
    filterEmployees(); // Call the filter function on page load
});

function filterEmployees() {
    const selectedEmployeeNip = document.getElementById("employee-select").value;
    const rows = document.querySelectorAll(".employee-row");

    rows.forEach(row => {
        const nip = row.getAttribute("data-nip");
        if (selectedEmployeeNip === "all" || nip === selectedEmployeeNip) {
            row.style.display = ""; // Show the row
            generateCalendar(nip); // Generate calendar for the visible employee
        } else {
            row.style.display = "none"; // Hide the row
        }
    });
}

// Generate calendar based on selected employee and month
function generateCalendar(nip) {
    const selectedMonth = document.getElementById("month-select").value;
    const year = new Date().getFullYear();
    const daysInMonth = getDaysInMonth(parseInt(selectedMonth), year);

    let daysContainer;
    if (nip) {
        daysContainer = document.getElementById(`days-container-${nip}`);
        console.log(`Generating calendar for NIP: ${nip} using container ID: days-container-${nip}`);
    } else {
        daysContainer = document.getElementById("days-container");
        console.log("Generating calendar for single employee (non-admin) using container ID: days-container");
    }

    if (!daysContainer) {
        console.error("Container for calendar not found.");
        return;
    }
    daysContainer.innerHTML = ''; // Clear previous calendar content

    fetch(`get_activities.php?month=${parseInt(selectedMonth) + 1}&year=${year}&nip=${nip ? nip : ''}`)
    .then(response => response.json())
    .then(data => {
        console.log("Activities data fetched:", data);
        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'day';
            dayElement.textContent = day.toString().padStart(2, '0');

            const currentDate = `${year}-${(parseInt(selectedMonth) + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
            const activitiesForDate = data.filter(activity => activity.date === currentDate);

            if (activitiesForDate.length > 0) {
                const activityIcon = document.createElement('i');
                activityIcon.className = 'fa-solid fa-check';
                activityIcon.style.color = 'green';

                const link = document.createElement('a');
                link.href = `tampil_kegiatan.php?date=${currentDate}&nip=${nip ? nip : ''}`;
                link.appendChild(activityIcon);

                dayElement.appendChild(link);
                dayElement.classList.add('icon-day');
            }

            daysContainer.appendChild(dayElement);
        }
    })
    .catch(error => console.error("Error fetching activities:", error));

    fetch(`get_activities.php?month=${parseInt(selectedMonth) + 1}&year=${year}&nip=${nip ? nip : ''}`)
    .then(response => {
        console.log("Response from server:", response);
        return response.json();
    })
    .then(data => {
        console.log("Data fetched for calendar:", data);
    })
    .catch(error => console.error("Error fetching activities:", error));
    
}


// Helper function to get the number of days in the selected month and year
function getDaysInMonth(month, year) {
    return new Date(year, month + 1, 0).getDate();
}

function openForm(day, month, year) {
    const selectedDate = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
    localStorage.setItem('selectedDate', selectedDate);
    window.location.href = 'perjalanan_tambah.php';
}