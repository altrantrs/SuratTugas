document.addEventListener("DOMContentLoaded", () => {
    filterEmployees(); // Call the filter function on page load
});

function filterEmployees() {
    const selectedEmployeeName = document.getElementById("employee-select").value;
    const rows = document.querySelectorAll(".employee-row");
    

    rows.forEach(row => {
        const nama = row.getAttribute("data-nama");
        if (selectedEmployeeName === "all" || nama === selectedEmployeeName) {
            row.style.display = ""; // Show the row
            generateCalendar(nama); // Generate calendar for the visible employee
        } else {
            row.style.display = "none"; // Hide the row
        }
    });
}

document.getElementById("employee-select").addEventListener("change", function() {
    const selectedEmployee = this.value;
    console.log("Selected employee:", selectedEmployee);
    generateCalendar(selectedEmployee);
});

// Generate calendar based on selected employee and month
function generateCalendar(employeeName) {
    const selectedMonth = document.getElementById("month-select").value;
    const year = new Date().getFullYear();
    const daysInMonth = getDaysInMonth(parseInt(selectedMonth), year);

    // Ubah spasi menjadi underscore agar sesuai dengan ID HTML
    const employeeId = employeeName.replace(/\s+/g, '_');
    const daysContainer = document.getElementById(`days-container-${employeeId}`);

    if (!daysContainer) {
        console.error("Container for calendar not found for:", employeeName);
        return;
    }
    daysContainer.innerHTML = ''; // Clear previous calendar content

    // Fetch activity data for selected employee
    fetch(`get_activities.php?month=${parseInt(selectedMonth) + 1}&year=${year}&pelaksana=${employeeName}`)
        .then(response => response.json())
        .then(data => {
            console.log("Activities data fetched:", data);

            // Generate calendar days
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'day';
                dayElement.textContent = day.toString().padStart(2, '0');

                const currentDate = `${year}-${(parseInt(selectedMonth) + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                const activitiesForDate = data.filter(activity => activity.date === currentDate);

                if (activitiesForDate.length > 0) {
                    const activityIcon = document.createElement('i');
                    activityIcon.className = 'fa-solid fa-check';

                    const link = document.createElement('a');
                    link.href = `tampil_kegiatan.php?date=${currentDate}&pelaksana=${employeeName}`;
                    link.appendChild(activityIcon);

                    dayElement.appendChild(link);
                    dayElement.classList.add('icon-day');
                }

                daysContainer.appendChild(dayElement);
            }
        })
        .catch(error => {
            console.error("Error fetching activities:", error);
        });
}


function updateCalendar() {
    const selectedEmployee = document.getElementById("employee-select").value;
    const selectedMonth = document.getElementById("month-select").value;

    // Panggil fungsi filterEmployees untuk memfilter pegawai jika admin
    if (document.getElementById("employee-select")) {
        filterEmployees();
    }

    // Panggil generateCalendar dengan parameter yang tepat
    generateCalendar(selectedEmployee);
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
