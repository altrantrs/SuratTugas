document.addEventListener("DOMContentLoaded", () => {
    filterEmployees(); // Call the filter function on page load
});

function filterEmployees() {
    const selectedEmployeeName = document.getElementById("employee-select").value;
    const rows = document.querySelectorAll(".employee-row");

    rows.forEach(row => {
        const name = row.getAttribute("data-nama"); // Changed from data-name to data-nama
        if (selectedEmployeeName === "all" || name === selectedEmployeeName) {
            row.style.display = ""; 
            if (selectedEmployeeName === "all") {
                // Clear existing calendars and generate for all employees
                document.querySelectorAll(".days").forEach(container => {
                    container.innerHTML = ''; // Clear previous content
                    generateCalendar(container.id.replace("days-container-", ""));
                });
            } else {
                generateCalendar(selectedEmployeeName); 
            }
        } else {
            row.style.display = "none"; 
        }
    });
}

// Generate calendar based on selected employee and month
function generateCalendar(employeeName) {
    const selectedMonth = document.getElementById("month-select").value;
    const year = new Date().getFullYear();
    const daysInMonth = getDaysInMonth(parseInt(selectedMonth), year);

    console.log("Generating calendar for:", employeeName);
    
    let daysContainer;
    if (employeeName === "all") {
        daysContainer = document.querySelectorAll(".days"); // For 'all' case
    } else {
        daysContainer = document.getElementById(`days-container-${employeeName}`);
        if (!daysContainer) {
            console.error("Container for calendar not found.");
            return;
        }
    }

    if (typeof daysContainer === 'object' && daysContainer.length) {
        daysContainer.forEach(container => {
            container.innerHTML = ''; // Clear previous calendar content
        });
    } else {
        daysContainer.innerHTML = ''; // Clear previous calendar content
    }

    fetch(`get_activities.php?month=${parseInt(selectedMonth) + 1}&year=${year}&pelaksana=${employeeName ? employeeName : 'all'}`)
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            throw new Error('Network response was not ok.');
        }
    })
    .then(data => {
        console.log("Activities data fetched:", data);
        const calendars = typeof daysContainer === 'object' && daysContainer.length ? daysContainer : [daysContainer];
        calendars.forEach(container => {
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'day';
                dayElement.textContent = day.toString().padStart(2, '0');

                const dayOfWeek = new Date(year, selectedMonth, day).getDay();
                if (dayOfWeek === 0 || dayOfWeek === 6) {
                    dayElement.classList.add('weekend');
                }

                const currentDate = `${year}-${(parseInt(selectedMonth) + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                const activitiesForDate = data.filter(activity => activity.date === currentDate);

                if (activitiesForDate.length > 0) {
                    const activityIcon = document.createElement('i');
                    activityIcon.className = 'fa-solid fa-check';
                    
                    const link = document.createElement('a');
                    link.href = `tampil_kegiatan.php?date=${currentDate}&pelaksana=${employeeName ? employeeName : ''}`;
                    link.appendChild(activityIcon);

                    dayElement.appendChild(link);
                    dayElement.classList.add('icon-day');
                }

                container.appendChild(dayElement);
            }
        });
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
