// Generate the calendar on page load
document.addEventListener("DOMContentLoaded", function() {
    generateCalendar(); // Call the function only once when the page loads
});

function generateCalendar() {
    const daysContainer = document.getElementById('days-container');
    const employeeSelect = document.getElementById('employee-select');
    const monthSelect = document.getElementById('month-select');
    const selectedEmployee = employeeSelect.value;
    const selectedMonth = parseInt(monthSelect.value);
    const year = new Date().getFullYear();
    const daysInMonth = getDaysInMonth(selectedMonth, year);

    daysContainer.innerHTML = ''; // Clear previous days

    // Fetch activities from the server based on selected employee and month
    fetch(`get_activities.php?month=${selectedMonth + 1}&year=${year}&nip=${selectedEmployee}`)
    .then(response => response.json())
    .then(data => {
        console.log("Data fetched:", data);
        if (data.length === 0) {
            console.error("No activities received");
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
            const activitiesForDate = data.filter(activity => activity.date === currentDate);

            if (activitiesForDate.length > 0) {
                // Create a link element
                const link = document.createElement('a');
                link.href = `tampil_kegiatan.php?date=${currentDate}`;
                
                // Create and add the checkmark icon to the link
                const icon = document.createElement('i');
                icon.className = 'fa-solid fa-check'; // FontAwesome checkmark icon class
                icon.style.color = 'green'; // Adjust color as needed
                icon.style.fontSize = '16px'; // Adjust size as needed

                link.appendChild(icon); // Add the icon to the link
                dayElement.appendChild(link); // Add the link to the day element
                
                dayElement.classList.add('icon-day');
            }

            dayElement.addEventListener('click', () => {
                openForm(day, selectedMonth + 1, year);
            });

            daysContainer.appendChild(dayElement);
        }
    })
    .catch(error => console.error("Error fetching activities:", error));
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
    window.location.href = 'perjalanan_tambah.php';
}
