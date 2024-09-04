// Generate the calendar on page load
document.addEventListener("DOMContentLoaded", function() {
    generateCalendar(); // Call the function only once when the page loads
});

function generateCalendar() {
    const daysContainer = document.getElementById('days-container');
    daysContainer.innerHTML = ''; // Clear previous days

    const monthSelect = document.getElementById('month-select');
    const selectedMonth = parseInt(monthSelect.value);

    const year = new Date().getFullYear();
    const daysInMonth = getDaysInMonth(selectedMonth, year);

    fetch('get_activities.php')
    .then(response => response.json())
    .then(datesWithActivities => {
        console.log("Dates with activities:", datesWithActivities);
        if (datesWithActivities.length === 0) {
            console.error("No dates received");
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'day';
            dayElement.textContent = day.toString().padStart(2, '0');

            const dayOfWeek = new Date(year, selectedMonth, day).getDay();

            if (dayOfWeek === 0 || dayOfWeek === 6) {
                dayElement.classList.add('weekend');
            }

            const currentDate = `${year}-${(selectedMonth + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
            console.log("Checking date:", currentDate);

            if (datesWithActivities.includes(currentDate)) {
                const checkIcon = document.createElement('i');
                checkIcon.className = 'fa-solid fa-check';
                
                // Create the link
                const link = document.createElement('a');
                link.href = `tampil_kegiatan.php?date=${currentDate}`;
                link.appendChild(checkIcon);

                dayElement.appendChild(link); // Add the link to the day element

                // Add the cream background class
                dayElement.classList.add('icon-day');
            }

            dayElement.addEventListener('click', () => {
                openForm(day, selectedMonth + 1, year);
            });

            daysContainer.appendChild(dayElement);
        }
    })
    .catch(error => console.error("Error fetching dates:", error));
}


function getDaysInMonth(month, year) {
    if (month === 1) { // February
        if ((year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0)) {
            return 29;
        } else {
            return 28;
        }
    } else {
        return [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month];
    }
}

function openForm(day, month, year) {
    const selectedDate = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
    localStorage.setItem('selectedDate', selectedDate);
    window.location.href = 'tambah_kegiatan.php';
}
